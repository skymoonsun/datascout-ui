<?php
ob_start();
require_once '../constant.php';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Giriş Yap | DataScout</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">

    <link rel="Shortcut Icon" href="<?=$paths['cdnUrl']?>/img/datascout-favicon.png" type="image/x-icon" />
</head>
<body class="hold-transition login-page" style="background-color:#c6c6c6">
<div class="login-box">
    <div class="login-logo">
        <a href="giris-yap.php">
            <img src="<?=$paths['cdnUrl']?>/img/datascout.png?ver=123" style="width: 100%;">
        </a>
    </div>
    <!-- /.login-logo -->
    <div class="card" style="background-color:#bbbbbb">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Hesabınıza giriş yapın</p>

            <form id="userLoginForm">
                <div class="input-group mb-3">
                    <input type="email" maxlength="255" required class="form-control" name="email" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-info-circle"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" maxlength="255" required class="form-control" name="password" placeholder="Parola">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" name="userLoginSubmit" class="btn btn-primary btn-block">Giriş Yap</button>
                        <button type="button" name="userLoginSubmit" class="btn btn-primary btn-block" style="display: none;"><i class="fa fa-sync fa-spin"></i></button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <div class="social-auth-links text-center">
                <p>- VEYA -</p>
            </div>

            <a href="kayit-ol.php" class="text-center">Üyeliğin yoksa kayıt ol</a>
        </div>
        <!-- /.login-card-body -->
    </div>
    <br>
    <div id="userLoginResults"></div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>

    $(document).ready(function (){
        $("#userLoginForm").submit(function(event) {
            $('#userLoginLoader').show();
            $("#userLoginSubmit").prop('disabled', true);
            $.ajax({
                type: 'post',
                url: 'ajax/userLogin.php',
                data: $('#userLoginForm').serialize(),
                beforeSend:function(){
                    $("#userLoginSubmit").prop('disabled', true);
                    $("#userLoginSubmit").hide();
                    $("#userLoginLoader").show();
                },
                success: function (result) {
                    $("#userLoginSubmit").prop('disabled', false);
                    $("#userLoginSubmit").show();
                    $("#userLoginLoader").hide();
                    $("#userLoginResults").html(result);
                }
            });
            return false;
        });
    });

</script>
</body>
</html>
