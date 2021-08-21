<?php
    session_start();

    /*VERIFICACAO DE TODAS AS PAGINAS PARA NÃO ENTRAR DIRETOR PELA URL NO CMS*/
    if(!isset($_SESSION['login'])){
    
      session_destroy();
      header('location:../index.php');
    
    }
  
    /*CONEXÃO*/
    require_once('../db/conexao.php');
    $conexao = conexaoMysql();
    
    //VARIAVEIS DECLARADAS
    $classificacao = null;
    $nome_foto = null;
    $botao = "Salvar";
    $cod_classificacao = 0;
    $modo = null;

    if(isset($_GET['modo'])){
        $modo = $_GET['modo'];
        $codigo = $_GET['id'];
        
        
        $_SESSION['idregistro'] = $codigo;
      
        //EXCLUIDNO REGISTRO NO BANCO DE DADOS
        if($modo == 'excluir'){
            $foto_excluir = $_GET['foto'];
            
            $sql = "DELETE FROM tbl_classificacao WHERE cod_classificacao = ".addslashes($codigo)."";
            
            mysqli_query($conexao, $sql);
            
            /*EXCLUINDO A FOTO*/
            $foto = "arquivos/".$foto_excluir;
            unlink($foto);
            
        
        //FAZENDO UMA BUSCA DE REGISTROS NO BANCO DE DADOS
        }elseif($modo == 'buscar'){
          
            /*SELECT DA CLASSIFICAÇÃO*/
            $sql = "SELECT * FROM tbl_classificacao
                    WHERE cod_classificacao =".addslashes($codigo);
            
            $select = mysqli_query($conexao, $sql);
            
            if($rsclassificacao = mysqli_fetch_array($select)){
                
                
                /*var_dump($rsloja);*/
                
                $classificacao = $rsclassificacao['classificacao'];
                $nome_foto = $rsclassificacao['foto_classificacao']; 
                                  
                $_SESSION['nome_foto'] = $nome_foto;

            }
            
                $botao = 'Editar';
            
            
        }
        
        
        
    }
    

    if(isset($_POST['btnEnviar'])){
        
        $classificacao = $_POST['txt_classificacao'];
        
        $diretorio = "arquivos/";//DIRETÓRIO
        $arquivos_permitidos = array(".jpeg",".png",".jpg");//ARQUIVOS ESTÃO PERMITIDOS
        
        $arquivo = $_FILES['fle_foto']['name'];/*PEGANDO O NOME DO ARQUIVO*/
        
        $tamanho_arquivo = $_FILES['fle_foto']['size'];//PEGANDO O TAMANHO DO ARQUIVO
        
        $tamanho_arquivo = round($tamanho_arquivo/1024);/*ARREDONDANDO O TAMANHO DO ARQUIVO*/
        
        $extensao_arquivo = strrchr($arquivo, ".");/*PEGANDO SOMENTE A EXTENSÃO*/
        
        $nome_arquivo = pathinfo($arquivo, PATHINFO_FILENAME);/*NOME DO ARQUIVO*/
        
        $arquivo_criptografado = md5(uniqid(time()).$nome_arquivo);//CRIPTOGRAFANDO A FOTO
        
        $foto = $arquivo_criptografado . $extensao_arquivo;//CONCATENANDO O NOME DA FOTO COM A EXTENSÃO
      
        /*SE OP INPUT FILE NÃ0 ESTIVER VAZIO ELE VAI CADASTRAR E ATUALIZAR COM A FOTO*/
        if(!empty($_FILES['fle_foto']['name'])){
          
          /*VERIFICANDO SE A EXTENSÃO DOA RQUIVO É PERMITIDA*/
          if(in_array($extensao_arquivo, $arquivos_permitidos)){
            
            /*VERIFICANDO SE O TAMANHO DO ARQUIVO É O ADEQUADO*/
            if($tamanho_arquivo <= 5000){
                
                $arquivo_temp = $_FILES['fle_foto']['tmp_name'];
                
                /*MOVENDO A FOTO PARA O SERVIDOR*/
                if(move_uploaded_file($arquivo_temp, $diretorio.$foto)){
                    
                    //INSERINDO REGISTRO NO BANCO DE DADOS 
                    if($_POST['btnEnviar'] == "Salvar"){
            
                        $sql = "INSERT INTO tbl_classificacao (classificacao, foto_classificacao)
                                VALUES ('".addslashes($classificacao)."','".addslashes($foto)."')";
                      
                      mysqli_query($conexao,$sql);
                    
                    
                    //ATUALIZANDO REGISTRO NO BANCO DE DADOS
                    }elseif($_POST['btnEnviar'] == "Editar"){


                        $sql = "UPDATE tbl_classificacao SET classificacao = '".addslashes($classificacao)."', foto_classificacao='".addslashes($foto)."' WHERE cod_classificacao =".$_SESSION['idregistro'];

                          if(mysqli_query($conexao,$sql)){

                          unlink('arquivos/'.$_SESSION['nome_foto']);

                        }

                    }
                    
                    

                        header('location:classificacao.php');

                }else{
                    
                    echo("<script> alert('Erro ao enviar o arquivo para o servidor!!!') </script>");
                    
                }
                
                
            }else{
                
                echo("<script> alert('Tamanho de arquivo inválido!!)</script>");
            }
            
          }else{
            
            echo("<script> alert('Extensão de arquivo inválido!') </script>");
          }
        
        // SE O ARQUIVO DE FOTO FOR VAZIO VAI ATUALIZAR O REGISTRO SEM A FOTO
        }elseif(empty($_FILES['fle_foto']['name'])){
          
            /*INSERIR VALORES*/
            if($_POST['btnEnviar'] == "Salvar"){

              echo("<script> alert('Escolha uma foto') </script>");


            /*ATUALIZAR VALORES*/
            }elseif($_POST['btnEnviar'] == "Editar"){


                $sql = "UPDATE tbl_classificacao SET classificacao = '".addslashes($classificacao)."'  WHERE cod_classificacao =".$_SESSION['idregistro'];

                mysqli_query($conexao,$sql);
                header('location:classificacao.php');


            }


        }
        
    }




