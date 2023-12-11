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


            $dataJson = array( // define o array com os dados iniciais = tarefa inicial 
                0 => array(
                    'tarefa' => $titulo,
                    'ptc' => $ptc,
                    'prioridade' => $prioridade,
                    'descricao' => $descricao,
                    'data_vencimento' => $data,
                    'usuario' => $usuario
                )
            );


            $count = 1; // define o count para entrar no while e percorrer o _POST
            while(isset($_POST["usuarios-$count"])){ // o while vai até aonde exista o _POST['usuarios-x]
                // define as variaveis para evitar usar concatenacao e não confundir no array push, obs: caso tenha muitos dados pode pesar 
                $usuario = $_POST["usuarios-$count"];
                $dataHora = $_POST["data_entrega-$count"] . ' '. $_POST["tempo_entrega-$count"];
                $titulo = $_POST["tarefa-$count"];
                $prioridade = $_POST["prioridade-$count"];

                // echo $count . ' - '. $_POST["usuarios-$count"] ;
                
                // array manipulator para inserir o array novo no array dataJson para tratar posteriormente
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
            
            $timestamp = getdate();
            $unix =  $timestamp[0];
            $path = "json";
            $file = "$path/user=$usuarioSession&time=$unix";

            if(file_put_contents("$file.json", json_encode($dataJson))){ // escreve o arquivo
                $jsonGet= file_get_contents("$file.json");
                $tasks =json_decode($jsonGet, true);
                
                // print_r($tasks);

                $task_titulo = $tasks[0]['tarefa'];
                $task_prioridade = $tasks[0]['prioridade'];
                $task_descricao = $tasks[0]['descricao'];
                $task_ptc = $tasks[0]['ptc'];
                $task_usuario = $tasks[0]['usuario'];
                $task_data_vencimento = $tasks[0]['data_vencimento'];

                $sql = "INSERT INTO tarefas_criadas(titulo, prioridade, ptc_num, descricao_tarefa, criado_por, usuario_tarefa, data_final, json_ref) 
                    values ('$task_titulo', '$task_prioridade', '$task_ptc', '$task_descricao' , '$usuarioSession', '$task_usuario', '$task_data_vencimento', '$file.json')";
                // echo $sql;
                $query = mysqli_query($conexao, $sql);
                if($query){
                    $last_inserted_id = mysqli_insert_id($conexao);
                    $sql = "INSERT INTO tarefas_todo(tarefa_id) values ('$last_inserted_id')";
                    $query = mysqli_query($conexao, $sql);
                }
                
                // echo $query;
                // print_r($tasks[1]);
                // echo $tasks[1]['tarefa'];
                unset($tasks[0]);
                // print_r($tasks);
                $tasks = array_values($tasks);
                print_r($tasks);
                $tasks = json_encode($tasks);
                file_put_contents("$file.json", $tasks);
            }else{
                echo json_encode(array(
                    'erro' => true,
                    'msg' => 'Ocorreu algum erro...'
                ));
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