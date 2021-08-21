<?php

    require_once('db/conexao.php');
    $conexao = conexaoMysql();


?>

<!doctype html>
<html lang="pt-br">
    <head>
        <title>Lojas</title>
        <link rel="stylesheet" href="css/lojas.css" type="text/css">
        <link rel="stylesheet" href="css/menu.css" type="text/css">
        <link rel="stylesheet" href="css/rodape.css" type="text/css">
        <link rel="stylesheet" href="css/modal_loja.css" type="text/css">
        <script src="js/jquery.js"></script>
        <script src="js/jquery-1.9.1.min.js"></script>
        <script src="js/jssor.slider.min.js"></script>
        <script src="js/modal.js"></script>  
        <meta charset="utf-8">
      
    </head>
    <body>
        
        <!--MODAL-->
        <div id="container">
            <div id="modal" class="center">
                
            </div>
        </div>
        
        <!--MENU-->
        <?php include("menu.php")?>
        
        
        <!--CONTEUDO 100%-->
        <div id="caixa_conteudo">
            
            <!--TITULO LOJAS-->
            <div id="titulo"  class="center">
                <h2>Lojas </h2>
            </div>
            
            <!--CONTEUDO COM TODAS AS INFORMACOES-->
            <div id="conteudo" class="center">
                
                <!--REDES SOCIAIS-->
                <div id="caixa_redes">
                    <div class="facebook"></div>
                    <div class="insta"></div>
                    <div class="twitter"></div>
                </div>
                
                <!--ITENS DO LADO ESQUERDO-->
                <div id="caixa_itens">
                    <div id="item">
                        <?php
                        
                            /*SELECT PARA TRAZER AS LOJAS, JUTNO COMA CIDADE E O ESTADO*/
                            $sql = "SELECT tbl_lojas.*, tbl_cidades.*, tbl_estados.sigla_estado
                                    FROM tbl_lojas INNER JOIN tbl_cidades
                                    ON tbl_lojas.cod_cidade = tbl_cidades.cod_cidade
                                    INNER JOIN tbl_estados
                                    ON tbl_cidades.cod_estado = tbl_estados.cod_estado
                                    WHERE tbl_lojas.ativo = 1
                                    GROUP BY tbl_estados.sigla_estado";
                        
                            $select = mysqli_query($conexao,$sql);
                            
                            while($rsestados = mysqli_fetch_array($select)){
                              
                              $cod_estado = $rsestados['cod_estado'];
                        
                        ?>
                    
                        <a href="lojas.php?lojas&cod_estado=<?php echo($cod_estado)?> "><div class="itens_db">
                            <span><?php echo($rsestados['sigla_estado'])?></span>
                        </div></a>
                      
                        <?php
                        
                            }
                        
                        ?>
                        
                        
                        
                    </div>
                </div>
                
                <!--LOJAS-->
                <div id="caixa_lojas">
                    
                    <?php
                  
                        if(isset($_GET['lojas'])){
                          
                          $codigo = $_GET['cod_estado'];
                          
                          $sql = "SELECT tbl_lojas.*, tbl_cidades.*, tbl_estados.sigla_estado, 
                                concat_ws(' - ', tbl_lojas.endereco, tbl_cidades.cidade, tbl_estados.sigla_estado) AS  endereco_completo
                                FROM tbl_lojas INNER JOIN tbl_cidades
                                ON tbl_lojas.cod_cidade = tbl_cidades.cod_cidade
                                INNER JOIN tbl_estados
                                ON tbl_cidades.cod_estado = tbl_estados.cod_estado
                                WHERE tbl_lojas.ativo = 1 AND tbl_estados.cod_estado =".$codigo;
                          
                        }else{
                        
                        $sql = "SELECT tbl_lojas.*, tbl_cidades.*, tbl_estados.sigla_estado, 
                                concat_ws(' - ', tbl_lojas.endereco, tbl_cidades.cidade, tbl_estados.sigla_estado) AS  endereco_completo
                                FROM tbl_lojas INNER JOIN tbl_cidades
                                ON tbl_lojas.cod_cidade = tbl_cidades.cod_cidade
                                INNER JOIN tbl_estados
                                ON tbl_cidades.cod_estado = tbl_estados.cod_estado 
                                WHERE tbl_lojas.ativo = 1";
                          
                        }
                        $select = mysqli_query($conexao,$sql);
        
                        while($rslojas = mysqli_fetch_array($select)){
                            
                         
                    
                    
                    ?>
                    
                    
                    
                    <div class="lojas">
                        
                        <!--FOTO DA LOJA-->
                        <div class="caixa_foto">
                            <figure class="foto">
                                <img src="cms/arquivos/<?php echo($rslojas['foto'])?>" title="<?php echo($rslojas['nome_loja'])?>" alt="<?php echo($rslojas['nome_loja'])?>" class="foto">
                            </figure>
                        </div>
                        
                        <!--TITULO LOJA-->
                        <!--<h3 class="titulo_loja">Loja 01</h3>-->
                    
                        <!--DETALHES DA LOJA-->
                        <div class="detalhes_loja">
                            <div class="nome_loja center"><h3><?php echo($rslojas['nome_loja'])?></h3></div>
                            
                            <div class="local_loja center"><p><?php echo($rslojas['endereco_completo'])?></p></div>
                            
                            <div class="telefone center"><p>Contato: <?php echo($rslojas['telefone'])?></p></div>
                            
                            <!--BOTAO PARA VER MAPA-->
                        <!--
                        <div class="caixa_ver_mapa ">
                         
                            <div class="ver_mapa" onclick="visualizarMapa(<?php echo($rslojas['cod_loja'])?>)">
                                <span>Mapa</span>
                            </div>  
                       
                        </div>
                        -->
                            
                            
                        </div>
                        
                        
                    </div>
                    
                    <?php
                    
                           
                        }
                    
                    ?>
                
                </div>
            </div>
        </div>
        
        <!--RODAPE-->
        <?php include("rodape.php");?>
    </body>
</html>