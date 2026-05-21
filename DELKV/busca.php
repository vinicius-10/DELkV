<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"  href="./busca_nome.css" />
    <link rel='shortcut icon' href='imagens/favicon.ico' />
    <title>Delkv</title>
</head>
<body>
    
    <?php

        include "conexao.php";
        include "cabecalho.php";
        include "sloganbuscar.php";


        $valor = @$_POST['busca'];
        $classe  = @$_GET['q'];
        $tipo = @$_GET['tp'];

        if(empty($valor) and (empty($classe) or empty($tipo))){
            header('location:index.php');
        }


    ?>


    <br>
    <center><h1 id="tituloPesq">
    
    <?php 
        
        
        if($tipo == 'g'){

            $sql = $db -> prepare("SELECT genero from genero where id_genero = ?");
            $sql ->bind_param('s',$classe);
            $sql -> execute();
            $sql-> bind_result($qualtipo );
            while($sql ->fetch()){}
            echo "Sua Busca Por $qualtipo";

        }elseif($tipo == 'e'){

            $sql = $db -> prepare("SELECT nome_editora from editora where id_editora = ?");
            $sql ->bind_param('s',$classe);
            $sql -> execute();
            $sql-> bind_result($qualtipo );
            while($sql ->fetch()){}
            echo "Sua Busca Por $qualtipo";

        }elseif($tipo == 'a'){

            $sql = $db -> prepare("SELECT nome_autora from autora where id_autora = ?");
            $sql ->bind_param('s',$classe);
            $sql -> execute();
            $sql-> bind_result($qualtipo );
            while($sql ->fetch()){}
            echo "Sua Busca Por $qualtipo";

        }else{

            echo "Sua Busca Por ${valor}";
        }
        
    
    ?> </h1></center>

    <div class="container">

        <?php
        
        if(!empty($valor)){

            $buscalivros = $db  -> prepare("SELECT id_livro,nome_livro,imagem from livro WHERE nome_livro LIKE '%$valor%'");
            
        
        }elseif($tipo == 'g'){
            $buscalivros = $db  -> prepare("SELECT id_livro,nome_livro,imagem from livro WHERE genero_fk = $classe");
            
            }
        elseif($tipo == 'e'){
            $buscalivros = $db  -> prepare("SELECT id_livro,nome_livro,imagem from livro WHERE id_editora_fk = $classe");            
        
        }elseif($tipo == 'a'){
            $buscalivros = $db  -> prepare("SELECT l.id_livro,l.nome_livro,l.imagem from livro l, autoraref a WHERE l.id_livro = a.id_livro_fk and a.id_autora_fk = $classe ");
            
        }
        
        $sb = 0;

        $buscalivros -> execute();
        $buscalivros-> bind_result($id,$nome,$img );

        while($buscalivros ->fetch()){
            $sb=1;


            echo "<a href='livro.php?id=${id}'><div class='item'>
            <img src='${img}' width='150' height='250'>
            <p>${nome}</p>
        </div></a>";
        }
        if($sb==0){
            echo "<script>
            function semNada(){
                const title = document.getElementById('tituloPesq');
                title.innerHTML = '0 resultados para sua pesquisa';
            }
            semNada()
            </script>";
        }
        
        ?>  
    </div>
</body>
</html>