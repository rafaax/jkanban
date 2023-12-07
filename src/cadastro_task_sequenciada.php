<?php require 'conexao.php';?>

<div class="card">
    <div class="card-header">
        Cadastro de Tarefa
        <div style="text-align: right;">
            
        </div>
    </div>
    <div class="card-body">
        <form id="form_cadastro" method="post">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="tarefa" class="control-label mb-1">Titulo da Tarefa</label>
                        <input id="tarefa" name="tarefa" class="form-control"
                        type="text" aria-required="true" aria-invalid="false" placeholder="Tarefa" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="ptc" class="control-label mb-1">Identificador do PTC</label>
                        <input id="ptc" name="ptc" class="form-control"
                        type="text" aria-required="true" aria-invalid="false" placeholder="PTC" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="prioridade" class="control-label mb-1">Prioridade</label>
                        <?php 
                        $sql = "SELECT id, prioridade from prioridade order by id asc ";
                        $query = mysqli_query($conexao, $sql);
                        ?>
                        <select name="prioridade" id="prioridade" class="form-control">
                            <?php 
                            while($array = mysqli_fetch_assoc($query)){
                                $prioridade = $array["prioridade"];
                                $prioridade_id = $array["id"];
                                echo "<option value='$prioridade_id'>$prioridade</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <label for="multiple-select-field" class="control-label mb-1">Atribuir para:</label>
                    <select class="form-select" name="usuarios[]" id="multiple-select-field" data-placeholder="Usuários" multiple>
                        <?php 
                        $sql = "SELECT * from usuarios order by nome asc"; 
                        $query = mysqli_query($conexao, $sql);

                        while($array = mysqli_fetch_array($query)){
                            $login = $array['login'];
                            $usuario_id = $array['id'];
                            echo "<option value='$usuario_id'> $login </option>";
                        }
                        ?>
                    </select>
                </div>  
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="descricao" class="control-label mb-1">Descrição da tarefa</label>
                        <input id="descricao" name="descricao" class="form-control"
                        type="text" aria-required="true" aria-invalid="false" placeholder="Descrição" required>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="data_entrega" class="control-label mb-1">Data da entrega da tarefa</label>
                        <input id="data_entrega" name="data_entrega" type="date" class="form-control" required>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="tempo_entrega" class="control-label mb-1"></label>
                        <input id="tempo_entrega" name="tempo_entrega" type="time" class="form-control">
                    </div>
                </div>
                <p>
            </div>

            <div id="plus"></div>

            <span class="btn btn-lg btn-info btn-warning" id="adicionarStep">
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" height="16" width="14" viewBox="0 0 448 512">
                        <path d="M416 208H272V64c0-17.7-14.3-32-32-32h-32c-17.7 0-32 14.3-32 32v144H32c-17.7 0-32 14.3-32 32v32c0 17.7 14.3 32 32 32h144v144c0 17.7 14.3 32 32 32h32c17.7 0 32-14.3 32-32V304h144c17.7 0 32-14.3 32-32v-32c0-17.7-14.3-32-32-32z"/>
                    </svg>
                </span>
            </span> 
            <p>
            
            <div>
                <button id="payment-button" type="submit" class="btn btn-lg btn-success">
                    <span>Cadastrar</span>
                </button>
                <div style="text-align: right;">
                    <a href="index">
                        <span class="btn btn-sm btn-info btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M11.5 280.6l192 160c20.6 17.2 52.5 2.8 52.5-24.6V96c0-27.4-31.9-41.8-52.5-24.6l-192 160c-15.3 12.8-15.3 36.4 0 49.2zm256 0l192 160c20.6 17.2 52.5 2.8 52.5-24.6V96c0-27.4-31.9-41.8-52.5-24.6l-192 160c-15.3 12.8-15.3 36.4 0 49.2z"/></svg>
                        </span>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>

    $(document).ready(function(){

    var formLine = 1;

    function addInput(divName) {
        var hr = $('<hr>');
        var p = $('<p>');
        var title = $('<h5>Proximo passo '+ formLine + '</h2>');

        var newdiv = $('<div>', { class: 'row' });
        var col1 = $('<div>', { class: 'col-6' });
        col1.append('<div class="form-group">' +
                        '<label for="tarefa' + formLine + '" class="control-label mb-1">Titulo da Tarefa</label>' +
                        '<input id="tarefa' + formLine + '" name="tarefa' + formLine + '" class="form-control" type="text" aria-required="true" aria-invalid="false" placeholder="Tarefa" required>' +
                    '</div>');

        var col2 = $('<div>', { class: 'col-6' });
        col2.append('<div class="form-group">' +
                        '<label for="ptc' + formLine + '" class="control-label mb-1">Identificador do PTC</label>' +
                        '<input id="ptc' + formLine + '" name="ptc' + formLine + '" class="form-control" type="text" aria-required="true" aria-invalid="false" placeholder="PTC" required>' +
                    '</div>');

        var col3 = $('<div>', { class: 'col-6' });
        col3.append('<div class="form-group">' +
            '<label for="prioridade' + formLine + '" class="control-label mb-1">Prioridade</label>' +
            '<select name="prioridade' + formLine + '" id="prioridade' + formLine + '" class="form-control">' 
                <?php $sql = "SELECT id, prioridade from prioridade order by id asc ";
                $query = mysqli_query($conexao, $sql);
                while($array = mysqli_fetch_assoc($query)){ 
                    $prioridade = $array["prioridade"];
                    $prioridade_id = $array["id"];
                    ?> + '<option value="<?=$prioridade_id?>"><?=$prioridade?></option>' 
                <?php } ?> +
            '</select></div>');

        var col4 = $('<div>', { class: 'col-6' });
        col4.append('<label for="multiple-select-field' + formLine + '" class="control-label mb-1">Atribuir para:</label>' +
            '<select class="form-select" name="usuarios' + formLine +'" id="multiple-select-field' + formLine + '" data-placeholder="Usuários">' 
                <?php $sql = "SELECT * from usuarios order by nome asc"; 
                $query = mysqli_query($conexao, $sql); 
                while($array = mysqli_fetch_array($query)){ 
                    $login = $array["login"]; 
                    $usuario_id = $array["id"];
                    ?> + '<option value="<?=$usuario_id?>"> <?=$login?></option>' 
                <?php } ?> + '</select></div>');
    

        
        newdiv.append(hr, title,  col1, col2, col3, col4, p);

        $('#' + divName).append(newdiv);
        formLine++;
    }

    $('#adicionarStep').on("click", function(){
        addInput('plus');
    });

    $('#multiple-select-field' ).select2( {
        theme: "bootstrap-5",
        width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
        placeholder: $( this ).data( 'placeholder' ),
        closeOnSelect: false,
    });

    $('#form_cadastro').on("submit", function(event){
            console.log(event);
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "src/insert_task.php",
                data: new FormData(this),
                contentType: false,
                processData: false,
                beforeSend: function () {
                    Swal.fire({
                        title: 'Aguarde...',
                        text: 'Cadastrando...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function (result) {
                    console.log(result);
                    var json = JSON.parse(result);
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
            })
        });
    });