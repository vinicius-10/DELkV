<?php

include "conexao.php";
if(isset($_GET['pag'])){
    $pagina=$_GET['pag'];
    
}else{
    $pagina='vazio';
}
$resultado='';
$resu=@$_GET['resu'];
if(empty($resu)){
    $resu=0;
}

$resultado= array(
0=>"",
1=>"O livro foi cadastrado com susceso",
2=>"Autora cadastrada com susceso",
3=>"Editora cadastrada com susceso",
4=>"Livro deletado com susceso",
5=>"Livro atualido com susceso",
6=>"Editora excluida com susceso",
7=>"Editora atualizada com susceso",
8=>"Aurora excluida com susceso",
9=>"Editora excluida com susceso",
10=>"Esse livro não pode ser deletado, existem emprestimo ou reserva pra esse livro cadastrado no sistema",
11=>"Não é possiverl excluir essa autora, ela posui livro cadastrado no sitema, apague o livro e tente novamente",
12=>"Não é possiverl excluir essa editora, ela posui livro cadastrado no sitema, apague o livro e tente novamente",
13=>"Não à exemplar emprestado com esse código",
14=>"Gênero Cadastrado com sucesso",
15=>"Genero Excluído com sucesso",
16=>"Genero atualizada com susceso",
17=>"Não é possiverl excluir esse genero, ele posui livro cadastrado no sitema, apague o livro e tente novamente"
);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./admcss.css">
    <link rel='shortcut icon' href='imagens/favicon.ico' />
    <title>Area adiministrativa</title>
    
</head>
<body>

<div class="usuario">
    <p>Bem Vindo/a, <?php echo $nome; ?></p>
    <?php echo "<h4 style='color:green;'>$resultado[$resu]</h4>"; ?>
    
</div>







