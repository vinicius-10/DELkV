<?php
include "conexao.php";

$editora = $_POST['editora'];
$nomeLivro = $_POST['nomeLivro'];
$sinop = $_POST['sinop'];
$genero = $_POST['genero'];
$desc = $_POST['desc'];
$exmp = $_POST['qnt'];


$ext=pathinfo($_FILES['arquivo']['name'],PATHINFO_EXTENSION);
$novoNome="./imagens/".uniqid().".$ext";



if(move_uploaded_file($_FILES['arquivo']['tmp_name'], $novoNome)){

    $sql = $db -> prepare("INSERT INTO livro (id_livro, id_editora_fk, nome_livro, sinopse, genero_fk, descricao, imagem, acessos)
    VALUES (NULL, ?,?,?,?,?,?, '0')");
    $sql -> bind_param("ssssss",$editora, $nomeLivro, $sinop, $genero, $desc,$novoNome);
    $sql -> execute();


}else{
    $sql = $db -> prepare("INSERT INTO livro (id_livro, id_editora_fk, nome_livro, sinopse, genero_fk, descricao, imagem, acessos)
    VALUES (NULL, ?,?,?,?,?,'./imagens/s_img.png', '0')");
    $sql -> bind_param("sssss",$editora, $nomeLivro, $sinop, $genero, $desc);
    $sql -> execute();

   
}

$sql = $db -> prepare("SELECT id_livro FROM livro where nome_livro = ?");
$sql ->bind_param('s',$nomeLivro);
$sql -> execute();
$sql-> bind_result($idlivro );
while($sql ->fetch()){}


$texto = 'INSERT INTO exemplar(id_livro_fk) VALUES';

$cont = 1;
while($cont <= intval($exmp)){
    $cont++;
    $texto=$texto." (${idlivro}),";
};

$texto = substr($texto, 0, -1);
$sql = $db -> prepare("$texto");
$sql -> execute();



$sql = $db -> prepare("SELECT id_autora from autora");
$sql -> execute();
$sql-> bind_result($id );

$comando = 'INSERT INTO autoraref VALUES';

while($sql ->fetch()){
    if(isset($_POST["$id"])){
        $controle=1;
        $comando=$comando." ($id,$idlivro),";

    }
}
if (isset($controle)){
$comando = substr($comando, 0, -1);
$sql = $db -> prepare("$comando");

$sql -> execute();
}


header("location:index.php?resu=1");







?>