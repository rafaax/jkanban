<?php require 'conexao.php';?>


<div class="card">
    <div class="card-header">Cadastro de Compra</div>
    <div class="card-body">
        <form id="form_cadastro" method="post">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="nome_produto" class="control-label mb-1">Titulo da Tarefa</label>
                        <input id="nome_produto" name="nome_produto" class="form-control"
                        type="text" aria-required="true" aria-invalid="false" placeholder="Nome do produto" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="quantidade" class="control-label mb-1">Prioridade</label>
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
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="data_compra" class="control-label mb-1">Data de Compra</label>
                        <input id="data_compra" name="data_compra" type="date" class="form-control" required>
                    </div>
                </div>
                <div class="col-6">
                    <div id="previsao-entrega" class="ifpagamento_remoto">
                        <div class="form-group">
                            <label for="previsao_entrega" class="control-label mb-1">Previsão de entrega</label>
                            <input id="previsao_entrega" name="previsao_entrega" type="date" class="form-control">
                        </div>
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

    $('#form_cadastro').on("submit", function(event){
            event.preventDefault();

            $.ajax({
                method: "POST",
                url: "funcoes/compras/backend_cadastro.php",
                data: new FormData(this),
                contentType: false,
                processData: false,
                beforeSend: function () {
                    Swal.fire({
                        title: 'Aguarde...',
                        text: 'Cadastrando evento...',
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