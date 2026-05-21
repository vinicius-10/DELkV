<?php
include "conexao.php";

$nome=$_POST['autora'];

$sql = $db -> prepare("SELECT id_autora FROM autora where nome_autora=?");
$sql -> bind_param("s",$nome);
$sql -> execute();
$sql-> bind_result($id);

while($sql ->fetch()){
}
if(empty($id)){


    $sql = $db -> prepare("INSERT into autora values(null,?)");
    $sql -> bind_param("s",$nome);
    $sql -> execute();
}

header("location:index.php?resu=2");


?>