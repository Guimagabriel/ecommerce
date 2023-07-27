<?php

function renderIndex($vars, $container)
{
    $vars['products'] = VirtualStore\Models\Product::listAll();
    $page = $container->get(VirtualStore\Page::class);
    $page->renderPage('index', ['products' => VirtualStore\Models\Product::checkList($vars['products'])]);
}

function category($vars, $container)
{
    $page = (isset($_GET['page']) ? (int)$_GET['page'] : 1);
    $category = $container->get(VirtualStore\Models\Category::class);
    $category->get((int) $vars['idcategory']);
    $productsPage = $category->getProductsPage($page);
    $pages = [];
    for ($i=1; $i <= $productsPage['pages']; $i++) {
        array_push($pages, [
            'link' => '/category'.'/'.$category->getidcategory().'?page='.$i,
            'page' => $i
        ]);
    }
    $page = $container->get(VirtualStore\Page::class);
    $page->renderPage('category', ['category' => $category->getValues(), 'products' => $productsPage['data'], 'pages' => $pages]);

}

function productsDetails($vars, $container)
{
    $product = $container->get(VirtualStore\Models\Product::class);
    $product->getFromUrl($vars['desurl']);
    $page = $container->get(VirtualStore\Page::class);
    $page->renderPage('product-detail', ['product' => $product->getValues(), 'categories' => $product->getCategories()]);
}

function checkout($vars, $container)
{
    VirtualStore\Models\User::verifyLogin(false);
    $address = $container->get(VirtualStore\Models\Address::class);
    $cart = VirtualStore\Models\Cart::getFromSession();

    if (isset($_GET['zipcode'])) {
        $_GET['zipcode'] = $cart->getdeszipcode();
    
        $address->loadFromCEP($_GET['zipcode']);
        
        $cart->setdeszipcode($_GET['zipcode']);
        $cart->save();
        $cart->getCalculateTotal();
    }

    if (!$address->getdesaddress()) $address->setdesaddress('');
    if (!$address->getdescomplement()) $address->setdescomplement('');
    if (!$address->getdesdistrict()) $address->setdesdistrict('');
    if (!$address->getdescity()) $address->setdescity('');
    if (!$address->getdesstate()) $address->setdesstate('');
    if (!$address->getdescountry()) $address->setdescountry('');
    if (!$address->getdeszipcode()) $address->setdeszipcode('');


    $page = $container->get(VirtualStore\Page::class);
    $page->renderPage('checkout', ['cart' => $cart->getValues(), 'address' => $address->getValues(), 'products' => $cart->getProducts(), 'error' => VirtualStore\Message::getMessage()]);
}

function checkoutPost($vars, $container) {
    VirtualStore\Models\User::verifyLogin(false);

    $fields = ['zipcode', 'desaddress', 'desdistrict', 'descity', 'desstate', 'descountry'];
    $friendlyMsg = [
        'zipcode' => 'CEP',
        'desaddress' => 'endereço',
        'desdistrict' => 'bairro',
        'descity' => 'cidade',
        'desstate' => 'estado',
        'descountry' => 'país'
    ];

    foreach($fields as $field) {
        if(!isset($_POST[$field]) || $_POST[$field] === '') {
            $msg = $friendlyMsg[$field];
            $errorMsg = 'Informe o(a) '. $msg;
            VirtualStore\Message::setMessage($errorMsg, 'error');
            header("Location: /checkout");
            exit;
        }
    }

    $user = VirtualStore\Models\User::getFromSession();

    $address = $container->get(VirtualStore\Models\Address::class);

    $_POST['deszipcode'] = $_POST['zipcode'];
    $_POST['idperson'] = $user->getidperson();

    $address->setData($_POST);
    $address->save();

    $cart = VirtualStore\Models\Cart::getFromSession();   
    $values = $cart->getValues();
    $total = $values['vltotal'];

    $order = $container->get(VirtualStore\Models\Order::class);
    $order->setData([
        'idcart' => $cart->getidcart(),
        'idaddress' => $address->getidaddress(),
        'iduser' => $user->getiduser(),
        'idstatus' => VirtualStore\Models\OrderStatus::EM_ABERTO,
        'vltotal' => $total
    ]);

    $order->save();

    header("Location: /order/".$order->getidorder());
    exit;    
}

