<?php


    session_start();
  
    /*VERIFICACAO DE TODAS AS PAGINAS PARA NÃO ENTRAR DIRETOR PELA URL NO CMS*/
    if(!isset($_SESSION['login'])){
    
      session_destroy();
      header('location:../index.php');
    
    }

    require_once('../db/conexao.php');
    $conexao = conexaoMysql();
    
    $ator = null;
    $profissao = null;
    $cod_ator = 0;
    $cod_profissao= 0;
    $modo = null;
    $botao = "Salvar";


    if(isset($_POST['btnEnviar'])){
        
        $ator = $_POST['slt_ator'];
        $profissao = $_POST['slt_profissao'];

        
        //INSERIR REGISTRO NO BANCO DE DADOS            
        if($_POST['btnEnviar'] == "Salvar"){

            $sql = "INSERT INTO tbl_ator_profissao (cod_ator, cod_profissao) VALUES (
                    '".$ator."', '".$profissao."')";


        // ATUALIZAR REGISTRO NO BANCO DE DADOS
        }elseif($_POST['btnEnviar'] == "Editar"){

            $sql = "UPDATE tbl_ator_profissao SET cod_ator = ".$ator.", cod_profissao = ".$profissao." WHERE cod_ator_profissao =".$_SESSION['idregistro'];
                      


        }
      
      
        if(mysqli_query($conexao,$sql)){
            header('location:ator_profissao.php');
        }else{
            echo("<script> alert('Erro no cadastro!!') </script>");
        }
        
    }

    if(isset($_GET['modo'])){
        $modo = $_GET['modo'];
        $codigo = $_GET['id'];
        
        $_SESSION['idregistro'] = $codigo;
        
        //EXCLUIR REGISTRO NO BANCO DE DADOS
        if($modo == 'excluir'){
            
            
            $sql = "DELETE FROM tbl_ator_profissao WHERE cod_ator_profissao = ".$codigo." ";
            mysqli_query($conexao, $sql);
            
            
            header('location:ator_profissao.php');
        
        // FAZER UMA BUSCA DE REGISTROS NO BANCO DE DADOS
        }elseif($modo == 'buscar'){
            
          //select fazendo a busca dos atores e suas profissões
            $sql = "SELECT tbl_ator.nome_artistico, tbl_ator_profissao.*, tbl_profissao.profissao
                    FROM tbl_ator INNER JOIN tbl_ator_profissao
                    ON tbl_ator.cod_ator = tbl_ator_profissao.cod_ator
                    INNER JOIN tbl_profissao
                    ON tbl_ator_profissao.cod_profissao = tbl_profissao.cod_profissao
                    WHERE cod_ator_profissao =".$codigo;
            
            
            
            $select = mysqli_query($conexao,$sql);
            
            if($rsator = mysqli_fetch_array($select)){
                
                $ator = $rsator['nome_artistico'];
                $profissao = $rsator['profissao'];
                
                $cod_ator = $rsator['cod_ator'];
                
                $cod_profissao = $rsator['cod_profissao'];
                
                $botao = "Editar";
                
            }
            
            
            
            
        }
        
    }


?>


<!doctype html>

