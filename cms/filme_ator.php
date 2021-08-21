<?php

    session_start();

    /*VERIFICACAO DE TODAS AS PAGINAS PARA NÃO ENTRAR DIRETOR PELA URL NO CMS*/
    if(!isset($_SESSION['login'])){
    
      session_destroy();
      header('location:../index.php');
    
    }


    require_once('../db/conexao.php');
    $conexao = conexaoMysql();
    
    //VARIAVEIS DECLARADAS
    $ator = null;
    $profissao = null;
    $cod_produto_filme = 0;
    $cod_ator= 0;
    $modo = null;
    $botao = "Salvar";


    if(isset($_POST['btnEnviar'])){
        
        $filme = $_POST['slt_filme'];
        $ator = $_POST['slt_ator'];

         //VERIFICANDO SE O PRODUTO QU ESTÁ SENDO CADSTRADO JÁ EXISTE
        $sql = "SELECT * FROM tbl_filme_ator WHERE cod_ator =".$ator." AND cod_produto_filme = ".$filme."";
        $select = mysqli_query($conexao, $sql);
        
      //SE O SELECT FOR IGUAL A ZERO, ENTÃO VAI INSERIR OU CADASTRAR
        if(mysqli_num_rows($select) == 0){
            //INSERIR REGISTRO NO BANCO DE DADOS            
            if($_POST['btnEnviar'] == "Salvar"){

                $sql = "INSERT INTO tbl_filme_ator (cod_produto_filme, cod_ator) VALUES (
                        '".$filme."', '".$ator."')";

                echo($sql);

            //ATUALIZAR REGISTROS NO BANCO DE DADOS
            }elseif($_POST['btnEnviar'] == "Editar"){

                $sql = "UPDATE tbl_filme_ator SET cod_ator = ".$ator.", cod_produto_filme = ".$filme." WHERE cod_filme_ator=".$_SESSION['idregistro'];



            }

            if(mysqli_query($conexao,$sql)){
                header('location:filme_ator.php');
            }else{
                echo("<script> alert('Erro no cadastro!!') </script>");
            }
          
        }else{
          echo("<script>alert('O ator já está cadastrado com esse filme!')</script>");
        }
        
    }

    if(isset($_GET['modo'])){
        $modo = $_GET['modo'];
        $codigo = $_GET['id'];
        
        $_SESSION['idregistro'] = $codigo;
        
        //EXCLUIR REGISTROS NO BANCO DE DADOS
        if($modo == 'excluir'){
            
            
            $sql = "DELETE FROM tbl_filme_ator WHERE cod_filme_ator = ".$codigo." ";
            mysqli_query($conexao, $sql);
            
            
            header('location:filme_ator.php');
        
        //FAZENDO BUSCA DE REGISTROS NO BANOC DE DADOS
        }elseif($modo == 'buscar'){
            
            $sql = "SELECT tbl_ator.nome_artistico, tbl_filme_ator.*, tbl_produto_filme.nome_filme
                    FROM tbl_ator INNER JOIN tbl_filme_ator
                    ON tbl_ator.cod_ator = tbl_filme_ator.cod_ator
                    INNER JOIN tbl_produto_filme
                    ON tbl_filme_ator.cod_produto_filme = tbl_produto_filme.cod_produto_filme
                    WHERE cod_filme_ator =".$codigo;
            
            
            
            $select = mysqli_query($conexao,$sql);
            
            if($rsrelacao = mysqli_fetch_array($select)){
                
                $ator = $rsrelacao['nome_artistico'];
                $filme = $rsrelacao['nome_filme'];
                
                $cod_ator = $rsrelacao['cod_ator'];
                
                $cod_produto_filme = $rsrelacao['cod_produto_filme'];
                
                $botao = "Editar";
                
            }
            
            
            
            
        }
        
    }






?>


<!doctype html>

