<div class="product-big-title-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h2><?php echo $category['descategory'] ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <?php foreach ($products as $product): ?>
            <div class="col-md-3 col-sm-6">
                <div class="single-shop-product">
                    <div class="product-upper">
                        <img src="/res/site/img/products/<?=$product['idproduct'].'.jpg'?>" alt="">
                    </div>
                    <h2><a href="/products/<?=$product['desurl']?>"><?=$product['desproduct']?></a></h2>
                    <div class="product-carousel-price">
                        <ins>R$<?php echo formatPrice($product['vlprice'])?></ins>
                    </div>  
                    
                    <div class="product-option-shop">
                        <a class="add_to_cart_button" data-quantity="1" data-product_sku="" data-product_id="70" rel="nofollow" href="/canvas/shop/?add-to-cart=70">Comprar</a>
                    </div>                       
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="product-pagination text-center">
                    <nav>
                        <ul class="pagination">
                        <li>
                            <a href="#" aria-label="Previous">
                            <span aria-hidden="true">«</span>
                            </a>
                        </li>
                        <?php foreach($pages as $page): ?>
                        <li><a href="<?php echo $page['link'] ?>"><?= $page['page']?></a></li>
                        <?php endforeach; ?>
                        </ul>
                    </nav>                        
                </div>
            </div>
        </div>
    </div>
</div>