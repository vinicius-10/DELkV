<?php
include "conexao.php";

$nome=$_POST['genero'];

$sql = $db -> prepare("SELECT id_genero FROM genero where genero=?");
$sql -> bind_param("s",$nome);
$sql -> execute();
$sql-> bind_result($id);

while($sql ->fetch()){
}
if(empty($id)){


    $sql = $db -> prepare("INSERT into genero values(null,?)");
    $sql -> bind_param("s",$nome);
    $sql -> execute();
}

header("location:index.php?resu=14");


?>