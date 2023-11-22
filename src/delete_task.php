<?php 
require 'conexao.php';
require 'validacao.php';


function validaProcess($task_id){
    require 'conexao.php';

    $sql = "SELECT * from tarefas_process where tarefa_id = '$task_id'";
    $query = mysqli_query($conexao, $sql);
    if(mysqli_num_rows($query) > 0){
        echo json_encode(array(
            'erro' => true,
            'msg' => 'Você não pode deletar a tarefa em desenvolvimento!'
        ));
        exit();
    }
}

function validaFeito($task_id){
    require 'conexao.php';

    $sql = "SELECT * from tarefas_done where tarefa_id = '$task_id'";
    $query = mysqli_query($conexao, $sql);
    if(mysqli_num_rows($query) > 0){
        echo json_encode(array(
            'erro' => true,
            'msg' => 'Você não pode deletar a tarefa finalizada!'
        ));
        exit();
    }
}
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if($permissoesSession == 1){
        $client_data = file_get_contents("php://input");
        $json = json_decode($client_data);
        $id = $json->id;
        $sql = "SELECT criado_por from tarefas_criadas where tarefa_id = '$id'";
        $query = mysqli_query($conexao, $sql);
        $array = mysqli_fetch_array($query);
        
        if($array['criado_por'] == $usuarioSession){
            
            validaProcess($id);
            validaFeito($id);
            
            $sql = "DELETE FROM tarefas_criadas where tarefa_id = '$id'";
            $query = mysqli_query($conexao, $sql);

            if($query){
                echo json_encode(array(
                    'erro' => false,
                    'msg' => 'Tarefa deletada com sucesso!'
                ));
            }else{
                echo json_encode(array(
                    'erro' => true,
                    'msg' => 'Erro ao deletar a tarefa!'
                ));
            }
            
        }else{
            echo json_encode(array(
                'erro' => true,
                'msg' => 'Não foi você que criou a tarefa.'
            ));
            exit();
        }

    }else{
        exit();
    }
}