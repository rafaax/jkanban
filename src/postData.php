<?php 

function retorna($erro, $msg){
    echo json_encode(array(
        'erro' => $erro,
        'msg' => $msg
    ));
}

function validaRegistro($task, $source){
    require 'conexao.php';
    $tabela = $source;
    
    $sql = "SELECT * from tarefas_criadas where tarefa_id = '$task' ";
    if(mysqli_num_rows(mysqli_query($conexao, $sql)) == 1){

        $sql = "SELECT * from $tabela where tarefa_id = $task";
        $query = mysqli_query($conexao, $sql);

        return $query ? true : false;
    
    }else{
        return false;
    }
}


function validaJsonRef($task){
    require 'conexao.php';
    
    $sql = "SELECT json_ref from tarefas_criadas where tarefa_id = '$task'";
    $query = mysqli_query($conexao, $sql);
    $array = mysqli_fetch_array($query);

    if($array['json_ref'] == null){
        return true;
    }else{
        return false;
    }
}


function buscaCriador($task_id){
    require 'conexao.php';
    $sql = "SELECT * from tarefas_criadas where tarefa_id = '$task_id'";
    $query = mysqli_query($conexao, $sql);
    $array = mysqli_fetch_array($query);
    return $array['criado_por'];
}


function curlEmail($task,$created_by, $user ){
    $arrayPost = array(
        'tarefa' => $task,
        'criador' => $created_by,
        'usuario' => $user
    );

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://127.0.0.1/jkanban/src/email_finalizado.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($arrayPost),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    $ch = curl_exec($curl);
    // echo $ch;
    curl_close($curl);

}


function logDragging($user, $task, $target, $source ){
    require 'conexao.php';

    $sql = "INSERT INTO logs(usuario_id, task_id, target, source) values('$user', '$task','$target','$source')";
    mysqli_query($conexao, $sql);
    
}


function curlEmailCadastro($task_id){
    require 'conexao.php';

    $sql = "SELECT * from tarefas_criadas where tarefa_id = $task_id";
    $query = mysqli_query($conexao, $sql);
    
    $array = mysqli_fetch_array($query);

    $arrayPost = array(
        'tarefa_id' => $task_id,
        'tarefa' => $array['titulo'],
        'ptc' => $array['ptc_num'],
        'descricao' => $array['descricao_tarefa'],
        'prioridade' => $array['prioridade'],
        'criador' => $array['criado_por'],
        'usuario' => $array['usuario_tarefa'],
        'data_criada' => $array['data_criada'],
        'data_final' => $array['data_final']
    );

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://127.0.0.1/jkanban/src/email_cadastro.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($arrayPost),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    $ch = curl_exec($curl);
    // echo $ch;
    curl_close($curl);
}


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    require 'conexao.php';
    require 'validacao.php';
    $client_data = file_get_contents("php://input");
    $json = json_decode($client_data);
    // file_put_contents('logPost.txt', file_get_contents("php://input"));

    if(validaRegistro($json->task_id, $json->source)){
        
        $sql = "INSERT INTO $json->target(tarefa_id) values ('$json->task_id')";
        if(mysqli_query($conexao, $sql)){
            $sql = "DELETE from $json->source where tarefa_id = '$json->task_id'";
            $query = mysqli_query($conexao, $sql);
            if($query){
                if($json->target == 'tarefas_done'){ // executar se o usuario enviar a tarefa para as tarefas finalizadas
                    if(validaJsonRef($json->task_id) != true){

                        $sql = "SELECT json_ref from tarefas_criadas where tarefa_id = $json->task_id"; // query pra pegar o caminho do json
                        $query = mysqli_query($conexao, $sql);
                        $array = mysqli_fetch_array($query); 

                        $jsonRef = $array['json_ref']; // caminho do json
                        $fileGet =  file_get_contents($jsonRef); // ler o arquivo json
                        
                        $next_task = json_decode($fileGet, true); // decodificar o json para transformar em array
                        // print_r($next_task); 
                        if(!empty($next_task)){

                            // atribuir os dados do array em variáveis
                            $task_titulo =  $next_task[0]['tarefa']; 
                            $task_ptc =  $next_task[0]['ptc'];
                            $task_descricao =  $next_task[0]['descricao'];
                            $task_data_vencimento =  $next_task[0]['data_vencimento'];
                            $task_usuario =  $next_task[0]['usuario'];
                            $task_prioridade = $next_task[0]['prioridade'];
                            //

                            $sql = "INSERT INTO tarefas_criadas(titulo, prioridade, ptc_num, descricao_tarefa, criado_por, usuario_tarefa, data_final, json_ref) 
                                values ('$task_titulo', '$task_prioridade', '$task_ptc', '$task_descricao' , '$usuarioSession', '$task_usuario', '$task_data_vencimento', '$jsonRef')";
                            // echo $sql;
                            $query = mysqli_query($conexao, $sql);
                            if($query){
                                $last_inserted_id = mysqli_insert_id($conexao);
                                $sql = "INSERT INTO tarefas_todo(tarefa_id) values ('$last_inserted_id')";
                                $query = mysqli_query($conexao, $sql);
                                curlEmailCadastro($last_inserted_id);
                            }


                            unset($next_task[0]); // remover o objeto de indice 0 -> primeiro, do array
                            // print_r($next_task); 

                            if(empty($next_task)){ // validar se o array é vazio 
                                $sql = "UPDATE tarefas_criadas set json_ref = null where tarefa_id = '$json->task_id'"; // COLOCAR NULO
                                $query = mysqli_query($conexao, $sql); // RODA A QUERY

                                file_put_contents($jsonRef, json_encode($next_task));
                                unlink($jsonRef);
                            }else{ // caso nao for, reindexar o array
                                $tasks = array_values($next_task);
                                // // print_r($tasks); 
                                file_put_contents($jsonRef, json_encode($tasks));
                            }

                        }

                    }else{
                        $created = buscaCriador($json->task_id);
                        curlEmail($json->task_id, $created, $usuarioSession);
                    }
                    
                }
                logDragging($usuarioSession, $json->task_id, $json->target, $json->source);
                retorna(false, 'sem erro');
                die();
            }else{
                retorna(true, 'Ocorreu algum erro...');
                die();
            }
        }else{
            retorna(true,'Ocorreu algum erro...');
            die();
        }

    }else{
        retorna(true, 'Ocorreu um erro ao encontrar o registro');
        die();
    }
}