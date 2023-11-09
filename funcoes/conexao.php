<?php 

$senha = '';
$usuario = 'root';  
$db = 'engeline_kanban';
$server = 'localhost';

$conexao = mysqli_connect($server, $usuario, $senha, $db);
$mysqli = new mysqli($server,$usuario,$senha, $db);
?>