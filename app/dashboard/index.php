<?php
require_once('./includes/header.php');

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">

                <?php
                $targetCount = new DB();
                $targetCount = $targetCount->query("SELECT COUNT(*) as count FROM target WHERE user_id = ".$loggedUser['id']);
                $targetCount = $targetCount[0]['count'];
                ?>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3><?=$targetCount?></h3>
                            <p>Hedeflerim</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-external-link-alt mr-1"></i>
                        </div>
                        <a href="hedefler.php" class="small-box-footer">Sayfaya Git <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->

                <?php
                $dataFeedCount = new DB();
                $dataFeedCount = $dataFeedCount->query("SELECT COUNT(*) as count FROM data_feed WHERE user_id = ".$loggedUser['id']);
                $dataFeedCount = $dataFeedCount[0]['count'];
                ?>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-indigo">
                        <div class="inner">
                            <h3><?=$dataFeedCount?></h3>
                            <p>Veri Akışlarım</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-rss-square mr-1"></i>
                        </div>
                        <a href="veri-akislarim.php" class="small-box-footer">Sayfaya Git <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->

            </div>
            <!-- /.row -->

        </div>
</div><!-- /.container-fluid -->



</div>
<!-- /.content-wrapper -->

<?php require_once('./includes/footer.php'); ?>
