<?php
include_once("conexaoEnterprise.php");

class ItemController extends conexaoEnterprise
{
    public function ListarPai()
    {
        $this->sql_conexaoEnterprise('ESTOQUE');

        $sql = "SELECT * FROM dbo.Cad_Familia WHERE ID_PAI = -1 ORDER BY Descricao ";

        $query = sqlsrv_query($this->con, $sql);

        return $query;

    }

    public function ListarItem($idFilho)
    {
        session_start();

        $acessoEditar =  $_SESSION['editarItem'];

        //echo $acessoVisualizar, $acessoEditar ;

        $this->sql_conexaoEnterprise('ESTOQUE');

        $sql = "SELECT * FROM dbo.Cad_Item WHERE ID_Familia = '$idFilho' and IS_DELETED = 'N' and ativo = 1 ORDER BY Descricao ";

        $query = sqlsrv_query($this->con, $sql);

            echo                "<table class='table table-striped '> ";
            echo                    "<tbody>";
            echo                        "<th>ID</th>";
            echo                        "<th>Descrição</th>";
            echo                        "<th>Modelo</th>";
            echo                        "<th>Estoque Atual</th>";
            echo                        "<th>Estoque Minimo</th>";
            echo                        "<th>Estoque Máximo</th>";
            echo                        "<th>Localização</th>";
                                    if($acessoEditar != 0 or $_SESSION['ID_Perfil'] == 0){     

            echo                        "<th>Ação</th>";
                                    }
            while($array = sqlsrv_fetch_array($query)){
                $EstoqueAtual = substr($array['Estoque_Atual'], 0, -5);
                $EstoqueMin = substr($array['Estoque_Minimo'], 0, -5);
                $EstoqueMax = substr($array['Estoque_Maximo'], 0, -5);

            echo                        "<tr>";
            echo                            "<td>";
            echo                                $array['ID_Item'];
            echo                            "</td>";
            echo                            "<td>";
            echo                                $array['Descricao'];
            echo                            "</td>";
            echo                            "<td>";
            echo                                $array['Modelo'];
            echo                            "</td>";
            echo                            "<td>";            
            echo                                $EstoqueAtual;
            echo                            "</td>";
            echo                            "<td>";            
            echo                                $EstoqueMin;
            echo                            "</td>";
            echo                            "<td>";            
            echo                                $EstoqueMax;
            echo                            "</td>";
            echo                            "<td>";
            echo                                $array['Localizacao'];
            echo                            "</td>";  
                                            if($acessoEditar != 0 or $_SESSION['ID_Perfil'] == 0){      
            echo                            "<td>";
            echo                                "<a href='editarItem.php?IdItem=".$array['ID_Item']." '><button class='btn btn-warning br-15 text-white'>Editar</button></a>";
            echo                            "</td>";
                                            }
            echo                        "</tr>";                 
        }
            echo                        "</tbody>";
            echo                "</table>";    
        
    }

    public function AdicionarItem($idFamilia,$Descricao,$Modelo,$CodFabricante,$UnidCompra,$UnidConsumo,$QtdeEmbalagem,$QtdeAtual,$QtdeMin,$QtdeMax,$ncm, $LocalEstoque, $ativo, $compraSimultanea, $options, $acessoInserir, $acessoVisualizar){
        $this->sql_conexaoEnterprise('ESTOQUE');

        if($QtdeMin == null){
            $QtdeMin = 0;
        }

        
        if($QtdeMax == null){
            $QtdeMax = 0;
        }

        $sql = "INSERT INTO dbo.Cad_Item (IS_DELETED, ID_Familia, Descricao, Modelo, Cod_Fabricante, Estoque_Atual, Estoque_Minimo, 
                Estoque_Maximo, Unid_Compra, Unid_Consumo, Qtde_Por_Embalagem, Localizacao, PROD_GRUPO, NCM,compraSimultania,ativo ) VALUES 
                ('N', $idFamilia, '$Descricao', '$Modelo', '$CodFabricante', $QtdeAtual, $QtdeMin, $QtdeMax, '$UnidCompra', '$UnidConsumo' 
                    ,$QtdeEmbalagem, '$LocalEstoque', '$options','$ncm', $compraSimultanea, $ativo ) ";

