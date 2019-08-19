
<?php
include_once("services/usuario.php");

session_start();

$usuario = new usuario(); 


?>

<style>


</style>

<!DOCTYPE html>
<html lang="pt">

<?php
    //Cabeçalho
    include_once("head.php");
    
    //Menu
    include_once("header.php");
?>

<body>

<?php

//Verifica se tem a sessão
if ( isset( $_SESSION["timer_portal"] ) ) { 
	if ($_SESSION["timer_portal"] < time() ) { 
        session_destroy();		
        header('Location: login.php');
	} else {
		//Seta mais tempo para o timer
        $_SESSION["timer_portal"] = time() + 600;
        
	}
} else { 
    session_destroy();
    header('Location: login.php');	
	//Redireciona para login
}

/*
if(isset($_SESSION['ID_Perfil'] )){
    if($_SESSION['ID_Perfil'] != 4 and $_SESSION['ID_Perfil']  != 1){
        header('Location: index.php');	
    }
}*/
?>


<div class="wrapper">
    <!-- Sidebar  -->
    <?php
        include_once("sidebar.php");
    ?>

        <!-- Page Content  -->
    <div id="content">
    
        <h1 class="text-center">Usuários Portal Interno</h1>
        <div class="line"></div>

    <!--PESQUISAR POR ID-->
    <div class="row" style="display: inherit; margin-top: 40px">
        <div class="col-12">
                <form method="POST">                
                    <!--<label style="font-size: 18px;">Nome:</label>-->
                    <input type="hidden" name="cod_post">

                    <input type="text" name="nome" placeholder="Digite o nome para pesquisar" style="padding: 0%; width: 33%;height: 37px;border-radius: 5px;border: 1px solid #ced4da;">
                    <input type="text" name="email" placeholder="Digite o email para pesquisar" style="padding: 0%; width: 33%;height: 37px;border-radius: 5px;border: 1px solid #ced4da;">
                    <button name="SendPesqUser" id="SendPesqUser" class="btn btn-xs btn-primary"  value="Pesquisar"> Pesquisar</button>				 
                    <a href="cadastro_clientesInternos.php"><img src="public/img/add.ico" class='float-r m-r-10' alt="" style="width:30px"></a>

                </form>
            </div>
        </div>


        <!--TABELA LISTAR -->
    
        <div class="row" id="tabela_listar_rhs" STYLE="display: inherit;">
            <div class="col-md-12 table-striped table-responsive shadow p-3 mb-5 bg-white rounded">
                <table class="table">
                    <thead class=''>
                        <h4 class='text-center m-b-15' style="color:gray">Últimos 10 cadastrados</h4>
                    </thead>
                    <thead class="">
                    <tr>
                        <th>Nome</th>
                        <th>Login</th>
                        <th>E-mail</th>
                        <th>Senha</th>
                        <th>Status</th>
                        
                        
                        
                        <!--<th><a href=cadastrar_rhs.php><button type=button class='btn btn-xs btn-success' style='margin: 0px 6px 0px'>Solicitar</button></a></th>-->
                        
                        <?php
                            $SendPesqUser = filter_input(INPUT_POST, 'SendPesqUser', FILTER_SANITIZE_STRING);
                            if($SendPesqUser){
                                echo "<th>"; 
                                echo "<a href=index.php style='color: inherit'</a><button type=button class='btn btn-xs btn-dark'>Voltar</button>";
                                echo "</th>";
                                
                            }
                            
                        ?>
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                            
                    <!--Inicio Loop com pesquisar-->
                    <?php
                    $SendPesqUser = filter_input(INPUT_POST, 'SendPesqUser', FILTER_SANITIZE_STRING);
                    if($SendPesqUser){     
                        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
                        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);

                        if($nome == "" && $email == ""){
                            echo
                            "<script>
                                alert('Digite algo para pesquisar');
                                window.location.href=' index.php';
                            </script>";
                        } else{
                            $usuario->listar_usuarioInterno();
                        }
                    }

                    ?>	                
                    <!-- Inicio Loop sem pesquisar-->                 
                    <tr>
                    
                        <?php
                            if(!$SendPesqUser){                                   
                                $usuario->listar_usuarioInterno();        
                            }
                        ?>
                        
                        
                    </tr>  
                    
                    <?php
                    if(!empty($_GET['status'])){
                        if($_GET['status'] == 1){
                            ?>
                            <div id='div_fechar' class="alert alert-success alert-dismissible fade show" role="alert" style=padding:10px;font-size:14px;>
                            
                                Senha enviada com sucesso!
                                <div>
                                    <button id='botao_fechar' type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>   
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                    
                        
                    </tbody>
                </table>            
            </div>
        </div>
    </div>
</div>


    
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

<script type="text/javascript">

$(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });
});


$('#botao_fechar').click(function() {

    $("#div_fechar").fadeOut("slow");
    window.history.pushState("", "", "/PortalInterno/index.php");

});



</script>


</body>

</html>