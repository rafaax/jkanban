<?php include 'src/validacao.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Kanban</title>
    <link rel="stylesheet" href="assets/js/jkanban.min.css" />
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet"/>
    <link rel="stylesheet" href="assets/css/stylekanban.css"/>

  </head>
  <body>
    <div id="myKanban"></div>
    <!-- <button id="addDefault" class="custom-button">Add "Default" board</button> -->
    <!-- <br />
    <button id="addToDo" class="custom-button">Add element in "To Do" Board</button>
    <br />
    <button id="addToDoAtPosition" class="custom-button">Add element in "To Do" Board at position 2</button>
    <br />
    <button id="removeBoard" class="custom-button">Remove "Done" Board</button>
    <br />
    <button id="removeElement" class="custom-button">Remove "My Task Test"</button> -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="assets/js/jkanban.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </body>
</html>

<script>

function alertaAdm(){
  Swal.fire({
    title: "Cuidado!",
    text: "Como administrador você pode adicionar tarefas à outro usuário que não seja você!",
    icon: "warning"
  });
}

function get_pendencias(user) {
  return new Promise(function(resolve, reject) {
    var jsonPost = {
      user_id: user
    };
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
    var jsonPost = {
      user_id: user
    };
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
    var jsonPost = {
      user_id: user
    };
    $.ajax({
        type: "POST",
        url: 'src/done.php',
        data: JSON.stringify(jsonPost),
        contentType: "application/json",
        success: function(response) {
            console.log(response);
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
async function fetchPendencias() {
  try {
      var pendencias = await get_pendencias(<?=$_GET['id']?>);
      return pendencias;
  } catch (error) {
      console.error("Erro:", error);
  }
}

async function fetchDesenvolvimento() {
  try {
      var in_progress = await get_desenvolvimento(<?=$_GET['id']?>);
      return in_progress;
  } catch (error) {
      console.error("Erro:", error);
  }
}

async function fetchFeitos() {
  try {
      var feitos = await get_concluidos(<?=$_GET['id']?>);
      return feitos;
  } catch (error) {
      console.error("Erro:", error);
  }
}

function postData(id, target, source){

  var json = {
    task_id: id,
    target: target, 
    source: source
  };
  json = JSON.stringify(json);

  $.ajax({
        type: "POST",
        url: 'src/postData.php',
        data: json,
        contentType: "application/json",
        success: function(response) {
            console.log(response);
            var response = JSON.parse(response);
            console.log(response.erro);
            if(response.erro == false){
              // initKanban();
            }
        },
        error: function(xhr, status, error) {
            reject(error);
        }
    });
}

var pendenciasData = [];
var desenvolvimentoData = [];
var concluidosData = [];

async function initKanban() {
    try{
        pendenciasData = await fetchPendencias();
        desenvolvimentoData = await fetchDesenvolvimento();
        concluidosData = await fetchFeitos();
        // console.log(pendenciasData);
        // console.log(pendenciasData); - resultado post das pendencias

        var KanbanTest = new jKanban({
          element: "#myKanban",
          gutter: "10px",
          widthBoard: "450px",
          dragItems: false, 
          itemHandleOptions:{
              enabled: true,
          },
          click: function(el){
            console.log(el.dataset.prioridade);
          },
          dropEl: function(el, target, source, sibling){
            var taskId = el.dataset.task_id;
            var targetPost = target.parentElement.getAttribute('data-id');
            var sourcePost = source.parentElement.getAttribute('data-id');
              // console.log(target.parentElement.getAttribute('data-id'));
              // console.log(el, target, source, sibling)
              // console.log(el.dataset);
              // console.log(el.dataset.prioridade);
              // console.log(el.dataset.task_id);
            if(targetPost == 'tarefas_done' && sourcePost == 'tarefas_todo'){
              return false;
            }else{
              postData(taskId, targetPost, sourcePost);  
            }
            
          },
          buttonClick: function(el, boardId) {
              console.log(el);
              console.log(boardId);
              // create a form to enter element
              var formItem = document.createElement("form");
              formItem.setAttribute("class", "itemform");
              formItem.innerHTML =
              '<div class="form-group"><textarea class="form-control" rows="2" autofocus></textarea></div><div class="form-group"><button type="submit" class="btn btn-primary btn-xs pull-right">Submit</button><button type="button" id="CancelBtn" class="btn btn-default btn-xs pull-right">Cancel</button></div>';

              KanbanTest.addForm(boardId, formItem);
              formItem.addEventListener("submit", function(e) {
                e.preventDefault();
                var text = e.target[0].value;
                KanbanTest.addElement(boardId, {
                    title: text
                });
                formItem.parentNode.removeChild(formItem);
              });
              document.getElementById("CancelBtn").onclick = function() {
                formItem.parentNode.removeChild(formItem);
              };
          },
          itemAddOptions: {
              enabled: true,
              content: '+ Adicionar novo registro',
              class: 'custom-button',
              footer: true
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

    } catch (error) {
        console.error("Erro:", error);
    }
}

<?php 
if($usuarioSession == $_GET['id']){ 
  echo 'initKanban();';
}else{ 
  if($permissoesSession == 1 && $usuarioSession == $_GET['id']){
      echo 'initKanban();';
  }else if($permissoesSession == 1 && $usuarioSession != $_GET['id']){
    echo 'initKanban();';
    echo 'alertaAdm();';
  }else{
    echo "window.location.replace('index?id=$usuarioSession')";
  }
}
?>
</script>
