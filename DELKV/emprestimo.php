<?php
include "conexao.php";
	
date_default_timezone_set("America/Sao_Paulo");

if(isset($_POST['botao1'])){



	#variaveis 
	$cpf=@$_POST['cpf'];
	$id=@$_POST['id'];
	$data=date("Y-m-d");
	$controladora=0;
	$reservas=array();

	#verifica disponibilidade
	function pagina($cpf,$id,$acao,$reserva,$nome,$idl){
	print("
	Favor $nome digite sua senha de login<br>
	Se o nome estiver errado, clique <a href='index.php?pag=emprestimo'>aqui</a> para voltar 
		<form id='cadastrar' action='index.php?pag=emprestimo' method='POST'>

			Senha   <input type=text placeholder='Senha' name=senha ><br>
			<input id='botao' type='submit' value='submeter' name='botao2' >
			<input id='inps' type='hidden' value=$cpf name='cpf'>
			<input id='inps' type='hidden' value=$id name='id'>
			<input id='inps' type='hidden' value=$acao name='acao'>
			<input id='inps' type='hidden' value=$reserva name='reserva'>
			<input id='inps' type='hidden' value=$idl name='livro'>
		</form>
	");
	}

	$sql = $db -> prepare("SELECT id_cliente,tipo,nome FROM usuario  where cpf=? ");
	$sql -> bind_param("s", $cpf);
	$sql -> execute();
	$sql-> bind_result($usuario,$tipo,$nome);
	while($sql ->fetch()){
	}

	if($tipo==''){
		header("location:index.php?pag=emprestimo&men=1");
	}else{
		
		$sql = $db -> prepare("SELECT id_livro_fk FROM exemplar  where id_exemplar=? ");
		$sql -> bind_param("s", $id);
		$sql -> execute();
		$sql-> bind_result($idl);
		while($sql ->fetch()){}

		$sql = $db -> prepare("SELECT r.id_exemplar_fk, r.reserva FROM reserva r, exemplar e where r.id_exemplar_fk=e.id_exemplar and r.id_cliente_fk=? and e.id_livro_fk=(SELECT id_livro_fk from exemplar where id_exemplar=?) ");
		$sql -> bind_param("ss", $usuario,$id );
		$sql -> execute();
		$sql-> bind_result($id_ex,$data_re);
		while($sql ->fetch()){
			if(strtotime($data)==strtotime($data_re) and $id_ex != $id){
				pagina($cpf,$id_ex,'reservado',$data,$nome,$idl);
				echo "O cliente posui uma reserva para esse livro, porem para o exemplar de código:$id_ex, o emprestimo será feito para o exempalr reservado, por favor troque o livro e prosiga normalmete";
				$controladora=1;
			}
		}

		if ($controladora ==0){

		
			#se o livro estiver reservado
			$sql = $db -> prepare("SELECT r.reserva, r.id_exemplar_fk FROM usuario u, reserva r where u.cpf= ? and u.id_cliente=r.id_cliente_fk");
			$sql -> bind_param("s", $cpf);
			$sql -> execute();
			$sql-> bind_result($reserva,$id_exemplar);
			while($sql ->fetch()){
				

				$data= intval(preg_replace("/-/",'',date("Y-m-d")));
				$reser= intval(preg_replace("/-/",'',$reserva));    
				
				if($data==$reser){
					
					#se o livro que o usuario pegou o mesmo que está reservado
					if($id==$id_exemplar){
						pagina($cpf,$id,'reservado',$reserva,$nome,$idl);
						$controladora=1;
						break;
					}
				}
			} 
		}
		#se não estiver reservado
		if($controladora==0){
			

			$sql = $db -> prepare("SELECT reserva FROM reserva where id_exemplar_fk= ? ");
			$sql -> bind_param("s", $id);
			$sql -> execute();
			$sql-> bind_result($data);
			
			while($sql ->fetch()){

				if($tipo==1){
					$datad=date("Y-m-d",strtotime(date("Y-m-d")."+7 day"));
				}else{
					$datad=date("Y-m-d",strtotime(date("Y-m-d")."+15 day"));
				}

				$data= intval(preg_replace("/-/",'',$data));
				$datad= intval(preg_replace("/-/",'',$datad));
				
				if($data<$datad){
					array_push($reservas, $id);
					
					break;
				}

			}

			if(count($reservas)!=0){
				print("Existe uma reserva para esse livro dentro do periodo em que ele estaria emprestado");
			}else{
				$datad=date("Y-m-d");
				pagina($cpf,$id,'emprestar',$datad,$nome,$idl);
			}
		}
	}
#fazer o emprestimo 
}elseif (isset($_POST['botao2'])){

	
	$cpf=$_POST['cpf'];
	$id=$_POST['id'];
	$senha=$_POST['senha'];
	$acao=$_POST['acao'];
	$reserva=$_POST['reserva'];
	$idl=$_POST['livro'];
	
	$sql = $db -> prepare("SELECT id_cliente,tipo FROM usuario where cpf= ? and senha=?");
	$sql -> bind_param("ss", $cpf, $senha);
	$sql -> execute();
	$sql-> bind_result($usuario,$tipo);
	while($sql ->fetch()){
	}
	if($usuario=='root'){
		print("
			<form action='index.php?pag=emprestimo' method='POST'>
					Senha incoreta.<br>
				Senha   <input type=text placeholder='Senha' name=senha ><br>
				<input type='submit' value='submeter' name='botao2'>
				<input type='hidden' value=$cpf name='cpf'>
				<input type='hidden' value=$id name='id'>
				<input type='hidden' value=$acao name='acao'>
				<input type='hidden' value=$reserva name='reserva'>
				<input type='hidden' value=$idl name='livro'>
			</form>
		");
	
	}else{
	
		$sql = $db -> prepare("SELECT id_cliente_fk FROM emprestimo where id_cliente_fk=? and id_exemplar_fk=? and devolucao is null");
		$sql -> bind_param("ss", $usuario, $id);
		$sql -> execute();
		$sql-> bind_result($i);
		while($sql ->fetch()){
		}
		if($i==''){
			
	
			if($acao=='reservado'){
				$sql=$db -> prepare("DELETE FROM reserva where id_cliente_fk=? and id_exemplar_fk=? and reserva=?");
				$sql -> bind_param("sss",$usuario,$id,$reserva);
				$sql -> execute();
	
			}
			$sql=$db -> prepare("INSERT into emprestimo values (?,?,?,null)");
			$sql -> bind_param("sss",$usuario,$id,$reserva );
			$sql -> execute();

			$sql=$db -> prepare("UPDATE	livro set acessos=(select acessos+1 from livro where id_livro=?) where id_livro=?");
			$sql -> bind_param("ss",$idl,$idl);
			$sql -> execute();


	
			if($tipo=1){
				$datah=date("d/m/Y",strtotime(date("Y-m-d")."+7 day"));
			}else{
				$datah=date("d/m/Y",strtotime(date("Y-m-d")."+15 day"));
			}
			
			print("
				<h1>Emprestimo efetuado</h1>
				a data de devolução do livro é:$datah

			");
		}else{
			print("Emprestimo ja existente");
		}
	}


}else{

	if(isset($_GET['men'])){
			$mensagem=$_GET['men'];
			if($mensagem==1){
				echo "cpf não encontrado, favor tente novamente.";
			}
		}
?>

<form id='cadastrar' action="index.php?pag=emprestimo" method="POST">

	<input id='inps' type=text placeholder="CPF Do Cliente" name=cpf ><br>
    <input id='inps' type=text placeholder="Código do Exemplar" name="id"><br>
	<input id='botao' type="submit" value="SUBMETER" name='botao1'>

</form>



<?php
}
?>