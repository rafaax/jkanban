<?php

require 'validacao.php';

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $return = array(
        'permissoesSession' => $permissoesSession,
        'usuarioSession' => $usuarioSession
    );
    
    echo json_encode($return);
}



if(isset($_GET['id'])){
    if($usuarioSession == $_GET['id']){?>
    initKanban();
    <?php
    }else{ 
    if($permissoesSession == 1 && $usuarioSession == $_GET['id']){?>
        initKanban();<?php
    }else if($permissoesSession == 1 && $usuarioSession != $_GET['id']){?>
        initKanban();
        alertaAdm();<?php
    }else{?>
        window.location.replace('index?id=<?=$usuarioSession?>');<?php
    }
    }
}else if(isset($_GET['cadastro']) && $_GET['cadastro'] == 'padrao'){?> 
    load_cadastro(); <?php 
}else if(isset($_GET['cadastro']) && $_GET['cadastro'] == 'sequencial'){?>
    load_cadastro_sequencial();<?php
}?>