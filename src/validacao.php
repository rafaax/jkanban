<?php

session_start();

include_once 'conexao.php';
if ($_SESSION["usuario"] == "" || $_SESSION["usuario"] == null) {
    header("Location: http://192.168.0.166/jkanban/login.php");
}

$usuarioLogado = $_SESSION["usuario"];

$sql = "SELECT * FROM usuarios WHERE id = $usuarioLogado";
$retorno = mysqli_query($conexao, $sql);
$array = mysqli_fetch_array($retorno);

$emailSession = $array['email'];
$permissoesSession = $array['permissoes'];
$nomeSession = $array['login'];
$usuarioSession = $usuarioLogado;

?>