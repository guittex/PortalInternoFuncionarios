<?php
include_once("services/usuario.php");
$usuario = new usuario();

?>

<header class="cabecalho">	

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button type="button" id="sidebarCollapse" class="btn btn-light">
                <i class="fas fa-align-justify"></i>
                <span></span>
            </button>
            <img src="img/logo-fresadora.jpg" id="logo">
            <?php
            if(isset($_SESSION["Nome_portal"])) {
                    $logado = $_SESSION["Nome_portal"];
                    ?>                          
            <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-align-justify"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">               
                <ul class="nav navbar-nav ml-auto">
                <div class="dropdown">
                    <i class="fa fa-user"></i><span style="margin-left:10px;">Bem vindo <?php echo $logado ?> <i class="fas fa-angle-down"></i></span>
                    <div class="dropdown-content">
                        <a href="services/logoff_portal.php">sair</a>
                    </div>
                </div>
                </ul>               
            </div>
            <?php }?>
        </div>
    </nav>

</header>
