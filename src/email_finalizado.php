<?php 
require 'chaves.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


if($_SERVER['REQUEST_METHOD'] === 'POST'){
    file_put_contents('logemail.txt', file_get_contents("php://input"));
    $smtpoptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    include_once 'conexao.php';

    $client_data = file_get_contents("php://input");
    $json = json_decode($client_data);
     
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

    foreach($json->criador as $userid){
        $sql = "SELECT * from usuarios where id = $userid";
        $query = mysqli_query($conexao, $sql);
        $result = mysqli_fetch_assoc($query);

        $cc_email = $result['email'];
        $nome = $result['nome'];
        $sobrenome = $result['sobrenome'];

        $mail->addCC($cc_email, "$nome $sobrenome");
        $mail->setFrom('vetorian@vetorian.com');
        $mail->addAddress($cc_email);
        // $mail->addCC('', 'Cópia');
        // $mail->addBCC('email@email.com.br', 'Cópia Oculta');
        $mail->isHTML(true);
        $mail->Subject = $json->tarefa;
        
        $sql = "SELECT html from email_template where tipo = 'TAREFA_FINALIZADA'";
        $query = mysqli_query($conexao, $sql);
        $array = mysqli_fetch_assoc($query);
        $body = $array['html'];

        $arrayHtml = array(
            "%user%" => $json->usuario,
            "%content%" => "Tarefa foi cadastrada: <strong> $json->horario_cadastro </strong>",
            "%content2%" => "Tarefa foi iniciada: <strong>$json->horario_inicio</strong>",
        );

        $mail->Body = strtr($body,$arrayHtml);
    }

    if(!$mail->send()) {
        echo 'Não foi possível enviar a mensagem.<br>';
        echo 'Erro: ' . $mail->ErrorInfo;
    } else {
        echo 'Mensagem enviada.';
    }
    
}


