<?php
if(isset($_POST['botaod'])){
    include "conexao.php";
    date_default_timezone_set("America/Sao_Paulo");

    $cod=$_REQUEST['cod'];
    $datah=date("Y-m-d");

    $sql = $db -> prepare("SELECT e.emprestimo, u.tipo, u.id_cliente, l.id_livro FROM emprestimo e, exemplar ex, livro l, usuario u where e.id_cliente_fk=u.id_cliente and e.id_exemplar_fk = ex.id_exemplar and ex.id_livro_fk= l.id_livro and devolucao is null and e.id_exemplar_fk=?");
    $sql -> bind_param("i",$cod);
    $sql -> execute();
    $sql-> bind_result($emprestimos,$tipo,$idc,$idl);
    while($sql ->fetch()){

    }
    if(empty($emprestimos)){
        header("location:index.php?men=2&pag=devolucao");
    }else{
        if($tipo==1){
            $data_devolu=date("Y-m-d",strtotime($emprestimos."+7 day"));
        }else{
            $data_devolu=date("Y-m-d",strtotime($emprestimos."+15 day"));
        }

        if(strtotime($datah)>=strtotime($data_devolu)){

            $data1 = new DateTime( $datah );
            $data2 = new DateTime( $data_devolu );
        
            $intervalo = $data1->diff( $data2 );
            $intervalo= $intervalo ->days;
        
            $multa= 5+1.5*$intervalo;
        
            $sql = $db -> prepare("INSERT into multa values(?,?,?)");
            $sql -> bind_param("sss",$idl,$idc,$multa);
            $sql -> execute();
        
            echo "O livro está atrasado $intervalo dias, o cliente deve pagar uma multa de:R$$multa,00<br>";
            
        }

        $sql = $db -> prepare("UPDATE emprestimo set devolucao=? where id_exemplar_fk=? and devolucao IS NULL");
        $sql -> bind_param("ss",$datah,$cod);
        $sql -> execute();
        echo "devolucao feita";
    }

}else{


	
	$mensagem=@$_GET['men'];
	if($mensagem==2){
		header('location:index.php?resu=13');
	}
	

	?>
<form id='cadastrar' action="index.php" method="POST">
	<input id='inps' type=text placeholder="código do livro" name='cod'><br>
	<input id='botao' type="submit" value="DEVOLVER" name='botaod'>

</form>


<?php
}
?>