function order($vars, $container)
{
    VirtualStore\Models\User::verifyLogin(false);

    $order = $container->get(VirtualStore\Models\Order::class);
    $order->get((int)$vars['idorder']);

    $page = $container->get(VirtualStore\Page::class);
    $page->renderPage('payment', ['order' => $order->getValues()]);
}

function boleto($vars, $container)
{   
    VirtualStore\Models\User::verifyLogin(false);
    $order = $container->get(VirtualStore\Models\Order::class);
    $order->get((int)$vars['idorder']);    

    // DADOS DO BOLETO PARA O SEU CLIENTE
    $dias_de_prazo_para_pagamento = 10;
    $taxa_boleto = 5.00;
    $data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006"; 
    $valor_cobrado = $order->getvltotal(); // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
    $valor_cobrado = str_replace(",", ".",$valor_cobrado);
    $valor_boleto = number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

    $dadosboleto["nosso_numero"] = $order->getidorder();  // Nosso numero - REGRA: Máximo de 8 caracteres!
    $dadosboleto["numero_documento"] = $order->getidorder();	// Num do pedido ou nosso numero
    $dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
    $dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
    $dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
    $dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

    // DADOS DO SEU CLIENTE
    $dadosboleto["sacado"] = $order->getdesperson();
    $dadosboleto["endereco1"] = $order->getdesaddress() ." - ". $order->getdesdistrict();
    $dadosboleto["endereco2"] = $order->getdescity()." - ".$order->getdesstate().' - '. "CEP: ".$order->getdeszipcode();

    // INFORMACOES PARA O CLIENTE
    $dadosboleto["demonstrativo1"] = "Pagamento de Compra na Loja Hcode E-commerce";
    $dadosboleto["demonstrativo2"] = "Taxa bancária - R$ 0,00";
    $dadosboleto["demonstrativo3"] = "";
    $dadosboleto["instrucoes1"] = "- Sr. Caixa, cobrar multa de 2% após o vencimento";
    $dadosboleto["instrucoes2"] = "- Receber até 10 dias após o vencimento";
    $dadosboleto["instrucoes3"] = "- Em caso de dúvidas entre em contato conosco: suporte@hcode.com.br";
    $dadosboleto["instrucoes4"] = "&nbsp; Emitido pelo sistema Projeto Loja Hcode E-commerce - www.hcode.com.br";

    // DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
    $dadosboleto["quantidade"] = "";
    $dadosboleto["valor_unitario"] = "";
    $dadosboleto["aceite"] = "";		
    $dadosboleto["especie"] = "R$";
    $dadosboleto["especie_doc"] = "";


    // ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //


    // DADOS DA SUA CONTA - ITAÚ
    $dadosboleto["agencia"] = "1690"; // Num da agencia, sem digito
    $dadosboleto["conta"] = "48781";	// Num da conta, sem digito
    $dadosboleto["conta_dv"] = "2"; 	// Digito do Num da conta

    // DADOS PERSONALIZADOS - ITAÚ
    $dadosboleto["carteira"] = "175";  // Código da Carteira: pode ser 175, 174, 104, 109, 178, ou 157

    // SEUS DADOS
    $dadosboleto["identificacao"] = "Virtual Store";
    $dadosboleto["cpf_cnpj"] = "00.000.000/0000-00";
    $dadosboleto["endereco"] = "Rua teste - Alvarenga, 00000-000";
    $dadosboleto["cidade_uf"] = "Rio de Janeiro - RJ";
    $dadosboleto["cedente"] = "Virtual Store";

    // NÃO ALTERAR!
    $path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "res" . DIRECTORY_SEPARATOR . "boletophp" . DIRECTORY_SEPARATOR;
    require_once($path . "include/funcoes_itau.php"); 
    require_once($path . "include/layout_itau.php");
}
