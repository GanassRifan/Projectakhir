<?php
$db = db_connect();
$info = $db->query("select * from infosistem")->getRowArray();
?>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="description" content="">
	<meta name="keywords" content="thema bootstrap template, thema admin, bootstrap, admin template, bootstrap admin">
	<meta name="author" content="LanceCoder">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="shortcut icon" href="<?php echo base_url('assets/gambar/default/'.$info['logo']) ?>">
	<title><?php echo $info['nama'] ?></title>
	<link href="<?php echo base_url('assets/css/global-plugins.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/vendors/jquery-icheck/skins/all.css') ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/css/table-responsive.css') ?>" rel="stylesheet"/>
    <link href="<?php echo base_url('assets/vendors/datatable/bootstrap/dataTables.bootstrap.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/theme.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/style-responsive.css') ?>" rel="stylesheet"/>
	<link href="<?php echo base_url('assets/css/class-helpers.css') ?>" rel="stylesheet"/>
	<link href="<?php echo base_url('assets/css/colors/green.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/colors/turquoise.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/colors/blue.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/colors/amethyst.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/colors/cloud.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/colors/sun-flower.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/colors/carrot.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/colors/alizarin.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/colors/concrete.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/colors/wet-asphalt.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/fonts/Indie-Flower/indie-flower.css') ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/fonts/Open-Sans/open-sans.css?family=Open+Sans:300,400,700') ?>" rel="stylesheet" />
</head>