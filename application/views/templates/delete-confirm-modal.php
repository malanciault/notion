<div id="confirm-delete<?=isset($class) ? '-' . $class : '';?>" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Supprimer</h4>
      </div>
      <div class="modal-body">
        <p>Voulez-vous vraiment supprimer cet item ?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
        <a id="btn-confirm-delete<?=isset($class) ? '-' . $class : '';?>" class="btn btn-danger btn-ok">Supprimer</a>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

      $('#confirm-delete<?=isset($class) ? '-' . $class : '';?>').on('show.bs.modal', function(e) {
        $('#btn-confirm-delete<?=isset($class) ? '-' . $class : '';?>').attr('href', $(e.relatedTarget).attr('data-href'));

    });
</script>