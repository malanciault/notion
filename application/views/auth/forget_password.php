<div id="login-form" class="row"> 
    <div class="col-md-4 m-auto">
        <?if ($email_exists):?>
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-warning"></i>Attention!</h4>
                <?=__("Il semble que vous <b>possédiez déjà un compte sur notre plateforme</b>.<br/><br/>Veuillez cliquez sur le bouton suivant afin de réinitialiser votre mot de passe. <br/><br/>Vous recevrez un courriel contenant les informations pour choisir un nouveau mot de passe.");?>
            </div>
        <?endif;?>

        <?php if(validation_errors() !== ''): ?>
          <div class="alert alert-warning alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h4><i class="icon fa fa-warning"></i> Attention!</h4>
              <?= validation_errors();?>
              <?= isset($msg)? $msg: ''; ?>
          </div>
        <?php endif; ?>
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success">
              <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
              <?=$this->session->flashdata('success')?>
            </div>
        <?php endif; ?>
        <?php if($this->session->flashdata('warning')): ?>
            <div class="alert alert-warning">
              <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
              <?=$this->session->flashdata('warning')?>
            </div>
        <?php endif; ?>
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger">
              <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
              <?=$this->session->flashdata('error')?>
            </div>
        <?php endif; ?>
        <div class="form-box">
            <div class="caption text-center">
                <h4><?=__('Mot de passe oublié')?></h4>
            </div>            <?php echo form_open(base_url('auth/forgot_password'), 'class="login-form mt-1" '); ?>
                <div class="">
                    <input type="text" name="email" id="email" class="form-control" value="<?=$email_exists ? $email_exists : ''?>" placeholder="<?=__('Adresse courriel')?>" >
                </div>
            <div class="row smooth-scroll">
              <div class="col-md-12 text-center">
                  <input type="submit" name="submit" id="submit" class="btn btn-rounded btn-primary" value="<?=__('Envoyer')?>">
              </div>
            </div>
            <p class="text-center small">
                <a href="<?= base_url('auth/login'); ?>"><?=__('Connexion')?></a> | <a href="<?= base_url('auth/register'); ?>"><?=__('Créer un compte')?></a>
              </p>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>