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
        <title>Editora</title>

                <meta charset='UTF-8'>

        <link rel='stylesheet' href='./ExcAtu_geral.css'>
        </head>
        <body>
            <div class='container'>
            <form id='cadastrar' action='ExcAtu_Editor.php' method='POST'>
                <h1>Atualizar editora</h1>
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
        $sql=$db -> prepare("UPDATE editora SET nome_editora = ? WHERE id_editora =? ");
        $sql -> bind_param("ss",$novo,$id);
        $sql -> execute();
        header('location:index.php?resu=7');
    }

}elseif($acao=='excluir'){

    $sql = $db -> prepare("SELECT id_editora_fk FROM livro WHERE id_editora_fk=? ");
    $sql -> bind_param('s',$id);
    $sql -> execute();
    $sql-> bind_result($editora);
    while($sql ->fetch()){}
    if(empty($editora)){

    $sql=$db -> prepare("DELETE FROM editora where id_editora=?");
    $sql -> bind_param("s",$id);
    $sql -> execute();
    header('location:index.php?resu=6');   
    }else{
        header('location:index.php?resu=12');
    } 
}else{
    header("location:index.php");
}

?>