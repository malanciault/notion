<section class="content">

  <div class="row">

	<div class="row">
	  <div class="col-md-12">
	      <div class="box box-body with-border">
	        <div class="col-md-6">
	          <h4><i class="fal fa-database"></i> &nbsp;Database version <?=$db_current_version?> </h4>
	        </div>
	        <div class="col-md-6 text-right">
	        	<?if ($db_current_version < $db_latest_version):?>
	         		<a href="<?=site_url('admin/dbtools/upgrade/');?>" class="btn btn-success" role="button">Upgrade to version <?=$db_latest_version?> </a>
	         <?else:?>	
	         	<a class="btn btn-success" disabled role="button">Up to date</a>
	         <?endif;?>	
	        </div>
	      </div>
	    </div>
	  </div>
</section>

<script>
	function dbRestore(result, callbackParams) {
		window.location.replace(result);
	}

	$('.restore-file').click(function(){
		var data = {file: $(this).attr('data-file')};
		var elements = [$(this)];
        doPost('admin/dbtools/restore', data, 'dbRestore', elements);
	});
</script>