
<?php

include "conexao.php";
$Autores='';

$qual = @$_GET['id'];

$sql = $db -> prepare("SELECT id_editora_fk, nome_livro,sinopse, descricao,imagem from livro where id_livro=?");
$sql ->bind_param('s',$qual);
$sql -> execute();
$sql-> bind_result($editoraid,$NomeLivro,$Sinopse,$descricao,$dirIMG );

while($sql ->fetch()){}
if (empty($editoraid)){
    header('location:index.php');
}else{



$sql = $db -> prepare("SELECT nome_editora from editora where id_editora = ?");
$sql ->bind_param('s',$editoraid);
$sql -> execute();
$sql-> bind_result($NomeEditora );
while($sql ->fetch()){}


$sql = $db -> prepare("SELECT a.nome_autora from autora a, autoraref r where a.id_autora = r.id_autora_fk and r.id_livro_fk=?");
$sql ->bind_param('s',$qual);
$sql -> execute();
$sql-> bind_result($NomeAutora );
while($sql ->fetch()){
    

    if($Autores==''){
        $Autores=$NomeAutora;
    }else{
        $Autores= $Autores.", $NomeAutora";
    }

}



?>




<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./livrocss.css">
    <link rel='shortcut icon' href='imagens/favicon.ico' />
    <title>Livro</title>
</head>
<body>
    <?php
    include "cabecalho.php";
    ?>

    <div class="container">
        <div class="livro">
            <div class="img"> <img src='<?php echo $dirIMG; ?>'  width="250" height="450"> </div>
            <div class="titulo">
                <p id="nomelivro">
                    <?php
                    echo $NomeLivro;
                    ?>
                </p>
                <p>
                    <?php
                    echo $NomeEditora;
                    ?>
                </p>
                <p>
                    <?php
                    echo $Autores;
                    ?>    
                </p>
            </div>
            <div id="botao" class="botao">
            <button id="button" onclick="DataReserva()">Reservar</button>
            <p id='erro'></p>
            </div>
        </div>
        <div class="maislivro">
            <?php

                #array que vai guardar os livros 
                $livros=array();
                $cont =0;
                

                $queryAutora  = $db -> query("SELECT id_autora_fk from autoraref where id_livro_fk=$qual ");
                while($row = $queryAutora -> fetch_array()){
                    $ida = $row['id_autora_fk'];

                    $querylivro  = $db -> query("SELECT l.id_livro,l.imagem from livro l, autoraref a where a.id_livro_fk =l.id_livro and a.id_livro_fk !=$qual and a.id_autora_fk=$ida");
                    while($rowl = $querylivro -> fetch_array()){
                        $idl = $rowl['id_livro'];
                        $img = $rowl['imagem'];
                        

                        foreach ($livros as $livro){

                            $controle=1;
                            $existe=0;
                            
                            if($idl != $livro[0] ){                       
                                $existe++;
                            }
                            $cont++;
                        }
                        
                        if (!isset($controle)){
                            array_push($livros,array($idl,$img));
                
                        }elseif ($existe != 0){
                            array_push($livros,array($idl,$img));
                        }
                        
                    }
                }
                $total=count($livros);
                if($total>2){
                    $valor1=rand(0,$total-1);
                    $valor2=rand(0,$total-1);
                    while ($valor1 ==$valor2){
                        $valor2=rand(0,$total-1);
                    }
                }elseif($total==2){
                    $valor1=0;
                    $valor2=1;
                }elseif($total==1){
                    $valor1=0;
                    $valor2=1;
                }

                if($total !=0){
                    echo "<a href='livro.php?id=".$livros[$valor1][0]."'><div>
                    <img src='".$livros[$valor1][2]."' width='125'>
                    </div></a>
                    
                    <a href='livro.php?id=".$livros[$valor2][0]."'><div>
                    <img src='".$livros[$valor2][2]."' width='125'>
                    </div></a>
                    ";
                }
                
             ?>

        </div>
    </div>

    <div class="abas sinopse">
        <p>Sinopse</p>
        <div>
           <?php
            echo $Sinopse;
           ?>
        </div>

    </div>
    <div class="abas desc">
        <p>Descrição</p>
        <ul>
            <?php
            $i = 0;
            $lista = explode(";",$descricao);


            while($i<count($lista)-1){
                $item = explode(":",$lista[$i]);
                $pt1 = $item[0];
                $pt2 = $item[1];
                echo "<li>&emsp;&emsp;${pt1}: ${pt2}</li>";
                $i++;
            };

            ?>  
        </ul>
    </div>


</body>

<script>

function DataReserva(){
    <?php

    echo"const id = ${qual};";

    ?>

    const botao = document.querySelector("#botao");
    const aaaa = document.querySelector(".botao").style = "padding-top:130px;"
    botao.innerHTML = "<form class='form2' action='./reserva.php' method='POST'><input name='id' type='text' value='"+id+"' style='display:none'><input name='data' type='date'><input type='submit' id='button'></form><br>";
}

function Erros(id){
const erro = document.querySelector("#erro");
if(id == 1){
    erro.style = "color: green;";
    erro.innerHTML = "Reserva Feita";
}else if(id == 2){
    erro.innerHTML = "Sem exemplares disponíveis";
}else if(id == 3){
    erro.innerHTML = "Revise a Data";
}else if(id == 4){
    erro.innerHTML = "Faça o Login/Cadastro no site";
}};



<?php
if(!empty(@$_GET['men'])){
    $id = $_GET['men'];
    echo "Erros(${id})";
}
?>


</script>




</html>

<?php
}
?>