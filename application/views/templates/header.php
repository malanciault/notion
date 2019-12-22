<!doctype html>
<html lang="<?=$this->i18n->get_current_short(true);?>" class="full-height">
  <head>

    <?if (ENVIRONMENT == 'production'):?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-1409784-63"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'UA-1409784-65');
          <?if ($this->session->user_id):?>
            gtag('set', {'user_id': '<?=$this->session->user_id?>'});
          <?endif;?>
        </script>

        <!-- Facebook Pixel Code -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t,s)}(window, document,'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '2593653490749171');
            fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
                       src="https://www.facebook.com/tr?id=2537165849845864&ev=PageView&noscript=1"
            /></noscript>
        <!-- End Facebook Pixel Code -->

        <!-- End Facebook Pixel Code -->
    <?endif;?>

      <!-- Notion New Bug Button -->
      <? if ($this->session->is_admin_login) :?>

      <?endif;?>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta property="og:site_name" content="<?=$site_name?>">
    <title><?=$page_title;?></title>
    <meta name="description" content="<?=$page_description;?>">
    <meta name="author" content="Amplio Numérique">

    <meta property="og:title" content="<?=$page_title;?>">
    <meta property="og:description" content="<?=$page_description;?>">
    <meta property="og:image" content="<?=$page_image;?>">
    <meta property="og:url" content="<?=current_url() . '?lang=' . $this->i18n->current()?>">

    <meta name="twitter:title" content="<?=$page_title;?>">
    <meta name="twitter:description" content="<?=$page_description;?>">
    <meta name="twitter:image" content="<?=$page_image;?>">
    <meta name="twitter:card" content="summary_large_image">

      <link rel="apple-touch-icon" sizes="180x180" href="<?=site_url('assets/favicons'); ?>/apple-touch-icon.png">
      <link rel="icon" type="image/png" sizes="32x32" href="<?=site_url('assets/favicons'); ?>/favicon-32x32.png">
      <link rel="icon" type="image/png" sizes="16x16" href="<?=site_url('assets/favicons'); ?>/favicon-16x16.png">
      <link rel="manifest" href="<?=site_url('assets/favicons'); ?>/site.webmanifest">
      <meta name="msapplication-TileColor" content="#da532c">
      <meta name="theme-color" content="#ffffff">

    <style>
        @media (max-width: 740px) {
            .full-height,
            .full-height body,
            .full-height header,
            .full-height header .view {
                height: 700px;
            }
        }
    </style>    

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="<?=site_url('assets/fontawesome/css/all.css'); ?>" rel="stylesheet">


    <!-- Material Design Bootstrap -->
    <link href="<?=site_url('assets/mdb/css/mdb.min.css'); ?>" rel="stylesheet">

    <!-- MDBootstrap Steppers Pro  -->
    <link href="<?=site_url('assets/mdb/css/addons-pro/stepper.min.css'); ?>" rel="stylesheet">

    <!-- App styles  -->
    <link href="<?=site_url('assets/css/styles.css'); ?>" rel="stylesheet">

    <style>
        html,
        body,
        header,
        .jarallax {
          height: 100%;
        }

        @media (min-width: 560px) and (max-width: 740px) {
            html,
            body,
            header,
            .jarallax {
              height: 500px;
            }
        }

        @media (min-width: 800px) and (max-width: 850px) {
            .navbar:not(.top-nav-collapse) {
                background: #23242a!important;
            }
            .navbar {
              box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12) !important;
            }
        }
    </style>

    <!-- On demand ressources -->
    <?if (isset($on_demand_ressources['header']['css'])):?>
        <?foreach($on_demand_ressources['header']['css'] as $url):?>
            <link rel="stylesheet" href="<?=$url?>">
        <?endforeach;?>
    <?endif;?>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    
    <?if (isset($on_demand_ressources['header']['js'])):?>
        <?foreach($on_demand_ressources['header']['js'] as $url):?>
            <script src="<?=$url?>"></script>
        <?endforeach;?>
    <?endif;?>


    <?if ($assets = header_assets()): ?>
        <!-- Dynamically loaded assets  -->
        <?foreach ($assets as $asset): ?>
          <?=$asset; ?>
        <?endforeach; ?>
    <?endif; ?>

    <script type="text/javascript">
      var siteUrl = "<?=site_url();?>";
      var siteLang = "<?=$this->i18n->current();?>";
    </script>

    <script type="text/javascript">
</script>
  </head>

<body class="intro-page transport-lp">
    <!--Navigation & Intro-->
    <?
        $this->load->view('templates/navbar');
    ?>
    <? if ($intro):?>
        <header>
            <? $this->load->view($intro); ?>
        </header>
    <? endif; ?>
    <!--/Navigation & Intro-->


    <!--Main content-->
    <? if ($intro != 'home-intro'):?>
        <main>
            <div class="container<?=$fluid;?> <?=$fluid;?> mt-5 pt-5 mb-1 pb-0 px-0 px-md-3">
                <? 
                    if (isset($breadcrumb)) {
                        $data['breadcrumb'] = $breadcrumb;
                        $this->load->view('templates/breadcrumb', $data);
                    }  
                ?>
    <?endif;?>