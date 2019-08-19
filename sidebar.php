<?php

include_once("services/usuario.php");

$usuario = new usuario();
$id = $_SESSION['ID'];
//$email = $_SESSION['email'];
//$idFornecedor = $_SESSION['ID_VenFor'];
$perfil = $_SESSION['ID_Perfil'];

$email = $_SESSION['email'];

$visualizarItemAcesso = $_SESSION['visualizarItem'];

$inserirItemAcesso = $_SESSION['inserirItem'];

?>

<style>
#acessoNegado{
    padding-left: 220px!important;
}
</style>

<nav id="sidebar">

    <ul class="list-unstyled components">      
        <li>
            
            <!-------------------------------MINHA CONTA ----------------------------------------------------------->

            <a href="#myconta" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> <i class="fas fa-user-cog" aria-hidden="true" style="margin-right:10px;"></i>Minha conta</a>
            <ul class="collapse list-unstyled" id="myconta">
                <li>
                    <a href='editar_dados.php?id=<?php echo base64_encode($id)  ?>'>Alterar minha senha</a>
                    
                </li>               
                <li>
                    <a href="mailto:example@email.com" >Enviar e-mail</a>
                </li>
                <!--<li>
                    <a href='suporte.php?idFornecedor=<?php echo base64_encode($idFornecedor) ?>&mail=<?php echo base64_encode($email)  ?>'>Suporte</a>
                    
                </li>-->
                
            </ul>


            <!-------------------------------ADMINISTRAÇÃO ----------------------------------------------------------->

                <a href="#admin" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> <i class="fas fa-laptop" aria-hidden="true" style="margin-right:10px;"></i>Administração</a>
                <ul class="collapse list-unstyled" id="admin">
                    <li>
                        <?php if($perfil == 0){ ?>
                        <a href='index_interno.php'>Usuários Portal Interno</a>
                        <?php } ?>
                        <a href='index.php'>Usuários Portal Externo</a>                        
                    </li>             
                </ul>

            <!-------------------------------ITENS ----------------------------------------------------------->

                <a href="#item" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> <i class="fas fa-shopping-cart" aria-hidden="true" style="margin-right:12px;"></i>Compras</a>
                <ul class="collapse list-unstyled" id="item">
                    <li>
                        <a href='#' id='ItemBtn'>Itens</a>
                    </li>   
                    <li>
                        <a href='#' id='ItemBtn'>Famílias</a>
                    </li>                                     
                </ul>

            <!-------------------------------SUPORTE ----------------------------------------------------------->
            <?php
                if($perfil == 0){                    
            ?>
                <a href='suporte_adm.php?idFuncionario=<?php echo ($id) ?>' ><i class="fas fa-toolbox" aria-hidden="true" style="margin-right:13px;"></i>Suporte</a>
            <?php 
                }else{
            ?>
                <a href='suporte.php?idFornecedor=<?php echo base64_encode($id) ?>' ><i class="fas fa-toolbox" aria-hidden="true" style="margin-right:13px;"></i>Suporte</a>
            <?php
                }
            ?>
            <!-------------------------------FeedBack ----------------------------------------------------------->
            <a href='feedback.php?idFornecedor=<?php echo base64_encode($id) ?>' ><i class="fas fa-comment-dots" aria-hidden="true" style="margin-right:13px;"></i>FeedBack</a>

        </li>       
    </ul>



    <!--- <ul class="list-unstyled CTAs">
        <li>
            <a href="https://bootstrapious.com/tutorial/files/sidebar.zip" class="download">Download source</a>
        </li>
        <li>
            <a href="https://bootstrapious.com/p/bootstrap-sidebar" class="article">Back to article</a>
        </li>
    </ul>-->
</nav>

<!-- Modal GERAR PDF-->
<div class="modal fade" id="gerar_pdf" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action='gerar_pdf.php' method='POST' >
            <div class="modal-content">            
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLongTitle"><strong>Selecione</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                        <label for="exampleInputEmail1">Digite a quantidade de registro que deseja ver</label>
                        <input type="text" class="form-control" name='qtd_registro' id="qtd_registro" placeholder="Digite aqui" required>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                    <input type="submit" class="btn btn-success" id='editar' value="Selecionar">
                </div>            
            </div>
        </form>
    </div>
</div>

<!-- Modal ACESSO NEGAO ITEM -->
<div class="modal fade" id="acessoNegado" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action='gerar_excell.php' method='POST' >
            <div class="modal-content">            
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLongTitle"><strong></strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                        <h2>Acesso negado!</h2>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                </div>            
            </div>
        </form>
    </div>
</div>

<script>

$( "#ItemBtn" ).click(function() {
    var vizualisarItem = '<?php echo $_SESSION['visualizarItem'] ?>';
    var nivel = '<?php echo $_SESSION['ID_Perfil'] ?>';
    console.log(nivel);
    if(vizualisarItem != '0' || nivel == '0'){
        window.location.href = "http://192.168.1.6:8089/PortalInterno/item.php ";
        console.log('permitido');
    }else{
        $('#acessoNegado').modal('show');

    }    

    /*
    var email = '<?php echo $email ?> ';    
    console.log(email);

    $.ajax({
        type: "POST",
        url: "services/usuario.php",
        data: { 'emailUser' : email},
        success: function(msg) {
            var result = $.trim(msg);
            if(result==="permitido"){
                window.location.replace("item.php");
            return false;
            }else{
                alert("Você não possui permissão");

            }
    
            //\$("#ContainerPesquisa").html(retorno);              
            
        }   

    })*/
    
});

</script>
