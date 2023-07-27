
<div class="product-big-title-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h2>Minha Conta</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">                
            <div class="col-md-3">
                <?php include "profile-menu.php"; ?>
            </div>
            <div class="col-md-9">
                
                <div class="cart-collaterals">
                    <h2>Meus Pedidos</h2>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Valor Total</th>
                            <th>Status</th>
                            <th>Endereço</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <th scope="row"><?=$order['idorder']?></th>
                            <td>R$ <?php echo formatPrice($order['vltotal'])?></td>
                            <td><?=$order['desstatus']?></td>
                            <td><?=$order['desaddress']?>, <?=$order['desdistrict']?>, <?=$order['descity']?> - , <?=$order['desstate']?> CEP: <?=$order['deszipcode']?></td>
                            <td style="width:222px;">
                                <a class="btn btn-success" href="/order/<?=$order['idorder']?>" role="button">Imprimir Boleto</a>
                                <a class="btn btn-default" href="/profile/orders/<?=$order['idorder']?>" role="button">Detalhes</a>
                            </td>
                        </tr>
                        <?php if(!isset($orders) || $orders == NULL): ?>
                        <div class="alert alert-info">
                            Nenhum pedido foi encontrado.
                        </div>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>