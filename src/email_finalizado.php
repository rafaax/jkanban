<?php 
require 'chaves.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function buscaHorarioCreate($task_id){
    require 'conexao.php';
    $sql = "SELECT * from logs where task_id = $task_id and source = 'create' order by id desc limit 1";
    $query = mysqli_query($conexao, $sql);
    $array = mysqli_fetch_array($query);

    return $array['data'];

}

function buscaHorarioProgress($task_id){
    require 'conexao.php';
    $sql = "SELECT * from logs where task_id = $task_id and source = 'tarefas_todo' order by id desc limit 1";
    $query = mysqli_query($conexao, $sql);
    $array = mysqli_fetch_array($query);

    return $array['data'];
}

function buscaNomeTarefa($task_id){
    require 'conexao.php';
    $sql = "select titulo, ptc_num from tarefas_criadas where tarefa_id = $task_id";
    $query = mysqli_query($conexao, $sql);
    $array = mysqli_fetch_array($query);

    $titulo = $array['titulo'];
    $ptc_num = $array['ptc_num'];

    return "$titulo - PTC: $ptc_num";
}

function buscaUsuario($usuario){
    require 'conexao.php';

    $sql = "SELECT * from usuarios where id = $usuario";
    $query = mysqli_query($conexao, $sql);
    $array = mysqli_fetch_array($query);

    return $array['login'];
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // file_put_contents('logemail.txt', file_get_contents("php://input"));
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

    $sql = "SELECT * from usuarios where id = $json->criador";
    $query = mysqli_query($conexao, $sql);
    $result = mysqli_fetch_assoc($query);

    $cc_email = $result['email'];
    $nome = $result['nome'];
    $sobrenome = $result['sobrenome'];
    $tarefa_nome = buscaNomeTarefa($json->tarefa);

    $mail->addCC($cc_email, "$nome $sobrenome");
    $mail->setFrom('vetorian@vetorian.com');
    $mail->addAddress($cc_email);
    $mail->isHTML(true);
    $mail->Subject =  $tarefa_nome;
    
    $sql = "SELECT html from email_template where tipo = 'TAREFA_FINALIZADA_2'";
    $query = mysqli_query($conexao, $sql);
    $array = mysqli_fetch_assoc($query);
    $body = $array['html'];

    $horario_cadastro = buscaHorarioCreate($json->tarefa);
    $horario_inicio = buscaHorarioProgress($json->tarefa);

    $horario_cadastro = new DateTime($horario_cadastro);
    $horario_inicio = new DateTime($horario_inicio);

    $horario_cadastro = $horario_cadastro->format('d/m/Y H:i:s');
    $horario_inicio = $horario_inicio->format('d/m/Y H:i:s');


    $arrayHtml = array(
        "%user%" => buscaUsuario($json->usuario),
        "%task%" => $tarefa_nome,
        "%content%" => "Tarefa foi cadastrada: <strong> $horario_cadastro </strong>",
        "%content2%" => "Tarefa foi iniciada: <strong>$horario_inicio</strong>",
    );

    $mail->Body = strtr($body,$arrayHtml);

    if(!$mail->send()) {
        echo 'Não foi possível enviar a mensagem.<br>';
        echo 'Erro: ' . $mail->ErrorInfo;
    } else {
        echo 'Mensagem enviada.';
    }
    
}


