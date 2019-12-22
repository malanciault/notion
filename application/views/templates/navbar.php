<!--Navbar-->

  
<nav class="navbar navbar-expand-lg fixed-top white ">
<div class="container">
    <!-- Navbar brand -->
    <?if ($this->session->partner != 'palais'):?>
        <a class="navbar-brand " href="<?=main_url()?>"><img src="https://planetair.ca/wp-content/uploads/2019/09/logo-planetair-200-2.png" alt="<?=$this->config->item('app_title');?>" /></a>
    <?endif;?>

    <!-- Collapse button -->
    <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#basicExampleNav" aria-controls="basicExampleNav" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Collapsible content -->
    <div class="collapse navbar-collapse" id="basicExampleNav">
        <!-- Links -->
        <ul class="navbar-nav <?=$this->session->partner == 'palais' ? 'm-auto' : ''?>">
            <li class="nav-item active">
                <? if($this->session->partner == 'palais'):?>
                    <a class="nav-link" href="<?=site_url('partner/palais')?>"><?=__("Accueil")?>
                        <span class="sr-only">(current)</span>
                    </a>
                <? else:?>
                <a class="nav-link" href="<?=main_url()?>"><?=__("Accueil")?>
                    <span class="sr-only">(current)</span>
                </a>
                <? endif;?>
            </li>
            <?if ($this->session->partner != 'palais'):?>
                <li class="nav-item dropdown px-4">
                    <a class="nav-link" id="navbarDropdownMenuLink" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false"><?=__("Comprendre")?></a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="<?=main_url('les-changements-climatiques/')?>"><?=__("Les changements climatiques")?></a>
                        <a class="dropdown-item" href="<?=main_url('votre-empreinte/')?>"><?=__("Votre empreinte climatique")?></a>
                        <a class="dropdown-item" href="<?=main_url('les-credits-carbone/')?>"><?=__("Les crédits-carbone")?></a>
                        <a class="dropdown-item" href="<?=main_url('les-projets/')?>"><?=__("Nos projets de compensation")?></a>
                        <a class="dropdown-item" href="<?=main_url('la-certification/')?>"><?=__("Certification et services B2B")?></a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?=site_url()?>"><?=__("Compenser")?></a>
                </li>
                <!-- Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link " id="navbarDropdownMenuLink" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false"><?=__("À propos")?></a>
                    <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="<?=main_url('a-propos-de-nous/')?>"><?=__("À propos de nous")?></a>
                        <a class="dropdown-item" href="<?=main_url('nouvelles/')?>"><?=__("Nouvelles")?></a>
                        <a class="dropdown-item" href="<?=main_url('f-a-q/')?>">F.A.Q</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?=main_url('nous-joindre/')?>"><?=__("Nous joindre")?></a>
                </li>
            <?endif;?>
            <li class="nav-item">
                <a title="<?=__('Panier')?>" href="<?=site_url('/buy')?>"  class="text-dark nav-link waves-effect">
                    <i class="fas fa-shopping-cart" style="color: #000000">
                        <span id="cart-count" class="badge badge-danger" style="display: none; "></span>
                    </i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?=$this->i18n->current() == 'french' ? current_url() . '?lang=english' : current_url() . '?lang=french'?>"><?=__("English")?></a>
            </li>


        </ul>
        <!-- Links -->


    </div>
    <!-- Collapsible content -->
</div>
</nav>
<!--/.Navbar-->