<html>
    <head>
        <title>CMS - Sitema de Gerenciamento do Site</title>
      
        <link href="css/filme_ator.css" type="text/css" rel="stylesheet">
        <link href="css/formatacao.css" type="text/css" rel="stylesheet">
        <link href="css/cabecalho_menu_adm.css" type="text/css" rel="stylesheet">
        <link href="css/rodape.css" type="text/css" rel="stylesheet">
        <meta charset="utf-8">
    </head>
    <body>
        <div id="caixa_conteudo">
            <div id="conteudo" class="center">
                
                <?php include('cabecalho_menu_adm.php')?>
                
                
                <div class="itens">
                  
                  <!--MENU VERTICAL COM O CADASTRO DE CARACTERISTICAS DO FILME-->
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
                                <!--<a href="filme_diretor.php">

                                  <div class="sub_itens">
                                      Diretor e Filme
                                  </div>
                              </a>
                                <a href="filme_genero.php">

                                  <div class="sub_itens">
                                      Gênero e Filme
                                  </div>
                              </a>-->


                            </div>

                        </div>

                        <!--<a href="filme_mes.php">
                            <div class="menu menu_opcoes">

                                 Filme do mês

                            </div>
                        </a>-->  
                        
                    </div>
                  <div class="cadastro">
                    <div class="titulo_filme_ator"><h2>Cadastro de Filme com Ator</h2></div>
                    <div class="cadastro_filme_ator center">
                      
                        <!--FORMULÁRIO-->
                        <form action="filme_ator.php" method="post" name="frm_filme_ator" enctype="multipart/form-data">
                           
                            <!--AREA DE CADASTRO DO FILME-->
                            <div class="form_filme">
                                <div class="form_texto_usuario">
                                    <label>Filme:</label>
                                </div>
                                <div class="form_caixa_usuario">
                                  
                                    <!--SELECT COM A BUSCA DE TODOS OS ATORES-->
                                    <select name="slt_filme" class="txtFilme" required>
                                        <?php
                                      
                                            if($modo == 'buscar'){
                                      
                                        ?>
                                      
                                      
                                          <option value="<?php echo($cod_produto_filme)?>"><?php echo($filme)?></option>
                                      
                                        <?php
                                      
                                          }else{
                                      
                                        ?>
                                        
                                          <option value="">Selecione...</option>
                                        
                                        <?php
                                              
                                          }
                      
                                            //SELECT PARA QUE TRAGA OS FILMES DIFENTES DO CODIGO FILME QUE FOI DECLARADO LÁ EM CIMA
                                            $sql = "SELECT * FROM tbl_produto_filme WHERE cod_produto_filme <> ".$cod_produto_filme." ORDER BY nome_filme";
                                            $select = mysqli_query($conexao,$sql);
                
                                            while($rsfilme = mysqli_fetch_array($select)){
                                        ?>
                                        
                                        <option value="<?php echo($rsfilme['cod_produto_filme'])?>"><?php echo($rsfilme['nome_filme'])?></option>
                                        <?php
                                        
                                            }
                                        
                                        ?>

                                        
                                    </select>
                                </div>
                            </div>
                            
                            <!--AREA DE CADSTRO DOS ATORES-->
                            <div class="form_ator">
                                <div class="form_texto_usuario">
                                    <label>Atores:</label>
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
                                              
                                        /*SELECT TRAZENDO OS ATORES DIFERENTES DO SELECIONADO*/
                                            $sql = "SELECT * FROM tbl_ator WHERE cod_ator <> ".$cod_ator." ORDER BY nome_artistico";
                                            $select = mysqli_query($conexao,$sql);
                
                                            while($rsator = mysqli_fetch_array($select)){
                                        ?>
                                        
                                        <option value="<?php echo($rsator['cod_ator'])?>"><?php echo($rsator['nome_artistico'])?></option>
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
                                <h4>Filme</h4>
                            </div>
                            <div class="titulo_profissao">
                                <h4>Ator</h4>
                            </div>
                            <div class="titulo_opcoes">
                                <h4>Opções</h4>
                            </div>
                            
                        </div>
                        <?php
  
                            /*SELECT QUE TRAZ OS FILMES E OS ATORES*/                      
                            $sql = "SELECT tbl_ator.*,tbl_filme_ator.*,tbl_produto_filme.*
                                    FROM tbl_produto_filme INNER JOIN tbl_filme_ator
                                    ON tbl_produto_filme.cod_produto_filme = tbl_filme_ator.cod_produto_filme
                                    INNER JOIN tbl_ator
                                    ON tbl_ator.cod_ator = tbl_filme_ator.cod_ator
                                    ORDER BY tbl_produto_filme.nome_filme";
                                       
                            $select = mysqli_query($conexao,$sql);
                                       
                            while($rsrelacionamento = mysqli_fetch_array($select)){
                                
                            
                        
                        ?>
                            
                        <!--LINHAS COM OS REGISTROS-->
                        <div class="informacoes_form">
                            <div class="linha_informacao">
                                <span><?php echo($rsrelacionamento['nome_filme'])?></span>
                            </div>
                            <div class="linha_profissao">
                                <span><?php echo($rsrelacionamento['nome_artistico'])?></span>
                            </div>
                            <div class="linha_botao">




                                <figure class="editar">
                                    <a href="filme_ator.php?modo=buscar&id=<?php echo($rsrelacionamento['cod_filme_ator'])?>">
                                        <img src="img/iconfinder_new-24_103173.png" alt="Editar" title="Editar" class="botao" >
                                    </a>
                                </figure>

                                <figure class="excluir">
                                    <a href="filme_ator.php?modo=excluir&id=<?php echo($rsrelacionamento['cod_filme_ator'])?>">

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
    </body>
</html>