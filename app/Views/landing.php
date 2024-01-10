<?php
$db = db_connect();
$info = $db->query("select * from infosistem")->getRowArray();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="description" content="">
    <meta name="keywords" content="thema bootstrap template, thema admin, bootstrap, admin template, bootstrap admin">
    <meta name="author" content="LanceCoder">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url('assets/gambar/default/'.$info['logo']) ?>">
    <title><?php echo $info['nama'] ?> - <?php echo $info['tagline'] ?></title>
    <link href="<?php echo base_url('assets/css/global-plugins.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/vendors/jquery-icheck/skins/all.css') ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/css/theme.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/style-responsive.css') ?>" rel="stylesheet"/>
    <link href="<?php echo base_url('assets/css/class-helpers.css') ?>" rel="stylesheet"/>
    <link href="<?php echo base_url('assets/css/colors/green.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/fonts/Indie-Flower/indie-flower.css') ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/fonts/Open-Sans/open-sans.css?family=Open+Sans:300,400,700') ?>" rel="stylesheet" />
</head>
<body id="default-scheme" class="form-background">
    <div class="bg-overlay"></div>
    <section class="registration-login-wrapper">
        <div class="row page-login">
            <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2"> 
                <div class="form-body bg-white padding-20">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-header bg-white padding-10 text-center">
                                <h2><strong>Akses Sistem</strong></h2>
                                <p>Masukkan Username dan Password untuk mengelola data</p>
                            </div>
                            <form action="<?php echo base_url('proseslogin') ?>" method="post">
                                <div class="inner-addon right-addon margin-bottom-15">
                                    <i class="fa fa-user"></i>
                                    <input type="text" class="form-control" placeholder="Username Pengguna" name="username" autofocus required />
                                </div>
                                <div class="inner-addon right-addon margin-bottom-15">
                                    <i class="fa fa-lock"></i>
                                    <input type="password" class="form-control" placeholder="Password Pengguna" name="password" required />
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <?php if(session()->getFlashData('gagal')){ ?>
                                            <p style="text-align: center;color: red;"><?php echo session()->getFlashData('gagal') ?></p>
                                        <?php } ?>
                                        <button type="submit" class="btn btn-green btn-raised btn-flat">Akses Akun</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <div class="form-header bg-white padding-10 text-center form-social-header">
                                <h2><strong><?php echo $info['nama'] ?></strong></h2>
                                <h5><strong><?php echo $info['tagline'] ?></strong></h5>
                                <img src="<?php echo base_url('assets/gambar/default/'.$info['logo']) ?>" width="30%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="<?php echo base_url('assets/js/global-plugins.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/theme.js') ?>" type="text/javascript" ></script>
    <script src="<?php echo base_url('assets/js/forms.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/form-validation.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/form-wizard.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/form-plupload.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/form-x-editable.js') ?>"></script>
    <script src="<?php echo base_url('assets/vendors/backstretch/jquery.backstretch.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/registration-login.js') ?>"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            new WOW().init();
            App.initPage();
            App.initLeftSideBar();
            App.initCounter();
            App.initNiceScroll();
            App.initPanels();
            App.initProgressBar();
            App.initSlimScroll();
            App.initNotific8();
            App.initTooltipster();
            App.initStyleSwitcher();
            App.initMenuSelected();
            App.initRightSideBar();
            App.initSummernote();
            App.initAccordion();
            App.initModal();
            App.initPopover();
        });
    </script>
</body>
</html>