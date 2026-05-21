<?php
include "conexao.php";

$id=@$_REQUEST['id'];
$acao=@$_REQUEST['acao'];
$novo=@$_REQUEST['nome'];


if ($acao=='editar'){
    if (empty($novo)){
            echo "
            <!DOCTYPE HTML>
        <html lang='pt-BR'>
        <head>
        <link rel='shortcut icon' href='imagens/favicon.ico' />
        <title>Emprestimo</title>

                <meta charset='UTF-8'>

        <link rel='stylesheet' href='./ExcAtu_geral.css'>
        </head>
        <body>
            <div class='container'>
            <form id='cadastrar' action='ExcAtu_Autora.php' method='POST'>
                <h1>Atualiza Autora</h1>
                <input id='inps' type=text placeholder='Novo nome' name='nome' ><br>
                <input id='botao' type='submit' value='submeter'>
                <input type='hidden' value=$id name='id'>
                <input type='hidden' value=$acao name='acao'>
            </form>
            </div>
        </body>
        </html>
        ";
    }else{
        $sql=$mysqli -> prepare("UPDATE autora SET nome_autora = ? WHERE id_autora =? ");
        $sql -> bind_param("ss",$novo,$id);
        $sql -> execute();
        header('location:index.php?resu=9');
    }

}elseif($acao=='excluir'){
    $sql = $db -> prepare("SELECT id_autora_fk FROM autoraref WHERE id_autora_fk=? ");
    $sql -> bind_param('s',$id);
    $sql -> execute();
    $sql-> bind_result($editora);
    while($sql ->fetch()){}
    if(empty($editora)){

    $sql=$mysqli -> prepare("DELETE FROM autora where id_autora=?");
    $sql -> bind_param("s",$id);
    $sql -> execute();
    header('location:index.php?resu=8'); 
    }else{
        header('location:index.php?resu=11');
    }   
}else{
    header("location:index.php");
}

?>