<html>
    <head>
        <title>CMS - Sitema de Gerenciamento do Site</title>
      
        <link href="css/ator_profissao.css" type="text/css" rel="stylesheet">
        <link href="css/formatacao.css" type="text/css" rel="stylesheet">
        <link href="css/cabecalho_menu_adm.css" type="text/css" rel="stylesheet">
        <link href="css/rodape.css" type="text/css" rel="stylesheet">
        <meta charset="utf-8">
    </head>
    <body>
        <div id="caixa_conteudo">
            <div id="conteudo" class="center">
                
                <?php include('cabecalho_menu_adm.php')?>
                
                <!--AREA DE MENU PARA CADASTRO -->
                <div class="itens">
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
                    <div class="titulo_ator_profissao"><h2>Cadastro de Ator com Profissão</h2></div>
                    <div class="cadastro_ator_profissao center">
                        <form action="ator_profissao.php" method="post" name="frm_ator_profissao" enctype="multipart/form-data">
                           
                            <!--AREA DE ESCOLHA DO ATOR-->
                            <div class="form_ator">
                                <div class="form_texto_usuario">
                                    <label>Ator:</label>
                                </div>
                                <div class="form_caixa_usuario">
                                  
                                    <!--SELECT COM A BUSCA DE TODOS OS ATORES-->
                                    <select name="slt_ator" class="txtAtor" required>
                                        <?php
                                      
                                            if($modo == 'buscar'){
                                      
                                        ?>
                                      
                                      
                                          <option value="<?php echo($cod_ator)?>"><?php echo($ator)?></option>
                                      
                                        <?php
                                      
                                          }else{
                                      
                                        ?>
                                        
                                          <option value="">Selecione...</option>
                                        
                                        <?php
                                              
                                              }
              
                                            /*select que tráz os atores diferentes do que está selecionado*/
                                            $sql = "SELECT * FROM tbl_ator WHERE cod_ator <> ".$cod_ator." ORDER BY nome_artistico";
                                            $select = mysqli_query($conexao,$sql);
                
                                            while($rsatores = mysqli_fetch_array($select)){
                                        ?>
                                        
                                        <option value="<?php echo($rsatores['cod_ator'])?>"><?php echo($rsatores['nome_artistico'])?></option>
                                        <?php
                                        
                                            }
                                        
                                        ?>

                                        
                                    </select>
                                </div>
                            </div>
                            
                            <!--AREA COM A ESOCLHA DE PROFISSÕES-->
                            <div class="form_profissao">
                                <div class="form_texto_usuario">
                                    <label>Profissão:</label>
                                </div>
                                <div class="form_caixa_usuario">
                                    <!--SELECT COM A BUSCA DE TODOS AS PROFISSOES-->

                                    <select name="slt_profissao" class="txtProfissao" required>
                                        
                                        <?php
                                      
                                            if($modo == 'buscar'){
                                      
                                        ?>
                                      
                                      
                                          <option value="<?php echo($cod_profissao)?>"><?php echo($profissao)?></option>
                                      
                                        <?php
                                      
                                          }else{
                                      
                                        ?>
                                        
                                          <option value="">Selecione...</option>
                                        
                                        <?php
                                              
                                            }
                                               /*select que tráz as profissões diferentes do que está selecionado*/
                                            $sql = "SELECT * FROM tbl_profissao WHERE cod_profissao <> ".$cod_profissao." ORDER BY profissao";
                                            $select = mysqli_query($conexao,$sql);
                
                                            while($rsprofissao = mysqli_fetch_array($select)){
                                        ?>
                                        
                                        <option value="<?php echo($rsprofissao['cod_profissao'])?>"><?php echo($rsprofissao['profissao'])?></option>
                                        <?php
                                        
                                            }
                                        
                                        ?>
                                    
                                    </select>
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
                                <h4>Nome</h4>
                            </div>
                            <div class="titulo_profissao">
                                <h4>Profissao</h4>
                            </div>
                            <div class="titulo_opcoes">
                                <h4>Opções</h4>
                            </div>
                            
                        </div>
                        <?php
  
                            /*SELECT QUE TRAZ OS NOMES E AS PROFISSOES DOS ATORES*/                      
                            $sql = "SELECT tbl_ator.*,tbl_profissao.*,tbl_ator_profissao.*
                                    FROM tbl_ator INNER JOIN tbl_ator_profissao
                                    ON tbl_ator.cod_ator = tbl_ator_profissao.cod_ator
                                    INNER JOIN tbl_profissao
                                    ON tbl_profissao.cod_profissao = tbl_ator_profissao.cod_profissao
                                    ORDER BY tbl_ator.nome_artistico";
                                       
                            $select = mysqli_query($conexao,$sql);
                                       
                            while($rsrelacionamento = mysqli_fetch_array($select)){
                                
                            
                        
                        ?>
                            
                        <!--LINHAS COM OS REGISTROS-->
                        <div class="informacoes_form">
                            <div class="linha_informacao">
                                <span><?php echo($rsrelacionamento['nome_artistico'])?></span>
                            </div>
                            <div class="linha_profissao">
                                <span><?php echo($rsrelacionamento['profissao'])?></span>
                            </div>
                            <div class="linha_botao">




                                <figure class="editar">
                                    <a href="ator_profissao.php?modo=buscar&id=<?php echo($rsrelacionamento['cod_ator_profissao'])?>">
                                        <img src="img/iconfinder_new-24_103173.png" alt="Editar" title="Editar" class="botao" >
                                    </a>
                                </figure>

                                <figure class="excluir">
                                    <a href="ator_profissao.php?modo=excluir&id=<?php echo($rsrelacionamento['cod_ator_profissao'])?>">

                                        <img src="img/ic_delete.png" alt="Excluir" title="Excluir" class="botao " onclick="return confirm('Tem certeza que deseja excluir?')">

                                    </a>
                                </figure>

                                <!--<figure class="desativar">
                                    <a href="ator_mes.php?ativo_ator=<?php echo($rsatores['ativo_ator'])?>&id=<?php echo($rsatores['cod_ator'])?>">
                                        <img src="img/cancel.png" alt="" title="" class="botao">
                                    </a>
                                </figure>-->


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