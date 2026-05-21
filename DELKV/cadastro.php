<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./cadastro_css.css" />
    <link rel='shortcut icon' href='imagens/favicon.ico' />




    <title>Login</title>
</head>
<body>

    
    <div id="novo" class="container">
        <div id="loglog" class="content login"><!--O trem  do  login fica aqui-->
            <div class="corlogin">
                <h2 class="edittilte marrom">Não Possui login?</h2>
                <p>Clique aqui para fazer o</p>
                <p>Cadastro na biblioteca Delkv</p>
                <button id="btnbtn" class="btn btnmarrom" onclick="PassarParaCadastro()">Cadastrar</button>
            </div>
            <div class="FormLogin">
                <h2 class="edittilte branco">Faça seu login</h2>

                <form action="./login.php" class="form" method="post">
                    <input name="username" type="text" placeholder="CPF, e-mail ou usuário">
                    <input  name="password" type="password" placeholder="Senha">
                    <p id="erros"></p>
                    
                    <button class="btn btnform">Logar</button>
                </form>

            </div>

        </div>
        <div id="cadcad" class="content cadastro"><!--O trem  do  CADASTRO fica aqui-->
                <div class="corlogin">
                    <h2 class="edittilte marrom">Ja tem um cadastro?</h2>
                    <p>Clique aqui para fazer o</p>
                    <p>Login na biblioteca Delkv</p>
                    <button class="btn btnmarrom" id="cadtolog" onclick="PassarParaLogin()">Login</button>
                </div>
                <div class="FormLogin">
                    <h2 class="edittilte branco ttlmts">Faça seu Cadastro</h2>
    
                    <form action="./valcadastro.php" class="form" method="post">
                        <input value='<?php echo @$_SESSION['cpf']; ?>' onkeyup="TestaCPF()" id="cpf" name='cpf' type="text" placeholder="CPF">
                        <input value='<?php echo @$_SESSION['nome']; ?>' name="nome" type="text" placeholder="Nome">
                        <input value='<?php echo @$_SESSION['usuario']; ?>' name="usuario" type="text" placeholder="Usuário">
                        <input value='<?php echo @$_SESSION['email']; ?>' name="email" type="text" placeholder="Email">
                        <input name="senha" type="password" placeholder="Senha" id='senha'>
                        <input onkeyup="verifica_senha()" id='confirmacao' type="password" placeholder="Repetir senha">
                        <p>Nascimento</p>
                        <input name="data" type="date" placeholder="Nascimento">
                        <p id="erros2"></p>
                        <div class="divsradios">
                            <div class="inpt1"><input type="radio" name="tipo" value='2'></div>
                            <div class="inpt2">Professor</div>
                            <div class="inpt1"><input type="radio" name="tipo" value='1'></div>
                            <div class="inpt2">Aluno</div>
                        </div>
                        <button  id="cadastrar" class="btn btnform btnmts">Cadastrar</button>
                    </form>
    
                </div>
        </div>
    </div>

<?php

$_SESSION['cpf'] = '';
$_SESSION['nome'] = '';
$_SESSION['usuario'] = '';
$_SESSION['email'] = '';


?>


</body>

<script>
    const body = document.querySelector("body");
    const login = document.querySelector("#loglog");
    const cadastro = document.querySelector("#cadcad");


    function PassarParaCadastro(){

        body.style = "--anim:ladoCadastro; --displayLogin:none; --displayCadastro:flex;";

    }

    function PassarParaLogin(){
        body.style = "--anim:ladoLogin; --displayLogin:flex; --displayCadastro:none;";
    }

    function ErroCPF(){
        const cpf = document.querySelector("#cpf");
        const cadastrar = document.querySelector("#cadastrar");
        

        cpf.style.border= 'solid 2px red';
        cadastrar.style.opacity = '0';
    }

    function CpfCorreto(){
        const cpf = document.querySelector("#cpf");
        const cadastrar = document.querySelector("#cadastrar");

        cpf.style.border= '0';
        cadastrar.style.opacity = '1';
    }
    

    function TestaCPF(){
        var Soma;
        var Resto;
        var ver = 1
        const strCPF = document.querySelector("#cpf").value;
        Soma = 0;
        if (strCPF == "") ver = 0;

        for (i=1; i<=9; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
            Resto = (Soma * 10) % 11;

            if ((Resto == 10) || (Resto == 11))  Resto = 0;
            if (Resto != parseInt(strCPF.substring(9, 10)) ) ver = 0;

            Soma = 0;
            for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
            Resto = (Soma * 10) % 11;

            if ((Resto == 10) || (Resto == 11))  Resto = 0;
            if (Resto != parseInt(strCPF.substring(10, 11) ) ) ver = 0;
            
            if(ver == 0){
                ErroCPF()
            }else{
                CpfCorreto();
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
        function Erros2(id){
            PassarParaCadastro()
            const erro = document.querySelector("#erros2");
            if(id == 1){
                erro.innerHTML = "CPF Cadastrado";
            }else if(id == 2){
                erro.innerHTML = "Email Cadastrado";
            }else if(id == 3){
                erro.innerHTML = "Usuário Cadastrado";
            }else if(id == 4){
                erro.innerHTML = "Algum Campo está vazio";
            }

        }
    /* aqui que vai validar a senha*/

    function verifica_senha(){

        NovaSenha = document.getElementById('senha').value;
        CNovaSenha = document.getElementById('confirmacao').value;

        if (NovaSenha != CNovaSenha) {
            const cpf = document.querySelector("#confirmacao");
            const cadastrar = document.querySelector("#cadastrar");
            

            cpf.style.border= 'solid 2px red';
            cadastrar.style.opacity = '0';
        }else{
            const cpf = document.querySelector("#confirmacao");
            const cadastrar = document.querySelector("#cadastrar");

            cpf.style.border= '0';
            cadastrar.style.opacity = '1';
        }
	
    }



<?php
if(!empty(@$_GET['men'])){
    $id = $_GET['men'];
    echo "Erros(${id})";
}
if(!empty(@$_GET['men2'])){
    $id = $_GET['men2'];
    echo "Erros2(${id})";
}


?>
    




</script>
</html>