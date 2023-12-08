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


        if(isset($_POST['usuarios'])){
            $usuario = $_POST['usuarios'];

            $dataJson = array(
                0 => array(
                    'tarefa' => $titulo,
                    'ptc' => $ptc,
                    'prioridade' => $prioridade,
                    'descricao' => $descricao,
                    'data_vencimento' => $data,
                    'usuario' => $usuario
                )
            );

            $count = 1;
            while(isset($_POST["usuarios-$count"])){
                $usuario = $_POST["usuarios-$count"];
                $dataHora = $_POST["data_entrega-$count"] . ' '. $_POST["tempo_entrega-$count"];
                $titulo = $_POST["tarefa-$count"];
                $prioridade = $_POST["prioridade-$count"];

                // echo $count . ' - '. $_POST["usuarios-$count"] ;
                
                array_push($dataJson, 
                    array(
                        'tarefa' => $titulo,
                        'prioridade'  => $prioridade,
                        'ptc' => $ptc, 
                        'descricao' =>  $descricao,
                        "usuario" =>$usuario,
                        "data_vencimento" => $dataHora,   
                    )
                );
                $count++;
            }
            
            if(file_put_contents('jsons/ptc='.$ptc.'user='.$usuarioSession.'.json', json_encode($dataJson))){
                $jsonGet= file_get_contents('jsons/ptc='.$ptc.'user='.$usuarioSession.'.json');
                
                $tasks =json_decode($jsonGet, true);
                print_r($tasks);
                // print_r($tasks[1]);
                // echo $tasks[1]['tarefa'];
                unset($tasks[0]);
                print_r($tasks);
                $tasks = array_values($tasks);
                print_r($tasks);
                $tasks = json_encode($tasks);
                file_put_contents('ptc='.$ptc.'user='.$usuarioSession.'.json', json_encode($tasks));
            }

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