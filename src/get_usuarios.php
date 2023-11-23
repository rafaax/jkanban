<?php 

require 'conexao.php';

$sql = "SELECT id, nome, sobrenome from usuarios order by nome asc";
$query = mysqli_query($conexao, $sql);

$usuarios = array();

while($array = mysqli_fetch_array($query)){
    $nome = $array['nome'];
    $sobrenome = $array['sobrenome'];
    
    $usuarios[] = [
        $array['id'] => "$nome $sobrenome"
    ];
}

echo json_encode($usuarios);

?>