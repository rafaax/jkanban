<?php 

require 'conexao.php';
require 'validacao.php';
date_default_timezone_set('America/Sao_Paulo');


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['usuario']) && isset($_POST['msg'])){

        $mensagem = $_POST['msg'];

        if($_POST['usuario'] == 'NULO'){
            echo 'nao selecionou usuario';
            $usuario = null;
        }else{
            $usuario = $_POST['usuario'];
        }

        echo $mensagem . '<br>' . $usuario;

    }
}else{
    echo 'nao post';
}