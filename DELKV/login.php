<?php
include "conexao.php";


$email=$_POST['username'];
$senha=$_POST['password'];
$controle=0;




$sql = $db -> prepare("SELECT id_cliente, bloqueado FROM usuario where cpf =? or email=? or usuario=? and senha=? ");
$sql -> bind_param('ssss',$email,$email,$email,$senha);
$sql -> execute();
$sql -> bind_result($id,$b);



while($sql ->fetch()){
	$controle=1;
	
	if ($b==1){
		setcookie('logado',$id);
		header("location:cadastro.php?men=3");
	}elseif($b==2){
		header("location:cadastro.php?men=2");
		setcookie('logado', '', time()-3600);
	}else{
		setcookie('logado',$id);
		header("location:index.php");
	}
}
if ($controle==0){
	header("location:cadastro.php?men=1");
	setcookie('logado', '', time()-3600);
}

?>