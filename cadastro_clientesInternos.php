<?php
    include_once("services/usuario.php");

    session_start();

    $usuario = new usuario();

?>
<!DOCTYPE html>
<html>

<?php    include_once("head.php"); ?>

<?php    include_once("header.php"); ?>

<body>

<?php
    //Verifica se tem a sessão
    //header("Refresh: 310; url = listar_usuarios.php");
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
    
    if(!isset($_SESSION['ID_Perfil'] ) or $_SESSION['ID_Perfil'] != 0){
        header('Location: index.php');	
    }
?>
    <div class="wrapper">
        <!-- Sidebar  -->
        <?php
            include_once("sidebar.php");
        ?>
        <!-- Page Content  -->
        <div id="content">
            <h1 class="text-center">Cadastro Portal Interno</h1>
            <div class="line"></div>

                <form method="POST">
                    <div class="form-row w-100">

                        <input type="hidden" name="cod_post" value='8'>

                        <div class="col-md-12 col-sm-12">                        
                            <label>Tipo do Perfil</label>
                        </div> 
                        <div class="form-group col-12">
                            <?php
                                if($perfil != 0){ ?>
                                    <input type='text' class='form-control' name='Nivel' id='ID_Perfil' value=Fornecedor  readonly>
                            <?php }elseif($perfil == 0){ ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="Nivel" id="ID_Perfil" value="0">
                                    <label class="form-check-label" for="exampleRadios1">
                                        Administrador
                                    </label>     
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="Nivel" id="ID_Perfil" value="2" checked>
                                    <label class="form-check-label" for="exampleRadios1">
                                        Funcionário
                                    </label>     
                                </div>
                            <?php    }         ?>     
                        </div>  

                        <div class="form-group col-md-6 col-sm-6" >
                            <label>Nome</label>
                            <input type="text" class="form-control" name="Nome" id="Nome" placeholder='Exemplo: Julio' required>
                        </div>  
                        <div class="form-group col-md-6 col-sm-6" >
                            <label>Sobrenome</label>
                            <input type="text" class="form-control" name="Sobrenome" id="Sobrenome" placeholder='Exemplo: Silva' required>
                        </div>  
                        <div class="form-group col-md-6 col-sm-6" >
                            <label>Login</label>
                            <input type="text" class="form-control" name="usuarioEmail" id="usuarioEmail" placeholder='Exemplo: example.g' required>
                        </div>                          
                        <div class="form-group col-md-6 col-sm-6" >
                            <label>Ramal</label>
                            <input type="text" class="form-control" name="Ramal" id="Ramal" placeholder='Exemplo: 8418' >
                        </div>  
                        <div class="form-group col-md-12 col-sm-12">
                            <label>E-mail</label>
                            <input type="text" class="form-control" name="Email_Envio" id="Email_Envio" placeholder="email@fresadorasantana.com.br" required>
                        </div>                        
                        <div class="form-group col-md-12 col-sm-12">
                            <label>Senha</label>
                            <?php
                                if($perfil != 0){
                                    echo "<input type='password' class='form-control' name='Senha' value=senha@123 id='Senha' placeholder='Senha' readonly>";
                                }elseif($perfil == 0){ 
                                    echo "<input type='password' class='form-control' name='Senha' id='DE_Senha' placeholder='Senha' required>";
                                }
                            ?>                            
                        </div>
                        <div class="text-center" style='margin:0 auto'>                     
                            <button type="submit" name='cadastrarFuncionario' id='cadastrarFuncionario' class="btn btn-success">Cadastrar</button>
                        </div>
                    </div>
                </form>
                <?php
                    $cadastrar = filter_input(INPUT_POST, 'cadastrarFuncionario', FILTER_SANITIZE_STRING);
                    if ($cadastrar){
                        $usuario->cadastrarFuncionarios();
                    }
                ?>  
        </div>
    </div>

<!-- jQuery CDN - Slim version (=without AJAX) -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
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

    console.log($('input[name=Nivel]:checked').val())

</script>
</body>

</html>