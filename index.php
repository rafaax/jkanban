<?php include 'src/validacao.php'; ?>
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
    
  </head>
  <body>
    <?php
    if(isset($_GET['cadastro'])){
      ?>
      <div id="cadastro_task"></div>

      <?php
    }else if(!isset($_GET['id'])){
      header('Location: ?id='. $usuarioSession);
    }else if(isset($_GET['id'])){
      ?>
      <div class="wrapper">
        <div id="myKanban"></div>
        <iframe src="src/iframe_tasks.php?id=<?=$_GET['id']?>" width="100%" height="300"></iframe>
      </div>
      
      <span class="button">Adicione uma tarefa!</span>
      
      
      <div class="modal fade" id="visualizar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Detalhes do Evento</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
                </div>
                <div class="modal-body">
                    <div class="visevent">
                        <dl class="row">

                            <dt class="col-sm-3">Título do evento</dt>
                            <dd class="col-sm-9" id="title"></dd>

                        </dl>
                        <button class="btn btn-warning btn-canc-vis">Editar</button>
                        <a href="" id="apagar_evento" class="btn btn-danger">Apagar</a>
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

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
  </body>
</html>

<script>

$(document).ready(function(){


  function load_cadastro(query){
    $.ajax({
      url:"src/cadastro_task.php",
      method:"post",
      success:function(data){
        $('#cadastro_task').html(data); 
      }
    });
  }
  
  function alertaAdm(){
    if(Cookies.get('alertadministrador') == 'true'){
      return false; // faz nada
    }else{
      Swal.fire({
        title: "Cuidado!",
        text: "Como administrador você pode adicionar tarefas à outro usuário que não seja você!",
        icon: "warning"
      });
      Cookies.set('alertadministrador', 'true', { expires: 1 }) // expira em 1 dia
    }
    
    
  }

  function get_pendencias(user) {
    return new Promise(function(resolve, reject) {
      var jsonPost = {user_id: user};
      $.ajax({
          type: "POST",
          url: 'src/to_do.php',
          data: JSON.stringify(jsonPost),
          contentType: "application/json",
          success: function(response) {
              // console.log(response);
              try {
                  var parsedResponse = JSON.parse(response);

                  if (Array.isArray(parsedResponse)) {
                      resolve(parsedResponse);
                  }
              } catch (error) {
                  reject(error);
              }
          },
          error: function(xhr, status, error) {
              reject(error);
          }
      });
    });
  }

  function get_desenvolvimento(user) {
    return new Promise(function(resolve, reject) {
      var jsonPost = {user_id: user};
      $.ajax({
          type: "POST",
          url: 'src/in_progress.php',
          data: JSON.stringify(jsonPost),
          contentType: "application/json",
          success: function(response) {
              // console.log(response);
              try {
                  var parsedResponse = JSON.parse(response);

                  if (Array.isArray(parsedResponse)) {
                      resolve(parsedResponse);
                  }

              } catch (error) {
                  reject(error);
              }
          },
          error: function(xhr, status, error) {
              reject(error);
          }
      });
    });
  }

  function get_concluidos(user) {
    return new Promise(function(resolve, reject) {
      var jsonPost = {user_id: user};
      $.ajax({
          type: "POST",
          url: 'src/done.php',
          data: JSON.stringify(jsonPost),
          contentType: "application/json",
          success: function(response) {
              // console.log(response);
              try {
                  var parsedResponse = JSON.parse(response);
                  if (Array.isArray(parsedResponse)) {
                      resolve(parsedResponse);
                  }
              } catch (error) {
                  reject(error);
              }
          },
          error: function(xhr, status, error) {
              reject(error);
          }
      });
    });
  }

  function postData(id, target, source){
    var json = {task_id: id, target: target,source: source};
    
    $.ajax({
      type: "POST",
      url: 'src/postData.php',
      data: JSON.stringify(json),
      contentType: "application/json",
      success: function(response) {
        console.log(response);
        var response = JSON.parse(response);
        console.log(response.erro);
        if(response.erro == false){
          // ainda nao sei oq colocar aqui
        }
      }
    });
  }


  <?php if(isset($_GET['id'])){ ?>
    async function fetchPendencias() {
      var pendencias = await get_pendencias(<?=$_GET['id']?>);
      return pendencias;
    }

    async function fetchDesenvolvimento() {
      var in_progress = await get_desenvolvimento(<?=$_GET['id']?>);
      return in_progress;
    }

    async function fetchFeitos() {
      var feitos = await get_concluidos(<?=$_GET['id']?>);
      return feitos;
    }
    <?php } ?>


    async function initKanban() {
      
      var pendenciasData = [];
      var desenvolvimentoData = [];
      var concluidosData = [];
      
      pendenciasData = await fetchPendencias();
      desenvolvimentoData = await fetchDesenvolvimento();
      concluidosData = await fetchFeitos();

      var KanbanTest = new jKanban({
        element: "#myKanban",
        gutter: "10px",
        widthBoard: "450px",
        itemHandleOptions:{
            enabled: true,
        },
        click: function(el){
          $('#visualizar').modal('show');
          $('#visualizar #title').text(el.dataset.prioridade);
          $('#visualizar #title').val(el.dataset.prioridade);
          // console.log(el.dataset);
        },
        dropEl: function(el, target, source, sibling){
          var taskId = el.dataset.task_id;
          var targetPost = target.parentElement.getAttribute('data-id');
          var sourcePost = source.parentElement.getAttribute('data-id');
          // console.log(el.dataset);
          if(targetPost == 'tarefas_done' && sourcePost == 'tarefas_todo'){
            return false; // não deixa o usuário enviar para o backend se ele esta tentando 'cortar caminho'
          }else{
            postData(taskId, targetPost, sourcePost);  
          }
        },
        boards: [
            {
              id: "tarefas_todo",
              title: "Pendencias",
              class: "info,good",
              dragTo: ["tarefas_process"],
              item: pendenciasData
            },
            {
              id: "tarefas_process",
              title: "Em desenvolvimento",
              class: "warning",
              item: desenvolvimentoData,
            },
            {
              id: "tarefas_done",
              title: "Feitos",
              class: "success",
              item: concluidosData
            }
        ],
        dragBoards: false
      });
    }

  <?php 
  if(isset($_GET['id'])){
    if($usuarioSession == $_GET['id']){?>
      initKanban();<?php
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
  }else if(isset($_GET['cadastro'])){?> 
    load_cadastro(); <?php 
  }?>


  $('.button').on("click", function(){
      Swal.fire({
          title: 'Alerta',
          text: "Você deseja cadastrar uma tarefa?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Sim',
          cancelButtonText: 'Não',
          allowOutsideClick: () => {
              const popup = Swal.getPopup()
              popup.classList.remove('swal2-show')
              setTimeout(() => {
              popup.classList.add('animate__animated', 'animate__headShake')
              })
              setTimeout(() => {
              popup.classList.remove('animate__animated', 'animate__headShake')
              }, 500)
              return false
          }
          }).then((result) => {
          if (result.isConfirmed) {
              setTimeout(function() {
                  window.location.href = "index?cadastro";
              }, 200)
          }
        })
    });
    
    $('.btn-canc-vis').on("click", function(){
        $('.visevent').slideToggle();
        $('.formedit').slideToggle();
    });
    
    $('.btn-canc-edit').on("click", function(){
        $('.formedit').slideToggle();
        $('.visevent').slideToggle();
    });

});

</script>