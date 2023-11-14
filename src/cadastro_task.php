<?php require 'conexao.php';?>

<div class="card">
    <div class="card-header">Cadastro de Compra</div>
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
                    <select class="form-select" name="usuarios[]" id="multiple-select-field" data-placeholder="Pessoas" multiple>
                        <?php 
                        $sql = "SELECT login from usuarios"; 
                        $query = mysqli_query($conexao, $sql);

                        while($array = mysqli_fetch_array($query)){
                            $login = $array['login'];
                            echo "<option> $login </option>";
                        }
                        ?>
                    </select>
                </div>
                
                    
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="data_entrega" class="control-label mb-1">Data da entrega da tarefa</label>
                        <input id="data_entrega" name="data_entrega" type="date" class="form-control" required>
                    </div>
                </div>
                <p>
            </div>
            
            <div>
                <button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block">
                    <span>Cadastrar</span>
                    <i class="zmdi zmdi-check"></i>&nbsp;
                </button>
            </div>
        </form>
    </div>
</div>

<script>

    $(document).ready(function(){

    $( '#multiple-select-field' ).select2( {
        theme: "bootstrap-5",
        width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
        placeholder: $( this ).data( 'placeholder' ),
        closeOnSelect: false,
    } );

    $('#form_cadastro').on("submit", function(event){
            event.preventDefault();
            let formulario = new FormData(this);
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
                        Swal.fire({
                            title: 'Compra cadastrada!',
                            html: 'A página se auto-reiniciará em 3 segundos.',
                            icon: 'success',
                            didOpen: () => {
                                Swal.showLoading()
                            },
                        })
                        setTimeout(function() {
                            window.location.href = "http://127.0.0.1/estoque_git/compras"
                        }, 3000)
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