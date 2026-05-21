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
            <form id='cadastrar' action='ExcAtu_Genero.php' method='POST'>
                <h1>Atualizar Genero</h1>
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
        $sql=$db -> prepare("UPDATE genero SET genero = ? WHERE id_genero =? ");
        $sql -> bind_param("ss",$novo,$id);
        $sql -> execute();
        header('location:index.php?resu=16');
    }

}elseif($acao=='excluir'){

    $sql = $db -> prepare("SELECT genero_fk FROM livro WHERE genero_fk=? ");
    $sql -> bind_param('s',$id);
    $sql -> execute();
    $sql-> bind_result($genero);
    while($sql ->fetch()){}
    if(empty($genero)){

    $sql=$db -> prepare("DELETE FROM genero where id_genero=?");
    $sql -> bind_param("s",$id);
    $sql -> execute();
    header('location:index.php?resu=15');   
    }else{
        header('location:index.php?resu=17');
    } 
}else{
    header("location:index.php");
}

?>