        //echo $sql;
        
        $query = sqlsrv_query($this->con, $sql);

        //var_dump($query);

        if($query == false){
            header('Location: ../item.php?status=1&class=danger&msg=Erro ao salvar na função Adicionar Item');
            exit();

        }
        header('Location: ../item.php?status=1&class=success&msg=Salvado com sucesso');
            
        
    }

    public function GetItem($IdItem)
    {
        $this->sql_conexaoEnterprise('ESTOQUE');

        $sql = "SELECT * FROM dbo.Cad_Item WHERE ID_Item = $IdItem ORDER BY Descricao ";

        $query = sqlsrv_query($this->con, $sql);

        return $query;
    }

    public function EditarItem($idItem,$Descricao,$Modelo,$CodFabricante,$UnidCompra,$UnidConsumo,$QtdeEmbalagem,$QtdeAtual,$QtdeMin,$QtdeMax, $ncm , $LocalEstoque, $ativo, $compraSimultanea)
    {
        $this->sql_conexaoEnterprise('ESTOQUE');


        $sql = "UPDATE dbo.Cad_Item SET Descricao = '$Descricao', Modelo = '$Modelo', Cod_Fabricante = '$CodFabricante', Estoque_Atual = $QtdeAtual,
                    Estoque_Minimo = $QtdeMin, Estoque_Maximo = $QtdeMax, Unid_Compra = '$UnidCompra', Unid_Consumo = '$UnidConsumo',
                    Qtde_Por_Embalagem = $QtdeEmbalagem, ativo = '$ativo', compraSimultania = '$compraSimultanea' , NCM = '$ncm', Localizacao = '$LocalEstoque' WHERE ID_item = $idItem";
        
        $query = sqlsrv_query($this->con, $sql);

        //var_dump($sql);

        if($query = FALSE){
            header('Location: ../item.php?status=1&class=danger&msg=Não foi possível editar, erro na função EditarItem');
            exit();
        }

        header('Location: ../item.php?status=1&class=success&msg=Editado com sucesso');

    }

    public function PesquisarItem($IdItemPesquisa, $descricaoPesquisa)
    {
        $this->sql_conexaoEnterprise('ESTOQUE');

        session_start();

        $acessoEditar =  $_SESSION['editarItem'];

        //echo $IdItemPesquisa , $descricaoPesquisa ;

        if(!empty($IdItemPesquisa)){
            $testeIdItem = ctype_digit($IdItemPesquisa);
            if($testeIdItem == false){
                echo "<h4 class='text-center m-t-10 m-b-10'>São aceito apenas números no campo do código do Item</h4>";
                echo    '<script>
                    var count = 50;
                    var tempo = document.getElementById("tempo");

                    function start() {
                        if (count > 0){
                            count -= 1;
                            if (count == 0) {
                                count = "Atualizado";
                                window.history.pushState("", "", "/PortalInterno/item.php");
                                document.location.reload(true);
                            }else if(count < 10){
                                count = "0" + count;
                            }
                            //tempo.innerText = count;
                            setTimeout(start, 100); 
                            // em vez de chamar setTimeout("start();", 100) usa só o nome da função
                            // o setTimeout vai executar a função mesmo sem pores os ()
                        }
                    }
                    start();
                    </script>';
                exit();

            }
        }

        if(empty($IdItemPesquisa)){
            $sql = "SELECT * from dbo.Cad_Item WHERE  Descricao like '%$descricaoPesquisa%' ";;
        }else{
            $sql = "SELECT * from dbo.Cad_Item WHERE ID_item = $IdItemPesquisa ";
        }

        //echo $sql;

        $query = sqlsrv_query($this->con, $sql);

        $testQuery = sqlsrv_has_rows($query);
        
        if($testQuery == false){
            echo "<h4 class='text-center m-t-10 m-b-10'>Nenhum resultado encontrado</h4>";
        }

        $query = sqlsrv_query($this->con, $sql);

        echo                "<table class='table table-striped '> ";
        echo                    "<tbody>";
        echo                        "<th>ID</th>";
        echo                        "<th>Descrição</th>";
        echo                        "<th>Modelo</th>";
        echo                        "<th>Estoque Atual</th>";
        echo                        "<th>Estoque Minimo</th>";
        echo                        "<th>Estoque Máximo</th>";
        echo                        "<th>Localização</th>";
                                if($acessoEditar != 0 or $_SESSION['ID_Perfil'] == 0){     

        echo                        "<th>Ação</th>";
                                }
        while($array = sqlsrv_fetch_array($query)){
            $EstoqueAtual = substr($array['Estoque_Atual'], 0, -5);
            $EstoqueMin = substr($array['Estoque_Minimo'], 0, -5);
            $EstoqueMax = substr($array['Estoque_Maximo'], 0, -5);

        echo                        "<tr>";
        echo                            "<td>";
        echo                                $array['ID_Item'];
        echo                            "</td>";
        echo                            "<td>";
        echo                                $array['Descricao'];
        echo                            "</td>";
        echo                            "<td>";
        echo                                $array['Modelo'];
        echo                            "</td>";
        echo                            "<td>";            
        echo                                $EstoqueAtual;
        echo                            "</td>";
        echo                            "<td>";            
        echo                                $EstoqueMin;
        echo                            "</td>";
        echo                            "<td>";            
        echo                                $EstoqueMax;
        echo                            "</td>";
        echo                            "<td>";
        echo                                $array['Localizacao'];
        echo                            "</td>";  
                                        if($acessoEditar != 0 or $_SESSION['ID_Perfil'] == 0){      
        echo                            "<td>";
        echo                                "<a href='editarItem.php?IdItem=".$array['ID_Item']." '><button class='btn btn-warning br-15 text-white'>Editar</button></a>";
        echo                            "</td>";
                                        }
        echo                        "</tr>";                 
    }
        echo                        "</tbody>";
        echo                "</table>";    
    

        /*
        while($array = sqlsrv_fetch_array($query)){
            $sqlFamilia = 'SELECT * FROM dbo.Cad_Familia WHERE ID_Familia = '.$array['ID_Familia'].' ';
            $queryFamilia = sqlsrv_query($this->con, $sqlFamilia);

            
            echo    "<div class='row m-t-15 m-b-15'>";
            echo        "<div class='col-12 text-cente'>";
            echo            "<h4 class='text-center m-b-25'>";
                            if($array['ID_Familia'] == null){
                                echo 'O item não possui família';
                            }else{
                                    while($array2 = sqlsrv_fetch_array($queryFamilia)){                                
            echo                        'Família ' . $array2['Descricao'];
                                    }         
                            }   
            echo            "</h4>";
            echo        "</div>";
            echo        '<div class="col-2">';
            echo            "<span class='bold'>ID</span>";
            echo            "<span class='form-control'>".$array['ID_Item']." ";
            echo        "</div>";
            echo        '<div class="col-10">';
            echo            "<span class='bold'>Descrição</span>";
            echo            "<span class='form-control' style='height:70%'>".$array['Descricao']." ";
            echo        "</div>";
            echo        '<div class="col-4 m-t-10">';
            echo            "<span class='bold'>Modelo</span>";
            echo            "<span class='form-control'>".$array['Modelo']." ";
            echo        "</div>";
            echo        '<div class="col-4 m-t-10">';
            echo            "<span class='bold'>Cod. Fabricante</span>";
            echo            "<span class='form-control'>".$array['Cod_Fabricante']." ";
            echo        "</div>";
            echo        '<div class="col-4 m-t-10">';
            echo            "<span class='bold'>Estoque Atual</span>";
            echo            "<span class='form-control'>".$array['Estoque_Atual']." ";
            echo        "</div>";
            echo        '<div class="col-3 m-t-10">';
            echo            "<span class='bold'>Estoque Mínimo</span>";
            echo            "<span class='form-control'>".$array['Estoque_Minimo']." ";
            echo        "</div>";
            echo        '<div class="col-3 m-t-10">';
            echo            "<span class='bold'>Estoque Maximo</span>";
            echo            "<span class='form-control'>".$array['Estoque_Maximo']." ";
            echo        "</div>";
            echo        '<div class="col-3 m-t-10">';
            echo            "<span class='bold'>Unid. Compra</span>";
            echo            "<span class='form-control'>".$array['Unid_Compra']." ";
            echo        "</div>";
            echo        '<div class="col-3 m-t-10">';
            echo            "<span class='bold'>Unid. Consumo</span>";
            echo            "<span class='form-control'>".$array['Unid_Consumo']." ";
            echo        "</div>";
            echo        '<div class="col-8 m-t-10">';
            echo            "<span class='bold'>Localização</span>";
            echo            "<span class='form-control'>".$array['Localizacao']." ";
            echo        "</div>";
            echo        '<div class="col-4 m-t-10">';
            echo            "<span class='bold'>Qtde. Embalagem</span>";
            echo            "<span class='form-control'>".$array['Qtde_Por_Embalagem']." ";
            echo        "</div>";
            echo        '<div class="line"></div>';
            echo    "</div>";  
            
        }*/

    }

}

