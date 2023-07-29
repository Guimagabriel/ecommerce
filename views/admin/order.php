<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <h1>
        Pedido N°<?=$order['idorder']?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/admin"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="/admin/orders">Pedidos</a></li>
        <li class="active"><a href="/admin/orders/<?=$order['idorder']?>">Pedido N°<?=$order['idorder']?></a></li>
    </ol>
    </section>

    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
            <h2 class="page-header">
                <img src="/res/site/img/logo.png" alt="Logo">
                <small class="pull-right">Date: <?php echo date('d/m/Y'); ?></small>
            </h2>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
            De
            <address>
                <strong>Virtual Store</strong><br>
                Rio de Janeiro - RJ<br>
                Telefone: <br>
                E-mail: suporte@teste.com.br
            </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
            Para
            <address>
                <strong><?=$order['desperson']?></strong><br>
                <?=$order['desaddress']?>, <?=$order['descomplement']?><br>
                <?=$order['descity']?> - <?=$order['desstate']?><br>
                <?php if($order['nrphone'] && $order['nrphone'] !='0'):?> Telefone: <?=$order['nrphone']?><br><?php endif; ?>
                E-mail: <?=$order['desemail']?>
            </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
            <b>Pedido #<?=$order['idorder']?></b><br>
            <br>
            <b>Emitido em:</b> <?php echo formatDate($order['dtregister'])?><br>
            <b>Pago em:</b> <?php echo formatDate($order['dtregister'])?>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    
        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Qtd</th>
                    <th>Produto</th>
                    <th>Código #</th>
                    <th>Subtotal</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo $product['nrqtd']?></td>
                    <td><?php echo $product['desproduct']?></td>
                    <td><?=$product['idproduct']?></td>
                    <td>R$ <?php echo formatPrice($order['vltotal'])?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    
        <div class="row">
            <!-- accepted payments column -->
            <div class="col-xs-6">

                <p class="lead">Forma de Pagamento</p>
                
                <table class="table">
                    <tbody>
                    <tr>
                        <th style="width:180px;">Método de Pagamento:</th>
                        <td>Boleto</td>
                    </tr>
                    <tr>
                        <th>Parcelas:</th>
                        <td>1x</td>
                    </tr>
                    <!--
                    <tr>
                        <th>Valor da Parcela:</th>
                        <td>R$100,00</td>
                    </tr>
                    -->
                    </tbody>
                </table>

            </div>
            <!-- /.col -->
            <div class="col-xs-6">
            <p class="lead">Resumo do Pedido</p>
    
            <div class="table-responsive">
                <table class="table">
                <tbody><tr>
                    <th style="width:50%">Subtotal:</th>
                    <td>R$<?php echo formatPrice($cart['vlsubtotal'])?></td>
                </tr>
                <tr>
                    <th>Frete:</th>
                    <td>R$<?php echo formatPrice($order['vlfreight'])?></td>
                </tr>
                <tr>
                    <th>Total:</th>
                    <td>R$<?php echo formatPrice($cart['vltotal'])?></td>
                </tr>
                </tbody></table>
            </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    
        <!-- this row will not appear when printing -->
        <div class="row no-print">
            <div class="col-xs-12">
                <button type="button" onclick="window.location.href = '/admin/orders/<?=$order['idorder']?>/status'" class="btn btn-default pull-left" style="margin-left: 5px;">
                    <i class="fa fa-pencil"></i> Editar Status
                </button>
                <button type="button" onclick="window.open('/boleto/<?=$order['idorder']?>')" class="btn btn-default pull-left" style="margin-left: 5px;">
                    <i class="fa fa-barcode"></i> Boleto
                </button>

                
                <button type="button" onclick="window.print()" class="btn btn-primary pull-right" style="margin-right: 5px;">
                    <i class="fa fa-print"></i> Imprimir
                </button>
            </div>
        </div>
    </section>

    <div class="clearfix"></div>

</div>
<!-- /.content-wrapper -->