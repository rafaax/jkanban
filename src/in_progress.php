<?php 

require 'conexao.php';
$client_data = file_get_contents("php://input");
$json = json_decode($client_data);
file_put_contents('loginprogress.txt', file_get_contents("php://input"));
$user = $json->user_id;


$sql = "SELECT tp.tarefa_id,tc.data_final, tc.titulo, tc.prioridade from tarefas_process tp 
    inner join tarefas_criadas tc on tc.tarefa_id = tp.tarefa_id
        where tc.usuario_tarefa = '$user'";
// echo $sql;
$query = mysqli_query($conexao, $sql);

$tarefas = [];

while($array = mysqli_fetch_array($query)){
    $titulo = $array['titulo'];
    $data_final = $array['data_final'];
    $titulo = "$titulo <span style='background-color:#f4ce46'>$data_final</span>";
    
    $tarefas[] = [
        'title' => $titulo,
        'task_id' => $array['tarefa_id'],
        'prioridade' => $array['prioridade']
    ];
}

echo json_encode($tarefas);

?>