<?php
    

    session_start();

    /*VERIFICACAO DE TODAS AS PAGINAS PARA NÃO ENTRAR DIRETOR PELA URL NO CMS*/
    if(!isset($_SESSION['login'])){
    
      session_destroy();
      header('location:../index.php');
    
    }

    require_once('../db/conexao.php');
    $conexao = conexaoMysql();
    
    $diretor = null;
    
    $botao = "Salvar";
    $cod_estado = 0;
    

    if(isset($_GET['modo'])){
        
        $modo = $_GET['modo'];
        $codigo = $_GET['id'];
        
        $_SESSION['idregistro'] = $codigo;
        
      
        //EXCLUINDO OS REGISTROS
        if($modo == 'excluir'){
            
            $sql = "DELETE FROM tbl_diretor WHERE cod_diretor = ".addslashes($codigo)." ";
            
            mysqli_query($conexao,$sql);
         
          
        //FAZENDO UMA BUSCA NO BANCO DE DADOS
        }elseif($modo == 'buscar'){
            
            $sql = "SELECT * FROM tbl_diretor WHERE cod_diretor = ".addslashes($codigo);
            
            $select = mysqli_query($conexao,$sql);
            
            if($rsdiretores = mysqli_fetch_array($select)){
                
                $diretor = $rsdiretores['diretor'];
                
                
                
            }
            
            $botao = "Editar";
            
            
        }
        
        
    }
    

  
    if(isset($_POST['btnEnviar'])){
        
        $diretor = $_POST['txt_diretor'];
        
      
        //INSERINDO OS REGISTROS NO BANCO DE DADOS
        if($_POST['btnEnviar'] == "Salvar"){
        
            $sql = "INSERT INTO tbl_diretor(diretor) VALUES ('".addslashes($diretor)."')";


        //ATUALIZANDO OS REGISTROS NO BANCO DE DADOS   
        }elseif($_POST['btnEnviar'] == "Editar"){
            
            $sql = "UPDATE tbl_diretor SET diretor ='".addslashes($diretor)."' WHERE cod_diretor = ".$_SESSION['idregistro'];
            
            
        }
        
        if(mysqli_query($conexao,$sql)){

            header('location:diretor.php');
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
        <link href="css/diretor.css" type="text/css" rel="stylesheet">
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
                  
                    <!--AREA DE MENU PARA CADASTRO DE CARACTERÍSTICAS RELACIONADAS AOS FILMES-->
                    <div id="menu_cadastro">
                        <a href="filme.php">
                            <div class="menu menu_filme">

                                 Filme

                            </div>
                        </a> 

                        <a href="diretor.php">
                            <div class="menu menu_opcoes">

                                 Diretor

                            </div>
                        </a>  
                        <a href="genero.php">
                              <div class="menu menu_opcoes">

                                   Gênero

                            </div>
                        </a>  
                        <a href="classificacao.php">
                            <div class="menu menu_opcoes">

                                 Classificação

                            </div>
                        </a>  

                        <a href="ator.php">
                            <div class="menu menu_opcoes">

                                 Ator

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
                                <a href="filme_diretor.php">

                                  <div class="sub_itens">
                                      Diretor e Filme
                                  </div>
                              </a>
                                <a href="filme_genero.php">

                                  <div class="sub_itens">
                                      Gênero e Filme
                                  </div>
                              </a>


                            </div>

                        </div>

                        <a href="filme_mes.php">
                            <div class="menu menu_opcoes">

                                 Filme do mês

                            </div>
                        </a>  
                        
                    </div>
                    
                  
                    
                    <div class="cadastro">
                  
                        <div class="titulo_diretor"><h2>Cadastro de Diretor</h2></div>
                        <div class="cadastro_diretor center">
                          
                            <!--FORMULÁRIO-->
                            <form action="diretor.php" method="post" name="frm_diretor">
                              
                                <!--AREA DE CADSTRO DO DIRETOR-->
                                <div class="form_diretor">
                                    <div class="form_texto_usuario">
                                        <label>Diretor:</label>
                                    </div>
                                    <div class="form_caixa_usuario">
                                        <input name="txt_diretor" class="txtDiretor" type="text" value="<?php echo($diretor);?>" onkeypress="return ValidarNumero(event);" required maxlength="100" autocomplete="off">
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
                                    <h4>Diretor</h4>
                                </div>

                                <div class="titulo_opcoes">
                                    <h4>Opções</h4>
                                </div>

                            </div>

                            <?php

                                //SELECT NA TABELA DIRETOR
                                $sql = 'SELECT * FROM tbl_diretor';

                                $select = mysqli_query($conexao, $sql);

                                while($rsdiretor = mysqli_fetch_array($select)){




                            ?>
                          
                            <!--LINHAS COM OS REGISTROS-->
                            <div class="informacoes_form">
                                <div class="linha_informacao">
                                    <span><?php echo($rsdiretor['diretor']);?></span>
                                </div>
                                <div class="linha_botao">
                                    <figure class="editar">
                                        <a href="diretor.php?modo=buscar&id=<?php echo($rsdiretor['cod_diretor']);?>">
                                            <img src="img/iconfinder_new-24_103173.png" alt="Editar" title="Editar" class="botao " >
                                        </a>
                                    </figure>

                                    <figure class="excluir">
                                        <a href="diretor.php?modo=excluir&id=<?php echo($rsdiretor['cod_diretor']);?>">
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