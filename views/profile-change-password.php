
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
                <?php include "profile-menu.php" ?>
            </div>
            <div class="col-md-9">
                <div class="cart-collaterals">
                    <h2>Alterar Senha</h2>
                </div>

                <?php if (isset($error['msg']) && $error['type'] == 'error'): ?>
                <div class="alert alert-danger">
                    <?=$error['msg']?>
                </div>
                <?php endif; ?>

                <?php if (isset($error['msg']) && $error['type'] == 'success'):?>
                <div class="alert alert-success">
                    <?=$error['msg']?>
                </div>
                <?php endif; ?>
                
                <form action="/profile/change-password" method="post">
                    <div class="form-group">
                    <label for="current_pass">Senha Atual</label>
                    <input type="password" class="form-control" id="current_pass" name="current_pass">
                    </div>
                    <hr>
                    <div class="form-group">
                    <label for="new_pass">Nova Senha</label>
                    <input type="password" class="form-control" id="new_pass" name="new_pass">
                    </div>
                    <div class="form-group">
                    <label for="new_pass_confirm">Confirme a Nova Senha</label>
                    <input type="password" class="form-control" id="new_pass_confirm" name="new_pass_confirm">
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </form>

            </div>
        </div>
    </div>
</div>