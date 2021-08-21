<?php

    session_start();

    /*VERIFICACAO DE TODAS AS PAGINAS PARA NÃO ENTRAR DIRETOR PELA URL NO CMS*/
    if(!isset($_SESSION['login'])){
    
      session_destroy();
      header('location:../index.php');
    
    }
    
    /*funções*/
    require_once('../funcoes.php');

    /*conexão*/
    require_once("../db/conexao.php");
    $conexao = conexaoMysql();
    

    $titulo = null;
    $sobre = null;
    $botao = "Salvar";
    $nome_foto = null;

    
    if(isset($_GET['modo'])){
        $modo = $_GET['modo'];
        $codigo = $_GET['id'];
        
        $_SESSION['idregistro'] = $codigo;
        
        //EXCLUINDO REGISTROS NO BANCO DE DADOS
        if($modo == 'excluir'){
            $foto_excluir = $_GET['foto'];
            
            $sql = "DELETE FROM tbl_sobre WHERE cod_sobre = ".addslashes($codigo)." ";
            
            mysqli_query($conexao,$sql);
            $foto = "arquivos/".$foto_excluir;
            
            unlink($foto);
          
            echo("<script> window.location.href = 'sobre_cms.php' </script>");
        
        //FAZENDO BUSCA NO BANCO DE DADOS
        }elseif($modo == 'buscar'){
            
            $sql = "SELECT * FROM tbl_sobre WHERE cod_sobre =".addslashes($codigo);
            $select = mysqli_query($conexao,$sql);
            
            if($rs = mysqli_fetch_array($select)){
                
                $titulo = $rs['titulo'];
                $sobre = $rs['texto'];
                $nome_foto = $rs['foto'];
                
                $_SESSION['nome_foto'] = $nome_foto;
                $_SESSION['path_foto'] = null;
                
                $botao = "Editar";
                
            }
            
            
        }
        
        
    }


    if(isset($_POST['btnEnviar'])){
        
        $titulo = $_POST['txt_titulo'];
        $sobre = $_POST['txt_sobre'];
       
        
        $diretorio = "arquivos/";//diretorio
        
        $arquivos_permitidos = array(".jpg",".jpeg",".png");//arquivos permitidos
   
        $arquivo = $_FILES['fle_foto']['name'];//nome do arquivo

        $tamanho_arquivo = $_FILES['fle_foto']['size'];//tamanho do arquivo
  
        $tamanho_arquivo = round($tamanho_arquivo/1024);//arredondando o tamanho do arquivo
        
        $extensao_arquivo = strrchr($arquivo, ".");//pegando a extensão
 
        $nome_arquivo = pathinfo($arquivo, PATHINFO_FILENAME);//pegando o nome do arquivo
    
        $arquivo_criptografado = md5(uniqid(time()).$nome_arquivo);//criptografando o arquivo
       
        $foto = $arquivo_criptografado . $extensao_arquivo;//concatenando o nome da foto e a extensão
        
        //se o input file estiver vazio
        if(!empty($_FILES['fle_foto']['name'])){
          
          //verifica se a foto é com extensão permitida
          if(in_array($extensao_arquivo, $arquivos_permitidos)){
            
            //verifica o tamanho do arquivo
            if($tamanho_arquivo <= 5000){
                
                 $arquivo_temp = $_FILES['fle_foto']['tmp_name'];

                //movepara o servidor a foto
                if(move_uploaded_file($arquivo_temp, $diretorio.$foto)){

                    //inserindo o registro com a foto
                    if($_POST['btnEnviar'] == "Salvar"){


                        $sql = "INSERT INTO tbl_sobre (titulo, texto, foto) VALUES('".addslashes($titulo)."','".addslashes($sobre)."', '".addslashes($foto)."')";

                        mysqli_query($conexao, $sql);


                    //atualizando o registro
                    }elseif($_POST['btnEnviar'] == "Editar"){

                        $sql = "UPDATE tbl_sobre SET titulo='".addslashes($titulo)."', texto='".addslashes($sobre)."', foto='".addslashes($foto)."' WHERE cod_sobre =".$_SESSION['idregistro'];

                        if(mysqli_query($conexao, $sql)){

                            unlink('arquivos/'.$_SESSION['nome_foto']);
                        }

                    }
                  
                    
                 header("location:sobre_cms.php");


                }else{

                     echo("<script> alert('Erro ao enviar o arquivo para o servidor!!)</script>");

                }
                
            }else{
                
                echo("<script> alert('Tamanho de arquivo inválido!!)</script>");
                
            }
            
            
          }else{

              echo("<script> alert('Extensão de arquivo inválido!') </script>");
          }
        
        //se estiver vazio vai atualizar o registro sem a foto
        }else{
          
          if($_POST['btnEnviar'] == "Editar"){
            
            $sql = "UPDATE tbl_sobre SET titulo='".addslashes($titulo)."', texto='".addslashes($sobre)."' WHERE cod_sobre =".$_SESSION['idregistro'];
            
            if(mysqli_query($conexao, $sql)){

                header("location:sobre_cms.php");

            }else{
              echo("<script> alert('Erro no cadastro!!')</script>"); 
            }
            
          }else{
            
            echo("<script> alert('Por favor escolha uma foto!') </script>");
            
          }
          
          
          
        }
        
        
    }


    //logica ativar
    if(isset($_GET['ativo'])){
        
        $codigo = $_GET['id'];
        $ativo = $_GET['ativo'];
      
        //se o ativar for igual a zero
        if($ativo == 0){
          
          //verifica a quantidade de registro no banco ativado
          $sql = "SELECT count(*) AS quantidade FROM tbl_sobre WHERE ativo = 1";
      
          $select = mysqli_query($conexao,$sql);

          if($rs = mysqli_fetch_array($select)){

            //se a quantidade de registros for menos que 4 pode ativar
            if($rs['quantidade'] < 4){

              $sql = "UPDATE tbl_sobre SET ativo = 1 WHERE cod_sobre =".$codigo."";

                if(mysqli_query($conexao,$sql)){

                  header('location:sobre_cms.php');

                }
            // se não for menor que 4 aparece uma mensagem de alerta
            }else{

                  echo("<script> alert('Só pode selecionar 4!'); </script>");

                }

          }
      
        //desativar os registros
        }else{
          
          $sql = "UPDATE tbl_sobre SET ativo = 0 WHERE cod_sobre =".$codigo."";

              if(mysqli_query($conexao,$sql)){

                header('location:sobre_cms.php');

              }
          
          
        }

       
        
    }


