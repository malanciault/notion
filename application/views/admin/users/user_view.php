<section class="content">

  <div class="row">
    <div class="col-md-12">
      <div class="box box-body with-border">
        <div class="col-md-4">
          <h4><i class="fal fa-user"></i> &nbsp;<?=$record['firstname'] . ' ' . $record['lastname']?> </h4>
        </div>
        <div class="col-md-8 text-right">
            <!--<a class="btn btn-success" data-href="<?=site_url('admin/users/password_reset/' . $user_id);?>" data-toggle="modal" data-target="#confirm-reset-password" role="button">
                Réinitialiser mot de passe</a>//-->
            <a class="btn btn-success" href="<?=site_url('admin/users/edit/' . $user_id);?>" role="button">
              Éditer</a>
          <a href="<?= base_url('admin/users'); ?>" class="btn btn-success"><i class="fa fa-list"></i> Utilisateurs</a>

        </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="box box-body">
          <div class="col-md-6">
            <h4><i class="fa fa-list"></i> Commandes</h4>
          </div>
          <div class="col-md-6 text-right">
          </div>
        </div>
      </div>
    </div>

    <div class="box">
        <div class="box-header"></div>
    <!-- /.box-header -->
    <div class="box-body table-responsive">
      <table id="order_datatable" class="table table-bordered table-striped" width="100%">
        <thead>
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>CO2</th>
            <th>Total</th>
            <th>Projet</th>
            <th>Statut</th>
            <th>Action</th>
        </tr>
        </thead>
      </table>
    </div>
    <!-- /.box-body -->
  </div>
  <!-- /.box -->
    </div>
  </div>
</section>
<? 
  $data['class'] = 'order';
  $this->load->view('templates/delete-confirm-modal', $data); 
?>


  <!-- DataTables -->
  <script src="<?= base_url() ?>public/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="<?= base_url() ?>public/plugins/datatables/dataTables.bootstrap.min.js"></script>
  <script>
  //---------------------------------------------------

  var table = $('#order_datatable').DataTable( {
      "processing": true,
      "serverSide": true,
      "ajax": "<?=base_url('admin/order/user_datatable_json/' . $user_id)?>",
      "order": [[0,'desc']],
      "columnDefs": [
          { "targets": 0, "name": "order_id", 'searchable':true, 'orderable':true},
          { "targets": 1, "name": "order_date", 'searchable':false, 'orderable':true},
          { "targets": 2, "name": "order_co2", 'searchable':false, 'orderable':true},
          { "targets": 3, "name": "order_total", 'searchable':false, 'orderable':true},
          { "targets": 4, "name": "project_key", 'searchable':true, 'orderable':true},
          { "targets": 5, "name": "order_status", 'searchable':true, 'orderable':true},
          { "targets": 6, "name": "Actions", 'searchable':false, 'orderable':false,'width':'80px'},
      ]
    });
  </script>
  <script type="text/javascript">
      $('#confirm-delete').on('show.bs.modal', function(e) {
      $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    });
  </script>


<div id="confirm-resend-activation" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Renvoyer le courriel d'activation</h4>
            </div>
            <div class="modal-body">
                <p>Voulez-vous vraiment envoyer à nouveau le courrield d'activation à cet utilisateur?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                <a id="btn-confirm-resend-activation" class="btn btn-danger btn-ok">Envoyer</a>
            </div>
        </div>
    </div>
</div>

<div id="confirm-reset-password" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Réinitialisation de mot de passe</h4>
            </div>
            <div class="modal-body">
                <p>Voulez-vous vraiment réinitialiser le mot de passe de cet utilisateur?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                <a id="btn-confirm-reset-password" class="btn btn-danger btn-ok">Réinitialiser</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $('#confirm-resend-activation').on('show.bs.modal', function(e) {
        $('#btn-confirm-resend-activation').attr('href', $(e.relatedTarget).attr('data-href'));
    });

    $('#confirm-reset-password').on('show.bs.modal', function(e) {
        $('#btn-confirm-reset-password').attr('href', $(e.relatedTarget).attr('data-href'));
    });
</script>
