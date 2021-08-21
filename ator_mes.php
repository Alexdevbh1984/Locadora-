<?php

 

  require_once('funcoes.php');
  require_once('db/conexao.php');
  $conexao = conexaoMysql();

  $dt_nasc = null;

?>


<!doctype html>
<html lang="pt-br">
    <head>
        <title>Ator do Mês</title>
        <link rel="stylesheet" href="css/ator_mes.css" type="text/css">
        <link rel="stylesheet" href="css/menu.css" type="text/css">
        <link rel="stylesheet" href="css/rodape.css" type="text/css">
        <script src="js/jquery.js"></script>
        <script src="js/modal.js"></script>
        <meta charset="utf-8">
        <script>
        
            $(document).ready(function(){
                
                $('.local_loja').click(function(){
                    
                    $('#container').fadeIn(800);
                })
                
            })
        
        </script>
        
    </head>
    <body>
        
        <!--MENU-->
        <?php include("menu.php")?>
        
        <!--DIV DO CONTEUDO 100%-->
        <div id="caixa_conteudo">
            
            <!--TITULO-->
            <div id=titulo class="center"><h2>Ator em Destaque</h2></div>
            
            <!--CONTEUDO COM TODAS AS INFORMACOES-->
            <div id="conteudo" class="center">
                
                <!--REDESE SOCIAIS-->
                <div id="caixa_redes">
                    <div class="facebook"></div>
                    <div class="insta"></div>
                    <div class="twitter"></div>
                </div>
                
                <!--ATOR DO MES-->
                <div id="caixa_ator_mes">
                        <?php
                        
                          /*SELCT QUE TRAZ O ATOR, A SUA PROFISSÃO E SEU ESTADO CIVIL*/
                          $sql = "SELECT COUNT(tbl_ator.cod_ator) AS quantidade, tbl_ator.*,tbl_estado_civil.estado_civil, 
                                  GROUP_CONCAT(DISTINCT tbl_profissao.profissao SEPARATOR ', ') AS profissoes  
                                  FROM tbl_ator_profissao
                                  INNER JOIN tbl_ator
                                  ON tbl_ator.cod_ator = tbl_ator_profissao.cod_ator
                                  INNER JOIN tbl_profissao
                                  ON tbl_profissao.cod_profissao = tbl_ator_profissao.cod_profissao
                                  INNER JOIN tbl_estado_civil
                                  ON tbl_ator.cod_estado_civil = tbl_estado_civil.cod_estado_civil
                                  WHERE tbl_ator.ativo_ator_mes = 1";
      
                          $select = mysqli_query($conexao,$sql);
                    
      
                            if($rsdestaque = mysqli_fetch_array($select)){
                         
                              /*VERFICAÇÃO DE SE RETORNAR A QUANTIDADE DE LINHAS */
                              if($rsdestaque['quantidade'] != 0){
                              
                                $dt_nasc = dataBrasileiro($rsdestaque['data_nascimento']);
                  
                        ?>
                      
                        
                        <!--TUDO SOBRE O ATOR-->
                        <div class="principal_sobre_ator">
                            
                            <!--FOTO DO ATOR-->
                            <figure class="imagem_ator center">
                                    <img src="cms/arquivos/<?php echo($rsdestaque['foto'])?>" alt="<?php echo($rsdestaque['nome_artistico'])?>" title="<?php echo($rsdestaque['nome_artistico'])?>">
                            </figure>
                            
                            <!--NOME DO ATOR-->
                            <h3 class="nome_ator center"><?php echo($rsdestaque['nome_artistico'])?></h3>
                            
                            <!--NOME COMPLETO DO ATOR-->
                            <div class="nome_completo">
                                <span class="texto_tipo">Nome Completo: </span><span><?php echo($rsdestaque['nome_completo'])?></span>
                            </div>
                            
                            <!--DATA DE NASCIMENTO DO ATOR-->
                            <div class="data_nascimento">
                                <span class="texto_tipo">Data de Nascimento: </span><span><?php echo($dt_nasc)?></span>
                            </div>
                          
                            <!--NACIONALIDADE DO ATOR-->
                            <div class="nacionalidade">
                                <span class="texto_tipo">Estado Civil: </span><span><?php echo($rsdestaque['estado_civil'])?></span>
                            </div>
                            
                            <!--LOCAL DE NASCIMENTO DO ATOR-->
                            <!--<div class="local_nascimento">
                                <span class="texto_tipo">Local de Nascimento: </span><span><?php echo($rsdestaque['local'])?></span>
                            </div>-->
                            
                            <!--O QUE O ATOR FAZ ATUALMENTE-->
                            <div class="ocupacao">
                                <span class="texto_tipo">Ocupações: </span><span><?php echo($rsdestaque['profissoes'])?></span>
                            </div>
                            
                        </div>
                        
                        <!--BIOGRAFIA DO ATOR-->
                        <div class="caixa_biografia">
                            <div class="titulo_biografia"><h2>Biografia</h2></div>
                            
                            <p><?php echo(nl2br($rsdestaque['biografia']))?></p>
                            
                        </div>
                        
                        <!--FILMOGRAFIA DO ATOR-->
                        <!--<div class="filmografia">
                            <div class="titulo_filmografia"><h2>Filmografia</h2></div>
                            <div class="filme">
                                <figure class="foto_filme">
                                    <img src="img/bela.jpg" alt="Bela" title="Bela">
                                </figure>
                                <div class="nome_filme">
                                    <span>SEI LÁ ffgf fdg</span>
                                </div>
                            </div>
                            <div class="filme">
                                <figure class="foto_filme">
                                    <img src="img/bela.jpg" alt="Bela" title="Bela">
                                </figure>
                                <div class="nome_filme">
                                    <span>SEI LÁ</span>
                                </div>
                            </div>
                            <div class="filme">
                                <figure class="foto_filme">
                                    <img src="img/bela.jpg" alt="Bela" title="Bela">
                                </figure>
                                <div class="nome_filme">
                                    <span>SEI LÁ</span>
                                </div>
                            </div>
                      </div>-->
                      <?php
                          
                          }/*else{
                      
                            echo("<h1 class='center'>Não se esqueça de colocar a profissão do ator.</h1>");

                          }*/
                  
                        }
                  
                      ?>
                  
                  
                </div>
            </div>
        </div>
        <!--RODAPE-->
        <?php include("rodape.php")?>
    </body>
</html>