<?php
session_start();

include_once("services/sql_conexao.php");
include_once("services/suporteController.php");

$suporte = new SuporteController();

$sql = new conexao();

$idFornecedor = base64_decode($_GET['idFornecedor']);

$query = $suporte->index($idFornecedor,'','');

//$email = base64_decode($_GET['mail']);

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

    <div class='container-fluid w-80' id="content">
        <h1 class="text-center">Suporte</h1>
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
            if($_GET['status'] == 2){                
                ?>                
                <div id='div_fechar' class="alert alert-danger alert-dismissible fade show text-center" role="alert" style=padding:10px;font-size:14px;>                
                    Erro ao enviar, entre em contato com o TI!
                    <div>
                        <button id='botao_fechar' type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>   
                    </div>
                </div>
                <?php
            }
        }
        ?>
            <table class="table table-hover" style='margin-top:30px;'>
                <thead>
                    <th colspan='6'>
                    <p style='font-weight:bold;color:black'>Filtrar por status: </p>
                    <button class="btn btn-warning" id="pendentePesquisa" style='color:white'>Pendente</button>
                    <button class="btn btn-success" id="concluidoPesquisa">Concluido</button>
                    <button class="btn btn-primary" id="todosPesquisa">Todos</button>
                    </th>
                </thead>
                <thead>
                    <tr>                        
                        <th scope="col">Chamado</th>
                        <th scope="col">Assunto</th>
                        <th scope="col">Situação</th>
                        <th scope="col">Criação</th>  
                        <th scope="col">Ação</th>
                        <a href="suporte_msg.php?idFornecedor=<?php echo base64_encode($id) ?>"><button class="btn btn-success m-b-15" style="float:right">Adicionar Chamado</button></a>
                    </tr>
                </thead>
                <tbody id="corpoTabela">                         
                    <?php while($array = sqlsrv_fetch_array($query)){ ?>
                        <tr>  
                            <?php $idChamado = $array['id'] ?>
                            <td><?php echo $array['id']; ?></td>
                            <td><?php echo $array['assunto']; ?></td>
                            <td>
                                <?php 
                                    if($array['situacao'] == "Pendente"){
                                        echo "<p style=color:red>".$array['situacao']."</p>";
                                    }
                                    if($array['situacao'] == "Concluido"){
                                        echo "<p style=color:green>".$array['situacao']."</p>";
                                    } 
                                ?>
                            </td>
                            <td><?php echo date('d-m-Y h:i', strtotime($array['criado'])); ?></td>
                            <td>
                                <a href="services/suporteController.php?idChamadoSuporteController=<?php echo $idChamado ?>&idFornecedor=<?php echo $idFornecedor ?>&mail=<?php echo $email ?>"><button type="button" class="btn btn-danger">Apagar</button></a>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?php echo $array['id'];?>">Ver</button>
                            </td>

                        </tr>
                        <!--Modal Vizualisar -->
                        <div class="modal fade" id="<?php echo $idChamado;?>" tabindex="-1" role="dialog" aria-labelledby="asda" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Chamado <?php echo $idChamado ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p class='text-black'><span style='font-weight:bold'>Assunto: </span><?php echo $array['assunto']; ?></p>
                                        <p class='text-black'><span style='font-weight:bold'>Mensagem: </span><?php echo $array['mensagem']; ?></p>
                                        <p style='color: black;padding: 20px;border: 1px solid #6c757d4d;border-radius: 15px;'><span style='font-weight:bold'>Resposta do Suporte: </span>
                                        <?php 
                                            if($array['resposta'] == NULL){
                                                echo "Aguarde a resposta do atendente";
                                            }else{
                                                echo $array['resposta'];
                                            } 
                                        ?>                                        
                                        </p>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </tbody>
            </table>        
        </div>
    </div>


    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

<script>

$(document).ready(function(){
    $("#botao_fechar").click(function(){
        
        $("#div_fechar").fadeOut("slow");
        //window.history.pushState("", "", "/portal/portal_php/portal/suporte_msg.php");
        
    });

    $('#pendentePesquisa').click(function() {
        idFornecedor = <?php echo $idFornecedor ?>,
        mail = '<?php echo $email ?>'
        $.ajax({
            type: "POST",
            url: "services/suporteController.php",
            data: { situacao : "Pendente", "idFornecedor" : idFornecedor, 'mail' : mail }
            }).done(function( msg ) {
                //alert( "Data Saved: " + msg );
                $("#corpoTabela").html(msg);
            });    
    });

    $('#concluidoPesquisa').click(function() {
        idFornecedor = <?php echo $idFornecedor ?>,
        mail = '<?php echo $email ?>'
        $.ajax({
            type: "POST",
            url: "services/suporteController.php",
            data: { situacao : "Concluido", "idFornecedor" : idFornecedor, 'mail' : mail }
            }).done(function( msg ) {
                //alert( "Data Saved: " + msg );
                $("#corpoTabela").html(msg);
            }); 
    });

    $('#todosPesquisa').click(function() {
        location.reload();
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