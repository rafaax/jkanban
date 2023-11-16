<?php 

require 'conexao.php';
require 'validacao.php';
date_default_timezone_set('America/Sao_Paulo');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if(isset($_POST['tarefa']) && isset($_POST['ptc']) && isset($_POST['prioridade']) && isset($_POST['descricao'])){
        $titulo = $_POST['tarefa']; 
        $ptc = $_POST['ptc']; 
        $prioridade = $_POST['prioridade']; 
        $descricao = $_POST['descricao'];
        $data_vencimento = $_POST['data_entrega'];
        $tempo_vencimento = $_POST['tempo_entrega'];
        
        $data = "$data_vencimento $tempo_vencimento";
        

        $dataAtual = date('Y-m-d H:i:s');

        if($dataAtual > $data ){
            echo json_encode(array(
                'erro' => true,
                'msg' => 'Data da tarefa não pode ser anterior a data atual!'
            ));
            exit();
        }
        

        if(isset($_POST['usuarios'])){
            $usuarios = $_POST['usuarios'];
            if(is_array($usuarios)){
                // print_r($usuarios);
                foreach($usuarios as $usuario){
                    $sql = "INSERT INTO tarefas_criadas(titulo, prioridade, ptc_num, descricao_tarefa, criado_por, usuario_tarefa, data_final) 
                    values ('$titulo', '$prioridade', '$ptc', '$descricao' , '$usuarioSession', '$usuario', '$data')";
                    $query = mysqli_query($conexao, $sql);
                    
                    if($query){
                        $last_inserted_id = mysqli_insert_id($conexao);
                        $sql = "INSERT INTO tarefas_todo(tarefa_id) values ('$last_inserted_id')";
                        $query = mysqli_query($conexao, $sql);

                        if($query){
                            echo json_encode(array(
                                'erro' => false,
                                'msg' => 'Registro efetuado com sucesso!'
                            ));
                        }else{
                            echo json_encode(array(
                                'erro' => true,
                                'msg' => 'Ocorreu algum erro...'
                            ));
                        }
                    }
                }
            }else{
                exit();
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