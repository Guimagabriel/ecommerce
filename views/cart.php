<div class="product-big-title-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h2>Carrinho de Compras</h2>
                </div>
            </div>
        </div>
    </div>
</div> <!-- End Page title area -->

<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            
            <div class="col-md-12">
                <div class="product-content-right">
                    <div class="woocommerce">

                        <form action="/checkout">
                            
                            <?php if($error != ""): ?>
                            <div class="alert alert-danger" role="alert">
                            <?php echo $error; ?>
                            </div>
                            <?php endif; ?>
                            <table cellspacing="0" class="shop_table cart">
                                <thead>
                                    <tr>
                                        <th class="product-remove">&nbsp;</th>
                                        <th class="product-thumbnail">&nbsp;</th>
                                        <th class="product-name">Produto</th>
                                        <th class="product-price">Preço</th>
                                        <th class="product-quantity">Quantidade</th>
                                        <th class="product-subtotal">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                    <tr class="cart_item">
                                        <td class="product-remove">
                                            <a title="Remove this item" class="remove" href="/cart/<?=$product['idproduct']?>/remove">×</a> 
                                        </td>

                                        <td class="product-thumbnail">
                                            <a href="/products/<?=$product['desurl']?>"><img width="145" height="145" alt="poster_1_up" class="shop_thumbnail" src="<?=$product['desphoto']?>"></a>
                                        </td>

                                        <td class="product-name">
                                            <a href="/products/<?=$product['desurl']?>"><?php echo $product['desproduct'];?></a> 
                                        </td>

                                        <td class="product-price">
                                            <span class="amount">R$ <?php echo formatPrice($product['vlprice']);?></span> 
                                        </td>

                                        <td class="product-quantity">
                                            <div class="quantity buttons_added">
                                                <input type="button" class="minus" value="-" onclick="window.location.href = '/cart/<?=$product['idproduct']?>/minus'">
                                                <input type="number" size="4" class="input-text qty text" title="Qty" value="<?=$product['nrqtd']?>" min="0" step="1">
                                                <input type="button" class="plus" value="+" onclick="window.location.href = '/cart/<?=$product['idproduct']?>/add'">
                                            </div>
                                        </td>

                                        <td class="product-subtotal">
                                            <span class="amount">R$ <?php echo formatPrice($product['vltotal']);?></span> 
                                        </td>
                                    </tr>
                                  <?php endforeach; ?>  
                                </tbody>
                            </table>

                            <div class="cart-collaterals">

                                <div class="cross-sells">

                                    <h2>Cálculo de Frete</h2>
                                    
                                    <div class="coupon">
                                        <label for="cep">CEP:</label>
                                        <input type="text" placeholder="00000-000" value="<?=$cart['deszipcode']?>" id="cep" class="input-text" name="zipcode">
                                        <input type="submit" formmethod="post" formaction="/cart/freight" value="CÁLCULAR" class="button">
                                    </div>

                                </div>

                                <div class="cart_totals ">

                                    <h2>Resumo da Compra</h2>

                                    <table cellspacing="0">
                                        <tbody>
                                            <tr class="cart-subtotal">
                                                <th>Subtotal</th>
                                                <td><span class="amount">R$ <?php echo formatPrice($cart['vlsubtotal']);?></span></td>
                                            </tr>

                                            <tr class="shipping">
                                                <th>Frete</th>
                                                <td>R$ <?php echo formatPrice($cart['vlfreight']);?> <?php if ($cart['nrdays'] > 0):?><small>prazo de <?=$cart['nrdays']?> dia(s)</small> <?php endif;?></td>
                                            </tr>

                                            <tr class="order-total">
                                                <th>Total</th>
                                                <td><strong><span class="amount">R$ <?php echo formatPrice($cart['vltotal']); ?></span></strong> </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                            <div class="pull-right">
                                <input type="submit" value="Finalizar Compra" name="proceed" class="checkout-button button alt wc-forward">
                            </div>

                        </form>

                    </div>                        
                </div>                    
            </div>
        </div>
    </div>
</div>