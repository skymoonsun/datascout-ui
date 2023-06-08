<?php
require_once("../../constant.php");

if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {

    header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );

    die("File does not exists!");

}

@session_start();

if($_POST){

    if($_POST["email"] == "" || $_POST["password"] == ""){
        ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban"></i> Bir hata oluştu!</h5>
            Lütfen E-mail ve şifrenizi giriniz!
        </div>
        <?php
        exit;
    }

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

    $user = new User();
    $log = new Log();

    $userCheck = $user->search([
        "email" => temizleGet($_POST["email"]),
        "password" => sifrele(temizle($_POST["password"]))
    ]);

    if(count($userCheck) == 0){
        ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban"></i> Bir hata oluştu!</h5>
            Girdiğiniz E-mail veya şifre hatalı!
        </div>
        <?php
        exit;
    }

    $_SESSION["USER_EMAIL"] = $userCheck[0]["email"];
    $_SESSION["USER_NAME"] = $userCheck[0]["name"];
    $_SESSION["USER_ID"] = $userCheck[0]["id"];

    if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $log->write($userCheck[0]["email"]. " giriş yaptı!". " Proxy forwarded for: ".$_SERVER['HTTP_X_FORWARDED_FOR']." Client IP: ".$_SERVER['REMOTE_ADDR']);
    }else{
        $log->write($userCheck[0]["email"]. " giriş yaptı!". " Client IP: ".$_SERVER['REMOTE_ADDR']);
    }

    ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Giriş Başarılı!</h5>
        Anasayfa'ya yönlendiriliyorsunuz!
    </div>
    <?php
    die("<script type='text/javascript'>setTimeout(function() {window.location.href = '".$paths['url']."index.php';}, 1000);</script>");

    exit;

}
