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
        <?php if($this->session->flashdata('warning')): ?>
              <div class="alert alert-warning">
              <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
              <?=$this->session->flashdata('warning')?>
            </div>
        <?php endif; ?>
        <?php if($this->session->flashdata('success')): ?>
              <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                <?=$this->session->flashdata('success')?>
              </div>
        <?php endif; ?>
        <div class="form-box">
            <div class="caption text-center">
                <h4><?=__('Connexion')?></h4>
            </div>
            <?php echo form_open(base_url('auth/login'), 'class="login-form mt-1" '); ?>

            <input type="text" name="email" id="name" class="form-control" placeholder="<?=__('Courriel')?>" >
            <input type="password" name="password" id="password" class="form-control" placeholder="<?=__('Mot de passe')?>" >
            <div class="row">
              <div class="col text-center">
                <input class="justify-content-center" type="hidden" name="back_to" id="back_to" value="<?=$back_to;?>">
              </div>
            </div>
            <div class="row smooth-scroll">
              <div class="col-md-12 text-center">
                  <input type="submit" name="submit" id="submit" class="btn btn-rounded btn-primary" value="<?=__('Connexion')?>">
              </div>
            </div>
            <p class="text-center small">
                <a href="<?= base_url('auth/forgot_password'); ?>"><?=__('Mot de passe oublié ?')?></a> | <a href="<?= base_url('auth/register'); ?>"><?=__('Créer un compte')?></a>
              </p>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>