<?php
$host = "localhost";
$usuario= "root";
$senha = "";
$db=mysqli_connect("$host","$usuario","$senha") or die ("Problema no servidor!");
$db -> select_db("biblioteca") or die ("Problema com o banco de dados!");
?>
