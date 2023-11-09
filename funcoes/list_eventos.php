<?php

include 'validacao.php';
include 'conexao.php';


$idget = $_GET['id'];
$formato = $_GET['formato'];

if($idget === 'all'){
    if($formato == 0){
        $sql  = "SELECT e.id, e.title, e.color, e.start, e.start_time, e.end, e.end_time, e.convidados, e.formato,e.link, u.nome, e.sala_engeline 
        from events e INNER JOIN usuario u ON u.id = e.usuario_id order by id desc" ;
        
    }else if($formato == 1){
        $sql  = "SELECT e.id, e.title, e.color, e.start, e.start_time, e.end, e.end_time, e.convidados, e.formato,e.link, u.nome, e.sala_engeline 
        from events e INNER JOIN usuario u ON u.id = e.usuario_id WHERE e.formato = 'Presencial' order by id desc" ;

    }else if($formato == 2){
        $sql  = "SELECT e.id, e.title, e.color, e.start, e.start_time, e.end, e.end_time, e.convidados, e.formato, 
            e.link, u.nome, e.sala_engeline 
            from events e INNER JOIN usuario u ON u.id = e.usuario_id WHERE e.formato = 'Remoto' order by id desc";
    
    }else if($formato == 3){
        $sql  = "SELECT e.id, e.title, e.color, e.start, e.start_time, e.end, e.end_time, e.convidados, e.formato, 
            e.link, u.nome, e.sala_engeline 
            from events e INNER JOIN usuario u ON u.id = e.usuario_id WHERE e.formato = 'Presencial em Campo' order by id desc";
        
    }else if($formato == 4){
        $sql  = "SELECT e.id, e.title, e.color, e.start, e.start_time, e.end, e.end_time, e.convidados, e.formato, 
            e.link, u.nome, e.sala_engeline 
            from events e INNER JOIN usuario u ON u.id = e.usuario_id WHERE e.sala_engeline = 1 order by id desc";

    }else if($formato == 5){
        $sql  = "SELECT e.id, e.title, e.color, e.start, e.start_time, e.end, e.end_time, e.convidados, e.formato, 
            e.link, u.nome, e.sala_engeline 
            from events e INNER JOIN usuario u ON u.id = e.usuario_id WHERE e.formato = 'Compromisso Pessoal' order by id desc";
    }
    $query = mysqli_query($conexao, $sql);
    

}else{


    $sql = "SELECT nome from usuario where id = $idget limit 1";
    $query = mysqli_query($conexao, $sql);
    
    $array = mysqli_fetch_assoc($query);
    $nomesql = $array['nome'];

    if($formato == 0){

        $sql  = "SELECT e.id, e.title, e.color, e.start, e.start_time, e.end, e.end_time, e.convidados,e.link, e.formato, u.nome, e.sala_engeline
        from events e INNER JOIN usuario u ON u.id = e.usuario_id 
        where (e.convidados LIKE '%$nomesql%' OR e.usuario_id = $idget) order by id desc";

    }else if($formato == 1){

        $sql  = "SELECT e.id, e.title, e.color, e.start, e.start_time, e.end, e.end_time, e.convidados,e.link, e.formato, u.nome, e.sala_engeline
        from events e INNER JOIN usuario u ON u.id = e.usuario_id 
        where (e.convidados LIKE '%$nomesql%' OR e.usuario_id = $idget) and formato = 'Presencial' order by id desc";

    }else if($formato == 2){
        
        $sql  = "SELECT e.id, e.title, e.color, e.start, e.start_time, e.end, e.end_time, e.convidados,e.link, e.formato, u.nome, e.sala_engeline
        from events e INNER JOIN usuario u ON u.id = e.usuario_id 
        where (e.convidados LIKE '%$nomesql%' OR e.usuario_id = $idget) and formato = 'Remoto' order by id desc";

    }else if($formato == 3){
        
        $sql  = "SELECT e.id, e.title, e.color, e.start, e.start_time, e.end, e.end_time, e.convidados,e.link, e.formato, u.nome, e.sala_engeline
        from events e INNER JOIN usuario u ON u.id = e.usuario_id
        where (e.convidados LIKE '%$nomesql%' OR e.usuario_id = $idget) and formato = 'Presencial em Campo' order by id desc";
        
    }else if($formato == 4){

        $sql  = "SELECT e.id, e.title, e.color, e.start, e.start_time, e.end, e.end_time, e.convidados,e.link, e.formato, u.nome, e.sala_engeline
        from events e INNER JOIN usuario u ON u.id = e.usuario_id 
        where  (e.convidados LIKE '%$nomesql%' OR e.usuario_id = $idget) and sala_engeline = 1 order by id desc";

    }else if($formato == 5){
        $sql  = "SELECT e.id, e.title, e.color, e.start, e.start_time, e.end, e.end_time, e.convidados,e.link, e.formato, u.nome, e.sala_engeline
        from events e INNER JOIN usuario u ON u.id = e.usuario_id
        where (e.convidados LIKE '%$nomesql%' OR e.usuario_id = $idget) and formato = 'Compromisso Pessoal' order by id desc";
    }

    $query = mysqli_query($conexao, $sql);



}
    $eventos = [];

    while($array = mysqli_fetch_array($query, MYSQLI_ASSOC)){

        $id = $array['id'];
        $title = $array['title'];
        $color = $array['color'];
        $start = $array['start'];
        $start_time = $array['start_time'];
        $end = $array['end'];
        $end_time = $array['end_time'];
        $convidados = $array['convidados'];
        $link = $array['link'];
        $sala_engeline = $array['sala_engeline'];
        $start_edit =  "$start $start_time";
        $end_edit =  "$end $end_time";

        if($convidados == NULL){
            $convidados = "Nenhum convidado no momento!";
        }

        if($sala_engeline == NULL){
            $sala_engeline = false;
        }else if($sala_engeline == 1){
            $sala_engeline = 'Compromisso na sala engeline';
        }

        $formato = $array['formato'];
        $usuario = $array['nome'];

        $eventos[] = [

            'id' => $id, 
            'title' => $title, 
            'color' => $color, 
            'start' => $start_edit,
            'start-time' => $start_time,
            'start-edit' => $start,
            'end' => $end_edit, 
            'end-time' => $end_time,
            'end-edit' => $end,
            'convidados' => $convidados,
            'formato' => $formato,
            'usuario_id' => $usuario,
            'link' => $link,
            'sala_engeline' => $sala_engeline
    
        ];

    }

    echo json_encode($eventos);

?>