<?php
include "conexao.php";
date_default_timezone_set("America/Sao_Paulo");


if(empty($_COOKIE['logado'])){
    $id_usu=$_COOKIE['logado'];
    $id_livro=$_POST['id'];
    header("location:livro.php?id=${id_livro}&men=4");

};

$id_usu=$_COOKIE['logado'];
$id_livro=$_POST['id'];
$data=$_POST['data'];



#verificar se a data que foi digitada é menor que a data atual
$datah=date("Y-m-d");
if(strtotime($data)<strtotime($datah)){
    header("location:livro.php?id=${id_livro}&men=3");
}else{

    $id_exemplar=array();
    $id_total=array();


    $sql = $db -> prepare("SELECT id_exemplar FROM exemplar where id_livro_fk= ? ");
    $sql -> bind_param("s", $id_livro);
    $sql -> execute();
    $sql-> bind_result($id);

    while($sql ->fetch()){
        array_push($id_exemplar, $id);
    }

    $sql = $db -> prepare("SELECT tipo FROM usuario where id_cliente= ? ");
    $sql -> bind_param("s", $id_usu);
    $sql -> execute();
    $sql-> bind_result($tipo_usu);

    while($sql ->fetch()){}
    foreach ($id_exemplar as $ids){

        $sql = $db -> prepare("SELECT e.id_exemplar_fk ,e.emprestimo,u.tipo  FROM emprestimo e, usuario u where e.id_exemplar_fk= ? and e.devolucao is null and e.id_cliente_fk=u.id_cliente");
        $sql -> bind_param("s", $ids);
        $sql -> execute();
        $sql -> bind_result($id,$emprestimos,$tipo);

        while($sql ->fetch()){

            if($tipo==1){
                $emprestimo=date("Y-m-d",strtotime($emprestimos."+7 day"));
            }else{
                $emprestimo=date("Y-m-d",strtotime($emprestimos."+15 day"));
            }

            if(strtotime($data)<=strtotime($emprestimo)){
                array_push($id_total, $id);
            }
        }

        $sql = $db -> prepare("SELECT r.id_exemplar_fk, r.reserva, u.tipo FROM reserva r, usuario u where r.id_exemplar_fk= ? and r.id_cliente_fk=u.id_cliente");
        $sql -> bind_param("s", $ids);
        $sql -> execute();
        $sql-> bind_result($id,$reservas,$tipo);

        while($sql ->fetch()){

            if($tipo==1){
                $reserva=date("Y-m-d",strtotime($reservas."+7 day"));
            }else{
                $reserva=date("Y-m-d",strtotime($reservas."+15 day"));
            }

            if($tipo_usu==1){
                $datad=date("Y-m-d",strtotime($data."+7 day"));
            }else{
                $datad=date("Y-m-d",strtotime($data."+15 day"));
            }


            if(strtotime($data)>=strtotime($reservas) and strtotime($data)<=strtotime($reserva)){
                array_push($id_total, $id);
            }

            if(strtotime($data)<=strtotime($reservas) and strtotime($datad)>=strtotime($reservas)){
                array_push($id_total, $id);
            }
        }
    }

    foreach($id_total as $ide){
        
        $chave= array_search($ide, $id_exemplar);
        if($chave !== false){
            unset($id_exemplar[$chave]);
        };

    }

    if(count($id_exemplar)===0){

        header("location:livro.php?id=${id_livro}&men=2");
        
    }else{
        $ide=reset($id_exemplar);
        $sql = $db -> prepare("INSERT into reserva values (?,?,?)");
        $sql -> bind_param("sis", $id_usu,$ide,$data);
        $sql -> execute();
        header("location:livro.php?id=${id_livro}&men=1");
        
    }

}

?>