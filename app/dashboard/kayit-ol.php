<?php
ob_start();
require_once '../constant.php';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kayıt Ol | DataScout</title>

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
<body class="hold-transition register-page">
<div class="register-box">
    <div class="register-logo">
        <img src="<?=$paths['cdnUrl']?>/img/datascout.png?ver=123" style="width: 100%;">
    </div>

    <div class="card">
        <div class="card-body register-card-body">
            <p class="login-box-msg">Üyelik oluştur</p>

            <form id="userRegisterForm">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="name" maxlength="255" placeholder="İsminiz" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="email" class="form-control" name="email" maxlength="255" placeholder="Email" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Parola" name="password" maxlength="255" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Parola Tekrar" name="retryPassword" maxlength="255" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="agreeTerms" name="terms" value="1" required>
                            <label for="agreeTerms">
                                <a href="#">Kullanıcı Sözleşmesi'ni</a> kabul ediyorum
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" name="userRegisterSubmit" class="btn btn-primary btn-block">Kayıt Ol</button>
                        <button type="button" name="userRegisterLoader" class="btn btn-primary btn-block" style="display: none"><i class="fa fa-sync fa-spin"></i></button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <div class="social-auth-links text-center">
                <p>- VEYA -</p>
            </div>

            <a href="giris-yap.php" class="text-center">Zaten bir üyeliğim var</a>
        </div>
        <!-- /.form-box -->
    </div><!-- /.card -->
    <br>
    <div id="userRegisterResults"></div>
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>

    $(document).ready(function (){
        $("#userRegisterForm").submit(function(event) {
            $('#userRegisterLoader').show();
            $("#userRegisterSubmit").prop('disabled', true);
            $.ajax({
                type: 'post',
                url: 'ajax/userRegister.php',
                data: $('#userRegisterForm').serialize(),
                beforeSend:function(){
                    $("#userRegisterSubmit").prop('disabled', true);
                    $("#userRegisterSubmit").hide();
                    $("#userRegisterLoader").show();
                },
                success: function (result) {
                    $("#userRegisterSubmit").prop('disabled', false);
                    $("#userRegisterSubmit").show();
                    $("#userRegisterLoader").hide();
                    $("#userRegisterResults").html(result);
                }
            });
            return false;
        });
    });

</script>
</body>
</html>
