<?php
include_once("services/usuario.php");
$usuario = new usuario(); 

?>

<!DOCTYPE html>
<html lang="pt">
<head>
	<title>Login</title>
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
	<link rel="icon" href="img/deal.png">

<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>

<style>
#BtnExterno:hover{
	color: #fff!important;
    background-color: black!important;
    border-color: black!important;

}

#BtnInterno:hover{
	color: white!important;

	background-color: #007bff!important;
    border-color: #007bff!important;
}

</style>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="container text-center">
						
				<?php
					if(!empty($_GET['error'])){
						if($_GET['error'] == 1){
							?>
							
							<div class="alert alert-danger alert-dismissible fade show" role="alert" style=padding:10px;font-size:16px;>
								Email ou senha estão incorretos!
							</div>
							<?php
						}
					
						if($_GET['error'] == 2){
							?>
							<div class="alert alert-danger alert-dismissible fade show" role="alert" style=padding:10px;font-size:17px;>
								Conta desativada, entre em contato com o departamento de TI!
							</div>
							<?php
						}
					}
                ?>
				</div>
				<div class="login100-pic js-tilt" data-tilt>
					<img src="img/logo_fres.jpg" alt="IMG">
				</div>
				
				<form method="POST" class="login100-form validate-form" >
			
					<span class="login100-form-title">
						Portal Interno
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Necessário um e-mail valido: ex@ex.com">
						<input class="input100" type="text" name="DE_Email" placeholder="Email">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>				

					<div class="wrap-input100 validate-input" data-validate = "Necessário digitar a senha">
						<input class="input100" type="password" name="DE_Senha" placeholder="Senha">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>

					
					
					<div class="container-login100-form-btn">
						<a class="txt2" href="#" style="display:none;">
								Esqueci minha senha
						</a>					
						<input class="login100-form-btn" type="submit" name="entrar" value="Entrar" style="cursor: pointer;margin-top: 20px;">

					</div>
					<div class="text-center p-t-136">
						<a class="txt2" href="http://www.fresadorasantana.com.br/site/index.html">
							VOLTAR
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
					<div class="text-center p-t-136" style="display:none;">
						<a class="txt2" href="cadastro.php">
							Crie uma conta
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
				</form>
				
				<?php
						$entrar = filter_input(INPUT_POST, 'entrar', FILTER_SANITIZE_STRING);
						if($entrar){
							$usuario->login();
							$usuario->getID();
							
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

		$('#BtnExterno').on('click', function () {
			window.location = "http://192.168.1.6:8089/portal/portal_php/portal/login.php";

		});
	</script>

	
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>