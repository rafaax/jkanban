<?php 

require 'conexao.php';
require 'validacao.php';

function retornaErro($msg){
    echo json_encode(array(
        'erro'=> true,
        'msg' => $msg
    ));
    exit();
}

function curlEmail($task_id, $user, $msg){
    
    require 'conexao.php';

    $array = array(
        'tarefa_id' => $task_id,
        'user' => $user,
        'msg' => $msg
    );

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://127.0.0.1/jkanban/src/email_recusado.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($array),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    $ch = curl_exec($curl);
    echo $ch;
    curl_close($curl);
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['usuario']) && isset($_POST['msg']) && isset($_POST['task_id'])){

        $mensagem = $_POST['msg'];
        $tarefa_id = $_POST['task_id'];
        
        if($_POST['usuario'] == 'NULO'){
            $usuario = null;
        }else{
            $usuario = $_POST['usuario'];
        }
        
    
        $sql = "SELECT * from tarefas_todo where tarefa_id =  $tarefa_id";
        $query = mysqli_query($conexao, $sql);
        if(mysqli_num_rows($query) > 0){
            $sql = "SELECT * from logs where task_id = $tarefa_id";
            $query = mysqli_query($conexao, $sql);

            if(mysqli_num_rows($query) > 1){
                retornaErro('Você não pode contestar uma tarefa que você movimentou');
            }else{
                $sql = "INSERT INTO contestacoes(user, msg, task_id, redirecionado) values ('$usuarioSession', '$mensagem','$tarefa_id','$usuario')";
                $query = mysqli_query($conexao, $sql);
                if($query){
                    if($usuario != null){
                        $sql2 = "UPDATE tarefas_criadas set usuario_tarefa = $usuario";
                        $query2 = mysqli_query($conexao, $sql2);
                    }else{
                        $sql2 = "DELETE FROM tarefas_todo where tarefa_id = $tarefa_id";
                        $query2 = mysqli_query($conexao, $sql2);
                    }
                    
                    if($query2){
                        echo json_encode(array(
                            'erro' => false,
                            'msg' => 'Sua contestação foi bem sucedida!'
                        ));

                        curlEmail($tarefa_id, $usuarioSession, $mensagem);
                    }else{
                        retornaErro("Ocorreu algum erro..");    
                    }
                }else{
                    retornaErro("Ocorreu algum erro..");
                }

                
            }
        }else{
            retornaErro('Você não pode contestar uma tarefa que não está nas pendências');
        }

    }
}else{
    retornaErro('');
}