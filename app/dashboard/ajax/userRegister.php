<?php
require_once("../../constant.php");

if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {

    header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );

    die("File does not exists!");

}

if($_POST){

    if (!filter_var(temizle($_POST['email']), FILTER_VALIDATE_EMAIL)) {
        ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban"></i> Bir hata oluştu!</h5>
            Geçersiz email adresi!
        </div>
        <?php
        exit;
    }

    if(!isset($_POST['terms'])){
        ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban"></i> Bir hata oluştu!</h5>
            Sözleşmeyi kabul etmeniz gerekiyor!
        </div>
        <?php
        exit;
    }

    if(temizle($_POST["password"])!=temizle($_POST["retryPassword"])){
        ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban"></i> Bir hata oluştu!</h5>
            Girdiğiniz parolalar uyuşmuyor!
        </div>
        <?php
        exit;
    }

    $emailCheck = new User();
    $emailCheck = $emailCheck->search(['email' => temizle($_POST['email'])]);
    if(count($emailCheck)>0){
        ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban"></i> Bir hata oluştu!</h5>
            Bu email adresi kullanılıyor!
        </div>
        <?php
        exit;
    }

    $log = new Log();
    $createUser = new User();

    $createUser->is_admin = false;
    $createUser->created_at = time();
    $createUser->email = temizle($_POST["email"]);
    $createUser->name = temizle($_POST["name"]);
    $createUser->password = temizle(sifrele($_POST["password"]));

    if($createUser->create()){
        $log->write(temizle($_POST["email"]) . ", email adresiyle bir kullanıcı kayıt oldu. "." Client IP: ".$_SERVER['REMOTE_ADDR']);
        ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i> Kayıt Başarılı!</h5>
            Giriş sayfasına yönlendiriliyorsunuz!
        </div>
        <?php
        die("<script type='text/javascript'>setTimeout(function() {window.location.href = '".$paths['url']."giris-yap.php';}, 2000);</script>");
    }else{
        ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban"></i> Bir hata oluştu!</h5>
        </div>
        <?php
        exit;
    }

    exit;

}