<div class="container">
    <div class="item">
        <h2 onclick="aparecer(1)">Emprestimo</h2>
        <div id="conteudo" class="cont1">
            <?php include "emprestimo.php"; ?>
        </div>
        
    </div>
    <div class="item">
        <h2 onclick="aparecer(9)">Devolução</h2>
        <div id="conteudo" class="cont9">
            <?php include "devolucao.php"; ?>
        </div>
        
    </div>
    <div class="item">
        <h2 onclick="aparecer(2)">Cadastrar Livro</h2>
        <div id="conteudo" class="cont2">

        <form id='cadastrar' action='./cadLivro.php' method='POST' enctype="multipart/form-data">

        <select id='inps' name='editora'>
            <option>Selecionar Editora</option>
            <?php

                $queryEditora  = $db -> query("SELECT * from editora");
                while($row = $queryEditora -> fetch_array()){
                    $nome = $row['nome_editora'];
                    $id = $row['id_editora'];

                    echo "<option value='${id}'>${nome}</option>";

                }


            ?>
        </select>
        
            <div id='item'>
            <h3 onclick="aparecer(5)" class='titulo-unico'>Selecionar Autora</h3>
            <div id="conteudo" class="cont5 listar-autoras">
            
            <?php

                $queryAutora  = $db -> query("SELECT * from autora");
                while($row = $queryAutora -> fetch_array()){
                    $nome = $row['nome_autora'];
                    $id = $row['id_autora'];

                    echo "
                    <div id=autores>
                    <label><input type=checkbox value='$id' name='$id'>$nome</label>
                    </div>
                    ";

                }


            ?>
            </div>
        
        </div>

        <input id='inps' name='nomeLivro' type='text' placeholder='Nome Livro'>
        <textarea name='sinop' placeholder='Sinopse do livro'></textarea>
        <select id='inps' name='genero'>
            <option>Selecionar Genero</option>
            <?php

                $query  = $db -> query("SELECT * from Genero");
                while($row = $query -> fetch_array()){
                    $nome = $row['genero'];
                    $id = $row['id_genero'];

                    echo "<option value='${id}'>${nome}</option>";

                }


            ?>
        </select>
        <textarea name='desc' placeholder='Descrição'></textarea>
        <input id='inps' name='qnt' type='number' placeholder='Quantidade de Exemplares'>
        <input id='inps' class='arquivo' name='arquivo' type='file' accept="image/*" placeholder='Imagem'>
        <input id='botao' type='submit' value='Cadastrar'>

        </form>


        </div>
    </div>

    <div class="item">
        <h2 onclick="aparecer(3)">Cadastrar Autora</h2>
        <div id="conteudo" class="cont3">

        <form id='cadastrar' action='./cadAutora.php' method='POST'>

            <input id='inps' name='autora' type='text' placeholder='Nome Da Autora'>
            <input id='botao' type='submit' value='Cadastrar'>


        </form>

        </div>

    </div>
    <div class="item">
        <h2 onclick="aparecer(10)">Cadastrar Gênero</h2>
        <div id="conteudo" class="cont10">

        <form id='cadastrar' action='./cadGenero.php' method='POST'>

            <input id='inps' name='genero' type='text' placeholder='Gênero'>
            <input id='botao' type='submit' value='Cadastrar'>


        </form>

        </div>

    </div>

    <div class="item">
        <h2 onclick="aparecer(4)">Cadastrar Editora</h2>
        <div id="conteudo" class="cont4">
        <form id='cadastrar' action='./cadEditora.php' method='POST'>

        <input id='inps' type='text' name='editora' placeholder='Nome Da Editora'>
        <input id='botao' type='submit' value='Cadastrar'>

        </form>
        
        </div>
        


    </div>
    <div class="item">
        <h2 onclick="aparecer(6)">Buscar livro</h2>
        <div id="conteudo" class="cont6">

        <div id="sloganbuscar">
        <form id='cadastrar' action='index.php?pag=busca' method='POST'>
            <div id="busca">
                <input id='inps' type="text" name="busca" placeholder="Pesquise Aqui">
                <input id="botao" type="submit" value="Buscar" name='botaob'>
            </div>
        </form>
        </div>
            
            <?php
            
                if (isset($_POST['botaob'])){

                    include "conexao.php";

                    $valor = @$_POST['busca'];
                    echo "<center><h3>Sua Busca Por ${valor}</h3></center>";

                    $buscalivros = $db  -> prepare("SELECT id_livro,nome_livro,imagem from livro WHERE nome_livro LIKE '%$valor%'");
                    $sb = 0;

                    $buscalivros -> execute();
                    $buscalivros-> bind_result($id,$nome,$img );

                    while($buscalivros ->fetch()){
                        $sb=1;


                        echo "<div class='item'>
                        <img src='$img' width='150' height='250'>
                        $nome
                        <a href='ExcAtu_livro.php?id=$id&acao=atualizar'><button id='bot'>Atualiza Dados</button></a>
                        <a href='ExcAtu_livro.php?id=$id&acao=deletar'><button id='bot'>Deletar livro</button></a>
                    </div>";
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

                }
            ?>
    </div>
        
    </div>
    <div class="item">
        <h2 onclick="aparecer(7)">Listar editora</h2>
        <div id="conteudo" class="cont7">
            <ul>
                <?php
                
                $sql = $db -> prepare("SELECT nome_editora,id_editora FROM editora order by nome_editora");

                $sql -> execute();
                $sql-> bind_result($nome,$id);

                while($sql ->fetch()){

                    echo "<li>
                                <div id='Bloq-usuario'>
                                ${nome}
                                </div>

                                <div id='Bloq-bot'>
                                <a href='ExcAtu_Editor.php?id=$id&acao=editar'> <button id='bot'>Editar</button></a> <a href='ExcAtu_Editor.php?id=$id&acao=excluir'><button id='bot'>Apagar</button></a><br>
                                </div>
                            </li>";
                }

                ?>
            </ul>
        </div>
        
    </div>
    <div class="item">
        <h2 onclick="aparecer(11)">Listar genero</h2>
        <div id="conteudo" class="cont11">
            <ul>
                <?php
                
                $sql = $db -> prepare("SELECT genero,id_genero FROM genero order by genero");

                $sql -> execute();
                $sql-> bind_result($nome,$id);

                while($sql ->fetch()){

                    echo "<li>
                                <div id='Bloq-usuario'>
                                ${nome}
                                </div>

                                <div id='Bloq-bot'>
                                <a href='ExcAtu_Genero.php?id=$id&acao=editar'> <button id='bot'>Editar</button></a> <a href='ExcAtu_Genero.php?id=$id&acao=excluir'><button id='bot'>Apagar</button></a><br>
                                </div>
                            </li>";
                }

                ?>
            </ul>
        </div>
        
    </div>
    <div class="item">
        <h2 onclick="aparecer(8)">Listar autoras</h2>
        <div id="conteudo" class="cont8">

            <ul>
                <?php
                    
                    $sql = $db -> prepare("SELECT nome_autora,id_autora FROM autora order by nome_autora");

                    $sql -> execute();
                    $sql-> bind_result($nome,$id);

                    while($sql ->fetch()){
                        echo "<li>
                                    <div id='Bloq-usuario'>
                                    ${nome}
                                    </div>

                                    <div id='Bloq-bot'>
                                    <a href='ExcAtu_Autora.php?id=$id&acao=editar'><button id='bot'>Editar</button></a> <a href='ExcAtu_Autora.php?id=$id&acao=excluir'><button id='bot'>Apagar</button></a><br>
                                    </div>
                                </li>";
                    }

                ?>
            </ul>

        </div>
        
    </div>
    
    <a  id='linksair' href='./sair.php'>Sair<a>

    




</div>



    
</body>

<script> 

    var qnt = []
    for(i=0;;i++){
        if(i>1){
            break
        }

        qnt.push(0)


    }


    function aparecer(vari){
        const id = document.querySelector(".cont"+vari);
        if(qnt[vari-1] == 1){
            id.style.display = "none";
            qnt[vari-1] = 0;
        }else{
            id.style.display = "block";
            id.style.cursor = 'default'
            qnt[vari-1] = 1;
        }
    }

    function Erros(id){
            const erro = document.querySelector("#erros");
            if(id == 1){
                erro.innerHTML = "Informações erradas";
            }else if(id == 2){
                erro.innerHTML = "O usuário que está tentando logar foi bloqueado!";
            }else if(id == 3){
                erro.innerHTML = "Por Favor, validar a conta no email!";
            }

        }

    
    pagina='<?php echo $pagina; ?>'; 
    if (pagina=='busca'){
        aparecer(6)
    }else if(pagina=='emprestimo'){
        aparecer(1)
    }else if(pagina=='devolucao'){
        aparecer(9)
    }



</script>
        <?php

if(!empty(@$_GET['men'])){
    $id = $_GET['men'];
    echo "Erros(${id})";
}
        ?>



</html>