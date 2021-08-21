<?php
    session_start();

    /*VERIFICACAO DE TODAS AS PAGINAS PARA NÃO ENTRAR DIRETOR PELA URL NO CMS*/
    if(!isset($_SESSION['login'])){
    
      session_destroy();
      header('location:../index.php');
    
    }

    require_once('../db/conexao.php');
    $conexao = conexaoMysql();
    
    $civil = null;
    $botao = "Salvar";
    $modo = null;

    if(isset($_GET['modo'])){
        $modo = $_GET['modo'];
        $codigo = $_GET['id'];
        
        
        $_SESSION['idregistro'] = $codigo;
        
        //EXCLUINDO REGISTROS NO BANCO DE DADOS
        if($modo == 'excluir'){
            
            $sql = "DELETE FROM tbl_estado_civil WHERE cod_estado_civil = ".addslashes($codigo)."";
            
            mysqli_query($conexao, $sql);
            
        
        //FAZENDO UMA BUSCA NO BANCO DE DADOS
        }elseif($modo == 'buscar'){
            $sql = "SELECT * FROM tbl_estado_civil
                    WHERE cod_estado_civil =".addslashes($codigo);
            
            $select = mysqli_query($conexao, $sql);
            
            if($rscivil = mysqli_fetch_array($select)){
                
                
                /*var_dump($rsloja);*/
                
                $civil = $rscivil['estado_civil'];

            }
            
                $botao = 'Editar';
            
            
        }
        
        
        
    }
    

    if(isset($_POST['btnEnviar'])){
        
        $civil = $_POST['txt_estado_civil'];
        
          
          //INSERINDO REGISTROS NO BANCO DE DADOS          
          if($_POST['btnEnviar'] == "Salvar"){

              $sql = "INSERT INTO tbl_estado_civil (estado_civil)
                      VALUES ('".addslashes($civil)."')";
          
          //ATUALIZANDO REGISTROS NO BANCO DE DADOS
          }elseif($_POST['btnEnviar'] == "Editar"){


              $sql = "UPDATE tbl_estado_civil SET estado_civil = '".addslashes($civil)."' WHERE cod_estado_civil=".$_SESSION['idregistro'];


          }


          if(mysqli_query($conexao,$sql)){

              header('location:estado_civil.php');

          }else{

              echo('erro');

          }
    }
               


?>

<!doctype html>
<html>
    <head>
        <title>CMS - Sitema de Gerenciamento do Site</title>
        <link href="css/estado_civil.css" type="text/css" rel="stylesheet">
        <link href="css/formatacao.css" type="text/css" rel="stylesheet">
        <link href="css/cabecalho_menu_adm.css" type="text/css" rel="stylesheet">
        <link href="css/rodape.css" type="text/css" rel="stylesheet">
        <script src="../js/valida.js"></script>
        <meta charset="utf-8">
    </head>
    <body>
        <div id="caixa_conteudo">
            <div id="conteudo" class="center">
                
                <?php include('cabecalho_menu_adm.php')?>
                
                <div class="itens">
                  
                    <!--AREA DO MENU VERTICAL COM OS CADASTROS RELACIONADOS AO FILME-->
                    <div id="menu_cadastro">
                        <a href="filme.php">
                            <div class="menu menu_filme">

                                 Filme

                            </div>
                        </a> 

                        <a href="ator.php">
                            <div class="menu menu_opcoes">

                                 Ator

                            </div>
                        </a> 
                        <a href="estado_civil.php">

                            <div class="menu menu_opcoes">
                                Estado Civil
                            </div>
                        </a>
                        <a href="profissao.php">
                            <div class="menu menu_opcoes">

                                  Profissão

                            </div>
                        </a>  
                        <div class="menu menu_opcoes">

                             Relação
                            <div class="sub_menu">
                                <a href="filme_ator.php">
                                  <div class="sub_itens">
                                      Ator e Filme
                                  </div>
                              </a>
                              <a href="ator_profissao.php">
                                  <div class="sub_itens">
                                      Profissão e Ator
                                  </div>
                                </a>

                            </div>

                        </div>
 
                        
                    </div>
                    
                    <div class="cadastro">
                        <div class="titulo_estado_civil"><h2>Cadastro do Estado Civil</h2></div>
                        <div class="cadastro_estado_civil center">
                          
                            <!--FORMULÁRIO-->
                            <form action="estado_civil.php" method="post" name="frm_estado_civil" enctype="multipart/form-data">
                                
                                <!--AREA DE CADASTRO DO ESTADO CIVIL-->
                                <div class="form_estado_civil">
                                    <div class="form_texto_civil">
                                        <label>Estado Civil:</label>
                                    </div>
                                    <div class="form_caixa_estado_civil">
                                        <input name="txt_estado_civil" class="txtEstadoCivil" type="text" value="<?php echo($civil)?>" onkeypress="return ValidarNumero(event);" required autocomplete="off" maxlength="100">
                                    </div>
                                </div>

                                <div class="botao_form">
                                    <input type="submit" name="btnEnviar" class="form_btn" value="<?php echo($botao);?>">
                                </div>
                            </form>
                        </div>
                      
                        <!--TABELA COM OS REGISTROS-->
                        <div class="tabela center">
                          
                            <!--TITULOS DAS COLUNAS-->
                            <div class="linha_titulo">
                                <div class="titulo_form">
                                    <h4>Estado Civil</h4>
                                </div>
                                <div class="titulo_opcoes">
                                    <h4>Opções</h4>
                                </div>

                            </div>

                            <?php

                                /*SELECT PARA PEGAR TODAS AS LOJAS, AS CIDADES E OS ESTADOS*/
                                $sql = "SELECT * from tbl_estado_civil";


                                $select = mysqli_query($conexao, $sql);



                                while($rscivil= mysqli_fetch_array($select)){


                            ?>

                            <!--LINHAS COM OS REGISTROS-->
                            <div class="informacoes_form">
                                <div class="linha_informacao">
                                    <span><?php echo($rscivil['estado_civil'])?></span>
                                </div>
                                <div class="linha_botao">

                                    <figure class="editar">
                                        <a href="estado_civil.php?modo=buscar&id=<?php echo($rscivil['cod_estado_civil'])?>">
                                            <img src="img/iconfinder_new-24_103173.png" alt="Editar" title="Editar" class="botao" >
                                        </a>
                                    </figure>

                                    <figure class="excluir">
                                        <a href="estado_civil.php?modo=excluir&id=<?php echo($rscivil['cod_estado_civil']);?>">

                                            <img src="img/ic_delete.png" alt="Excluir" title="Excluir" class="botao " onclick="return confirm('Tem certeza que deseja excluir?')">

                                        </a>
                                    </figure>


                                </div>

                            </div>  
                            <?php

                                }

                            ?>


                        </div>
                    </div>
                  
                </div>
               
                <?php include('rodape.php')?>
                
            </div>
        </div>
        <script src="../js/expressaoRegular.js"></script>

    </body>
</html>