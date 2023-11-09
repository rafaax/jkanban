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

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    require 'conexao.php';
    $client_data = file_get_contents("php://input");
    $json = json_decode($client_data);
    file_put_contents('logPost.txt', file_get_contents("php://input"));

    if(validaRegistro($json->task_id, $json->source)){
        
        $sql = "INSERT INTO $json->target(tarefa_id) values ('$json->task_id')";
        // echo $sql;
        if(mysqli_query($conexao, $sql)){
            $sql = "DELETE from $json->source where tarefa_id = '$json->task_id'";
            $query = mysqli_query($conexao, $sql);
            return $query ? retorna(false, 'sem erro') : retorna(true, 'Ocorreu algum erro...');

        }else{
            retorna(true,'Ocorreu algum erro...');
        }
    }else{
        retorna(true, 'Ocorreu um erro ao encontrar o registro');
    }



}