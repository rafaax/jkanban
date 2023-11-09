<?php 
include 'conexao.php';
include 'validacao.php';

$sql = "UPDATE usuario SET auth_token = NULL where id = $usuario ";
$query = mysqli_query($conexao, $sql);

if($query){
    $sqllog = "INSERT INTO logs(log, user) values ('Deslogou do sistema', $usuario )";
    $querylog = mysqli_query($conexao, $sqllog);
    session_destroy();
    unset($_COOKIE['auth_token']);
    setcookie('auth_token', null, -1, '/');
    header("Location: ../login");
}
