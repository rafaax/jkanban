<?php 
include 'conexao.php';
include 'validacao.php';

$sql = "UPDATE usuarios SET auth_token = NULL where id = $usuarioSession";
$query = mysqli_query($conexao, $sql);

if($query){
    session_destroy();
    unset($_COOKIE['auth_token2']);
    setcookie('auth_token2', null, -1, '/');
    header("Location: ../login");
}