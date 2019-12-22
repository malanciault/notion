<? if ($intro != 'home-intro'):?>    
    </div>
</main>
<?endif;?>
    <!--/Main content-->

    <script type="text/javascript" src="<?=site_url('assets/mdb/js/jquery-3.3.1.min.js'); ?>"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="<?=site_url('assets/mdb/js/popper.min.js'); ?>"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="<?=site_url('assets/mdb/js/bootstrap.min.js'); ?>"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="<?=site_url('assets/mdb/js/mdb.min.js'); ?>"></script>

    <!-- Threel JavaScript -->
    <script src="<?=site_url('assets/js/threel.js');?>"></script>

    <script>
        //Animation init
        new WOW().init();
    </script>

    <!-- On demand ressources  -->

    <?if (isset($on_demand_ressources['footer']['js'])):?>
        <?foreach($on_demand_ressources['footer']['js'] as $url):?>
            <script src="<?=$url?>"></script>
        <?endforeach;?>
    <?endif;?>

    <!-- Feedback messages  -->
    <?if ($this->session->flashdata('error_msg')):?>
        <script>
            showFeedback('<?=$this->session->flashdata('error_msg');?>', 'error');
        </script>
    <?endif;?>
    <?if ($this->session->flashdata('info_msg')):?>
        <script>
            showFeedback('<?=$this->session->flashdata('info_msg');?>', 'info');
        </script>
    <?endif;?>
    <?if ($this->session->flashdata('success_msg')):?>
        <script>
            showFeedback('<?=$this->session->flashdata('success_msg');?>', 'success');
        </script>
    <?endif;?>

    <?if ($assets = footer_assets()): ?>
        <!-- Dynamically loaded assets  -->
        <?foreach ($assets as $asset): ?>
          <?=$asset; ?>
        <?endforeach; ?>
    <?endif; ?>
    <script type="text/javascript">
        $( document ).ready(function() {
            <?if ($this->session->calculations && is_array($this->session->calculations) && count($this->session->calculations) > 0 ):?>
                $('#cart-count').html(<?=count($this->session->calculations)?>);
            $('#cart-count').fadeIn();
            <?else:?>
                var cartCount = 0
            <?endif;?>
        });
    </script>
  </body>
</html>