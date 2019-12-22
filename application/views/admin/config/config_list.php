  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?= base_url() ?>public/plugins/datepicker/datepicker3.css">

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-body">
          <div class="col-md-6">
            <h4><i class="fa fa-list"></i> &nbsp; Gestion des configs</h4>
          </div>
          <div class="col-md-6 text-right">

          </div>
        </div>
      </div>
    </div>

    <div class="box">
    <div class="box-header">
    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive">
      <table id="na_datatable" class="table table-bordered table-striped" width="100%">
        <thead>
        <tr>
          <th>Nom</th>
          <th>Valeur</th>
          <th>Actions</th>
        </tr>
        </thead>
      </table>
    </div>
    <!-- /.box-body -->
  </div>
</section>

<? $this->load->view('templates/delete-confirm-modal'); ?>


  <!-- bootstrap datepicker -->
  <script src="<?= base_url() ?>public/plugins/datepicker/bootstrap-datepicker.js"></script>
  <script>
    $('.datepicker').datepicker({
      autoclose: true
    });
  </script>
  <!-- DataTables -->
  <script src="<?= base_url() ?>public/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="<?= base_url() ?>public/plugins/datatables/dataTables.bootstrap.min.js"></script>
  <script>
  //---------------------------------------------------
  var table = $('#na_datatable').DataTable( {
      "processing": true,
      "serverSide": true,
      "ajax": "<?=base_url('admin/config/datatable_json')?>",
      "order": [[0,'asc']],
      "columnDefs": [
        { "targets": 0, "name": "config_key", 'searchable':true, 'orderable':true},
        { "targets": 1, "name": "config_value", 'searchable':true, 'orderable':true},
        { "targets": 2, "name": "Actions", 'searchable':false, 'orderable':false,'width':'80px'},
      ]
    });

  </script>