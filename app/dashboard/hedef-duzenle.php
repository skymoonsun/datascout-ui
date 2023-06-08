<?php require_once("includes/header.php"); ?>
<?php

if(!empty($_GET["id"])){

    $gelen_id = temizle_sayi($_GET["id"]);

    $target = new Target();
    $target->id = $gelen_id;
    $target->find();

    @$adet = count($target->id);
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Hedef <small>Düzenle/Sırala</small></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="./">Anasayfa</a></li>
                        <li class="breadcrumb-item"><a href="hedefler.php">Hedefler</a></li>
                        <li class="breadcrumb-item active">Düzenle</li>
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
            if(!empty($_GET["id"]) && $adet>=1){
                $resim = $target->logo;
                ?>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <form role="form" method="post" enctype="multipart/form-data" id="gonderilenform">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            İsim <span style="color:red">*</span> | Url <span style="color:red">*</span>
                                        </h3>
                                        <div class="card-tools">
                                            <button id="dataFeedButton" class="btn btn-tool" data-toggle="tooltip" title="Veri Akışı Oluştur">
                                                <i id="dataFeedIcon" class="fa fa-sync"></i>
                                                <i id="dataFeedLoader" class="fa fa-sync fa-spin" style="display: none;"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <p id="dataFeedResults"></p>

                                        <div class="form-group">
                                            <label for="name"> Hedef Adı <span style="color:red">*</span></label>
                                            <input name="name" type="text" class="form-control" id="name" value="<?=$target->name?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="url"> Hedef URL <span style="color:red">*</span></label>
                                            <input name="url" type="text" class="form-control" id="url" value="<?=$target->url?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label> Çıktı Türü <span style="color:red">*</span></label>
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="export_type_xml" name="export_type" value="0" <?=($target->export_type==0)?'checked':''?>>
                                                <label for="export_type_xml" class="custom-control-label">XML</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="export_type_json" name="export_type" value="1" <?=($target->export_type==1)?'checked':''?>>
                                                <label for="export_type_json" class="custom-control-label">JSON</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="export_type_excel" name="export_type" value="2" <?=($target->export_type==2)?'checked':''?>>
                                                <label for="export_type_excel" class="custom-control-label">EXCEL</label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            Resim
                                        </h3>
                                    </div>
                                    <div class="card-body">

                                        <div class="form-group">
                                            <label for="logo">Resim </label>
                                            <div class="input-group">
                                                <input type="file" name="logo" id="logo">
                                            </div>
                                            <p class="help-block">JPG,PNG,JPEG</p>
                                            <img width="300" style="background-color: #d2d6de;padding:5px;border-radius:20px;" src="<?=$paths['cdnUrl']?>/images/<?=$target->logo?>">
                                            <?php $resimm = $target->logo; ?>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <input type="hidden" name="request" id="request" value="update">
                                        <button name="duzenle" type="submit" class="btn btn-primary">Kaydet</button>
                                    </div>
                                </div>
                            </form>
                            <?php



                            if(isset($_POST["duzenle"])){

                                if($_POST['request']=="update"){

                                    $log = new Log();

                                    $url             = temizle($_POST["url"]);
                                    $name             = temizle($_POST["name"]);
                                    $slug          = permalink($name);

                                    $resim_yol = "targets/";

                                    if(!empty($_FILES['logo']['name'])){

                                        if($_FILES["logo"]["type"]=="image/jpeg" || $_FILES["logo"]["type"]=="image/png" || $_FILES["logo"]["type"]=="image/webp"){


                                            $handle = new \Verot\Upload\Upload($_FILES['logo']);
                                            if ($handle->uploaded){

                                                unlink($paths['path'].$paths['main']."/images/".$resimm);

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

                                        }else{

                                            echo "<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button><h4><i class='icon fa fa-ban'></i> Dosya yalnızca JPEG,JPG,PNG formatında olabilir!</h4></div>";
                                        }

                                    }else{
                                        $logoName = $resim;
                                    }

                                    $updateTarget = new Target();
                                    $updateTarget->id = $gelen_id;

                                    $updateTarget->export_type = $_POST['export_type'];
                                    $updateTarget->logo = $logoName;
                                    $updateTarget->url = $url;
                                    $updateTarget->name = $name;
                                    $updateTarget->slug = $slug;


                                    if($updateTarget->save()){
                                        $log->write($_SESSION["USER_EMAIL"] . ", " . $name . " isimli hedefi düzenledi. "." Client IP: ".$_SERVER['REMOTE_ADDR']);

                                        header("Location: hedef-duzenle.php?id=$gelen_id");

                                    }else{
                                        echo "<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button><h4><i class='icon fa fa-ban'></i> Bir hata oluştu!</h4></div>";
                                    }

                                }


                            }

                            ?>
                            <form role="form" method="post" enctype="multipart/form-data" id="gonderilenform2">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            Etiket <small>Ekle</small>
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="tag_name"> Etiket Adı <span style="color:red">*</span></label>
                                            <input name="tag_name" type="text" class="form-control" id="tag_name" required>
                                        </div>

                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline mr-15">
                                                <input type="radio" name="is_dom" id="dom_degil" value="0" checked>
                                                <label for="dom_degil">
                                                    Normal
                                                </label>
                                            </div>

                                            <div class="icheck-primary d-inline mr-15">
                                                <input type="radio" name="is_dom" id="dom" value="1">
                                                <label for="dom">
                                                    HTML Dom Parser
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group">
                                                <input name="start_tag" type="text" class="form-control" id="start_tag" placeholder="Etiket Başı *" required>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-info-circle" style="width: 20px;"></i></span>
                                                </div>
                                                <input name="end_tag" type="text" class="form-control" id="end_tag" placeholder="Etiket Sonu *" required>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="card-footer">
                                        <input type="hidden" name="request" id="request" value="create_tag">
                                        <button name="ekle_tag" type="submit" class="btn btn-primary">Ekle</button>
                                    </div>
                                </div>
                            </form>
                            <?php


                            if(isset($_POST["ekle_tag"])){

                                if($_POST['request']=="create_tag"){

                                    $targetTag = new TargetTag();

                                    $log = new Log();


                                    $targetTag->target_id = $gelen_id;
                                    $targetTag->name = $_POST['tag_name'];
                                    $targetTag->slug = permalink($_POST['tag_name']);
                                    $targetTag->start_tag = $_POST['start_tag'];
                                    $targetTag->end_tag = $_POST['end_tag'];
                                    $targetTag->is_dom = $_POST["is_dom"];

                                    if($targetTag->create()){
                                        $log->write($_SESSION["USER_EMAIL"] . ", bir etiket ekledi. "." Client IP: ".$_SERVER['REMOTE_ADDR']);

                                        header("Location: hedef-duzenle.php?id=$gelen_id");

                                    }else{
                                        echo "<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button><h4><i class='icon fa fa-ban'></i> Bir hata oluştu!</h4></div>";
                                    }
                                }
                            }



                            ?>
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Etiketler
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <?php

                                    $targetTags = new TargetTag();
                                    $targetTags = $targetTags->search(array("target_id" => $gelen_id));

                                    $no=1;
                                    foreach ($targetTags as $targetTag) {

                                        if(isset($_GET['komut']) && $_GET['komut']=='tagsil'){

                                            $targetTagDelete = new TargetTag();
                                            $targetTagDelete->id = $_GET["tagid"];
                                            $targetTagDelete->find();
                                            $targetTagDelete->delete();

                                            header("Location: hedef-duzenle.php?id=$gelen_id");
                                        }

                                        if(isset($_GET['komut']) && $_GET['komut']=='tagedit' && $_GET['tagid']==$targetTag['id']) {
                                            ?>
                                            <form role="form" method="post" enctype="multipart/form-data" id="gonderilenform3">

                                            <div class="form-group clearfix">
                                                <div class="icheck-primary d-inline mr-15">
                                                    <input type="radio" name="is_dom" id="dom_degil2" value="0" <?=($targetTag['is_dom']==0)?'checked':''?>>
                                                    <label for="dom_degil2">
                                                        Normal
                                                    </label>
                                                </div>

                                                <div class="icheck-primary d-inline mr-15">
                                                    <input type="radio" name="is_dom" id="dom2" value="1" <?=($targetTag['is_dom']==1)?'checked':''?>>
                                                    <label for="dom2">
                                                        HTML Dom Parser
                                                    </label>
                                                </div>
                                            </div>


                                        <?php } ?>
                                        <div class="form-group">
                                            <label for="tag_name"> Etiket Adı <span style="color:red">*</span></label>
                                            <input name="tag_name" type="text" class="form-control" id="tag_name" value="<?=$targetTag['name']?>" <?=(isset($_GET['komut']) && $_GET['komut']=='tagedit' && $_GET['tagid']==$targetTag['id'])?'':'disabled'?>>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" name="start_tag" class="form-control" value="<?=htmlspecialchars($targetTag['start_tag'], ENT_QUOTES)?>" <?=(isset($_GET['komut']) && $_GET['komut']=='tagedit' && $_GET['tagid']==$targetTag['id'])?'':'disabled'?>>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-lira-sign" style="width: 20px;"></i></span>
                                                </div>
                                                <input type="text" name="end_tag" class="form-control" value="<?=htmlspecialchars($targetTag['end_tag'], ENT_QUOTES)?>" <?=(isset($_GET['komut']) && $_GET['komut']=='tagedit' && $_GET['tagid']==$targetTag['id'])?'':'disabled'?>>
                                                <div class="input-group-prepend">
                                                    <a href="hedef-duzenle.php?komut=tagedit&tagid=<?=$targetTag["id"]?>&id=<?=$gelen_id?>" class="input-group-text">
                                                        <i class="fas fa-edit" style="width: 20px;"></i>
                                                    </a>
                                                </div>
                                                <div class="input-group-prepend">
                                                    <a href="?komut=tagsil&tagid=<?=$targetTag["id"]?>&id=<?=$gelen_id?>" class="input-group-text">
                                                        <i class="fas fa-trash" style="width: 20px;"></i>
                                                    </a>
                                                </div>

                                                <?php
                                                if(isset($_GET['komut']) && $_GET['komut']=='tagedit' && $_GET['tagid']==$targetTag['id']) {
                                                    ?>
                                                    <input type="hidden" name="request" id="request" value="save_tag">
                                                    <input type="hidden" name="tag_id" id="tag_id" value="<?=$targetTag['id']?>">
                                                    <div class="input-group-prepend">
                                                        <button name="save_tag" type="submit" class="input-group-text">
                                                            <i class="fas fa-save" style="width: 20px;color:green;"></i>
                                                        </button>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <hr>
                                        <?php
                                        if(isset($_GET['komut']) && $_GET['komut']=='tagedit' && $_GET['tagid']==$targetTag['id']) {
                                            ?>
                                            </form>
                                            <?php

                                        }
                                        $no++;
                                    }

                                    if(isset($_POST["save_tag"])){

                                        if($_POST['request']=="save_tag"){


                                            $log = new Log();
                                            $tag_save = new TargetTag();
                                            $tag_save->id = $_POST['tag_id'];

                                            $tag_save->name       = temizle($_POST['tag_name']);
                                            $tag_save->slug       = temizle(permalink($_POST['tag_name']));
                                            $tag_save->start_tag       = $_POST['start_tag'];
                                            $tag_save->end_tag         = $_POST['end_tag'];
                                            $tag_save->target_id     = $gelen_id;
                                            $tag_save->is_dom         = $_POST["is_dom"];



                                            if($tag_save->save()){
                                                $log->write($_SESSION["USER_EMAIL"] . ", " . $target->name . " hedefinin bir tagini düzenledi. "." Client IP: ".$_SERVER['REMOTE_ADDR']);

                                                header("Location: hedef-duzenle.php?id=$gelen_id");

                                            }else{
                                                echo "<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button><h4><i class='icon fa fa-ban'></i> Bir hata oluştu!</h4></div>";
                                            }

                                        }


                                    }

                                    ?>
                                </div>
                            </div>
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
            }else{ require_once("includes/404.html"); }
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
                    url: 'hedef-duzenle.php?id=<?=$gelen_id?>',
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

    .lalak .select2-container {
        margin-top:-28px;
    }

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

    $(function () {
        $("#dataFeedButton").click(function() {
            $('#dataFeedShow').show();
            $('#dataFeedIcon').hide();
            $("#dataFeedButton").prop('disabled', true);
            $.ajax({
                type: 'post',
                url: 'ajax/createDataFeed.php',
                data: {'targetId': <?=$gelen_id?>},
                beforeSend:function(){
                    $("#dataFeedButton").prop('disabled', true);
                    $("#dataFeedIcon").hide();
                    $("#dataFeedLoader").show();
                },
                success: function (result) {
                    $("#dataFeedButton").prop('disabled', false);
                    $("#dataFeedIcon").show();
                    $("#dataFeedLoader").hide();
                    $("#dataFeedResults").html(result);
                }
            });
            return false;
        });
    });
</script>
</body>
</html>