<?php
    session_start();

    include_once("services/sql_conexao.php");

    include_once("services/usuario.php");

    $usuario = new usuario();

    $sql = new conexao();

    $sql->sql_conexao('CPD');

    $id =  base64_decode($_GET['id']);

    $result_usuario = "SELECT * FROM dbo.TB_Usuario WHERE ID_Usuario = '$id' ";

    $resultado_usuario = sqlsrv_query ($sql->con, $result_usuario);

    if(!empty($resultado_usuario)){
        $row_usuario = sqlsrv_fetch_array($resultado_usuario);
    }
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

        <h1 class="text-center">Meus Dados</h1>
        <div class="line"></div>
        <?php
        if(!empty($_GET['status'])){
            if($_GET['status'] == 1){                
                ?>                
                <div id='div_fechar' class="alert alert-success alert-dismissible fade show text-center" role="alert" style=padding:10px;font-size:14px;>
                
                    Alterado com sucesso!

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

                    <input type="hidden" name="cod_post" value="2">

                    <input type="hidden" name="ID_Usuario" value="<?php echo base64_encode($row_usuario['ID_Usuario']);?>">
                    
                    <div class="form-group col-12">
                        <label>Senha</label>
                        <input type="password" maxlength='10' class="form-control" name="DE_Senha" id="inputPassword4" value="<?php echo $row_usuario['Senha'];   ?>" placeholder="Password">
                        <span style='color:gray;font-size:13px'>Máximo 10 digitos</span>
                    </div>
                    </div>                          
                    <div class="text-center">                     
                        <input type="submit" class="btn btn-success w-25" id='editar' value="Editar">
                    </div>
                </div>
            </form>                
            <?php 
                $editar = filter_input(INPUT_POST, 'editar', FILTER_SANITIZE_STRING);
                if ($editar){
                    $usuario->editarMeusDados();
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
            window.history.pushState("", "", "/PortalInterno/editar_dados.php?id=<?php echo $id ?>");
            
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