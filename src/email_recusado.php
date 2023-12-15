<?php 

require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    require 'chaves.php'; require 'conexao.php';

    $client_data = file_get_contents("php://input");
    $json = json_decode($client_data);
    
    $datenow = date('d/m/Y H:i:s');

    $tarefa_id = $json->tarefa_id;
    $user = $json->user;
    $msg = $json->msg;

    $sql = "select * from usuarios where id = $user limit 1";
    $query = mysqli_query($conexao, $sql);
    $array = mysqli_fetch_array($query);
    
    $nome_usuario = $array['nome'] . ' ' . $array['sobrenome'];

    $sql2 = "select * from tarefas_criadas where tarefa_id = $tarefa_id limit 1";
    $query2 = mysqli_query($conexao, $sql2);
    $array2 = mysqli_fetch_array($query2);
    
    $nome_tarefa = $array2['titulo'];
    $ptc_tarefa = $array2['ptc_num'];
    $criador_tarefa = $array2['criado_por'];

    $sql3 = "select email from usuarios where id = $criador_tarefa limit 1 ";
    $query3 = mysqli_query($conexao, $sql3);
    $array3 = mysqli_fetch_array($query3);
    $email = $array3['email'];

    $mail = new PHPMailer();
    $mail->CharSet = "UTF-8";
    $mail->SMTPDebug = 0;
    $mail->Debugoutput = 'html';
    $mail->isSMTP();
    $mail->Host = $hostsmtp;
    $mail->SMTPAuth = true;
    $mail->Username = $usersmtp;
    $mail->Password = $passsmtp;
    $mail->SMTPSecure = 'auto';
    $mail->Port = $portsmtp;
    $mail->SMTPOptions = $smtpoptions;



    $mail->addCC($email);
    $mail->setFrom('vetorian@vetorian.com');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject =  'Tarefa Recusada - ' . $nome_tarefa;
    
    $sql = "SELECT html from email_template where tipo = 'TAREFA_RECUSADA'";
    $query = mysqli_query($conexao, $sql);
    $array = mysqli_fetch_assoc($query);
    $body = $array['html'];

    $arrayHtml = array(
        "%user%" => $nome_usuario,
        "%task%" => "$nome_tarefa - $ptc_tarefa",
        "%content%" => $msg,
        "%data%" => $datenow,
        
    );

    $mail->Body = strtr($body,$arrayHtml);

    if(!$mail->send()) { 
        echo $mail->ErrorInfo;
    }
    
}


