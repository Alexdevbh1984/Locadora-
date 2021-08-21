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
    $cod_produto_filme = 0;
    $cod_diretor= 0;
    $modo = null;
    $botao = "Salvar";


    if(isset($_POST['btnEnviar'])){
        
        $filme = $_POST['slt_filme'];
        $diretor = $_POST['slt_diretor'];

         //VERIFICANDO SE O PRODUTO QU ESTÁ SENDO CADSTRADO JÁ EXISTE
        $sql = "SELECT * FROM tbl_filme_diretor WHERE cod_diretor =".$diretor." AND cod_produto_filme = ".$filme."";
        $select = mysqli_query($conexao, $sql);
        
        if(mysqli_num_rows($select) == 0){
            //INSERIR REGISTRO NO BANCO            
            if($_POST['btnEnviar'] == "Salvar"){

                $sql = "INSERT INTO tbl_filme_diretor (cod_produto_filme, cod_diretor) VALUES (
                        '".$filme."', '".$diretor."')";


            //ATUALIZAR REGISTRO NO BANCO
            }elseif($_POST['btnEnviar'] == "Editar"){

                $sql = "UPDATE tbl_filme_diretor SET cod_produto_filme = ".$filme.", cod_diretor = ".$diretor." WHERE cod_filme_diretor =".$_SESSION['idregistro'];

                var_dump($sql);

            }


            if(mysqli_query($conexao,$sql)){
                header('location:filme_diretor.php');
            }else{
                echo("<script> alert('Erro no cadastro!!') </script>");
            }
        }else{
          echo("<script>alert('O diretor já está cadastrado com esse filme!')</script>");
        }
        
    }

    if(isset($_GET['modo'])){
        $modo = $_GET['modo'];
        $codigo = $_GET['id'];
        
        $_SESSION['idregistro'] = $codigo;
        
        //ESCLUIR REGISTRO NO BANCO
        if($modo == 'excluir'){
            
            
            $sql = "DELETE FROM tbl_filme_diretor WHERE cod_filme_diretor = ".$codigo." ";
            mysqli_query($conexao, $sql);
            
            
            header('location:filme_diretor.php');
         
        //FAZENDO A BUSCA DO REGISTRO NO BANCO
        }elseif($modo == 'buscar'){
            
            $sql = "SELECT tbl_produto_filme.*,tbl_filme_diretor.*,tbl_diretor.*
                    FROM tbl_produto_filme INNER JOIN tbl_filme_diretor
                    ON tbl_produto_filme.cod_produto_filme = tbl_filme_diretor.cod_produto_filme
                    INNER JOIN tbl_diretor
                    ON tbl_filme_diretor.cod_diretor = tbl_diretor.cod_diretor
                    WHERE cod_filme_diretor =".$codigo;
            
            
            
            $select = mysqli_query($conexao,$sql);
            
            if($rsrelacao = mysqli_fetch_array($select)){
                
                $filme = $rsrelacao['nome_filme'];
                $diretor = $rsrelacao['diretor'];
                
                $cod_produto_filme = $rsrelacao['cod_produto_filme'];
                
                $cod_diretor = $rsrelacao['cod_diretor'];
                
                $botao = "Editar";
                
            }
            
            
            
            
        }
        
    }






?>


<!doctype html>

<html>
    <head>
        <title>CMS - Sitema de Gerenciamento do Site</title>
      
        <link href="css/filme_diretor.css" type="text/css" rel="stylesheet">
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
                  
                    <!--MENU COM OS CADASTROS RELACIONADOS AO FILME-->
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
                  
                        <div class="titulo_relacao"><h2>Cadastro de Filme com Diretor</h2></div>
                        <div class="cadastro_relacao center">
                          
                            <!--FORMULÁRIO-->
                            <form action="filme_diretor.php" method="post" name="frm_filme_diretor" enctype="multipart/form-data">

                                <!--ÁREA DE CADASTRO DO FILME-->
                                <div class="form_filme">
                                    <div class="form_texto_usuario">
                                        <label>Filme:</label>
                                    </div>
                                    <div class="form_caixa_usuario">

                                        <!--SELECT COM A BUSCA DE TODOS OS FILMES-->
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

                                                $sql = "SELECT * FROM tbl_produto_filme WHERE cod_produto_filme <> ".$cod_produto_filme ." ORDER BY nome_filme";
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
                                
                                <!--AREA COM O CADASTRO DOS DIRETORES-->
                                <div class="form_diretor">
                                    <div class="form_texto_usuario">
                                        <label>Diretor:</label>
                                    </div>
                                    <div class="form_caixa_usuario">
                                      
                                        <!--SELECT COM A BUSCA DE TODOS OS DIRETORES-->
                                        <select name="slt_diretor" class="txtDiretor" required>

                                            <?php

                                                if($modo == 'buscar'){

                                            ?>


                                              <option value="<?php echo($cod_diretor)?>"><?php echo($diretor)?></option>

                                            <?php

                                              }else{

                                            ?>

                                              <option value="">Selecione...</option>

                                            <?php

                                                }

                                                $sql = "SELECT * FROM tbl_diretor WHERE cod_diretor <> ".$cod_diretor." ORDER BY diretor";
                                                $select = mysqli_query($conexao,$sql);

                                                while($rsdiretor = mysqli_fetch_array($select)){
                                            ?>

                                            <option value="<?php echo($rsdiretor ['cod_diretor'])?>"><?php echo($rsdiretor ['diretor'])?></option>
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
                                    <h4>Diretor</h4>
                                </div>
                                <div class="titulo_opcoes">
                                    <h4>Opções</h4>
                                </div>

                            </div>
                            <?php

                                /*SELECT QUE TRAZ OS FILMES E DIRETORES DO FILME*/                      
                                $sql = "SELECT tbl_produto_filme.*,tbl_filme_diretor.*,tbl_diretor.*
                                        FROM tbl_produto_filme INNER JOIN tbl_filme_diretor
                                        ON tbl_produto_filme.cod_produto_filme = tbl_filme_diretor.cod_produto_filme
                                        INNER JOIN tbl_diretor
                                        ON tbl_filme_diretor.cod_diretor = tbl_diretor.cod_diretor
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
                                    <span><?php echo($rsrelacionamento['diretor'])?></span>
                                </div>
                                <div class="linha_botao">




                                    <figure class="editar">
                                        <a href="filme_diretor.php?modo=buscar&id=<?php echo($rsrelacionamento['cod_filme_diretor'])?>">
                                            <img src="img/iconfinder_new-24_103173.png" alt="Editar" title="Editar" class="botao" >
                                        </a>
                                    </figure>

                                    <figure class="excluir">
                                        <a href="filme_diretor.php?modo=excluir&id=<?php echo($rsrelacionamento['cod_filme_diretor'])?>">

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