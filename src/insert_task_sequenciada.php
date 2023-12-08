<?php 

require 'conexao.php';
require 'validacao.php';
date_default_timezone_set('America/Sao_Paulo');

function logDragging($user, $task, $target, $source ){
    require 'conexao.php';

    $sql = "INSERT INTO logs(usuario_id, task_id, target, source) values('$user', '$task','$target','$source')";
    mysqli_query($conexao, $sql);
    
}

function validaData($data_post){
    $dataAtual = date('Y-m-d H:i:s');
    if($dataAtual > $data_post){
        echo json_encode(array(
            'erro' => true,
            'msg' => 'Data da tarefa não pode ser anterior a data atual!'
        ));
        exit();
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if(isset($_POST['tarefa']) && isset($_POST['ptc']) && isset($_POST['prioridade']) && isset($_POST['descricao'])){
        $titulo = $_POST['tarefa'];    
        $ptc = $_POST['ptc']; 
        $prioridade = $_POST['prioridade']; 
        $descricao = $_POST['descricao'];
        $data_vencimento = $_POST['data_entrega'];
        $tempo_vencimento = $_POST['tempo_entrega'];
        
        $data = "$data_vencimento $tempo_vencimento";
        
        validaData($data);

        $dataJson = array();
        
        
        if(isset($_POST['usuarios'])){
            $usuarios = $_POST['usuarios'];
            echo '1 - ' .$usuarios . PHP_EOL;
            $count = 2;
            $dataHora  = $data_vencimento . ' ' . $tempo_vencimento; 
            while(isset($_POST["usuarios-$count"])){
                $dataHora = $_POST["data_entrega-$count"] . ' '. $_POST["tempo_entrega-$count"];
                echo $count . ' - '. $_POST["usuarios-$count"] ;
                echo(' - ' .  $dataHora . PHP_EOL);
                $count++;
            }
            echo 'não existem mais dados: parou no count' . $count;
            
        }else{
            echo json_encode(array(
                'erro' => true,
                'msg' => 'Você deve selecionar ao menos um usuário.'
            ));
        }
    }else{
        exit();
    }

}