<?php
include "conexao.php";

$id=@$_REQUEST['id'];
$acao=@$_GET['acao'];
$con=@$_GET['con'];
$img=@$_REQUEST['img'];
$autoras=array();
$exem='';
?>

<link rel="stylesheet" href="./Exec_atuLivro.css">

<?php
if($con=='sim'){
    if($img!='./imagens/s_img.png'){
     @unlink($img);
    }


    $sql= $db -> prepare("DELETE from exemplar where id_livro_fk=?");
    $sql-> bind_param('s',$id);
    $sql-> execute();

    $sql= $db -> prepare("DELETE from autoraref where id_livro_fk=?");
    $sql-> bind_param('s',$id);
    $sql-> execute();

    $sql= $db -> prepare("DELETE from livro where id_livro=?");
    $sql-> bind_param('s',$id);
    $sql-> execute();
    
    header('location:index.php?resu=4');
}elseif($acao=='atualizar'){

    $sql = $db -> prepare("SELECT id_editora_fk,nome_livro,sinopse,genero_fk,descricao,imagem FROM livro WHERE id_livro=? ");
    $sql -> bind_param('s',$id);
    $sql -> execute();
    $sql-> bind_result($ide,$nome,$sinopse,$genero,$descricao,$img);
    while($sql ->fetch()){}

    $sql = $db -> prepare("SELECT count(id_exemplar) FROM exemplar WHERE id_livro_fk=? ");
    $sql -> bind_param('s',$id);
    $sql -> execute();
    $sql-> bind_result($exemplar);
    while($sql ->fetch()){}


    $sql = $db -> prepare("SELECT id_autora_fk FROM autoraref WHERE id_livro_fk=? ");
    $sql -> bind_param('s',$id);
    $sql -> execute();
    $sql-> bind_result($ida);  
    while($sql ->fetch()){
        array_push($autoras, $ida);
    }

    $query = $db -> query("SELECT id_exemplar from exemplar where id_livro_fk=$id");
    while($row = $query -> fetch_array()){
        $ide=$row['id_exemplar'];
        
        $ide2='';
        $reserva = $db -> query("SELECT id_exemplar_fk from reserva where id_exemplar_fk=$ide");
        while($row = $reserva -> fetch_array()){
            $ide2=$row['id_exemplar_fk'];
        }

        $emprestimo = $db -> query("SELECT id_exemplar_fk from emprestimo where id_exemplar_fk=$ide");
        while($row = $emprestimo -> fetch_array()){
            $ide2=$row['id_exemplar_fk'];
        }
        if(empty($ide2)){
            
            if(empty($exem)){
                $exem= "$ide";
                
            }else{
                
                $exem= "$exem , $ide";
            }
        }
    }

    # submete os novos dados
    if(isset($_POST['nomeLivro'])){
        $editora = @$_POST['editora'];
        $nomeLivro = @$_POST['nomeLivro'];
        $sinop = @$_POST['sinop'];
        $genero = @$_POST['genero'];
        $desc = @$_POST['desc'];
        $exmp = @$_POST['qnt'];
        $exc = @$_POST['excluir'];
        $exa = @$_POST['adicionar'];

        $ext=pathinfo($_FILES['arquivo']['name'],PATHINFO_EXTENSION);
        $novoNome="./imagens/".uniqid().".$ext";

        if(move_uploaded_file($_FILES['arquivo']['tmp_name'], $novoNome)){
            if($img!='./imagens/s_img.png'){
                @unlink($img);
            }
            $image=$novoNome;
        }else{
            $image=$img;
        }
        
        if (!empty($exc)){

            $i = 0;
            $lista = explode(";",$exc); 
            while($i<count($lista)){
                $va=$lista[$i];
                $i++;

                $sql= $db -> prepare("DELETE from exemplar where id_exemplar=?");
                $sql-> bind_param('s',$va);
                $sql-> execute();
            };

        }elseif(!empty($exa)){

            $texto = 'INSERT INTO exemplar(id_livro_fk) VALUES';

            $cont = 1;
            while($cont <= intval($exmp)){
                $cont++;
                $texto=$texto." ($id),";
            };
            
            $texto = substr($texto, 0, -1);
            $sql = $db -> prepare("$texto");
            $sql -> execute();
        }

        $sql = $db -> prepare("SELECT id_autora from autora");
        $sql -> execute();
        $sql-> bind_result($idau );

        $comando = 'INSERT INTO autoraref VALUES';
        while($sql ->fetch()){
            if(isset($_POST["$idau"])){
                $chave= array_search($idau, $autoras);
                if($chave !== false){
                    unset($autoras[$chave]);
                    
                }else{
                    $controladora=1;
                    $comando=$comando." ($idau,$id),";
                }
            }
        }
        foreach ($autoras as $id_au) {
            $sql = $db -> prepare("DELETE from autoraref where id_autora_fk=? and id_livro_fk=?");
            $sql -> bind_param('ss',$id_au,$id);
            $sql -> execute();
        }
        $comando = substr($comando, 0, -1);
        if(isset($controladora)){
            $sql = $db -> prepare("$comando");
            $sql -> execute();
        }

        $sql = $db -> prepare("UPDATE livro set id_editora_fk=?,nome_livro=?,sinopse=?,genero_fk=?,descricao=?,imagem=? where id_livro=?");
        $sql -> bind_param('sssssss',$editora,$nomeLivro,$sinop,$genero,$desc,$image,$id);
        $sql -> execute();
        header('location:index.php?resu=5');
    }else{
    ?>


    <div class="img"> <img src='<?php echo $img; ?>'  width="250" height="450"> </div>
    <div class='itens'>
    <form id='cadastrar' name='cadastrar' action='' method='POST' enctype="multipart/form-data">

        <select id='inps' name='editora' value=<?php echo $ide; ?>>
            <option>Editora</option>
            <?php

                $queryEditora  = $db -> query("SELECT * from editora");
                while($row = $queryEditora -> fetch_array()){
                    $nome_edi = $row['nome_editora'];
                    $ided = $row['id_editora'];

                    if ($ide == $ided)
						{
						$selecionado = "selected";
						}  else  {
						$selecionado ="";
						}

                    echo "<option value='$ided' $selecionado>${nome_edi}</option>";

                }


            ?>
        </select>
        
            <div  id='item'>
            <h3 onclick="aparecer(5)" class='titulo-unico'>Autoras</h3>
            <div id="conteudo" class="cont5"  style='display:none;'>
            
            <?php

                $queryAutora  = $db -> query("SELECT * from autora");
                while($row = $queryAutora -> fetch_array()){
                    $nome_au = $row['nome_autora'];
                    $ida = $row['id_autora'];

                    if(array_search($ida, $autoras) !== false){
                        $selecionado= 'checked';
                    }else{
                        $selecionado ="";
                    }

                    echo "<div id=autores><label><input type=checkbox value='$ida' name='$ida' $selecionado>$nome_au</label></div>";

                }


            ?>
            </div>
        
        </div>

        <input id='inps' name='nomeLivro' type='text' placeholder='Nome Livro' value= '<?php echo $nome ?>'><br>

        <textarea name='sinop' placeholder='Sinopse do livro'><?php echo $sinopse ?></textarea><br>

        <select id='inps' name='genero'>
            <option>Selecionar Genero</option>
            <?php

                $query  = $db -> query("SELECT * from Genero");
                while($row = $query -> fetch_array()){
                    $nome = $row['genero'];
                    $idg = $row['id_genero'];

                    if ($idg == $genero)
						{
						$selecionado = "selected";
						}  else  {
						$selecionado ="";
						}

                    echo "<option value='${idg}' $selecionado>${nome}</option>";

                }


            ?>
        </select><br>
        <textarea name='desc' placeholder='Descrição'><?php echo $descricao ?></textarea><br>
        <input id='exemplar' class='exec' name='qnt' type='number' placeholder='Quantidade de Exemplares' value ='<?php echo $exemplar ?>' ><br>
        <input id='inps' class='arquivo' name='arquivo' type='file' accept="image/*" placeholder='Imagem'><br>
        <button id='botao' type="button" onClick="veri_exemplar()">Enviar</button>
        </div>
        <input type=hidden value='<?php echo $img ?>' name= img>
        <div id=adicionar></div>
        </form>
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

            function veri_exemplar(){
                ex1=<?php echo $exemplar ?>;
                dispo= '<?php echo $exem ?>';
                ex2=document.getElementById('exemplar').value;
                const title = document.getElementById('adicionar');
                
                if(ex1 <ex2){
                    valor = ex2-ex1;
                    title.innerHTML = "<input type=text value='"+valor+"' name='adicionar'>";
                    document.cadastrar.submit();
                }else if(ex1 > ex2){
                    if (dispo== ''){
                        alert('Não é posivel excluir nenhum exemplar desse livro');
                        document.cadastrar.submit();
                    }else{
                        valor = ex1-ex2;
                        var num=prompt("Digite os cod do exemplar a se excluido separando eles por ; ex:(cod1;cod2;cod3;...)\nOs unicos exempalres que podem ser excluid são: "+dispo);                       
                        if(num!= null){
                            title.innerHTML = "<input type=text value='"+num+"' name= 'excluir'>";
                            document.cadastrar.submit();

                        }
                    }
                }else{
                
                document.cadastrar.submit();
                }
            }
        </script>
        <?php 
    }

}elseif($acao=='deletar'){

    $query = $db -> query("SELECT e.id_exemplar, l.imagem from exemplar e, livro l where e.id_livro_fk =l.id_livro and e.id_livro_fk=$id");
    while($row = $query -> fetch_array()){
        $ide=$row['id_exemplar'];
        $img=$row['imagem'];
        

        $reserva = $db -> query("SELECT id_exemplar_fk from reserva where id_exemplar_fk=$ide");
        while($row = $reserva -> fetch_array()){
            $ide2=$row['id_exemplar_fk'];
            break;
        }

        $emprestimo = $db -> query("SELECT id_exemplar_fk from emprestimo where id_exemplar_fk=$ide");
        while($row = $emprestimo -> fetch_array()){
            $ide2=$row['id_exemplar_fk'];
            break;
        }
    }

    if(empty($ide2)){
        echo "<a href=ExcAtu_livro.php?con=sim&id=$id&img=$img><button id='bot'>deletar</button></a>";
        echo "<a href=listarLivro.php><button id='bot'>Não deletar</button></a>";
    }else{
        header('location:index.php?resu=10');
    }

}else{
    header('location:index.php');
}

?>