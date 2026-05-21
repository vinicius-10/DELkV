<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./recuperarCSS.css" />
	<link rel='shortcut icon' href='imagens/favicon.ico' />
    <title>Recuperar senha</title>
</head>
<body>


<?php
include "conexao.php";

include "cabecalho.php";

if(isset($_POST['botao'])){
	$email=$_POST['username'];
	$controladora=0;


	$sql = $db -> prepare("SELECT id_cliente, email FROM usuario");
	$sql -> execute();
	$sql-> bind_result($id,$e);
	while($sql ->fetch()){


		if($email==$e){
			$controladora=1;

			echo shell_exec("python recuperar.py $id $e");

			?>
			<div class="item">
				Email enviado
			</div>
			<?php

			break;
		}
	}
	if($controladora==0) {
		header("location:recuperar_html.php?men=1");
	}
}else{
	?>

<div class="container">
	<div class="item">
			<?php

			if(isset($_GET['men'])){
				$mensagem=$_GET['men'];
				if($mensagem==1){
					echo 'usuario não encontrado';
				}
			}

			?>

		<form action='' method='POST'>

			<input type=text placeholder='Email' name=username><br>
			<input type='submit' value='Entrar' name='botao'>

		</form>
	</div>
</div>
</body>
</html>
<?php
}
?>

