<!DOCTYPE html>
<html lang="pt">
<head>
	<title>Autenticar</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
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

<?php
	if(empty($_GET['cod'])){

		header('Location: login.php');	
		
	}
?>
<body>	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">	
				<form method='POST' action="services/autenticar_usuario.php" class="login100-form validate-form" style="margin:0 auto; margin-bottom:146px;    margin-left: 245px;">
					<span class="login100-form-title">
						Validação de usuário
                    </span>
					<?php
                    if(!empty($_GET['status'])){						
						if($_GET['status'] == $_GET['status']){
							?>
							<div class="alert alert-success alert-dismissible fade show text-center" role="alert" style=padding:10px;font-size:14px;>
                            
								Senha = <?php echo  base64_decode($_GET['status']) ?></br>
								Para voltar clique aqui: <a href='http://localhost:8089/portal/portal_php/portal/login.php'> <i class="fas fa-undo"></i>	</a>
                                <div>
                                
                                </div>
							</div>
							<?php
						}
					}
					if(!empty($_GET['error'])){
						if($_GET['error'] == 2){
							?>
							<div class="alert alert-danger alert-dismissible fade show text-center" role="alert" style=padding:10px;font-size:14px;>
                            
								Código de acesso ou e-mail estão incorretos
                                <div>
                                
                                </div>
							</div>
							<?php
						}
                    }
                    ?>
					<div class="wrap-input100 validate-input" data-validate = "Digite o seu e-mail aqui">
						<input class="input100" type="text" name="email" placeholder="Seu e-mail">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
                    </div>                    
                    <div class="wrap-input100 validate-input" data-validate = "Digite o código aqui">
						<input class="input100" type="password" name="codigo_acesso" placeholder="Codigo de acesso">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
                    </div>  				
					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Confirmar
						</button>
					</div>
				</form>
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