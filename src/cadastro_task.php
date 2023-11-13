<style>

</style>
<div class="card">
    <div class="card-header">Cadastro de Compra</div>
    <div class="card-body">
        <form id="form_cadastro" method="post">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="nome_produto" class="control-label mb-1">Nome Produto</label>
                        <input id="nome_produto" name="nome_produto" class="form-control"
                        type="text" aria-required="true" aria-invalid="false" placeholder="Nome do produto" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="partnumber_produto" class="control-label mb-1">PartNumber</label>
                        <input id="partnumber_produto" name="partnumber_produto" type="text" class="form-control"
                        aria-required="true" aria-invalid="false" placeholder="PartNumber do produto" required> 
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="quantidade" class="control-label mb-1">Quantidade</label>
                        <input id="quantidade" name="quantidade" class="form-control"
                        type="number" aria-required="true" aria-invalid="false" min="1" placeholder="Quantidade" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                </div>
            </div>

            <div class="form-group div-parcelas" id="div-parcelas">
                <label for="parcelas" class="control-label mb-1">Parcelas</label>
                <input id="parcelas" name="parcelas" type="number" class="form-control" 
                placeholder="Digite o total de parcelas da compra" min="1" max="24">
            </div>

            <div class="form-group">
                <label for="pagamento_formato" class="control-label mb-1">Pagamento</label>
                <select name="pagamento_formato" id="pagamento_formato" class="form-control">
                    <option value="pagamento_presencial">Presencial</option>
                    <option value="pagamento_remoto">Internet</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="preco_unitario" class="control-label mb-1">Preço unitário</label>
                <input id="preco_unitario" name="preco_unitario" type="text" class="form-control" min="0"
                placeholder="Digite o preço unitário do produto" required>
            </div>


            <div id="div-frete-imposto" class="ifpagamento_remoto">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="frete" class="control-label mb-1">Frete</label>
                            <input id="frete" name="frete" type="text" class="form-control" min="0"
                            placeholder="Digite o valor do frete">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="imposto" class="control-label mb-1">Imposto</label>
                            <input id="imposto" name="imposto" type="text" class="form-control"  min="0"
                            placeholder="Digite o valor do imposto">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="site" class="control-label mb-1">Site</label>
                    <input id="site" name="site" type="text" class="form-control"
                    aria-required="true" aria-invalid="false" placeholder="Site que foi comprado"> 
                </div>
            </div>
            <div class="form-group">
                <label for="descricao" class=" form-control-label">Descrição</label>
                <textarea name="descricao" id="descricao" rows="5" placeholder="Descricao do produto..." class="form-control"></textarea>
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