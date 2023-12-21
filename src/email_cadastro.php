<?php 
require 'chaves.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function buscaNomeCriador($id){
    require 'conexao.php';

    $sql = "SELECT * from usuarios where id = $id";
    $query = mysqli_query($conexao, $sql);
    $array = mysqli_fetch_array($query);

    return $array['nome'] . ' ' . $array['sobrenome'];

}

function buscaPrioridade($id){
    require 'conexao.php';
    
    $sql = "SELECT * from prioridade where id = $id";
    $query = mysqli_query($conexao, $sql);
    $array = mysqli_fetch_array($query);

    return $array['prioridade'];

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
    // print_r($client_data);
    $tarefa_id = $json->tarefa_id;
    $tarefa=  $json->tarefa;
    $ptc = $json->ptc;
    $descricao = $json->descricao;
    $prioridade = $json->prioridade;
    $criador = $json->criador;
    $usuario = $json->usuario;
    $data_criada = $json->data_criada;
    $data_final = $json->data_final;


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

    $sql = "SELECT * from usuarios where id = $usuario";
    $query = mysqli_query($conexao, $sql);
    $result = mysqli_fetch_assoc($query);

    $cc_email = $result['email'];
    $nome = $result['nome'];
    $sobrenome = $result['sobrenome'];

    $mail->addCC($cc_email, "$nome $sobrenome");
    $mail->setFrom('vetorian@vetorian.com');
    $mail->addAddress($cc_email);
    $mail->isHTML(true);
    $mail->Subject =  'Nova Tarefa - Engedoc';
    
    $sql = "SELECT html from email_template where tipo = 'TAREFA_CRIADA'";
    $query = mysqli_query($conexao, $sql);
    $array = mysqli_fetch_assoc($query);
    $body = $array['html']; // pegando o template do email


    $horario_cadastro = new DateTime($data_criada);
    $horario_final = new DateTime($data_final);

    $horario_cadastro = $horario_cadastro->format('d/m/Y H:i:s');
    $horario_final = $horario_final->format('d/m/Y H:i:s');

    $criador = buscaNomeCriador($criador);
    $prioridade = buscaPrioridade($prioridade);


    $arrayHtml = array(
        "%titulo%" => $tarefa,
        "%prioridade%" => $prioridade,
        "%ptc%" => $ptc,
        "%descricao%" => $descricao,
        "%criador%" => $criador,
        "%data_criada%" => $horario_cadastro,
        "%data_final%" => $horario_final,
        "%link%" => '192.168.0.122/jkanban/tarefa.php?id=' . $tarefa_id,
    );

    $mail->Body = strtr($body,$arrayHtml);

    if(!$mail->send()) {
        echo 'Não foi possível enviar a mensagem.<br>';
        echo 'Erro: ' . $mail->ErrorInfo;
    } else {
        echo 'Mensagem enviada.';
    }
    
}


