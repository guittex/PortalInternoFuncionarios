<?php
session_start();

include_once("services/sql_conexao.php");
include_once("services/usuario.php");

$usuario = new usuario();

$sql = new conexao();

//$email = base64_decode($_GET['mail']);

$idFornecedor = base64_decode($_GET['idFornecedor']);

?>
<!DOCTYPE html>
<html lang="pt-br">
<?php
    //Cabeçalho
    include_once("head.php");

    //Menu
    include_once("header.php");
?>		
<style>

</style>

<body>

<?php

//Verifica se tem a sessão
header("Refresh: 310; url = index.php");
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
?>


<div class="wrapper">

<?php

	include_once("sidebar.php");

?>

    <div class='container w-50' id="content">

        <h1 class="text-center">Enviar Mensagem</h1>
        <div class="line"></div>
        <?php
        if(!empty($_GET['status'])){
            if($_GET['status'] == 1){                
                ?>                
                <div id='div_fechar' class="alert alert-success alert-dismissible fade show text-center" role="alert" style=padding:10px;font-size:14px;>                
                    Enviado com sucesso!
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

        
            <form method="POST">
                <div class="form-row">
                    
                    <input type="hidden" name="cod_post" value="7">

                    <input type="hidden" id="idFornecedor" name="idFornecedor" value="<?php echo $idFornecedor ?>">

                    <input type="hidden" name="email" value="<?php echo $email;?>">
                    
                    <div class="form-group col-12">
                        <input type="text" class="form-control" name="assunto_mensagem" id="assunto_mensagem" placeholder="Assunto">
                    </div>

                    <div class="form-group col-12">
                        <textarea  type="text" class="form-control" name="corpo_mensagem" id="corpo_mensagem" placeholder="Digite a mensagem" style=height:200px;></textarea>
                    </div>
                    
                    </div>                          
                    <div class="text-center">                     
                        <input type="submit" class="btn btn-success w-25" id='enviar' value="Enviar">
                    </div>
                </div>
            </form>
                
            <?php 
                $enviar = filter_input(INPUT_POST, 'enviar', FILTER_SANITIZE_STRING);

                if ($enviar){
                    $usuario->msg_suporte();
                }

            ?>

        </div>
    </div>
</div>




<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

<script>

$(document).ready(function(){
    $("#botao_fechar").click(function(){
        
        $("#div_fechar").fadeOut("slow");
        window.history.pushState("", "", "/portal/portal_php/portal/suporte_msg.php");
        
    });
});

</script>


<script type="text/javascript">
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
    });
</script>

</body>

</html>