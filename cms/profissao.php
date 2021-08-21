<?php
    

    session_start();

    /*VERIFICACAO DE TODAS AS PAGINAS PARA NÃO ENTRAR DIRETOR PELA URL NO CMS*/
    if(!isset($_SESSION['login'])){
    
      session_destroy();
      header('location:../index.php');
    
    }

    require_once('../db/conexao.php');
    $conexao = conexaoMysql();
    
    $profissao = null;
    $botao = "Salvar";
    

    if(isset($_GET['modo'])){
        
        $modo = $_GET['modo'];
        $codigo = $_GET['id'];
        
        $_SESSION['idregistro'] = $codigo;
        
      //EXCLUINDO REGISTROS NO BANCO DE DADOS
        if($modo == 'excluir'){
            
            $sql = "DELETE FROM tbl_profissao WHERE cod_profissao = ".$codigo." ";
            
            mysqli_query($conexao,$sql);
          
          
        //FAZNEDO BUSCA DE REGISTROS NO BANCO DE DADOS    
        }elseif($modo == 'buscar'){
            
            $sql = "SELECT * FROM tbl_profissao WHERE cod_profissao = ".$codigo;
            
            $select = mysqli_query($conexao,$sql);
            
            if($rsprofissao = mysqli_fetch_array($select)){
                
                $profissao = $rsprofissao['profissao'];
                
                
                
            }
            
            $botao = "Editar";
            
            
        }
        
        
    }
    

    if(isset($_POST['btnEnviar'])){
        
        $profissao = $_POST['txt_profissao'];
      
        //INSERINDO REGISTROS NO BANCO DE DADOS  
        if($_POST['btnEnviar'] == "Salvar"){
        
            $sql = "INSERT INTO tbl_profissao(profissao) VALUES ('".addslashes($profissao)."')";


        //ATUALIZANDO REGISTROS NO BANCO DE DADOS    
        }elseif($_POST['btnEnviar'] == "Editar"){
            
            $sql = "UPDATE tbl_profissao SET profissao ='".addslashes($profissao)."' WHERE cod_profissao = ".$_SESSION['idregistro'];
            
            
            
        }
        
        if(mysqli_query($conexao,$sql)){

            header('location:profissao.php');
        }else{

            echo("<script>
                    alert('Erro no Cadastro');
                </script>");

        }
        
    }
        
    
    

?>


<!doctype html>

<html>
    <head>
        <title>CMS - Sitema de Gerenciamento do Site</title>
        <link href="css/profissao.css" type="text/css" rel="stylesheet">
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
                    
                    <!--MENU VERTICAL RELACIONADO AO FILME-->
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
                        <!--<a href="estado_civil.php">

                            <div class="menu menu_opcoes">
                                Estado Civil
                            </div>
                        </a>-->
                        <a href="profissao.php">
                            <div class="menu menu_opcoes">

                                  Profissão

                            </div>
                        </a>  


                        <div class="menu menu_opcoes">

                             Relação
                            <div class="sub_menu">
                              <a href="ator_profissao.php">
                                  <div class="sub_itens">
                                      Profissão e Ator
                                  </div>
                              </a>
                              <a href="filme_ator.php">
                                <div class="sub_itens">
                                    Ator e Filme
                                </div>
                              </a>

                            </div>

                        </div>
                        
                    </div>
                    
                    <div class="cadastro">
                      
                          <div class="titulo_profissao"><h2>Cadastro de Profissões</h2></div>
                          <div class="cadastro_profissao center">
                            
                              <!--FORMULARIO-->
                              <form action="profissao.php" method="post" name="frm_profissao">
                                
                                  <!--AREA DE CADASTRO DA PROFISSAO-->
                                  <div class="form_profissao">
                                      <div class="form_texto_usuario">
                                          <label>Profissão:</label>
                                      </div>

                                      <div class="form_caixa_usuario">
                                          <input name="txt_profissao" class="txtProfissao" type="text" value="<?php echo($profissao);?>" onkeypress="return ValidarNumero(event);" required maxlength="100" autocomplete="off">
                                      </div>

                                  </div>



                                  <div class="botao_form">
                                      <input type="submit" name="btnEnviar" class="form_btn" value="<?php echo($botao)?>">
                                  </div>
                              </form>
                          </div>
                          
                          <!--TABELA COM OS REGISTROS-->
                          <div class="tabela center">
                              <div class="linha_titulo">
                                  <div class="titulo_form">
                                      <h4>Profissao</h4>
                                  </div>

                                  <div class="titulo_opcoes">
                                      <h4>Opções</h4>
                                  </div>

                              </div>

                              <?php

                                  //SELECT DE REGISTROS NA TABELA PROFISSAO
                                  $sql = 'SELECT * FROM tbl_profissao';

                                  $select = mysqli_query($conexao, $sql);

                                  while($rsprofissoes = mysqli_fetch_array($select)){


                              ?>
                            
                              <!--LINHAS COM REGISTROS-->
                              <div class="informacoes_form">
                                  <div class="linha_informacao">
                                      <span><?php echo($rsprofissoes['profissao']);?></span>
                                  </div>
                                  <div class="linha_botao">
                                      <figure class="editar">
                                          <a href="profissao.php?modo=buscar&id=<?php echo($rsprofissoes['cod_profissao']);?>">
                                              <img src="img/iconfinder_new-24_103173.png" alt="Editar" title="Editar" class="botao " >
                                          </a>
                                      </figure>

                                      <figure class="excluir">
                                          <a href="profissao.php?modo=excluir&id=<?php echo($rsprofissoes['cod_profissao']);?>">
                                              <img src="img/ic_delete.png" alt="Excluir" title="Excluir" class="botao" onclick="return confirm('Deseja excluir esse nivel ?')">
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
    </body>
</html>