<!DOCTYPE HTML>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./recuperarCSS.css" />
	<link rel='shortcut icon' href='imagens/favicon.ico' />
    <title>Recuperar senha</title>

<script>
	function validarSenha(){
	NovaSenha = document.getElementById('senha').value;
	CNovaSenha = document.getElementById('confirmacao').value;
	if (NovaSenha != CNovaSenha) {
		alert("Senhas diferentes\nConfira a senha e tente novamente"); 
	}else{
		document.FormSenha.submit();
	}
	}
</script>

</head>
<body>


<?php
include "conexao.php";
include "cabecalho.php";
$cod=@$_GET['cod'];

if(isset($_POST['senha'])){
	$senha=$_POST['senha'];

	$sql=$db -> prepare("UPDATE usuario SET senha = ? WHERE id_cliente= ? ");

	$sql -> bind_param("si", $senha,$cod);

	$sql -> execute();
	echo "senha alterada com suscesso<br><a href=index.php> Clique aqui para voltar</a>";
}else{
?>


<div class="container">
	<div class="item">
<form action="" method="POST" name="FormSenha" id="FormSenha">
	<input type="password" placeholder="Nova senha" id='senha' name="senha"><br>

	<input type="password" onkeyup="verifica_senha()" placeholder="Confirme Senha" id='confirmacao'>

	<button type="button" class="btn btn-primary pull-right botao_reset_senha" onClick="validarSenha()">Enviar</button>
	

</form>
</div>
</div>
</body>
</html>
<?php
}
?>