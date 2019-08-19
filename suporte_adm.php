
<?php
session_start();

include_once("services/suporteController.php");

$suporte = new SuporteController();

$query =  $suporte->ListarChamadosAdm();

$idFornecedor = $_GET['idFuncionario'];

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
header("Refresh: 60");
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
        <h1 class="text-center">Painel de Suporte Adm</h1>
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
                        <button id='botao_fechar' type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                    </tr>
                </thead>
                <tbody id='corpoTabela'>
                    <?php
                        while($array = sqlsrv_fetch_array($query))
                        {
                    ?>
                        <tr>
                            <td>
                                <?php echo $array['id'] ?>
                            </td>
                            <td>
                                <?php echo $array['assunto'] ?>
                            </td>
                            <?php
                                if($array['situacao'] == 'Pendente'){
                            ?>
                                <td style="color:red">   
                            <?php }else{ ?>

                                <td style="color:green"> 
                            <?php } ?>                                 
                                    <?php    echo $array['situacao']; ?>                                    
                                </td>
                            <td>
                                <?php echo $array['criado'] ?>
                            </td>
                            <td>
                                <img src="public/img/ver.png" data-toggle="modal" data-target="#<?php echo $array['id'];?>" style="cursor:pointer">
                            </td>
                        </tr>

                        <!--Modal Vizualisar -->
                        <div class="modal fade" id="<?php echo $array['id'];?>" tabindex="-1" role="dialog" aria-labelledby="asda" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Chamado <?php echo $array['id']; ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form method="POST">
                                        <div class="modal-body">
                                            <input type="hidden" name="IdChamado" id="IdChamado" value="<?php echo $array['id'];?> ">
                                            <p class='text-black'><span style='font-weight:bold'>Assunto: </span><?php echo $array['assunto']; ?></p>
                                            <p class='text-black'><span style='font-weight:bold'>Mensagem: </span><?php echo $array['mensagem']; ?></p>
                                            <?php  if($array['situacao'] == 'Concluido'){ ?>
                                                <p class='text-black'><span style='font-weight:bold'>Resposta: </span><?php echo $array['resposta']; ?></p>
                                            <?php  }else{ ?>
                                            <p style='color: black;'><span style='font-weight:bold'>Resposta do Suporte: </span></p>
                                            <textarea class="form-control" id="resposta" name='resposta' rows="3"></textarea> 
                                            <?php } ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                                                <?php  if($array['situacao'] != 'Concluido'){ ?>
                                            <button type='submit' class='btn btn-success' id="BotaoEnviar" name="BotaoEnviar" value="BotaoEnviar">Enviar</button>
                                                <?php } ?>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php        
                        }
                    ?>
                </tbody>
            </table>
            <!-------Enviar COTAÇÃO PARA O CONTROLLER ----------------->
            <?php
            $EnviarSuporte = filter_input(INPUT_POST, 'BotaoEnviar', FILTER_SANITIZE_STRING);
            if($EnviarSuporte){
                $resposta = filter_input(INPUT_POST, 'resposta', FILTER_SANITIZE_STRING);
                $IdChamado = filter_input(INPUT_POST, 'IdChamado', FILTER_SANITIZE_STRING);

                $result = $suporte->RespostaChamadoAdm($resposta, $idFornecedor, $IdChamado);

                if($result == true){
                    echo '<script>window.location = "suporte_adm.php?idFuncionario='.$idFornecedor.'&status=1"; </script>' ;
                }else{
                    echo '<script>window.location = "suporte_adm.php?idFuncionario='.$idFornecedor.'&status=2"; </script>' ;

                }
            }

            ?>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>



<script type="text/javascript">
//ABRIR E FECHAR SIDEBAR
$(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });
});


//FECHAR NOTIFICAÇÃO DE MSG DE ALERTA  
$(document).ready(function(){
    $("#botao_fechar").click(function(){        
        $("#div_fechar").fadeOut("slow");
        window.history.pushState("", "", "/PortalInterno/suporte_adm.php?idFuncionario=<?php echo $idFornecedor ?> ");    
    });

    $('#pendentePesquisa').click(function() {
        idFornecedor = <?php echo $idFornecedor ?>,
        mail = '<?php echo $_SESSION['ID_Perfil'] ?>'
        console.log(idFornecedor, mail);

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
        mail = '<?php echo $_SESSION['ID_Perfil'] ?>'
        console.log(idFornecedor, mail);
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

</body>
</html>