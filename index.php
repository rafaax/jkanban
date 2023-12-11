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

  </body>
</html>

<script>

$(document).ready(function(){

  $('#calendario').on("click", function(){
    window.location.href = "https://engedoc.com.br/calendario";
  });

  function validaJson(user){
    $.ajax({
      type: "GET",
      url: `src/to_do.php?iduser=${user}`,
      contentType: "application/json",
      success: function(response) {
        console.log(response);
        if(response == true){
          Swal.fire({
              title: 'Existem tarefas novas para voce!',
              icon: 'warning',
              confirmButtonText: "OK!",
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
          })
        }else if(response == false){
          console.log('ok');
        }
      }
    })
  }
  

  function get_usuarios() {
    return new Promise(function(resolve, reject) {
      $.ajax({
          type: "GET",
          url: 'src/get_usuarios.php',
          contentType: "application/json",
          success: function(response) {
            // console.log(response);
            var json = JSON.parse(response);
            console.log(json)
            const data = {};
            json.forEach(item => {
                const key = Object.keys(item)[0];
                const value = item[key];
                console.log('Key' + key);
                console.log('Value' + value);
                data[key] = value;
            });

            console.log(data);
            resolve(data);
            
          }})
        })
    }


  function get_userid(user){
    return new Promise(function(resolve, reject) {
      $.ajax({
        type: "GET",
        url: 'src/get_id.php?username=' + user,
        contentType: "application/json",
        success: function(response) {
          console.log(response);
          resolve(response);
        }
      })
    })
  } 

  $('#filtrar-usuario').on("click", async function(){
    const { value: user } = await Swal.fire({
      title: "Selecione algum kanban para visualizar!",
      input: "select",
      inputOptions: get_usuarios(),
      inputPlaceholder: "Selecione o usuário",
      showCancelButton: true,

    });
    if (user) {
      get_userid(user)
        .then(id => {
          window.location.href = 'index?id=' + id;
        })
    }
  });



  function load_cadastro(query){
    $.ajax({
      url:"src/cadastro_task.php",
      method:"post",
      success:function(data){
        $('#cadastro_task_pd').html(data);
      }
    });
  }

  
  function load_cadastro_sequencial(query){
    $.ajax({
      url:"src/cadastro_task_sequenciada.php",
      method:"post",
      success:function(data){
        $('#cadastro_task_sq').html(data);
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
        // console.log(response);
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
          
          var data_criada = moment(el.dataset.data_criada).toDate();
          var data_vencimento = moment(el.dataset.data_vencimento).toDate();
          $("#apagar_evento").val(el.dataset.task_id);
          $('#visualizar').modal('show');
          $('#visualizar #titulo_tarefa').text(el.dataset.titulo_tarefa);
          $('#visualizar #prioridade').text(el.dataset.prioridade);
          $('#visualizar #prioridade').val(el.dataset.prioridade);
          $('#visualizar #created_by').text(el.dataset.criado_por);
          $('#visualizar #created_by').val(el.dataset.criado_por);
          $('#visualizar #data_criada').text(moment(data_criada).format('DD/MM/YYYY hh:mm'));
          $('#visualizar #data_criada').val(el.dataset.data_criada);
          $('#visualizar #data_termino').text(moment(data_vencimento).format('DD/MM/YYYY hh:mm'));
          $('#visualizar #data_termino').val(el.dataset.data_vencimento);
          $('#visualizar #ptc').text(el.dataset.ptc);
          $('#visualizar #ptc').val(el.dataset.ptc);
          $('#visualizar #descricao').text(el.dataset.descricao);
          $('#visualizar #descricao').val(el.dataset.descricao);
          
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
      <?php
      if(isset($_GET['id'])){?>
        validaJson(<?=$_GET['id']?>);
      <?php } ?>
      
    }

  <?php 
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


  $('#adicionar-tarefa').on("click", function(){
    window.location.href = "index?cadastro";
  });

  $('#apagar_evento').on("click", function(){
      var id = this.value;

      Swal.fire({
        title: 'Alerta',
        text: "Você realmente deseja deletar?",
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
          $.ajax({
            type: 'POST',
            url: 'src/delete_task.php',
            contentType: 'application/json',
            data: JSON.stringify({id:id}),
            success: function(resposta) {
              // console.log(resposta);
              var json = JSON.parse(resposta);
              Swal.close();
              if(json.erro == false){
                let timerInterval;
                Swal.fire({
                  icon: 'success',
                  title: "Sucesso!",
                  html: "Fechando em <b></b> milisegundos...",
                  timer: 2000,
                  timerProgressBar: true,
                  didOpen: () => {
                      Swal.showLoading();
                      const timer = Swal.getPopup().querySelector("b");
                      timerInterval = setInterval(() => {
                      timer.textContent = `${Swal.getTimerLeft()}`;
                      }, 100);
                  },
                  willClose: () => {
                      clearInterval(timerInterval);
                  }
                }).then((result) => {
                  if (result.dismiss === Swal.DismissReason.timer) {
                      window.location.href = "http://192.168.0.166/jkanban/";
                  }
                });
              }else if(json.erro == true){
                Swal.fire({
                    title: json.msg,
                    icon: 'error',
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
                })
              }
            }
          });
        }
      })
  });
    
});

</script>