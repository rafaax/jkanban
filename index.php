<?php include 'src/validacao.php'; 

function buscaNomeKanban($user){
  require 'src/conexao.php';
  $sql = "select nome, sobrenome from usuarios where id = $user";
  $query = mysqli_query($conexao, $sql);
  $array = mysqli_fetch_assoc($query);
  $nomeKanban = $array['nome'] . ' ' . $array['sobrenome']; 
  return $nomeKanban;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<title>Kanban</title>
<link rel="stylesheet" href="assets/js/jkanban.min.css"/>
<link rel="stylesheet" href="assets/css/style_kanban.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<script src="assets/js/renderizar_cadastro.js"></script>
</head>
  <body>
    <?php
    if(isset($_GET['cadastro'])){
      if($permissoesSession == 1){?>
      
      <?php
        if($_GET['cadastro'] != 'sequencial' && $_GET['cadastro'] != 'padrao'){
         ?><div id="container"></div>
         <script>renderizarTela()</script>
         <?php
        //  if($_GET['cadastro'] != 'sequencial' && $_GET['cadastro'] != 'padrao'){
        //   echo '
        //   <div class="col-sm-3">
        //     <div class="card">
        //       <div class="card-body">
        //         <h5 class="card-title">Tarefa padrão</h5>
        //         <p class="card-text">Tarefa atrelada a um ou mais usuários.</p>
        //         <a href="index?cadastro=padrao" class="btn btn-primary">Cadastrar</a>
        //       </div>
        //     </div>
        //   </div>
        //   <div class="col-sm-3">
        //     <div class="card">
        //       <div class="card-body">
        //         <h5 class="card-title">Tarefa com Sequência</h5>
        //         <p class="card-text">Tarefa step-by-step, com 1 ou mais passos, no qual o segundo depende do primeiro para iniciar.</p>
        //         <a href="index?cadastro=sequencial" class="btn btn-primary">Cadastrar</a>
        //       </div>
        //     </div>
        //   </div>
        // ';
        // }
        }

        if($_GET['cadastro'] == 'sequencial'){
          echo '<div id="cadastro_task_sq"></div>';
        }else if($_GET['cadastro'] == 'padrao'){
          echo '<div id="cadastro_task_pd"></div>';
        }
        ?>
        
        <?php  
      }else{
        header('Location: ?id='. $usuarioSession);
      }
    }else if(!isset($_GET['id'])){
      header('Location: ?id='. $usuarioSession);
    }else if(isset($_GET['id'])){
      ?>
      <?php 
      if($_GET['id'] != $usuarioSession){
        echo '
        <header> 
          <h3>Visualizando o kanban de: '. buscaNomeKanban($_GET['id']).'</h3>
        </header>';
        echo '<div style="position: absolute; bottom: 0.8%; right:6%;">
          <a href="index"><img class="logout" src="assets/imgs/home.png"></img></a>
        </div>';
      }
      ?>
      
      <div style="display: flex;">
        <div id="myKanban"></div>
        <iframe src="src/iframe_tasks.php?id=<?=$_GET['id']?>" width="100%" height="700"></iframe>
      </div>
      
      <div style="position: absolute; bottom: 0.8%; right:1.6%;">
        <a href="src/logout.php"><img class="logout" src="assets/imgs/logout.png"></img></a>
      </div>
      <div style="position: absolute; bottom: 10%; right:2%;">
        <span id="calendario"><img class="logout" src="assets/imgs/calendar.png"></img></span>
      </div>
      <?php if($permissoesSession == 1){
        echo '<span class="button" id="adicionar-tarefa">Adicione uma tarefa!</span>';
        echo '<div style="padding-bottom: 50px;">
          <span id="filtrar-usuario" class="button">Visualizar outros KANBANS</span>
        </div>';
      }?>

      
      
      
      <div class="modal fade" id="visualizar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Informações da Tarefa - <dd id="titulo_tarefa"></dd> </h5> 
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="visevent">
                        <dl class="row">
                          <dt class="col-sm-3">Prioridade da tarefa</dt>
                          <dd class="col-sm-9" id="prioridade"></dd>
                        </dl>
                        <dl class="row">
                          <dt class="col-sm-3">Data criada</dt>
                          <dd class="col-sm-9" id="data_criada"></dd>
                        </dl>
                        <dl class="row">
                          <dt class="col-sm-3">Data de término</dt>
                          <dd class="col-sm-9" id="data_termino"></dd>
                        </dl>
                        <dl class="row">
                          <dt class="col-sm-3">Quem criou a tarefa</dt>
                          <dd class="col-sm-9" id="created_by"></dd>
                        </dl>
                        <dl class="row">
                          <dt class="col-sm-3">Número PTC</dt>
                          <dd class="col-sm-9" id="ptc"></dd>
                        </dl>
                        <dl class="row">
                          <dt class="col-sm-3">Descricao da tarefa</dt>
                          <dd class="col-sm-9" id="descricao"></dd>
                        </dl>
                        <?php 
                        if($permissoesSession == 1){?>
                          <button id="apagar_evento" class="btn btn-danger">Apagar</button><?php
                        }?>
                        
                    </div>

                    <!-- editar -->
                    <div class="formedit">
                        <span id="msg-edit"></span>
                        <form id="editevent" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" id="id">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Título</label>
                                <div class="col-sm-10">
                                    <input type="text" name="title" class="form-control" id="title" placeholder="Título do evento">
                                </div>
                            </div>
                        </form>                            
                    </div>
                </div>
            </div>
        </div>
      </div>
      <?php
    }
    ?>
    
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="assets/js/jkanban.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.5/dist/js.cookie.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="assets/js/index.js"></script>

  </body>
</html>
