<?php



include "conexao.php";



function centro(){
    include "conexao.php";
    $MaisAcess = $db -> query("SELECT id_livro,imagem from livro ORDER by livro.acessos DESC LIMIT 10;");
    

?>






<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./incial.css" />
    <link rel='shortcut icon' href='imagens/favicon.ico' />
    <title>Delkv</title>
</head>
<body>
    <?php
    include "cabecalho.php";
    ?>

    <?php
    include "sloganbuscar.php";
    ?>

    <div class="container">
        <div id="carrossel">
        <?php
            while($row = $MaisAcess -> fetch_array()){
                $conteudo = $row['imagem'];
                $id=  $row['id_livro'];
                echo "<a href='livro.php?id=${id}'><img src='${conteudo}' width='150' height='250'></a>";
            }
        ?>

    </div>




</body>
<script language="javascript">
    const imgs = document.getElementById("carrossel");
    const img = document.querySelectorAll("#carrossel img");

    let idx = 0;

    console.log(img.length)
    console.log(imgs.clientWidth)


    function carrossel() {
        idx++;
        if(idx > img.length - parseInt(imgs.clientWidth/210)){
            idx=0;
        }
        imgs.style.transform = `translateX(${-idx * (150 + 60)}px)`;

    }

    setInterval(carrossel, 2000);


</script>
</html>
<?php
};





if(isset($_COOKIE['logado'])){
    $id = $_COOKIE['logado'];
    $tipagem = $db -> query("SELECT tipo,nome from usuario where id_cliente = ${id}");
    while($row = $tipagem -> fetch_array()){
        if($row['tipo'] == 3){
            $nome = $row['nome'];
            include "adm.php";
        ?>




<?php


        }else{
            centro();

        }
    }
}else{
    centro();
}
    
?>

