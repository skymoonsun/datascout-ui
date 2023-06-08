<?php require_once("includes/header.php"); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Veri Akışlarım <small>Listele</small></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="./">Anasayfa</a></li>
                        <li class="breadcrumb-item active">Veri Akışlarım</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <?php
//        $usertype = new UserType();
//        $usertype->id = $_SESSION["USER_TYPE_ID"];
//        $usertype->find();
//        $yetki = $usertype->BLOG;

        $yetki="1";
        if($yetki=="1"){
            ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Veri Akışlarım
                                </h3>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th style="width: 10px;">ID</th>
                                        <th>Hedef</th>
                                        <th>Oluşturma Tarihi</th>
                                        <th>Dosya Uzantısı</th>
                                        <th style="width: 100px;">Dosya</th>
                                        <th style="width: 10px;">İşlem</th>
                                    </tr>
                                    </thead>
                                </table>
                                <?php

                                if(!empty($_GET["id"])){

                                    $g_id = temizle_sayi($_GET["id"]);

                                    $dataFeed = new DataFeed();
                                    $dataFeed->id = $g_id;
                                    $dataFeed->find();

                                    $adet = count($dataFeed->id);

                                    if($adet>=1) {

                                        if (isset($_GET['komut']) && $_GET['komut'] == 'sil') {

                                            $deleteDataFeed = new DataFeed();
                                            $deleteDataFeed->id = $g_id;
                                            $deleteDataFeed->find();

                                            unlink($paths['path'].$paths['main']."/exports/" . $deleteDataFeed->file);

                                            $deleteDataFeed->delete();

                                            header("Location: veri-akislarim.php");

                                        }
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
            </div>
            <?php
        }else{ require_once("includes/401.html"); }
        ?>
    </section>
</div>

<?php require_once("includes/footer.php"); ?>
<script>

    $(function () {
        $('#example1').DataTable({
            "responsive": true,
            "columnDefs": [
                { "orderable": false, "targets": [3,4,5] }
            ],
            'order': [[ 0, "desc" ]],
            'processing': true,
            'serverSide': true,
            //'serverMethod': 'post',
            "ajax": {"url":"datatables/dataFeeds.php",
                data:function(reqParam) {
                    // to see exactly what is being sent
                    console.log(reqParam);
                    return reqParam;
                },
                dataFilter: function(response){
                    // this to see what exactly is being sent back
                    console.log(response);
                    return response
                },
                error: function(error) {
                    // to see what the error is
                    console.log(error);
                }
            },
            'columns': [
                { data: 'id' },
                { data: 'target_id' },
                { data: 'created_at' },
                { data: 'export_type' },
                { data: 'file' },
                { data: 'islem' },
            ],
            lengthMenu: [
                [ 10, 30, 50, 100 ],
                [ '10', '30', '50', '100' ]
            ],
            "dom": 'Blfrtip',
            "buttons": [
                {
                    extend: 'excel',
                    title: 'DataScout Veri Akışlarım',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    extend: 'csv',
                    title: 'DataScout Veri Akışlarım',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    extend: 'pdf',
                    title: 'DataScout Veri Akışlarım',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    extend: 'copy',
                    text: 'Kopyala',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    extend: 'print',
                    text: 'Yazdır',
                    title: 'DataScout Veri Akışlarım',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    extend: 'colvis',
                    text: 'Sütun Gizle',
                }
            ]
        });
    });

</script>
</body>
</html>