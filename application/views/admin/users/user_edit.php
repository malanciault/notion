<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-body">
        <div class="col-md-6">
          <h4><i class="fa fa-pencil"></i> &nbsp; Edit User</h4>
        </div>
        <div class="col-md-6 text-right">
          <a class="btn btn-success" href="<?=site_url('admin/users/view/' . $user['user_id']);?>" role="button">
              Voir</a>
          <a href="<?= base_url('admin/users'); ?>" class="btn btn-success"><i class="fa fa-list"></i> Utilisateurs</a>
        </div>
        
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Edit User</h3>
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
           
            <?php echo form_open(base_url('admin/users/edit/'.$user['user_id']), 'class="form-horizontal"' )?>
              <div class="form-group">
                <label for="firstname" class="col-sm-2 control-label">First Name</label>

                <div class="col-sm-9">
                  <input type="text" name="firstname" value="<?= $user['firstname']; ?>" class="form-control" id="firstname" placeholder="">
                </div>
              </div>

              <div class="form-group">
                <label for="lastname" class="col-sm-2 control-label">Last Name</label>

                <div class="col-sm-9">
                  <input type="text" name="lastname" value="<?= $user['lastname']; ?>" class="form-control" id="lastname" placeholder="">
                </div>
              </div>

              <div class="form-group">
                <label for="email" class="col-sm-2 control-label">Email</label>

                <div class="col-sm-9">
                  <input type="email" name="email" value="<?= $user['email']; ?>" class="form-control" id="email" placeholder="">
                </div>
              </div>

              <div class="form-group">
                <label for="is_verify" class="col-sm-2 control-label">Verified ?</label>

                <div class="col-sm-9">
                  <input type="text" name="is_verify" value="<?= $user['is_verify']; ?>" class="form-control" id="is_verify" placeholder="">
                </div>
              </div>

              <div class="form-group">
                <label for="role" class="col-sm-2 control-label">Select Status</label>

                <div class="col-sm-9">
                  <select name="status" class="form-control">
                    <option value="">Select Status</option>
                    <option value="1" <?= ($user['is_active'] == 1)?'selected': '' ?> >Active</option>
                    <option value="0" <?= ($user['is_active'] == 0)?'selected': '' ?>>Deactive</option>
                  </select>
                </div>
              </div>
             <div class="form-group">
                <label for="role" class="col-sm-2 control-label">Select Group</label>

                <div class="col-sm-9">
                  <select name="group" class="form-control">
                    <option value="">Select Group</option>
                    <?php foreach($user_groups as $group): ?>
                      <?php if($group['id'] == $user['role']): ?>
                      <option value="<?= $group['id']; ?>" selected><?= $group['group_name']; ?></option>
                      <?php else: ?>
                      <option value="<?= $group['id']; ?>"><?= $group['group_name']; ?></option>
                      <?php endif; ?>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>



                <div class="form-group">
                    <label for="expiration_date" class="col-sm-2 control-label">Expiration du compte</label>
                    <div class="col-sm-9">
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" name="expiration_date" class="form-control pull-right" id="expiration_date" value="<?=isset($user['expiration_date']) ? $user['expiration_date'] : '';?>">
                        </div>
                        <input type="checkbox" name="expiration_clear" value="clear"> Supprimer la date d'expiration<br>
                    </div>
                </div>

              <div class="form-group">
                <label for="password" class="col-sm-2 control-label">Password</label>
                <div class="col-sm-9">
                  <input type="password" name="password" value="" class="form-control" id="password" placeholder="">
                </div>
              </div>
              <div class="form-group">
                <label for="password" class="col-sm-2 control-label">Password Validation</label>
                <div class="col-sm-9">
                  <input type="password" name="password2" value="" class="form-control" id="password2" placeholder="">
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-11">
                  <input type="submit" name="submit" value="Update User" class="btn btn-info pull-right">
                </div>
              </div>
            <?php echo form_close(); ?>
          </div>
          <!-- /.box-body -->
      </div>
    </div>
  </div>  

</section>

<script src="<?= base_url() ?>public/plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- Page script -->
<script>
    $(function () {

        //Date picker
        $('#expiration_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            closeText: 'Clear',
        }).keyup(function(e) {
            if(e.keyCode == 8 || e.keyCode == 46) {
                $.datepicker._clearDate(this);
            }
        });
    });
</script>