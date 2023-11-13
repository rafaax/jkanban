<?php 

require 'conexao.php';
$client_data = file_get_contents("php://input");
$json = json_decode($client_data);
// file_put_contents('log.txt', file_get_contents("php://input"));
$user = $json->user_id;


$sql = "SELECT td.tarefa_id, tc.titulo, tc.prioridade from tarefas_todo td
        inner join tarefas_criadas tc on tc.tarefa_id = td.tarefa_id
            where tc.usuario_tarefa = '$user'";

$query = mysqli_query($conexao, $sql);

$tarefas = [];

while($array = mysqli_fetch_array($query)){
    if($array['prioridade'] == 'Urgente'){
        $titulo = $array['titulo'] . ' <i class="fas fa-exclamation-triangle"></i>';
    }else{
        $titulo = $array['titulo'];
    }

    $tarefas[] = [
        'title' => $titulo,
        'task_id' => $array['tarefa_id'],
        'prioridade' => $array['prioridade']
    ];
}

echo json_encode($tarefas);

?>