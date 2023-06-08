<?php require_once("includes/header.php"); ?>
<?php
$maksimum = new Target();
$maksimums = $maksimum->all();
if(count($maksimums)>0){
    $max = max(array_column($maksimums, 'display_order'));
}else{
    $max = 0;
}

//$db = new Db();
//$person = $db->query("SELECT * FROM table_site ORDER BY item_order ASC");
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Hedef <small>Ekle/Sil/Düzenle/Sırala</small></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="./">Anasayfa</a></li>
                        <li class="breadcrumb-item">Hedefler</li>
                        <li class="breadcrumb-item active">Ekle</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <?php
//        $usertype = new UserType();
//        $usertype->USER_TYPE_ID = $_SESSION["USER_TYPE_ID"];
//        $usertype->find();
//        $yetki = $usertype->SITE;

        $yetki = "1";

        if($yetki=="1"){
            ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    İsim <span style="color:red">*</span> | Tür <span style="color:red">*</span>
                                </h3>
                            </div>

                            <form role="form" method="post" enctype="multipart/form-data" id="gonderilenform">
                                <div class="card-body">

                                    <div class="form-group">
                                        <label for="name"> Hedef Adı <span style="color:red">*</span></label>
                                        <input name="name" type="text" class="form-control" id="name" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="url"> Hedef URL <span style="color:red">*</span></label>
                                        <input name="url" type="text" class="form-control" id="url" required>
                                    </div>

                                    <div class="form-group">
                                        <label> Çıktı Türü <span style="color:red">*</span></label>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="export_type_xml" name="export_type" value="0" checked>
                                            <label for="export_type_xml" class="custom-control-label">XML</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="export_type_json" name="export_type" value="1">
                                            <label for="export_type_json" class="custom-control-label">JSON</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="export_type_excel" name="export_type" value="2">
                                            <label for="export_type_excel" class="custom-control-label">EXCEL</label>
                                        </div>
                                    </div>

                                </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Resim <span style="color:red">*</span>
                                </h3>
                            </div>
                            <div class="card-body">

                                <div class="form-group">
                                    <label for="logo">Logo <span style="color:red">*</span></label>
                                    <div class="input-group">
                                        <input type="file" name="logo" id="logo" required="required">
                                    </div>
                                    <p class="help-block">JPG,PNG,JPEG (890x606)</p>
                                </div>

                            </div>
                            <div class="card-footer">
                                <input type="hidden" name="request" id="request" value="create">
                                <button name="ekle" type="submit" class="btn btn-primary">Ekle</button>
                            </div>
                        </div>
                        </form>
                        <?php
                        if(isset($_POST["ekle"])){

                            if($_POST['request']=="create"){

                                if($_FILES["logo"]["type"]=="image/jpeg" || $_FILES["logo"]["type"]=="image/png") {


                                    $target = new Target();
                                    $log = new Log();

                                    $name             = temizle($_POST["name"]);
                                    $url             = temizle($_POST["url"]);
                                    $slug          = permalink($name);

                                    $displayOrder           = $max+1;

                                    $sayi_tut = mt_rand(1000000000, mt_getrandmax());

                                    $resim_yol = "targets/";

                                    $handle = new \Verot\Upload\Upload($_FILES['logo']);
                                    if ($handle->uploaded){

                                        $handle->file_new_name_body     = randomString();
                                        $handle->image_convert = 'webp';
                                        $handle->image_ratio_crop     = false;
                                        $handle->image_resize         = false;
                                        $handle->process($paths['path'].$paths['main']."/images/".$resim_yol);
                                        $logoName = $resim_yol.$handle->file_dst_name;

                                        if($handle->processed){
                                            $handle->clean();
                                        }else{
                                            echo 'error : ' . $handle->error;
                                        }
                                    }

                                    $target->user_id = $_SESSION["USER_ID"];
                                    $target->export_type = $_POST["export_type"];
                                    $target->logo = $logoName;
                                    $target->url = $url;
                                    $target->name = $name;
                                    $target->slug = $slug;
                                    $target->display_order = $displayOrder;

                                    $lastId = $target->create();

                                    if($lastId){
                                        $log->write($_SESSION["USER_EMAIL"] . ", " . $name . " isimli bir hedef ekledi. "." Client IP: ".$_SERVER['REMOTE_ADDR']);

                                        header("Location: hedef-duzenle.php?id=".$lastId);


                                    }else{
                                        echo "<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button><h4><i class='icon fa fa-ban'></i> Bir hata oluştu!</h4></div>";
                                    }

                                }else{

                                    echo "<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button><h4><i class='icon fa fa-ban'></i> Dosya yalnızca JPEG,JPG,PNG formatında olabilir!</h4></div>";

                                }
                            }


                        }

                        ?>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Sil/Düzenle/Sırala
                                </h3>
                            </div>
                            <div class="card-body">
                                <ul id="sortable" class="todo-list ui-sortable">
                                    <?php
                                    $no=1;
                                    $targets = new Target();
                                    $targets = $targets->search(['user_id' => $_SESSION["USER_ID"]], ['display_order' => 'ASC']);
                                    foreach ($targets as $targetItem) {

                                        if(isset($_GET['komut']) && $_GET['komut']=='sil'){

                                            $target = new Target();
                                            $target->id = $_GET["id"];
                                            $target->find();

                                            $targetTags = new TargetTag();
                                            $targetTags = $targetTags->search(["target_id" => $target->id]);
                                            foreach($targetTags as $targetTag){
                                                $deleteTag = new TargetTag();
                                                $deleteTag->id = $targetTag['id'];
                                                $deleteTag->find();
                                                $deleteTag->delete();
                                            }

                                            $dataFeeds = new DataFeed();
                                            $dataFeeds = $dataFeeds->search(["target_id" => $target->id]);
                                            foreach($dataFeeds as $dataFeed){
                                                $deleteFeed = new DataFeed();
                                                $deleteFeed->id = $dataFeed['id'];
                                                $deleteFeed->find();

                                                unlink($paths['path'].$paths['main']."/exports/".$deleteFeed->file);

                                                $deleteFeed->delete();
                                            }

                                            unlink($paths['path'].$paths['main']."/images/".$target->logo);

                                            $target->delete();

                                            header("Location: hedefler.php");
                                        }
                                        ?>

                                        <li id="<?=$targetItem['id']?>">

                                            <!-- drag handle -->
                                            <span class="handle ui-sortable-handle">
                      <i class="fa fa-ellipsis-v"></i>
                      <i class="fa fa-ellipsis-v"></i>
                    </span>
                                            <!-- todo text -->

                                            <span class="text"><?=$no?>. &nbsp; <img src="<?=$paths['cdnUrl']?>/images/<?=$targetItem['logo']?>" width="150" style="background-color: #d2d6de;padding:5px;border-radius:20px;"> <span class='badge badge-primary' style='font-size:13px;'><?=$targetItem['name']?></span></span>

                                            <!-- General tools such as edit or delete-->
                                            <div class="tools">
                                                <a href="hedef-duzenle.php?id=<?=$targetItem['id']?>"><i class="fa fa-edit" style="font-size: 25px;"></i></a>
                                                <a data-toggle="modal" data-target="#sil-<?=$targetItem["id"]?>"><i class="fa fa-trash" style="font-size: 25px;"></i></a>
                                            </div>

                                        </li>

                                        <?php
                                        $no++;
                                    }
                                    if(isset($_POST['list_order'])){

                                        $db = new Db();
                                        $list_order = $_POST['list_order'];
                                        // convert the string list to an array
                                        $list = explode(',' , $list_order);
                                        $i = 1 ;
                                        foreach($list as $id) {
                                            try {
                                                $query = $db->query("UPDATE target SET display_order = :display_order WHERE id = :id",array("display_order"=>$i,"id"=>$id));

                                            } catch (PDOException $e) {
                                                echo 'PDOException : '.  $e->getMessage();
                                            }
                                            $i++ ;
                                        }

                                    }
                                    ?>
                                </ul>
                                <?php
                                foreach ($targets as $targetItem) {
                                    ?>
                                    <div class="modal fade" id="sil-<?=$targetItem["id"]?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Sil</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="İptal">
                                                        <span aria-hidden="true">&times;</span></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Bu kaydı silmek istediğinizden emin misiniz?</p>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">İptal</button>
                                                    <a href="?komut=sil&id=<?=$targetItem["id"]?>" type="button" class="btn btn-primary">Onayla</a>
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /.modal -->

                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <?php
        }else{ require_once("includes/401.html"); }
        ?>
    </section>
</div>

<?php require_once("includes/footer.php"); ?>
<script type="text/javascript">
    $(function() {
        $('#sortable').sortable({
            axis: 'y',
            opacity: 0.7,
            handle: 'span',
            update: function(event, ui) {
                var list_sortable = $(this).sortable('toArray').toString();
                // change order in the database using Ajax
                $.ajax({
                    url: 'hedef-ekle.php',
                    type: 'POST',
                    data: {list_order:list_sortable},
                    success: function(data) {
                        //finished
                    }
                });
            }
        }); // fin sortable
    });
</script>
<style type="text/css">

    .gorunur {display:show;}
    .gorunmez {display:none;}

</style>

<script type="text/javascript">

    function ackapa(id,id2){
        if(document.getElementById(id2).checked) {

            document.getElementById(id).className="gorunur";
            document.getElementById(id).disabled = false;

        }else{

            document.getElementById(id).className="gorunmez";
            document.getElementById(id).disabled = true;

        }
    }

    function kapa(id,id2){

        document.getElementById(id).className="gorunmez";
        document.getElementById(id).disabled = true;

    }


</script>
</body>
</html>