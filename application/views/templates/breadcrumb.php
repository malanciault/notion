<? if (isset($breadcrumb)): ?>
	<nav aria-label="breadcrumb">
	  <ol class="breadcrumb">
	  	<?foreach ($breadcrumb as $k =>$v):?>
			    <li class="breadcrumb-item <?if (isset($v['active'])):?>active<?endif;?>"><?=isset($v['active']) ? $v['caption'] : '<a href="' . site_url($v['url']) . '">' . $v['caption'] . '</a>';?></li>
		    <?endforeach;?>
	  </ol>
	</nav>	
<? endif;?>