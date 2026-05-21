<?php
setcookie('logado', '', time()-3600);
header("location:index.php");
?>