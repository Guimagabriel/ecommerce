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

    <!-- Main content -->
    <section class="content">
        
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Editar Status do Pedido</h3>
            </div>
            <!-- /.box-header -->
            <?php if($msg != '' && $msg['type'] == 'error'):?> 
            <div class="alert alert-danger alert-dismissible" style="margin:10px">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <p><?php echo $msg['msg']?></p>
            </div>
            <?php endif; ?>
            <?php if($msg != '' && $msg['type'] == 'success'):?>
            <div class="alert alert-success alert-dismissible" style="margin:10px">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <p><?php echo $msg['msg']?></p>
            </div>
            <?php endif; ?>
            <!-- form start -->
            <form role="form" action="/admin/orders/<?=$order['idorder']?>/status" method="post">
                <div class="box-body">
                    <div class="form-group">
                        <label for="desproduct">Status do Pedido</label>
                        <select class="form-control" name="idstatus">
                            <?php foreach($status as $sts): ?>
                            <option <?php if($sts['idstatus'] === $order['idstatus']):?>selected="selected"<?php endif;?> value="<?=$sts['idstatus']?>"><?=$sts['desstatus']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
            </div>
            </div>
        </div>
    
    </section>
    <!-- /.content -->

    <div class="clearfix"></div>

</div>
<!-- /.content-wrapper -->