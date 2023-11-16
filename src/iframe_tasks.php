<?php
include_once 'validacao.php';
include_once 'conexao.php';

$idGet = $_GET['id'];
?>
<!DOCTYPE html>
<head>
<link rel="stylesheet" href="../assets/css/style_iframe.css">
<meta charset='utf-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<link href='https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css' rel='stylesheet'>
<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<?php 
date_default_timezone_set('America/Sao_Paulo');
function diferencaEntreDatasSegundos($dataMenor, $dataMaior){
    $datetime1 = strtotime($dataMaior);
    $datetime2 = strtotime($dataMenor);
    $interval = ($datetime1 - $datetime2);
    return $interval;
}
function diferencaEntreDatasTempo($dataMenor, $dataMaior){
    $datetime1 = new DateTime($dataMenor);
    $datetime2 = new DateTime($dataMaior);
    $interval = $datetime1->diff($datetime2);
    return $interval->format('%a dias %H horas %i minutos ');
}
function segundosToTempo($segundos){
    return sprintf('%02d:%02d:%02d', ($segundos/3600),($segundos/60%60), $segundos%60);
}


$sql = "SELECT tc.tarefa_id, tc.titulo, tc.criado_por, tc.usuario_tarefa, tc.data_criada, tc.data_final,
        (select prioridade from prioridade where id = tc.prioridade) as prioridade
        FROM tarefas_criadas tc
        INNER JOIN tarefas_process tp ON tc.tarefa_id = tp.tarefa_id
        WHERE tc.usuario_tarefa = '$idGet'

        UNION

        SELECT tc.tarefa_id, tc.titulo, tc.criado_por, tc.usuario_tarefa, tc.data_criada, tc.data_final,
        (select prioridade from prioridade where id = tc.prioridade) as prioridade
        FROM tarefas_criadas tc
        INNER JOIN tarefas_todo tt ON tc.tarefa_id = tt.tarefa_id
        WHERE tc.usuario_tarefa = '$idGet'

        ORDER BY prioridade DESC, data_final asc;";

$query = mysqli_query($conexao,$sql);
?>
<div class="col-md-2">
    <div class="mt-2">
        <ul class="list list-inline">
<?php

$count = mysqli_num_rows($query);

while($array = mysqli_fetch_array($query, MYSQLI_ASSOC)){
    $tarefa_id = $array['tarefa_id'];       
    $titulo = $array['titulo'];
    $criador_id = $array['criado_por'];
    $usuario_tarefa = $array['usuario_tarefa'];
    $data_criada = $array['data_criada'];
    $data_vencimento = $array['data_final'];
    $prioridade = $array['prioridade'];
    $data_criada  = date("Y-m-d H:i:s", strtotime($data_criada));
    $data_vencimento  = date("Y-m-d H:i:s", strtotime($data_vencimento));
    $datahoje = date('Y-m-d H:i:s');

    echo  ($data_vencimento < $datahoje) ? '<div class="tooltip-9" title="Atrasado">' : '';
    echo '<li class="d-flex justify-content-between">';
        echo ($data_vencimento < $datahoje) ? '<div class="d-flex flex-row align-items-center" style="background-color: rgba(255, 0, 0, 0.73)">' :  '<div class="d-flex flex-row align-items-center">';
            echo '<div class="ml-2">';
                echo ($prioridade != 'Urgente') ? "<h6 class='mb-0'>$titulo</h6>" : "<h6 class='mb-0'>$titulo - $prioridade</h6>";
                echo '<div class="d-flex flex-row mt-1 text-black-50 date-time">';
                echo '<div><i class="fa fa-calendar-o"></i><span class="ml-2">' . date('d/m/y', strtotime($data_vencimento)). '</span></div>';
                echo '<div class="ml-3">
                    <i class="fa fa-clock-o"></i>
                    <span class="ml-2">';
                echo ($data_vencimento < $datahoje) ? 'Atrasado: '. diferencaEntreDatasTempo($datahoje, $data_vencimento)  : 'Faltam: '. diferencaEntreDatasTempo($datahoje, $data_vencimento); 
                echo '</span></div>';
            echo '</div>';
        echo'</div>';
    echo '</div>';
    echo '<div class="d-flex flex-row align-items-center">';
    echo '</li>';
    echo ($data_vencimento < $datahoje) ? '</div>' : '';
    }
        echo '</ul>';
        echo '</div>';
    ?>
</body>
<script>

    function pageScroll(count) {
            count = parseInt(count);
            if(count > 3){
                window.scrollBy(0,1);
                scrolldelay = setTimeout(pageScroll,100, count);
                setInterval('autoRefresh()', (count * 5)*1000);
            }else{
                setInterval('autoRefresh()', 5000);
            }   
        }
    function autoRefresh() {
        window.location = window.location.href;
    }
    
    var count = "<?=$count?>";
    var count = parseInt(count);
    pageScroll(count);

    $(function() {
        $('.tooltip-9').tooltip({
            track: true,
            hide: {
                effect: "explode",
                delay: 250
            }
        });
    });

</script>