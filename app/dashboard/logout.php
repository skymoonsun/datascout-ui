<?php
session_start();
unset($_SESSION);
session_destroy();

header("Location: giris-yap.php");
exit();
?>