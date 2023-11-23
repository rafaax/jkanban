<?php 

require 'conexao.php';
$client_data = file_get_contents("php://input");
$json = json_decode($client_data);
// file_put_contents('logconcluidos.txt', file_get_contents("php://input"));
$user = $json->user_id;


// $sql = "SELECT td.tarefa_id, tc.titulo, (select prioridade from prioridade where id = tc.prioridade) as prioridade from tarefas_done td
//     inner join tarefas_criadas tc on tc.tarefa_id = td.tarefa_id
//         where tc.usuario_tarefa = '$user'";
// echo $sql;

$sql = "SELECT td.tarefa_id, tc.titulo, tc.ptc_num, tc.data_criada, tc.descricao_tarefa, tc.data_final, tc.prioridade as prioridade_id, 
        (SELECT login from usuarios where id = tc.criado_por) as created_by,  
        (select prioridade from prioridade where id = tc.prioridade) as prioridade from tarefas_done td
        inner join tarefas_criadas tc on tc.tarefa_id = td.tarefa_id
            where tc.usuario_tarefa = '$user' and tc.data_final >= (NOW() - INTERVAL 24 HOUR) order by id desc limit 5";

$query = mysqli_query($conexao, $sql);

$tarefas = [];

while($array = mysqli_fetch_array($query)){
    if($array['ptc_num'] == NULL){
        $ptc = 'Não foi identificado um ptc';
    }else{
        $ptc = $array['ptc_num'];
    }

    $tarefas[] = [
        'title' => $array['titulo'],
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

echo json_encode($tarefas);

?>