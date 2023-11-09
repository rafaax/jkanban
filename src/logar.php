<?php
session_start();
include_once 'conexao.php';

$emailUsuario = trim($_POST['usuario']);
$senhaDigitada = trim($_POST['senha']);

$sql = "SELECT id,email, senha FROM usuarios WHERE email = '$emailUsuario' OR login = '$emailUsuario'";

$retornoEmailUsuario = mysqli_query($conexao,$sql);
$totalRetornado = mysqli_num_rows($retornoEmailUsuario);

if($totalRetornado == 0){
    header("Location: ../login?semCadastro=".$emailUsuario); 
}
if($totalRetornado >= 2){
    header("Location: ../login?emailCadastrado=".$emailUsuario); 
}
if($totalRetornado == 1){
    while($array = mysqli_fetch_array($retornoEmailUsuario,MYSQLI_ASSOC)){
        $senhaCadastrada = $array['senha'];
        if($senhaDigitada == $senhaCadastrada){
            $idDb = $array['id'];
            $_SESSION['usuario'] = $array["id"];
            $token = md5(uniqid(rand(), true));
            setcookie('auth_token', $token, time() + (30 * 24 * 60 * 60), '/'); // Cookie válido por 30 dias
            $sql = "UPDATE usuarios SET auth_token = '$token' where id = $idDb";
            $query = mysqli_query($conexao, $sql);

            header("Location: ../index.php?id=" . $array['id']); 
        } else{
            header("Location: ../login?dadosInvalidos="); 
        }
    }
}

?>