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
        file_put_contents('log.json', $_POST['tarefa1']);
        /*
        $ptc = $_POST['ptc']; 
        $prioridade = $_POST['prioridade']; 
        $descricao = $_POST['descricao'];
        $data_vencimento = $_POST['data_entrega'];
        $tempo_vencimento = $_POST['tempo_entrega'];
        
        $data = "$data_vencimento $tempo_vencimento";
        
        validaData($data);

        
        if(isset($_POST['usuarios'])){
            $usuarios = $_POST['usuarios'];
            if(is_array($usuarios)){

                foreach($usuarios as $usuario){
                    $sql = "INSERT INTO tarefas_criadas(titulo, prioridade, ptc_num, descricao_tarefa, criado_por, usuario_tarefa, data_final) 
                    values ('$titulo', '$prioridade', '$ptc', '$descricao' , '$usuarioSession', '$usuario', '$data')";
                    $query = mysqli_query($conexao, $sql);
                    
                    if($query){
                        $last_inserted_id = mysqli_insert_id($conexao);
                        $sql = "INSERT INTO tarefas_todo(tarefa_id) values ('$last_inserted_id')";
                        $query = mysqli_query($conexao, $sql);

                        if($query){
                            logDragging($usuarioSession, $last_inserted_id, 'tarefas_todo', 'create');
                        }else{
                            echo json_encode(array(
                                'erro' => true,
                                'msg' => 'Ocorreu algum erro...'
                            ));
                            break;
                            die();
                        }
                    }
                }

                echo json_encode(array(
                    'erro' => false,
                    'msg' => 'Registro inserido com sucesso!'
                ));

            }else{
                exit();
            }

        }else{
            echo json_encode(array(
                'erro' => true,
                'msg' => 'Você deve selecionar ao menos um usuário.'
            ));
        }
    */}else{
        exit();
    }

}