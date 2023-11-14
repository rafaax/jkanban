<?php 

require 'conexao.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    require 'conexao.php';
    
    file_put_contents('log_inserttask.txt', $_POST['usuarios'][1]); // acessar o segundo elemento do usuário que está vindo

}