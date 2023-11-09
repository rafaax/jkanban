<?php include 'src/validacao.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Kanban</title>
    <link rel="stylesheet" href="../dist/jkanban.min.css" />
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet"/>
    <link rel="stylesheet" href="assets/css/stylekanban.css"/>

  </head>
  <body>
    <div id="myKanban"></div>
    <button id="addDefault" class="custom-button">Add "Default" board</button>
    <br />
    <button id="addToDo" class="custom-button">Add element in "To Do" Board</button>
    <br />
    <button id="addToDoAtPosition" class="custom-button">Add element in "To Do" Board at position 2</button>
    <br />
    <button id="removeBoard" class="custom-button">Remove "Done" Board</button>
    <br />
    <button id="removeElement" class="custom-button">Remove "My Task Test"</button>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../dist/jkanban.js"></script>
  </body>
</html>

<script>



function get_pendencias(user) {
    return new Promise(function(resolve, reject) {
        var jsonPost = {
            user_id: user
        };

        $.ajax({
            type: "POST",
            url: 'php/to_do.php',
            data: JSON.stringify(jsonPost),
            contentType: "application/json",
            success: function(response) {
                // console.log(response);
                try {
                    var parsedResponse = JSON.parse(response);

                    // Verifica se o resultado é um array
                    if (Array.isArray(parsedResponse)) {
                        resolve(parsedResponse);
                    } else {
                        var itemJson = parsedResponse.map(function(title) {
                            return { title: title };
                        });

                        resolve(itemJson);
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

async function fetchData() {
    try {
        var pendencias = await get_pendencias(<?=$_GET['id']?>);
        console.log(pendencias)
        return pendencias;
    } catch (error) {
        console.error("Erro:", error);
    }
}

var itemJson = [
    {
        title: "item"
    },
    {
        title: "item 2"
    },
    {
        title: "item 3"
    },
];

var pendenciasData = [];

async function fetchDataAndInitializeKanban() {
    try{
        pendenciasData = await fetchData();
        // console.log(pendenciasData); - resultado post das pendencias

        var KanbanTest = new jKanban({
          element: "#myKanban",
          gutter: "10px",
          widthBoard: "450px",
          dragItems: false, 
          itemHandleOptions:{
              enabled: true,
          },
          dropEl: function(el, target, source, sibling){
              console.log(target.parentElement.getAttribute('data-id'));
              // console.log(el, target, source, sibling)
              console.log(el.dataset.eid);
              console.log(el.dataset.task);

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
              content: '+ Add New Card',
              class: 'custom-button',
              footer: true
          },
          boards: [
              {
                id: "_todo",
                title: "Pendencias",
                class: "info,good",
                dragTo: ["_working"],
                item: pendenciasData
              },
              {
                id: "_working",
                title: "Em desenvolvimento",
                class: "warning",
                item: itemJson // vindo do array - teste
              },
              {
                id: "_done",
                title: "Feitos",
                class: "success",
                dragTo: ["_working"],
                item: [
                  {
                    "id"      : "item-id-1",
                    "title"   : "Item 1",
                    "username": "username1"
                  },
                  {
                    "id"      : "item-id-2",
                    "title"   : "Item 2",
                    "username": "username2"
                  }
                ]
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
  echo 'fetchDataAndInitializeKanban();';
}else{ 
  if($permissoesSession == 1){
      echo 'fetchDataAndInitializeKanban();';
  }else{
    echo "window.location.replace('index.php?id=$usuarioSession')";
  }
}
?>
</script>
