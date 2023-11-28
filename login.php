<?php
require 'src/conexao.php';
if (isset($_COOKIE['auth_token'])) {

    $auth_token = $_COOKIE['auth_token'];
    $sql = "SELECT id from usuarios where auth_token = '$auth_token'";
    $query = mysqli_query($conexao, $sql);
    $numrows = mysqli_num_rows($query);  
    if($numrows >= 1){
        $array = mysqli_fetch_array($query);
        $id = $array['id'];
        if($auth_token === $_COOKIE['auth_token']){
            session_start();
            $_SESSION['usuario'] = $array['id'];
            header("Location: http://192.168.0.166/jkanban/index?id=" . $_SESSION['usuario']);
            die();
        }
    }
}
?>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<link rel="stylesheet" href="assets/css/bootstrap.css">
<link rel="stylesheet" href="assets/css/style.css">
<title>Login</title>
</head>
<body>
<div id="particles-js">
    <div class="container tamanho-largura">
        <form id="login" method="POST">
        <div class="form-group">
            <label>Login</label>
            <input class="form-control" type="text" name="user" placeholder="Digite o login do usuário"
            autocomplete="off" />
        </div>
        <div class="form-group">
            <label>Senha</label>
            <input class="form-control" type="password" name="pass" placeholder="Digite a senha do usuário" autocomplete="off" />
        </div>
        <button type="submit" class="btn btn-success btn-sm btn-block">Entrar</button>
        <br>
        </form>
    </div>
</div> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
<script  src="assets/js/particles.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
<script>

     $("#login").on("submit", function (event) {
        
        event.preventDefault();

        $.ajax({
            method: "POST",
            url: "src/logar.php",
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function (json) {
                var resposta = JSON.parse(json);
                console.log(resposta.erro);
                if(resposta.erro == true) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Usuário ou senha incorretos!'
                    })
                }else if(resposta.erro == 'empty'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Preencha o usuário e senha!'
                    })
                }
                else if(resposta.erro == false) {
                    window.location.href = "http://192.168.0.166/jkanban/"
                }
            }
        })
    });
</script>
</html>
