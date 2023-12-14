<?php 

require 'conexao.php';
require 'validacao.php';
date_default_timezone_set('America/Sao_Paulo');


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    echo 'post';
}else{
    echo 'nao post';
}