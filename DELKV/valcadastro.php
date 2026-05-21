<?php
session_start();
include "conexao.php";
$cpf = $_POST['cpf'];
$nome = $_POST['nome'];
$usuario = $_POST['usuario'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$data =  $_POST['data'];
$tipo = $_POST['tipo'];

if(empty($_POST['cpf']) or empty($_POST['nome']) or empty($_POST['usuario']) or empty($_POST['email']) or empyty($_POST['senha']) or empyty($_POST['data']) or empyty($_POST['tipo'])){
    header("location:cadastro.php?men2=4")
}else{
    $cpf = $_POST['cpf'];
    $nome = $_POST['nome'];
    $usuario = $_POST['usuario'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $data =  $_POST['data'];
    $tipo = $_POST['tipo'];
}





$_SESSION['cpf'] = $cpf;
$_SESSION['nome'] = $nome;
$_SESSION['usuario'] = $usuario;
$_SESSION['email'] = $email;








$sql = $db -> prepare("SELECT id_cliente FROM usuario where cpf=?");
$sql -> bind_param("s", $cpf);
$sql -> execute();
$sql-> bind_result($id);

while($sql ->fetch()){}
if($id==''){
    
    $sql = $db -> prepare("SELECT id_cliente FROM usuario where email=? ");
    $sql -> bind_param("s", $email);
    $sql -> execute();
    $sql-> bind_result($id);
    while($sql ->fetch()){}
    if($id==''){
        
        $sql = $db -> prepare("SELECT id_cliente FROM usuario where usuario=? ");
        $sql -> bind_param("s",$usuario);
        $sql -> execute();
        $sql-> bind_result($id);
        while($sql ->fetch()){}
        if($id==''){
            $sql=$db -> prepare("INSERT INTO usuario(cpf,nome,usuario,senha,nascimento,tipo,email)VALUES(?,?,?,?,?,?,?)");
            $sql -> bind_param("sssssss",$cpf,$nome,$usuario,$senha,$data,$tipo,$email);
            $sql -> execute();

            $_SESSION['cpf'] = '';
            $_SESSION['nome'] = '';
            $_SESSION['usuario'] = '';
            $_SESSION['email'] = '';
            header("location:cadastro.php");
        }else{
            header("location:cadastro.php?men2=3");
        }
    }else{
        header("location:cadastro.php?men2=2");
    }
}else{
    header("location:cadastro.php?men2=1");
}
?>