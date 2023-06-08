<?php
session_start();
if(!isset($_SESSION["USER_EMAIL"])) {
    header("Location: ./giris-yap.php");
    exit();
}
ob_start();

require_once '../constant.php';
$activePage = basename($_SERVER['PHP_SELF'], ".php");
$actualLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$loggedUser = new User();
$loggedUser = $loggedUser->search(['id' => $_SESSION["USER_ID"]]);
if(count($loggedUser)==0){
    unset($_SESSION);
    session_destroy();
    header("Location: ./giris-yap.php");
    exit();
}

$loggedUser = $loggedUser[0];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DataScout | Dashboard</title>
    <link rel="Shortcut Icon" href="<?=$paths['cdnUrl']?>/img/datascout-favicon.png" type="image/x-icon" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
    <!-- Custom CSSS -->
    <link rel="stylesheet" href="dist/css/custom.css?ver=11">
</head>
<body class="sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="logout.php" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i>&nbsp; Çıkış Yap
                    </a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">

                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="./" class="brand-link">
                <img src="<?=$paths['cdnUrl']?>/img/datascout-favicon.png" alt="" class="brand-image" style="opacity: .8">
                <span class="brand-text font-weight-light">DataScout</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="<?=$paths['cdnUrl']?>/img/mail.png" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a class="d-block"><?=$loggedUser['name']?></a>
                    </div>
                </div>
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                             with font-awesome or any other icon font library -->

                        <li class="nav-item">
                            <a href="./" class="nav-link <?=($activePage=='index')?'active':''?>">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Anasayfa</p>
                            </a>
                        </li>

                        <li class="nav-header">Hedefler</li>

                        <li class="nav-item">
                            <a href="hedefler.php" class="nav-link <?=($activePage=='hedefler'||$activePage=='hedef-duzenle')?'active':''?>">
                                <i class="nav-icon fas fa-external-link-alt"></i>
                                <p>Hedeflerim</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="hazir-hedefler.php" class="nav-link <?=($activePage=='hazir-hedefler')?'active':''?>">
                                <i class="nav-icon fas fa-globe"></i>
                                <p>Hazır Hedefler</p>
                            </a>
                        </li>

                        <li class="nav-header">Çıktı</li>

                        <li class="nav-item">
                            <a href="veri-akislarim.php" class="nav-link <?=($activePage=='veri-akislarim')?'active':''?>">
                                <i class="nav-icon fas fa-rss-square"></i>
                                <p>Veri Akışlarım</p>
                            </a>
                        </li>

                        <li class="nav-header">Destek/İletişim</li>

                        <li class="nav-item">
                            <a href="destek-taleplerim.php" class="nav-link <?=($activePage=='destek-taleplerim')?'active':''?>">
                                <i class="nav-icon fas fa-question-circle"></i>
                                <p>Destek Taleplerim</p>
                            </a>
                        </li>

                        <li class="nav-header">Üyelik</li>

                        <li class="nav-item">
                            <a href="profil-ayarlari.php" class="nav-link <?=($activePage=='profil-ayarlari')?'active':''?>">
                                <i class="nav-icon fas fa-user-check"></i>
                                <p>Profil Ayarları</p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>