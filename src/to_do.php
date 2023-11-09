<?php 

require 'conexao.php';
$client_data = file_get_contents("php://input");
$json = json_decode($client_data);
file_put_contents('log.txt', file_get_contents("php://input"));
$user = $json->user_id;


$sql = "SELECT * from tarefas_todo where usuario_tarefa = '$user'";
$query = mysqli_query($conexao, $sql);

$tarefas = [];

while($array = mysqli_fetch_array($query)){
    
    $tarefas[] = [
        'title' => $array['titulo'],
        'task' => $array['tarefa'],
        'id' => $array['id']
    ];
}

echo json_encode($tarefas);

?>