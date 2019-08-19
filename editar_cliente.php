<?php
    session_start();

    include_once("services/sql_conexao.php");

    include_once("services/usuario.php");

    $usuario = new usuario();

    $sql = new conexao();

    $sql->sql_conexao('portal');

    $id =  $_GET['ID_Usuario'];

    $perfil = 0;

    $result_usuario = "SELECT * FROM dbo.PT_Usuario WHERE ID_Usuario = '$id'";

    $resultado_usuario = sqlsrv_query ($sql->con, $result_usuario);

    $row_usuario = sqlsrv_fetch_array($resultado_usuario);
?>
<!DOCTYPE html>
<html lang="pt-br">
<?php
    //Cabeçalho
    include_once("head.php");

    //Menu
    include_once("header.php");
?>	

<body >

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

        <h1 class="text-center"><?php echo $row_usuario['DE_RazaoSocial']; ?></h1>
            <div class="line"></div>

            <form method="POST">
                <div class="form-row">
                    <input type="hidden" name="cod_post">

                    <input type="hidden" name="ID_Usuario" value="<?php echo $row_usuario['ID_Usuario'];?>">
                    
                    <div class="form-group col-md-12 col-sm-12">
                        <label>Nome</label>
                        <input type="text" class="form-control" name="DE_RazaoSocial" id="inputPassword4" value="<?php echo $row_usuario['DE_RazaoSocial'];   ?>">
                    </div> 
                    <div class="form-group col-md-12 col-sm-12">
                        <label>E-mail</label>
                        <input type="text" class="form-control" name="DE_Email" id="inputPassword4" value="<?php echo $row_usuario['DE_Email'];   ?>">
                    </div>
                    <div class="form-group col-md-12 col-sm-12">
                    <?php
                        /*if($row_usuario['ID_Perfil'] == 1){
                            $perfil = 'Administrador';
                        }else{
                            $perfil = 'Usuario';
                        }*/
                        //print_r('id_perfil='.$_SESSION['ID_Perfil'].'</br>');
                    ?>
                        <label>Tipo do Perfil</label>                    
                        <?php
                            $tipo_perfil = $row_usuario['ID_Perfil'];

                            if($tipo_perfil == 1){
                                $tipo_perfil = 'Administrador';

                            }elseif($tipo_perfil == 2){
                                $tipo_perfil = 'Fornecedor';

                            }else{
                                $tipo_perfil = 'Funcionario';

                            }
                            if($_SESSION['ID_Perfil'] == 0){
                                echo"<input type='text' class='form-control' name='ID_Perfil' id='inputPassword4' value=' " . $row_usuario['ID_Perfil'] . " '  placeholder='Password' > ";
                                    
                            }else{
                                echo "<input type=text class='form-control' name=ID_Perfil id=inputPassword4 value= " . $row_usuario['ID_Perfil'] . "  placeholder=Password hidden>";
                                echo "<input type=text class='form-control' value= " . $tipo_perfil . "  placeholder=Password readonly>";

                            }
                        ?>
                        <small id="emailHelp" class="form-text text-muted">1 para Administrador, 2 para Usuário ou 4 para Funcionario</small>
                    </div>
                    <div class="form-group col-md-12 col-sm-12">
                    <?php
                        /*if($row_usuario['IS_Ativo'] == 1){
                            $status = 'Ativo';
                        }else{
                            $status = 'Desativado';
                        }*/
                    ?>
                        <label>Status da Conta</label>
                        <?php
                            //print_r('</br>status ='.$row_usuario['IS_Ativo']);
                            $status = $row_usuario['IS_Ativo'];
                            if($status == 1){
                                $status = 'Ativado';
                            }else{
                                $status = 'Desativado';
                            }

                            if($_SESSION['ID_Perfil'] == 0){
                                echo "<input type='text' class='form-control' name='IS_Ativo' id='IS_Ativo' value=" . $row_usuario['IS_Ativo'] . " >";

                            }else{
                                echo "<input type='text' class='form-control' name='IS_Ativo' id='IS_Ativo' value=" . $row_usuario['IS_Ativo'] . " hidden>";
                                echo "<input type='text' class='form-control' value=" . $status ."  readonly>";

                            }
                        ?>
                        <small id="emailHelp" class="form-text text-muted">1 para Ativo ou  0 para Desativado</small>
                    </div>
                    <div class="form-group col-md-12 col-sm-12">
                        <label>Senha</label>                        
                        <?php

                            if($_SESSION['ID_Perfil'] == 0){
                                echo "<input type='text' class='form-control' name='DE_Senha' id='inputPassword4' value=" . $row_usuario['DE_Senha'] . " placeholder='Password'>";

                            }else{
                                echo "<input type='text' class='form-control' name='DE_Senha' id='inputPassword4' value=" . $row_usuario['DE_Senha'] . " placeholder='Password' hidden>";
                                echo "<input type='text' class='form-control' value='******'  placeholder='Password' readonly>";

                            }
                        ?>
                    </div>
                    </div>                          
                    <div class="text-center">           
                        <a href='index.php'><input class="btn btn-danger w-25" value="Voltar"></a>          
                        <input type="submit" class="btn btn-success w-25" name='editar' id='editar' value="Editar">
                    </div>
                </div>
            </form>                
            <?php 
                $editar = filter_input(INPUT_POST, 'editar', FILTER_SANITIZE_STRING);
                if ($editar){
                    $usuario->editarCliente();
                }
            ?>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
    });
</script>

</body>

</html>