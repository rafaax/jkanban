<?php if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['username'])){
    
    require 'conexao.php';
    
    $user = $_GET['username'];
    $sql = "SELECT id from usuarios where login = '$user'";
    $query = mysqli_query($conexao, $sql);

    $array = mysqli_fetch_array($query);
    echo $array['id'];

}?>