$item = new ItemController();

if(!empty($_POST['IdFamilia'])){

    $idFilho = $_POST['IdFamilia'];
    //echo $idFilho;
    $item->ListarItem($idFilho);
}



if(!empty($_POST['idFamiliaAdd']) or (!empty($_POST['idItem'] ))){

    if(!empty($_POST['idFamiliaAdd'])){
        $idFamilia = $_POST['idFamiliaAdd'];
    }
    if(!empty($_POST['idItem'])){
        $idItem = $_POST['idItem'];
    }

    $Descricao = $_POST['Descricao'];

    $Modelo = $_POST['Modelo'];

    $CodFabricante = $_POST['CodFabricante'];

    $UnidCompra = $_POST['UnidCompra'];

    $UnidConsumo = $_POST['UnidConsumo'];

    $QtdeEmbalagem = $_POST['QtdeEmbalagem'];

    $QtdeAtual = $_POST['QtdeAtual'];

    $QtdeEmbalagem = $_POST['QtdeEmbalagem'];

    $QtdeAtual = $_POST['QtdeAtual'];

    $QtdeMin = $_POST['QtdeMin'];

    if($QtdeMin == null){
        $QtdeMin = 0;
    }

    $QtdeMax = $_POST['QtdeMax'];

    if($QtdeMax == null){
        $QtdeMax = 0;
    }

    $ncm = $_POST['NCM'];

    $LocalEstoque = $_POST['LocalEstoque'];

    $ativo = $_POST['ativo'];

    if($ativo == null){
        $ativo = 0;
    }

    $compraSimultanea = $_POST['compraSimultania'];

    if($compraSimultanea == null){
        $compraSimultanea = 0;

    }
    
    $options =$_POST['options'];

    $acessoVisualizar = $_POST['accessView'];

    $acessoInserir = $_POST['accessInsert'];


    if(!empty($_POST['idFamiliaAdd'])){
        $item->AdicionarItem($idFamilia,$Descricao,$Modelo,$CodFabricante,$UnidCompra,$UnidConsumo,$QtdeEmbalagem,$QtdeAtual,$QtdeMin,$QtdeMax,$ncm, $LocalEstoque, $ativo, $compraSimultanea, $options, $acessoInserir, $acessoVisualizar);
    }

    if(!empty($_POST['idItem'])){
        $item->EditarItem($idItem,$Descricao,$Modelo,$CodFabricante,$UnidCompra,$UnidConsumo,$QtdeEmbalagem,$QtdeAtual,$QtdeMin,$QtdeMax, $ncm , $LocalEstoque, $ativo, $compraSimultanea);

    }

}


if(!empty($_POST['IdItemPesquisa'])){
    $IdItemPesquisa = $_POST['IdItemPesquisa'];
    $item->PesquisarItem($IdItemPesquisa, '');
}

if(!empty($_POST['descricaoPesquisa'])){
    $descricaoPesquisa = $_POST['descricaoPesquisa'];
    $item->PesquisarItem('', $descricaoPesquisa);
}


