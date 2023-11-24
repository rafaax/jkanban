<?php 

require 'conexao.php';
$client_data = file_get_contents("php://input");
$json = json_decode($client_data);
// file_put_contents('log.txt', file_get_contents("php://input"));
$user = $json->user_id;


$sql = "SELECT td.tarefa_id, tc.titulo, tc.ptc_num, tc.data_criada,tc.descricao_tarefa, tc.data_final, tc.prioridade as prioridade_id, 
        (SELECT login from usuarios where id = tc.criado_por) as created_by,  
        (select prioridade from prioridade where id = tc.prioridade) as prioridade from tarefas_todo td
        inner join tarefas_criadas tc on tc.tarefa_id = td.tarefa_id
            where tc.usuario_tarefa = '$user'";

$query = mysqli_query($conexao, $sql);

$tarefas = [];

while($array = mysqli_fetch_array($query)){
    if($array['ptc_num'] == NULL){
        $ptc = 'Não foi identificado um ptc';
    }else{
        $ptc = $array['ptc_num'];
    }
    if($array['prioridade_id'] == 1){
        $titulo = $array['titulo'] . ' <i class="fas fa-exclamation-triangle"></i>';
    }else{
        $titulo = $array['titulo'];
    }

    $tarefas[] = [
        'title' => $titulo,
        'task_id' => $array['tarefa_id'],
        'prioridade' => $array['prioridade'],
        'titulo_tarefa' => $array['titulo'],
        'ptc' => $ptc, 
        'data_vencimento' => $array['data_final'],
        'data_criada' => $array['data_criada'],
        'criado_por' => $array['created_by'],
        'descricao' => $array['descricao_tarefa']
    ];
}
$caminho = "../files/_$user";
if(is_dir($caminho)){
    $file = file_get_contents("$caminho/tarefas.json");
    if($file != json_encode($tarefas)){
        file_put_contents("../files/_$user/tarefas.json", json_encode($tarefas));    
    }
}else{
    mkdir($caminho, 0700);
    file_put_contents("$caminho/tarefas.json", json_encode($tarefas));
}


echo json_encode($tarefas);

?>