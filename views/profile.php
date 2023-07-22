
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
                <?php if (isset($message['msg']) && $message['type'] == 'success'): ?>
                <div class="alert alert-success">
                    <?=$message['msg']?>
                </div>
                <?php endif; ?>
                <?php if (isset($message['msg']) && $message['type'] == 'error'): ?>
                <div class="alert alert-danger">
                    <?=$message['msg']?>
                </div>
                <?php endif; ?>  
                <form method="post" action="/profile">
                    <div class="form-group">
                    <label for="desperson">Nome completo</label>
                    <input type="text" class="form-control" id="desperson" name="desperson" placeholder="Digite o nome aqui" value="<?=$user['desperson']?>">
                    </div>
                    <div class="form-group">
                    <label for="desemail">E-mail</label>
                    <input type="email" class="form-control" id="desemail" name="desemail" placeholder="Digite o e-mail aqui" value="<?=$user['desemail']?>">
                    </div>
                    <div class="form-group">
                    <label for="nrphone">Telefone</label>
                    <input type="tel" class="form-control" id="nrphone" name="nrphone" placeholder="Digite o telefone aqui" value="<?=$user['nrphone']?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>