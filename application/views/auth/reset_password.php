<div id="login-form" class="row"> 
    <div class="col-md-4 m-auto">
        <?php if(isset($msg) || validation_errors() !== ''): ?>
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-warning"></i> Attention!</h4>
            <?= validation_errors();?>
            <?= isset($msg)? $msg: ''; ?>
        </div>
        <?php endif; ?>
        <div class="form-box">
            <div class="caption text-center">
                <h4>Réinitialiser<br />le mot de passe</h4>
            </div>
            <?php echo form_open(base_url('auth/reset_password/'.$reset_code), 'class="login-form mt-0" '); ?>
                <div class="">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Mot de passe" >
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirmer" >
                    <div class="row">
                  </div>
                    <div class="row smooth-scroll">
              <div class="col-md-12 text-center">
                  <input type="submit" name="submit" id="submit" class="btn btn-rounded btn-primary" value="Envoyer">
              </div>
            </div>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
