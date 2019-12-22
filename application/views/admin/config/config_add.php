<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-body with-border">
        <div class="col-md-6">
          <h4><i class="fa fa-plus"></i> &nbsp; <?=$page_title;?></h4>
        </div>
        <div class="col-md-6 text-right">
          <a href="<?= base_url('admin/config'); ?>" class="btn btn-success"><i class="fa fa-list"></i> Configs</a>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <div class="box-body my-form-body">
          <?php if(isset($msg) || validation_errors() !== ''): ?>
              <div class="alert alert-warning alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                  <?= validation_errors();?>
                  <?= isset($msg)? $msg: ''; ?>
              </div>
            <?php endif; ?>
           
            <?php echo form_open_multipart(base_url('admin/config/post'), 'class="form-horizontal"');  ?>

              <div class="form-group">
                <label for="config_key" class="col-sm-2 control-label">Clé</label>
                <div class="col-sm-9">
                  <input type="text" name="config_key" class="form-control" id="config_key" value="<?=isset($record['config_key']) ? $record['config_key'] : '';?>">
                </div>
              </div>
                <div class="form-group">
                  <label for="config_value" class="col-sm-2 control-label">Valeur</label>
                  <div class="col-sm-9">
                    <input type="text" name="config_value" class="form-control" id="config_value" value="<?=isset($record['config_value']) ? $record['config_value'] : '';?>">
                  </div>
                </div>

              <div class="form-group">
                <div class="col-md-11">
                  <input type="hidden" name="op" value="<?=$op;?>">
                  <?if($op == 'edit') :?>
                    <input type="hidden" name="config_id" value="<?=$config_id;?>">
                  <?endif;?>
                  <input type="submit" name="submit" value="<?=$form_button;?>" class="btn btn-info pull-right">
                </div>
              </div>
              
            <?php echo form_close( ); ?>
          </div>
          <!-- /.box-body -->
      </div>
    </div>
  </div>  
</section> 

<script src="<?= base_url() ?>public/plugins/datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/plugins/mdb-fileupload/js/addons/mdbFileupload.js"></script>

<!-- Page script -->
<script>
    $('.mdb_upload').mdb_upload();
</script>