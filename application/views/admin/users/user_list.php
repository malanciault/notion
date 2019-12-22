  
 <section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-body">
        <div class="col-md-6">
          <h4><i class="fa fa-list"></i> &nbsp; Utilisateurs</h4>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?= base_url('admin/users/export'); ?>" class="btn btn-success"><i class="fa fa-plus"></i> Exporter les utilisateurs</a>
            <a href="<?= base_url('admin/users/add'); ?>" class="btn btn-success"><i class="fa fa-plus"></i> Ajouter un utilisateur</a>

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
          <th>Creation</th>
          <th>Last login</th>
          <th>Email</th>
          <th>Source</th>
          <th>Role</th>
          <th>Group</th>
          <th>Status</th>
          <th>Mixpanel</th>
          <th>Action</th>
        </tr>
        </thead>
      </table>
    </div>
    <!-- /.box-body -->
  </div>
  <!-- /.box -->
</section>  

<!-- Modal -->
<div id="confirm-delete" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Delete</h4>
      </div>
      <div class="modal-body">
        <p>As you sure you want to delete.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <a class="btn btn-danger btn-ok">Delete</a>
      </div>
    </div>

  </div>
</div>


  <!-- DataTables -->
  <script src="<?= base_url() ?>public/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="<?= base_url() ?>public/plugins/datatables/dataTables.bootstrap.min.js"></script>
  <script>
  //---------------------------------------------------
  var table = $('#na_datatable').DataTable( {
      "processing": true,
      "serverSide": true,
      "ajax": "<?=base_url('admin/users/datatable_json')?>",
      "order": [[0,'asc']],
      "columnDefs": [
        { "targets": 0, "name": "created_at", 'searchable':true, 'orderable':true},
        { "targets": 1, "name": "last_login", 'searchable':true, 'orderable':true},
        { "targets": 2, "name": "email", 'searchable':true, 'orderable':true},
        { "targets": 3, "name": "role", 'searchable':false, 'orderable':false},
        { "targets": 4, "name": "is_admin", 'searchable':true, 'orderable':true},
        { "targets": 5, "name": "group", 'searchable':false, 'orderable':true},
        { "targets": 6, "name": "source", 'searchable':true, 'orderable':true},
        { "targets": 7, "name": "mixpanel", 'searchable':false, 'orderable':false},
        { "targets": 8, "name": "Action", 'searchable':false, 'orderable':false, 'sClass':'action-col'}
      ]
    });
  </script>
  
  <script type="text/javascript">
      $('#confirm-delete').on('show.bs.modal', function(e) {
      $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    });
  </script>
  

