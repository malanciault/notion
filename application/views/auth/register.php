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
                <h4><?=__("Inscription")?></h4>
                <h5><?=__("Entrez vos information pour vous inscrire gratuitement.")?></h5>
            </div>
            <?php echo form_open(base_url('auth/register'), 'class="login-form mt-1" '); ?>
                <div class="">
                    <input type="text" name="firstname" id="firstname" class="form-control" value="<?=isset($firstname) ? $firstname : '' ?>" placeholder="<?=__('Prénom')?>" >
                    <input type="text" name="lastname" id="lastname" class="form-control" value="<?=isset($lastname) ? $lastname : '' ?>" placeholder="<?=__('Nom')?>" >
                    <input type="text" name="email" id="email" class="form-control" value="<?=isset($email) ? $email : '' ?>" placeholder="<?=__('Courriel')?>" >
                    <input type="password" name="password" id="password" class="form-control" placeholder="<?=__('Mot de passe')?>" >
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="<?=__('Confirmer le mot de passe')?>" >
                </div>
                <div class="row smooth-scroll">
              <div class="col-md-12 text-center">
                  <input type="submit" name="submit" id="submit" class="btn btn-rounded btn-primary" value="<?=__('Inscription')?>">
              </div>
            </div>
            <p class="text-center small">
                <a href="<?= base_url('auth/login'); ?>"><?=__('Connexion')?></a>
              </p>
            <?php echo form_close(); ?>
        </div>

    </div>
</div>