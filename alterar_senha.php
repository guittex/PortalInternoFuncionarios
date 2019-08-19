<?php
include_once("services/usuario.php");

$usuario = new usuario(); 

if(!empty($_GET['id'])){
	$id = base64_decode($_GET['id']);
}

//print_r($id);

?>


<!DOCTYPE html>
<html lang="pt">
<head>
	<title>Cadastrar Senha</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>

<?php
	//Verifica se tem a sessão
	//header("Refresh: 310; url = index.php");
	if (empty( $_GET["id"] ) ) { 

		header('Location: login.php');	
		//Redireciona para login
	}
?>
<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="img/logo_fres.jpg" alt="IMG">
				</div>

				<form method="POST" class="login100-form validate-form" style=margin-bottom:100px>
				
				<?php
					if(!empty($_GET['erro'])){
						$id = $_GET['id'];
						if($_GET['erro'] == 1 and $_GET['id'] == $id){
							?>
							
							<div class="alert alert-danger alert-dismissible fade show text-center" role="alert" style=padding:10px;font-size:14px;>
								AS SENHAS NÃO SÃO IGUAIS!
							</div>
							<?php
						}
					}
					
                ?>
					<span class="login100-form-title">
						Crie sua senha
					</span>

					<input type="hidden" name="cod_post" value='5'>

					<div class="wrap-input100 validate-input" data-validate = "Necessário digitar a senha aqui">
						<input class="input100" type="password" name="senha" placeholder="Digite a senha">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>				

					<div class="wrap-input100 validate-input" data-validate = "Necessário Confirmar a senha aqui">
						<input class="input100" type="password" name="confirmar_senha" placeholder="Confirme a senha">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					
					<div class="container-login100-form-btn">											
						<input class="login100-form-btn" type="submit" name="entrar" value="criar" style="cursor: pointer;margin-top: 20px;">
					</div>				
				</form>				
				<?php
					$entrar = filter_input(INPUT_POST, 'entrar', FILTER_SANITIZE_STRING);
					if($entrar){
						$usuario->criar_senha($id);							
						
					}										
				?>
			</div>
		</div>
	</div>	

	
<!--===============================================================================================-->	
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>