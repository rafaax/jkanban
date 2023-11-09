<?php
require 'conexao.php';
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
            header("Location: http://127.0.0.1/jkanban/index");
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
        <form action="funcoes/usuario/logar.php" method="POST">
        <div class="form-group">
            <label>Login</label>
            <input class="form-control" type="text" name="usuario" placeholder="Digite o e-mail ou login do usuário"
            autocomplete="off" />
        </div>
        <div class="form-group">
            <label>Senha</label>
            <input class="form-control" type="password" name="senha" placeholder="Digite a senha do usuário" autocomplete="off" />
        </div>
        <button type="submit" class="btn btn-success btn-sm btn-block">Entrar</button>
        <br>
          <?php 
          if (isset($_GET['semCadastro'])) 
          {
              echo '<div id="alerta" class="alert alert-danger" role="alert">
              Usuario <b>' . $_GET['semCadastro'] . '</b>  sem cadastro!.
              </div>';
          }
          
          if (isset($_GET['dadosInvalidos'])) 
          {
              echo '<div id="alerta" class="alert alert-danger" role="alert">
              Senha <b>' . $_GET['dadosInvalidos'] . '</b>  inválida!.
              </div>';
          }

          if (isset($_GET['emailCadastrado'])) 
          {
              echo '<div id="alerta" class="alert alert-danger" role="alert">
              Email <b>' . $_GET['emailCadastrado'] . '</b>  duplicado, pedir a central para recuperá-lo!.
              </div>';
          }
          ?>
        </form>
    </div>
</div> 
<!-- <script src="http://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>  -->
<script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>

<script  src="assets/js/particles.min.js"></script>
</body>
</html>
