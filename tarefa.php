<?php 
require 'src/conexao.php';
require 'src/validacao.php';

function LocationIndex(){
	header('Location: index');
}

function getId(){
	if(isset($_GET['id'])){
		if(is_numeric($_GET['id'])){
			return $_GET['id'];
		}else{
			LocationIndex();
		}
	}else{
		LocationIndex();
	}
}

$idGet = getId();

$sql = "SELECT tc.tarefa_id, tc.usuario_tarefa, tc.titulo, tc.data_criada, tc.data_final,
	(SELECT prioridade from prioridade where id = tc.prioridade) as prioridade,  
	(SELECT CONCAT(`nome`, ' ', `sobrenome`) FROM usuarios where id = tc.criado_por) as criador,
	(SELECT email from usuarios where id = tc.criado_por) as criador_email
	from tarefas_criadas tc where tarefa_id = '$idGet' LIMIT 1";
$query = mysqli_query($conexao, $sql);
if(mysqli_num_rows($query) > 0){
	$array = mysqli_fetch_array($query);
	if($array['usuario_tarefa'] !== $usuarioSession){
		exit();
	}
	$titulo_tarefa = $array['titulo'];
	$prioridade = $array['prioridade'];
	$criado_por = $array['criador'];
	$data_final = $array['data_final'];
	$criador_email = $array['criador_email'];
}else{
	LocationIndex();
}


?>
<!doctype html>
<html lang="pt-br">
  <head>
  	<title>Contestar pendência</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/css/style2.css">
	</head>
	<body>
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-10 col-md-12">
					<div class="wrapper">
						<div class="row no-gutters">
							<div class="col-md-7 d-flex align-items-stretch">
								<div class="contact-wrap w-100 p-md-5 p-4">
									<tr>
										<td align="center" valign="top" style="border-collapse:collapse;border-spacing:0;margin:0;padding:0;padding-left:6.25%;padding-right:6.25%;width:87.5%;font-size:24px;font-weight:700;line-height:130%;padding-top:25px;color:#000;font-family:sans-serif"
											class="header"><a target="_blank" style="text-decoration:none" href="https://engedoc.com.br/calendario"><img border="0" vspace="0" hspace="0" src="https://engedoc.com.br/images/engedoc_logo.png" width="200" height="50" alt="Logo" title="Logo" style="color:#000;font-size:10px;margin:0;padding:0;outline:0;text-decoration:none;-ms-interpolation-mode:bicubic;border:none;display:block"></a></td>
									</tr>
									<br>
									<form id="contest_task">
										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
                                                    Redirecionar tarefa para:
													<select class="form-control" name="usuario" id="multiple-select-field">
                                                        <?php 
                                                        
                                                        $sql = "SELECT * from usuarios order by nome asc"; 
                                                        $query = mysqli_query($conexao, $sql);
                                                        echo '<option>Apenas cancelar</option>';
                                                        while($array = mysqli_fetch_array($query)){
                                                            $login = $array['login'];
                                                            $usuario_id = $array['id'];
                                                            echo "<option value='$usuario_id'> $login </option>";
                                                        }
                                                        ?>
                                                    </select>
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group">
													<textarea name="message" class="form-control" id="message" cols="30" rows="7" placeholder="Mensagem"></textarea>
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group">
													<input type="submit" value="Enviar contestação" class="btn btn-primary">
													<div class="submitting"></div>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="col-md-5 d-flex align-items-stretch">
								<div class="info-wrap bg-primary w-100 p-lg-5 p-4">
									<h3 class="mb-4 mt-md-4">Informações</h3>
				        	<div class="dbox w-100 d-flex align-items-start">
				        		<div class="icon d-flex align-items-center justify-content-center">
				        			<span class="fa fa-envelope"></span>
				        		</div>
				        		<div class="text pl-3">
					            <p><span>Tarefa:</span> <?=$titulo_tarefa?></p>
					          </div>
				          </div>
				        	<div class="dbox w-100 d-flex align-items-center">
				        		<div class="icon d-flex align-items-center justify-content-center">
				        			<span class="fa fa-exclamation"></span>
				        		</div>
				        		<div class="text pl-3">
					            <p><span>Prioridade:</span> <?=$prioridade?></p>
					          </div>
				          </div>
				        	<div class="dbox w-100 d-flex align-items-center">
				        		<div class="icon d-flex align-items-center justify-content-center">
				        			<span class="fa fa-paper-plane"></span>
				        		</div>
				        		<div class="text pl-3">
					            <p><span>Pedido por:</span> <a href="mailto:<?=$criador_email?>"><?=$criado_por?></a></p>
					          </div>
				          </div>
				        	<div class="dbox w-100 d-flex align-items-center">
				        		<div class="icon d-flex align-items-center justify-content-center">
				        			<span class="fa fa-calendar"></span>
				        		</div>
				        		<div class="text pl-3">
					            <p><span>Data Final:</span> <?=date('d/m/Y H:i:s', strtotime($data_final))?></p>
					          </div>
				          </div>
			          </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	</body>
</html>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js//contest-task.js"></script>


