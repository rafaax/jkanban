$(document).ready(function(){

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

    $('#calendario').on("click", function(){
        window.location.href = "https://engedoc.com.br/calendario";
    });

    $('#adicionar-tarefa').on("click", function(){
        window.location.href = "index?cadastro";
    });

    $('#tela-acompanhamento').on("click", function(){
        window.location.href = "index?acompanhamento";
    });

    function validaJson(user){
        $.ajax({
        type: "GET",
        url: `src/to_do.php?iduser=${user}`,
        contentType: "application/json",
        success: function(response) {
            // console.log(response);
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
                // console.log('ok');
            }
        }
        })
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
                console.log(response);
                // var response = JSON.parse(response);
                // // console.log(response.erro);
                // if(response.erro == false){
                // // ainda nao sei oq colocar aqui
                // }
            }
        }); 
    }

    function alertaAdm(){
        // console.log(Cookies.get('alertadministrador'));
        if(Cookies.get('alertadministrador') == 'true'){
            return false; // faz nada
        }else{
        Swal.fire({
            title: "Cuidado!",
            text: "Como administrador você pode adicionar tarefas à outro usuário que não seja você!",
            icon: "warning"
        });
        Cookies.set('alertadministrador', 'true', { expires: 7 }) // expira em 7 dia
        }
    }
    

    async function fetchPendencias() {
      const url = new URLSearchParams(window.location.search);
      const id = url.get('id');
      var pendencias = await get_pendencias(id);
      return pendencias;
    }

    async function fetchDesenvolvimento() {
      const url = new URLSearchParams(window.location.search);
      const id = url.get('id');
      var in_progress = await get_desenvolvimento(id);
      return in_progress;
    }

    async function fetchFeitos() {
      const url = new URLSearchParams(window.location.search);
      const id = url.get('id');
      var feitos = await get_concluidos(id);
      return feitos;
    }



    async function buscaDadosSession(){
        return new Promise(function(resolve, reject) {
            $.ajax({
                type: "GET",
                url: 'src/dados_session.php',
                contentType: "application/json",
                success: function(response) {
                    // console.log(response);
                    resolve(response);
                }
            }); 
        });  
    }

    
    function load_acompanhamento(){

        $.ajax({
            url:"src/acompanhamento.php",
            method:"post",
            success:function(data){
                $('#acompanhamento').html(data);
            }
        });
    }


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

     

    function get_usuarios() {
        return new Promise(function(resolve, reject) {
            $.ajax({
                type: "GET",
                url: 'src/get_usuarios.php',
                contentType: "application/json",
                success: function(response) {
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
                }
            })
        })
    }


    function get_userid(user){
        return new Promise(function(resolve, reject) {
            $.ajax({
                type: "GET",
                url: 'src/get_id.php?username=' + user,
                contentType: "application/json",
                success: function(response) {
                    // console.log(response);
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
            get_userid(user).then(id => {
                window.location.href = 'index?id=' + id;
            })
        }
    });



    async function firstProcess(){
        var url = new URLSearchParams(window.location.search);
        var id = url.get('id');
        if (window.location.search.includes('id')) {
            
            const session = await buscaDadosSession();
            let json =  JSON.parse(session);
            var permissoesSession = json.permissoesSession;
            var usuarioSession = json.usuarioSession;

            permissoesSession = parseInt(permissoesSession);
            usuarioSession = parseInt(usuarioSession);

            id = parseInt(id); 
            // console.log(usuarioSession + typeof(usuarioSession))
            // console.log(permissoesSession + typeof(permissoesSession))

            if(usuarioSession == id){
                initKanban();
            }else{
                if(permissoesSession == 1 && usuarioSession == id){
                    initKanban();
                }else if(permissoesSession == 1 && usuarioSession != id){
                    initKanban();
                    alertaAdm();
                }else{
                    window.location.replace(`index?id=${usuarioSession}`);
                }
            }
        } else if (window.location.search.includes('cadastro') && url.get('cadastro') === 'padrao') {
            load_cadastro();
        } else if (window.location.search.includes('cadastro') && url.get('cadastro') === 'sequencial') {
            load_cadastro_sequencial();
        } else if(window.location.search.includes('acompanhamento')){
            alert('oi');
            load_acompanhamento();
        }
    }



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

      if (window.location.search.includes('id')) {
        var params = new URLSearchParams(window.location.search);
        var id = params.get('id');
        validaJson(id);
      }
      
    }


    firstProcess();
    
});