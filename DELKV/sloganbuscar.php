<link rel="stylesheet" href="./incial.css" />
<div id="sloganbuscar">
        <form action='busca.php' method='POST'>
        <div id="busca">
        <input id="texto" type="text" name="busca" placeholder="Pesquise Aqui"><input id="botao" type="submit" value="Buscar">
        </div>
        </form>

        <div id="filtros">

            <ul class="ullista">
                <li class="item">Gênero
                    <ul>
                    <?php
                        $genQ = $db -> query("SELECT * from genero");
                        while($row = $genQ -> fetch_array()){
                            $conteudo = $row['genero'];
                            $ids = $row['id_genero'];
                            echo "<li class='partes'> <a href='./busca.php?q=$ids&tp=g'> $conteudo </a> </li>";
                        }
                        ?>
                    </ul>
                </li>
                <li class="item">Editora
                    <ul>
                    <?php
                        $editQ = $db -> query("SELECT * from editora");
                        while($row = $editQ -> fetch_array()){
                            $conteudo = $row['nome_editora'];
                            $ids = $row['id_editora'];
                            echo "<li class='partes'> <a href='./busca.php?q=$ids&tp=e'> $conteudo </a> </li>";
                        }
                        ?>
                    </ul>
                </li>
                <li class="item">Autoria
                    <ul>
                    <?php
                        $CoAutora = $db-> prepare("SELECT nome_autora, id_autora from autora");
                        $CoAutora -> execute();
                        $CoAutora-> bind_result($nomea, $ids);
                        
                        while($CoAutora ->fetch()){
                        
                            echo "<li class='partes'> <a href='./busca.php?q=$ids&tp=a'> $nomea </a> </li>";
                        }
                        ?>
                    </ul>
                </li>
            </ul>

        </div>


</div>