?>

<!doctype html>

<html>
    <head>
        <title>CMS - Sitema de Gerenciamento do Site</title>
        <link href="css/sobre_cms.css" type="text/css" rel="stylesheet">
        <link href="css/formatacao.css" type="text/css" rel="stylesheet">
        <link href="css/cabecalho_menu_adm.css" type="text/css" rel="stylesheet">
        <link href="css/rodape.css" type="text/css" rel="stylesheet">
        <script src="../js/valida.js"></script>
        <script src="js/jquery.min.js"></script>
        <script src="js/jquery.form.js"></script>
        <meta charset="utf-8">
      
    </head>
    <body>
        <div id="caixa_conteudo">
            <div id="conteudo" class="center">
                
                <?php include('cabecalho_menu_adm.php')?>
                
                
                <div class="itens">
                    <div class="titulo_usuarios"><h2>Cadastro Sobre a Empresa</h2></div>
                    <div class="cadastro_usuarios center">
                         
                      
                      <!--FORMULARIO DOS DADOS-->
                      <form action="sobre_cms.php" method="post" name="frm_sobre" enctype="multipart/form-data"> 
                        
                             <?php
                                
                                if(isset($nome_foto)){
                            
                            ?>
                            
                                <div class="caixa_foto center">
                                    <img class="img" src="arquivos/<?php echo($nome_foto);?>" alt="<?php echo($titulo)?>" title="<?php echo($titulo)?>">
                                </div>
                            
                            <?php
                            
                                }
                            
                            ?>
                            <div class="form_foto">
                                <div class="form_texto_usuario">
                                    <label>Foto:</label>
                                </div>
                              
                              
                                <div class="form_caixa_usuario">
                                    
                                  <?php

                                      if($botao == "Salvar"){
                                    ?>

                                      <input name="fle_foto" class="txtFoto" title="Selecione uma imagem" type="file" accept="image/*" required>
                                    <?php

                                      }else{
                                    ?>
                                      <input name="fle_foto" class="txtFoto" title="Selecione uma imagem" type="file" accept="image/*">

                                    <?php

                                      }

                                  ?>
                                </div>
                              
                              
                            </div>
                        
                            <!--AREA DE CADASTRO DO TITULO-->
                            <div class="form_titulo">
                                <div class="form_texto_usuario">
                                    <label>Título:</label>
                                </div>
                                <div class="form_caixa_usuario">
                                    <input name="txt_titulo" class="txtTitulo" type="text" value="<?php echo($titulo)?>" required maxlength="100" autocomplete="off">
                                </div>
                            </div>
                            
                            <div class="form_sobre">
                                <div class="form_texto_usuario">
                                    <label>Sobre a empresa:</label>
                                </div>
                                <div class="form_caixa_usuario">
                                    <textarea name="txt_sobre" class="txtSobre" type="text" required><?php echo($sobre)?></textarea>
                                </div>
                            </div>
                            
                            <div class="botao_form">
                                <input type="submit" name="btnEnviar" class="form_btn" value="<?php echo($botao)?>">
                            </div>
                        </form>
                    </div>
                    
                    <div class="tabela center">
                        <div class="linha_titulo">
                            <div class="titulo_form">
                                <h4>Título</h4>
                            </div>
                            <div class="titulo_sobre">
                                <h4>Sobre</h4>
                            </div>
                            <div class="titulo_opcoes">
                                <h4>Opções</h4>
                            </div>
                            
                        </div>
                     <!--<div class="info_tabela">-->
                        <?php
                        
                        //select que tras todos os registros cadastrados
                            $sql = "SELECT * FROM tbl_sobre";
                            $select = mysqli_query($conexao,$sql);
                
                            while($rssobre = mysqli_fetch_array($select)){
                                
                                if($rssobre['ativo'] == 1){
                                    
                                    $img = "img/ok.png";
                                    $legenda = "Ativado";
                                    
                                }elseif($rssobre['ativo'] == 0){
                                    
                                    $img = "img/cancel.png";
                                    $legenda = "Desativado";
                                    
                                }
                                
                        
                        ?>
                            <div class="informacoes_form">
                                <div class="linha_informacao">
                                    <span><?php echo($rssobre['titulo'])?></span>
                                </div>
                                <div class="linha_sobre">
                                    <span><?php echo($rssobre['texto'])?></span>
                                </div>
                                <div class="linha_botao">

                                    <figure class="editar">
                                        <a href="sobre_cms.php?modo=buscar&id=<?php echo($rssobre['cod_sobre'])?>">
                                            <img src="img/iconfinder_new-24_103173.png" alt="Editar" title="Editar" class="botao" >
                                        </a>
                                    </figure>

                                    <figure class="excluir">
                                        <a href="sobre_cms.php?modo=excluir&id=<?php echo($rssobre['cod_sobre'])?>&foto=<?php echo($rssobre['foto'])?>">

                                            <img src="img/ic_delete.png" alt="Excluir" title="Excluir" class="botao" onclick="return confirm('Tem certeza que deseja excluir?')">

                                        </a>
                                    </figure>

                                    <figure class="desativar">
                                        <a href="sobre_cms.php?ativo=<?php echo($rssobre['ativo'])?>&id=<?php echo($rssobre['cod_sobre'])?>">
                                            <img src="<?php echo($img)?>" alt="<?php echo($legenda)?>" title="<?php echo($legenda)?>" class="botao">
                                        </a>
                                    </figure>


                                </div>

                            </div>  
                       <?php
                                    
                        
                            }
                        
                        ?>
                        <!--</div>-->
                        
                  </div>
                    
                    
                </div>
               
                <?php include('rodape.php')?>
                
            </div>
        </div>
        <script src="../js/expressaoRegular.js"></script>
    </body>
</html>