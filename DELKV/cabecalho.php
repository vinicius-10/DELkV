<?php




?>


<div id="cabecalho">
        <div id="titulo">
            <a href='index.php'>
                <div>Delkv</div>
                <div><img src="./imagens/icon.png" alt="IconeBiblioteca" width="48px" height="48px"></div>
            </a>
        </div>
        <div id="menu">
            <ul>
                <?php

                if(!isset($_COOKIE['logado'])){
                    
                    ?>
                    <li><a href='./cadastro.php'>Login/Cadastro</a></li>
                    
                <?php
                }
                else{
                    $id = $_COOKIE['logado'];
                    $nomeusu = $db -> query("SELECT nome from usuario where id_cliente = ${id}");
                    while($row = $nomeusu -> fetch_array()){
                        $nome2 = $row['nome'];
                    };
                    ?>
                    <li><a href='./sair.php'>Sair</a></li>
                    <li>Olá, <?php echo explode(" ",$nome2)[0]; ?></li>
                    
                    <?php
                }

                ?>
            </ul>
        </div>
    </div>