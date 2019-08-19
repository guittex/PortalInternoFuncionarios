<?php
session_start();

include_once("services/ItemController.php");

$ItemController = new ItemController();

$query = $ItemController->ListarPai();

include_once("services/conexaoEnterprise.php");

$conexao = new conexaoEnterprise();

$conexao->sql_conexaoEnterprise('Estoque');

if(!isset($_SESSION['visualizarItem']) or $_SESSION['visualizarItem'] == 0 and $_SESSION['ID_Perfil'] != 0){
    header('Location: index.php');

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
                Selecione a Família
            </h1>   

            <!--------------INPUT PESQUISA --------------->
            <div class="row m-t-50">
                <div class="col-2 from-group">
                    <input class='form-control br-15' name="IdItemPesquisa" type="text" id='CodItemPesquisar' placeholder="Cód. Item">
                </div>
                <div class="col-6 p-l-0">
                    <input class='form-control br-15 ' name="DescricaoPesquisa" type="text" id='DescricaoItemPesquisar' placeholder="Descrição do Item">
                </div>
                <div class="col-2">
                    <button class="btn btn-dark br-15"  data-toggle="modal" data-target="#modalItemPesquisar" id='BtnPesquisar'>Pesquisar</button>
                </div>
                
                <?php if(!empty($_GET['IdItemPesquisa']) or !empty($_GET['DescricaoPesquisa'])){?>
                    <div class="col-2">
                        <a href="item.php"><button class="btn btn-success br-15 float-r" type='button'>Fechar</button></a>
                    </div>
                <?php  } ?>
            </div>   
            <!--------------CONTAINER DO RESULTADO PESQUISA --------------->
            
            <!------------ FIM --------------------------------->

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
            <?php
            while($array = sqlsrv_fetch_array($query)){
            ?>      
                <!-------------------Coluna 1 -------------------->              
                <div class="faqDisplay">
                    <dl>
                        <dt id="colunaPai" >
                            <?php
                            $sqlTesteFirst =  'SELECT * from dbo.Cad_Familia WHERE ID_PAI = '.$array['ID_Familia'].' ' ;
                            $queryTesteFirst = sqlsrv_query($conexao->con, $sqlTesteFirst);
                            
                            $testeLinhaFirst = sqlsrv_has_rows($queryTesteFirst);

                            ?>
                            <?php if($testeLinhaFirst == true) { ?>
                                <i id="setaDireita" class='fas fa-arrow-alt-circle-right m-r-5'></i>
                            <?php }else{ ?>
                                <i class='fas fa-times-circle  m-r-5' style='color:red'></i>
                            <?php } ?>

                            <!--<i id="setaBaixo" class="fas fa-arrow-circle-down -right m-r-5" style="display:none"></i>-->
                            <span><?php echo $array['ID_Familia']?></span> - <?php echo $array['Descricao'] ?>
                        </dt>
                        <dd id="colunaFilho">                            
                            <?php
                            $sql2 = "SELECT * from dbo.Cad_Familia WHERE ID_PAI = ".$array['ID_Familia']." ORDER BY Descricao";
                            
                            $query2 = sqlsrv_query($conexao->con, $sql2);  

                            while($array2 = sqlsrv_fetch_array($query2)){                                

                                ?> 
                                <!-------------------Coluna 2 ---------------->    
                                <div class="faqDisplay2">
                                    <dl>
                                        <dt id="colunaPai" >
                                            <?php
                                            $sqlTeste =  'SELECT * from dbo.Cad_Familia WHERE ID_PAI = '.$array2['ID_Familia'].' ' ;
                                            $queryTeste = sqlsrv_query($conexao->con, $sqlTeste);
                                            
                                            $testeLinha = sqlsrv_has_rows($queryTeste);

                                            echo   "<span>";
                                                        if($testeLinha == true){
                                            echo        "<i class='fas fa-arrow-alt-circle-right m-t-10 m-l-20 m-r-5' style='color:#337ab7'></i>";  
                                                        }else{
                                            echo        "<i class='fas fa-times-circle m-t-10 m-l-20 m-r-5' style='color:red'></i>";  
                                                        }
                                            echo       " ".$array2['ID_Familia']."</span> - <span id='DescricaoLabel'> ".$array2['Descricao']."</span> ";        
                                            echo        "<button data-toggle='modal' data-target='#modalItemVer' id='buttonVer' class='buttonItem' value=".$array2['ID_Familia']."></button> " ;
                                                        if($_SESSION['inserirItem'] != 0 or $_SESSION['ID_Perfil'] == 0 ){
                                            echo        "<button data-toggle='modal' data-target='#modalItemAdd' id='buttonAdd' class='buttonItem' value=".$array2['ID_Familia']."></button> " ;                               
                                                        }
                                            ?>
                                        </dt>
                                        <!------------ Coluna 3 --------------------->
                                        <dd id="colunaFilho2">
                                            <?php
                                            $sql3 = "SELECT * from dbo.Cad_Familia WHERE ID_PAI = ".$array2['ID_Familia']." ORDER BY Descricao";

                                            $query3 = sqlsrv_query($conexao->con, $sql3);  

                                            while($array3 = sqlsrv_fetch_array($query3)){
                                                ?>
                                                <div class="faqDisplay2">
                                                    <dl>
                                                    <dt id="colunaPai" style="margin:10px 0px">                                                    
                                                            <?php         
                                                            $sqlTeste2 =  'SELECT * from dbo.Cad_Familia WHERE ID_PAI = '.$array3['ID_Familia'].' ' ;
                                                            $queryTeste2 = sqlsrv_query($conexao->con, $sqlTeste2);
                                                            
                                                            $testeLinha2 = sqlsrv_has_rows($queryTeste2);                                                    
                                                            echo   "<span>";
                                                                        if($testeLinha2 == true){
                                                            echo        "<i class='fas fa-arrow-alt-circle-right m-t-10 m-l-35 m-r-5' style='color:#28a745'></i>";  
                                                                        }else{
                                                            echo        "<i class='fas fa-times-circle m-t-10 m-l-35 m-r-5' style='color:red'></i>";  
                                                                        }
                                                            echo       " ".$array3['ID_Familia']."</span> - ".$array3['Descricao']." ";        
                                                            echo        "<button data-toggle='modal' data-target='#modalItemVer' id='buttonVer' class='buttonItem' value=".$array3['ID_Familia']."></button> " ;
                                                                            if($_SESSION['inserirItem'] != 0 or $_SESSION['ID_Perfil'] == 0 ){
                                                            echo        "<button data-toggle='modal' data-target='#modalItemAdd' id='buttonAdd' class='buttonItem' value=".$array3['ID_Familia']."></button> " ;                               
                                                                            }
                                                            ?>
                                                        </dt>
                                                        <dd id="colunaFilho4">
                                                        <?php
                                                        $sql4 = "SELECT * from dbo.Cad_Familia WHERE ID_PAI = ".$array3['ID_Familia']." ORDER BY Descricao";

                                                        $query4 = sqlsrv_query($conexao->con, $sql4);  

                                                        while($array4 = sqlsrv_fetch_array($query4)){
                                                            ?>
                                                            <div class="faqDisplay3">
                                                                <dl>
                                                                <dt id="colunaPai" style="margin:10px 0px">
                                                                        <?php                                                           
                                                                        $sqlTeste3 =  'SELECT * from dbo.Cad_Familia WHERE ID_PAI = '.$array4['ID_Familia'].' ' ;
                                                                        $queryTeste3 = sqlsrv_query($conexao->con, $sqlTeste3);
                                                                        
                                                                        $testeLinha3 = sqlsrv_has_rows($queryTeste3);                                                    
                                                                        echo   "<span>";
                                                                                    if($testeLinha3 == true){
                                                                        echo        "<i class='fas fa-arrow-alt-circle-right m-t-10 m-l-52 m-r-5' style='color:#5bc0de'></i>";  
                                                                                    }else{
                                                                        echo        "<i class='fas fa-times-circle m-t-10 m-l-52 m-r-5' style='color:red'></i>";  
                                                                                    }
                                                                        echo       " ".$array4['ID_Familia']."</span> - ".$array4['Descricao']." ";        
                                                                        echo        "<button data-toggle='modal' data-target='#modalItemVer' id='buttonVer' class='buttonItem' value=".$array4['ID_Familia']."></button> " ;
                                                                                        if($_SESSION['inserirItem'] != 0 or $_SESSION['ID_Perfil'] == 0 ){

                                                                        echo        "<button data-toggle='modal' data-target='#modalItemAdd' id='buttonAdd' class='buttonItem' value=".$array4['ID_Familia']."></button> " ;                               
                                                                                        }
                                                                        ?>
                                                                    </dt>
                                                                    <dd id="colunaFilho5">
                                                                        
                                                                        
                                                                        
                                                                    </dd>
                                                                </dl>
                                                            </div>   
                                                        <?php } ?>                                                     
                                                        </dd>
                                                    </dl>
                                                </div>   
                                            <?php } ?>                                            
                                        </dd>
                                    </dl>
                                </div>   
                            <?php } ?>  
                        </dd>
                    </dl>                
                </div>                
            <?php } ?>        
        </div>        
    </div>
</div>

<!---------------- Modal Ver -------------------->
<div class="modal fade" id="modalItemVer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Itens</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body table-responsive" id="corpoItem">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>


<!---------------- Modal Pesquisar -------------------->
<div class="modal fade" id="modalItemPesquisar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Itens</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body table-responsive" id="corpoItemPesquisa">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="BtnFecharPesquisa" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>



<!-----------------MODAL ADICIONAR --------------->
<div class="modal fade" id="modalItemAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content br-10">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Adicionar Item</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body table-responsive" id="corpoItem">
                <div class="container">
                    <form method="POST" action="services/ItemController.php">
                        <input type="hidden" id="idFamiliaAdd" value="" name="idFamiliaAdd">                                      
                                                
                        
                        <div class="row">                       
                            
                            <div class="col-12 m-t-13">
                                <span class='bold' style="color:gray">Opções: </span>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="options" id="MP" value="MP" checked>
                                    <label class="form-check-label">Matéria Prima</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="options" id="MC" value="MC">
                                    <label class="form-check-label">Material de Uso</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="options" id="ST" value="ST">
                                    <label class="form-check-label">Serviço Tomado</label>
                                </div>      
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="options" id="ATV" value="ATV">
                                    <label class="form-check-label">Ativo Imobilizado</label>
                                </div>                        
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 m-t-20">
                                <h5 class='text-center bold'>Informações do Item</h5>
                            </div>
                            <div class="line"></div>
                            <div class="col-5">
                                <span class='bold m-r-10' style="color:gray">Familia: </span>
                                <input type="text" id="FamiliaLabel" value="" name="FamiliaLabel" style="border:none;position:fixed" readonly>
                            </div>
                            <div class="col-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="ativo" name='ativo' value=1 checked>
                                    <label class="form-check-label">
                                        Ativo
                                    </label>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="form-check ">
                                    <input class="form-check-input" type="checkbox" id="compraSimultania" name="compraSimultania" value=0>
                                    <label class="form-check-label">
                                        Permitir Compra Simultânea
                                    </label>
                                </div>
                            </div>
                            <div class="col-12 m-t-20">
                                <span>Descrição</span>
                                <input  class="form-control br-10" type="text" name="Descricao" required>
                            </div>
                            <div class="col-4 m-t-10">
                                <span>Modelo</span>
                                <input  class="form-control br-10" type="text" name="Modelo" required>
                            </div>
                            <div class="col-4 m-t-10">
                                <span>Codigo Fabricante</span>
                                <input  class="form-control br-10" type="text" name="CodFabricante" required>
                            </div>
                            <div class="col-4 m-t-10">
                                <span>NCM</span>
                                <input  class="form-control br-10" type="text" maxlength=8 name="NCM" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 m-t-20">
                                <h5 class='text-center bold'>Informações de Compra</h5>
                            </div>
                            <div class="line"></div>
                            <div class="col-4">
                                <span>Unidade Compra</span>
                                <select class="form-control br-10" name="UnidCompra" id="">
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
                                    <option value="PC" selected>PC</option>
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
                                    <option value="PC" selected>PC</option>
                                    <option value="PÇ">PÇ</option>
                                    <option value="PCT">PCT</option>
                                    <option value="PEÇA">PEÇA</option>
                                    <option value="PEÇAS">PEÇAS</option>
                                    <option value="UNID">UNID</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <span>Qtde por Embalagem</span>
                                <input  class="form-control br-10" type="text" name="QtdeEmbalagem" value=1 required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 m-t-20">
                                <h5 class='text-center bold'>Quantidade em Estoque</h5>
                            </div>
                            <div class="line"></div>
                            <div class="col-4">
                                <span>Quantidade Atual</span>
                                <input  class="form-control br-10" type="number" name="QtdeAtual" required>
                            </div>
                            <div class="col-4">
                                <span>Quantidade Mínima</span>
                                <input  class="form-control br-10" name="QtdeMin" type="number" >
                            </div>
                            <div class="col-4">
                                <span>Quantidade Máxima</span>
                                <input  class="form-control br-10" name="QtdeMax" type="number" >
                            </div>
                            <div class="col-12 m-t-10">
                                <span>Localização no Estoque</span>
                                <input  class="form-control br-10" name="LocalEstoque" type="text" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger br-10" data-dismiss="modal">Fechar</button>
                    <button class="btn btn-success" id='BtnAddItem' type="submit">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="public/js/bootstrap-4.1.js"></script>

<script>

//Mandar pro ajax os itens
$( "#colunaPai button" ).click(function() {
    descricao = $(this).val();
    $('#idFamiliaAdd').attr('value', descricao);
    $('#FamiliaLabel').attr('value', descricao);
    
    console.log(descricao);
    $.ajax({
        type: "POST",
        url: "services/ItemController.php",
        data: { 'IdFamilia' : descricao}
        }).done(function( msg ) {
            //alert( "ID Familia: " + msg );
            //document.getElementById('colunaFilho').style.display = 'inherit';
            $("#corpoItem").html(msg);   
            
    }); 
});

//-----------Altera valor da compra simultania e ativo-------------
var checkCompra = document.getElementById("compraSimultania");
var checkAtivo = document.getElementById("ativo");

$("#BtnAddItem").click(function() {

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

$("#BtnFecharPesquisa").click(function() {
    $('#DescricaoItemPesquisar').val("");
    $('#CodItemPesquisar').val("");

});

//---------Fim----------------------------------------------

$( "#BtnPesquisar" ).click(function() {
    descricaoPesquisa = $('#DescricaoItemPesquisar').val();
    itemPesquisa = $('#CodItemPesquisar').val();    
    console.log(descricaoPesquisa, itemPesquisa);

    if(descricaoPesquisa == '' && itemPesquisa == ''){
        alert('Necessário digitar algo');
        location.reload();
    }

    $.ajax({
        type: "POST",
        url: "services/ItemController.php",
        data: { 'descricaoPesquisa' : descricaoPesquisa, 'IdItemPesquisa' : itemPesquisa}
        }).done(function( retorno ) {
            //alert( "Retorno: " + retorno );
            //document.getElementById('ContainerPesquisa').style.display = 'inherit';
            $("#corpoItemPesquisa").html(retorno);   
            
    });

});




//Abrir e fechar a familia
$('dt').click(function() {
	$(this).next('dd').slideToggle();
})

//Fechar a barra de status
$("#botao_fechar").click(function(){
    
    $("#div_fechar").fadeOut("slow");
    window.history.pushState("", "", "/PortalInterno/item.php");

    
});



//Abre e fecha SIDEBAR
$(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });
});

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
</html>

