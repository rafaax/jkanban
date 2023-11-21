<?php 
require 'conexao.php';
require 'validacao.php';
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if($permissoesSession == 1){
        $client_data = file_get_contents("php://input");
        $json = json_decode($client_data);
        $id = $json->id;
        $sql = "SELECT criado_por from tarefas_criadas where tarefa_id = '$id'";
        $query = mysqli_query($conexao, $sql);
        $array = mysqli_fetch_array($query);
        
        if($array['criado_por'] == $usuarioSession){
            
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
            exit();
        }

    }else{
        exit();
    }
}


