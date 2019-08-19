<?php
session_start();

include_once('services/FeedBackController.php');

$Feedback = new Feedback();

$idFornecedor =  base64_decode($_GET['idFornecedor']);


?>

<!DOCTYPE html>
<html lang="pt-br">

<?php
    //Cabeçalho
    include_once("head.php");
    //Menu
    include_once("header.php");
?>	
<body>

<?php
    //Verifica se tem a sessão
    //header("Refresh: 310; url = index.php");
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
        <h1 class="text-center">
            Feedback       
        </h1>
        <div class="line">
        
        </div>  
        <?php if(!empty($_GET['status'])){ ?>
        <div class="row">
            <div class="col-12">
                <h2 class='text-center' style="color:cornflowerblue">Obrigado pelo seu Feedback =)</h2>
            </div>
        </div>
        <?php }else{ ?>
        <form type="GET">
            <div class="row">
                <input type="hidden" name="idFornecedor" value="<?php echo $idFornecedor ?> ">
                <div class="col-12">
                    <textarea type="text" name="Mensagem" placeholder="Escreva seu comentário sobre o sistema aqui" class="form-control lh-200"></textarea>
                </div>
                <div class="col-12 text-center m-t-15">
                    <button class="btn btn-success" name="SalvarFeeback" id="SalvarFeeback" value="SalvarFeeback">Enviar</button>
                </div>
            </div>        
        </form>  
        <?php } ?>      
    </div>
</div>
<?php
$SalvarFeeback = filter_input(INPUT_GET, 'SalvarFeeback', FILTER_SANITIZE_STRING);

if($SalvarFeeback){
    $idFornecedor = $_GET['idFornecedor'];
    $mensagem = $_GET['Mensagem'];
    
    $Feedback->SalvarFeeback($idFornecedor,$mensagem);

    echo '<script>window.location.replace("http://192.168.1.6:8089/PortalInterno/feedback.php?status=1&idFornecedor='.base64_encode($idFornecedor).' ");</script>';

    //echo '<script>window.history.pushState("", "", "/portal/portal_php/portal/feedback.php?status=1&idFornecedor='.base64_encode($idFornecedor).' ");</script>';

}

?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

<script>
//Abre e fecha SIDEBAR


$(document).ready(function () {

    $( "#fecharMsg" ).click(function() {
        $("#containerMsg").fadeOut();
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