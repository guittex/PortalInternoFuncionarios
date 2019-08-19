
<?php
include_once("services/ItemController.php");

session_start();

$IdItem = $_GET['IdItem'];

$ItemController = new ItemController();

$query = $ItemController->GetItem($IdItem);


//echo $_SESSION['editarItem'];

if(!isset($_SESSION['editarItem']) or $_SESSION['editarItem'] == 0 and $_SESSION['ID_Perfil'] != 0){
    header('Location: index.php');

};


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
    //header("Refresh: 310; url = item.php");
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
    <?php	include_once("sidebar.php");    ?>        
        <div class='container w-100' id='content' >   
            <h1 class="text-center">
                Editar Item
            </h1>            
            <div class="line"></div>
            <?php
            if(!empty($_GET['status'])){
                if($_GET['status'] == 1){                    
                    ?>                    
                    <div id='div_fechar' class="alert alert-<?php echo $_GET['class']; ?> alert-dismissible fade show text-center" role="alert" style=padding:10px;font-size:14px;>                    
                        <?php echo $_GET['msg']; ?>
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
            <form action="services/ItemController.php" method="POST">
                <input type="hidden" name='codEditarItem' value='1'>
                <input type="hidden" name='idItem' value=<?php echo $IdItem ?> >
                <?php while($array = sqlsrv_fetch_array($query)){
                    $EstoqueAtual = substr($array['Estoque_Atual'], 0, -5);
                    $EstoqueMin = substr($array['Estoque_Minimo'], 0, -5);
                    $EstoqueMax = substr($array['Estoque_Maximo'], 0, -5);?>

                    <!-- <div class="row text-center">                    
                        <div class="col-12 m-t-13">
                            <span class='bold' style="color:gray">Opções: </span>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="options" id="<?php echo $array['PROD_GRUPO']; ?>" value="<?php echo $array['PROD_GRUPO']; ?>" checked>
                                <label class="form-check-label"><?php echo $array['PROD_GRUPO']; ?></label>
                            </div>         
                        </div>
                    </div>-->
                    <div class="row ">
                        <div class="col-2 float-r" id='colunaAtivo'>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ativo" name='ativo' value= <?php echo $array['ativo']; ?>
                                    <?php 
                                        if($array['ativo'] == 1){ 
                                            echo 'checked '; 
                                        }else{
                                            '';
                                        }
                                    ?>                                     
                                >
                                <label class="form-check-label">
                                    Ativo                                   
                                </label>
                            </div>
                        </div>
                        <div class="col-5 float-r ">
                            <div class="form-check ">
                                <input class="form-check-input" type="checkbox" id="compraSimultania" name="compraSimultania" value=<?php echo $array['compraSimultania']; ?> 
                                    <?php 
                                        if($array['compraSimultania'] == 1){ 
                                            echo 'checked'; 
                                        }else{
                                            '';
                                        } 
                                    ?>                                 
                                >
                                <label class="form-check-label">
                                    Permitir Compra Simultânea
                                </label>
                            </div>
                        </div>
                    </div> 
                    
                    <div class="row">                    
                        <div class="col-12 m-t-30">
                            <span>Descrição</span>
                            <input  class="form-control br-10" type="text" name="Descricao" value="<?php echo $array['Descricao']; ?>" >
                        </div>
                        <div class="col-4 m-t-10">
                            <span>Modelo</span>
                            <input  class="form-control br-10" type="text" name="Modelo" value="<?php echo $array['Modelo']; ?>" >
                        </div>
                        <div class="col-4 m-t-10">
                            <span>Codigo Fabricante</span>
                            <input  class="form-control br-10" type="text" name="CodFabricante" value="<?php echo $array['Cod_Fabricante']; ?>" >
                        </div>
                        <div class="col-4 m-t-10">
                            <span>NCM</span>
                            <input  class="form-control br-10" type="text" maxlength='8' name="NCM" value="<?php echo $array['NCM']; ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 m-t-40">
                            <h5 class=text-center>Informações de Compra</h5>
                        </div>
                        <div class="line"></div>
                        <div class="col-4">
                            <span>Unidade Compra</span>
                            <select class="form-control br-10" name="UnidCompra" id="">
                                <option value="<?php echo $array['Unid_Compra'] ?>" selected><?php echo $array['Unid_Compra'] ?></option>
                                <option value="BARRA">BARRA</option>
                                <option value="CJ">CJ</option>
                                <option value="CX">CX</option>
                                <option value="GL">GL</option>
                                <option value="GRAMA">GRAMA</option>
                                <option value="HR">HR</option>
                                <option value="JOGO">JOGO</option>
                                <option value="KG">KG</option>
                                <option value="LT">LT</option>
                                <option value="MM">MM</option>
                                <option value="MT">MT</option>
                                <option value="MT2">MT2</option>
                                <option value="MT3">MT3</option>
                                <option value="PC">PC</option>
                                <option value="PÇ">PÇ</option>
                                <option value="PCT">PCT</option>
                                <option value="PEÇA">PEÇA</option>
                                <option value="PEÇAS">PEÇAS</option>
                                <option value="UNID">UNID</option>                                
                            </select>
                        </div>
                        <div class="col-4 ">
                            <span>Unidade Consumo</span>
                            <select class="form-control br-10" name="UnidConsumo" id="">
                                <option value="<?php echo $array['Unid_Consumo'] ?>" selected><?php echo $array['Unid_Consumo'] ?></option>
                                <option value="BARRA">BARRA</option>
                                <option value="CJ">CJ</option>
                                <option value="CX">CX</option>
                                <option value="GL">GL</option>
                                <option value="GRAMA">GRAMA</option>
                                <option value="HR">HR</option>
                                <option value="JOGO">JOGO</option>
                                <option value="KG">KG</option>
                                <option value="LT">LT</option>
                                <option value="MM">MM</option>
                                <option value="MT">MT</option>
                                <option value="MT2">MT2</option>
                                <option value="MT3">MT3</option>
                                <option value="PC">PC</option>
                                <option value="PÇ">PÇ</option>
                                <option value="PCT">PCT</option>
                                <option value="PEÇA">PEÇA</option>
                                <option value="PEÇAS">PEÇAS</option>
                                <option value="UNID">UNID</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <span>Qtde por Embalagem</span>
                            <input  class="form-control br-10" type="text" name="QtdeEmbalagem" value="<?php echo substr($array['Qtde_Por_Embalagem'], 0, -5) ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 m-t-40">
                            <h5 class=text-center>Quantidade em Estoque</h5>
                        </div>
                        <div class="line"></div>
                        <div class="col-4">
                            <span>Quantidade Atual</span>
                            <input  class="form-control br-10" type="number" name="QtdeAtual" value="<?php echo $EstoqueAtual; ?>" required>
                        </div>
                        <div class="col-4">
                            <span>Quantidade Mínima</span>
                            <input  class="form-control br-10" name="QtdeMin" type="number" value="<?php echo $EstoqueMin; ?>">
                        </div>
                        <div class="col-4">
                            <span>Quantidade Máxima</span>
                            <input  class="form-control br-10" name="QtdeMax" type="number" value="<?php echo $EstoqueMax; ?>">
                        </div>
                        <div class="col-12 m-t-10">
                            <span>Localização no Estoque</span>
                            <input  class="form-control br-10" name="LocalEstoque" type="text" value="<?php echo $array['Localizacao']; ?>">
                        </div>
                    </div> 
                    
                    <div class="row m-t-30 text-center">
                        <div class="col-12">
                            <button class="btn btn-warning text-white" type="button"><a href="item.php">Voltar</a></button>
                            <button class="btn btn-success" id='BtnEditarItem'>Salvar</button>
                        </div>
                    </div>
                    
                <?php } ?>
            </form>        
        </div>
</div>

<script src="public/js/bootstrap-4.1.js"></script>


<script>
//Abre e fecha SIDEBAR
$(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });
});


<?php
if($_SESSION['ID_Perfil'] != 0 ){
    echo " $('#colunaAtivo').hide();  ";

}

?>

//-----------Altera valor da compra simultania e ativo-------------
var checkCompra = document.getElementById("compraSimultania");
var checkAtivo = document.getElementById("ativo");

$("#BtnEditarItem").click(function() {

    if (checkCompra.checked) {
        $('#compraSimultania').attr('value', 1);
        console.log('valor compra ativado = ' + checkCompra.value);
    } else {
        $('#compraSimultania').attr('value', 0);
        console.log('valor compra desativado = ' +checkCompra.value);
    }

    if(checkAtivo.checked){
        $('#ativo').attr('value', 1);
        console.log('valor ativo ativado = ' + checkAtivo.value)

    }else{
        $("#ativo").attr('value', 0);
        console.log('valor ativo desativado = ' + checkAtivo.value)
    }
});
//---------Fim----------------------------------------------

$(document).ready(function () {

    $( "#fecharMsg" ).click(function() {
        $("#containerMsg").fadeOut();
    });
});

$('select').on('change', function () {
    if ($(this).val() == "declinado_nao") {
    $('#cria-recinto').modal('show');
    }
});    
</script>

</body>

</html