?>

<!doctype html>
<html>
    <head>
        <title>CMS - Sitema de Gerenciamento do Site</title>
        <link href="css/classificacao.css" type="text/css" rel="stylesheet">
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
                  
                    <!--MENU VERTICAL PARA CADASTRO DE INFORMAÇÕES DO FILME-->
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
                      
                    <div class="titulo_classificacao"><h2>Cadastro de Classificação</h2></div>
                    <div class="cadastro_classificacao center">
                      
                        <!--FORMULÁRIO-->
                        <form action="classificacao.php" method="post" name="frm_classificacao" enctype="multipart/form-data">
                            
                            <!--ÁREA DE CADASTRO DA CLASSIFICAÇÃO-->
                            <div class="form_nome">
                                <div class="form_texto_usuario">
                                    <label>Classificação:</label>
                                </div>
                                <div class="form_caixa_usuario">
                                    <input name="txt_classificacao" class="txtClassificacao" type="text" value="<?php echo($classificacao)?>" onkeypress="return ValidarNumero(event);" required autocomplete="off" maxlength="150">
                                </div>
                            </div>
                            
                            <!--AREA DE CADASTRO DA FOTO-->
                            <div class="form_foto">
                                <div class="form_texto_usuario">
                                    <label>Foto:</label>
                                </div>
                                <div class="form_caixa_usuario">
                                    <input name="fle_foto" class="txtFoto" title="Selecione uma imagem" type="file"> 
                                </div>
                            </div>
                            <?php
                            
                                if(isset($nome_foto)){
                                    
                            
                            ?>
                            
                                <!--LOCAL QUE APARECE A FOTO-->
                                <div class="caixa_foto">
                                    <img src="arquivos/<?php echo($nome_foto);?>"  alt="<?php echo($classificacao)?>" title="<?php echo($classificacao)?>" class="foto">
                                </div>
                            <?php
                            
                                }
                            ?>
                            
                            
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
                                <h4>Nome</h4>
                            </div>
                            <div class="titulo_foto">
                                <h4>Foto</h4>
                            </div>
                            <div class="titulo_opcoes">
                                <h4>Opções</h4>
                            </div>
                            
                        </div>
                            
                            <?php
                                
                                /*SELECT PARA PEGAR TODAS AS LOJAS, AS CIDADES E OS ESTADOS*/
                                $sql = "SELECT * FROM tbl_classificacao";
                
                
                                $select = mysqli_query($conexao, $sql);
                        
                                

                                while($rsclassificacao = mysqli_fetch_array($select)){
                                    
                                  
                            ?>
                            
                            <!--LINHAS COM REGISTROS-->
                            <div class="informacoes_form">
                                <div class="linha_informacao">
                                    <span><?php echo($rsclassificacao['classificacao'])?></span>
                                </div>
                                <div class="linha_foto">
                                    <img class="foto_class" src="arquivos/<?php echo($rsclassificacao['foto_classificacao'])?>" title="<?php echo($rsclassificacao['classificacao'])?>" alt="<?php echo($rsclassificacao['classificacao'])?>">
                                </div>
                                <div class="linha_botao">

                                    <figure class="editar">
                                        <a href="classificacao.php?modo=buscar&id=<?php echo($rsclassificacao['cod_classificacao'])?>">
                                            <img src="img/iconfinder_new-24_103173.png" alt="Editar" title="Editar" class="botao" >
                                        </a>
                                    </figure>

                                    <figure class="excluir">
                                        <a href="classificacao.php?modo=excluir&id=<?php echo($rsclassificacao['cod_classificacao']);?>&foto=<?php echo($rsclassificacao['foto_classificacao'])?>">

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