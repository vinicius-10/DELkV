<?php

include "conexao.php";

$nome=$_POST['editora'];

$sql = $db -> prepare("SELECT id_editora FROM editora where nome_editora=?");
$sql -> bind_param("s",$nome);
$sql -> execute();
$sql-> bind_result($id);

while($sql ->fetch()){
}
if(empty($id)){


    $sql = $db -> prepare("INSERT into editora values(null,?)");
    $sql -> bind_param("s",$nome);
    $sql -> execute();
}

header("location:index.php?resu=3");


?>