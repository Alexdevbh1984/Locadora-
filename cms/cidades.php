<?php
    

    session_start();

    /*VERIFICACAO DE TODAS AS PAGINAS PARA NÃO ENTRAR DIRETOR PELA URL NO CMS*/
    if(!isset($_SESSION['login'])){
    
      session_destroy();
      header('location:../index.php');
    
    }

    require_once('../db/conexao.php');
    $conexao = conexaoMysql();
    
    $cidade = null;
    
    $botao = "Salvar";
    $cod_estado = 0;
    
  
    if(isset($_GET['modo'])){
        
        $modo = $_GET['modo'];
        $codigo = $_GET['id'];
        
        $_SESSION['idregistro'] = $codigo;
      
        /*EXCLUIR CIDADE*/  
        if($modo == 'excluir'){
            
            $sql = "DELETE FROM tbl_cidades WHERE cod_cidade = ".$codigo." ";
            
            mysqli_query($conexao,$sql);

        /*FAZER BUSCA DA CIDADE E DO ESTADO NO BANCO*/  
        }elseif($modo == 'buscar'){
            
            $sql = "SELECT tbl_cidades.*, tbl_estados.sigla_estado 
                    FROM tbl_cidades INNER JOIN tbl_estados 
                    ON tbl_cidades.cod_estado = tbl_estados.cod_estado 
                    WHERE tbl_cidades.cod_cidade =".$codigo;
            
            $select = mysqli_query($conexao,$sql);
            
            if($rscidade = mysqli_fetch_array($select)){
                
                $cidade = $rscidade['cidade'];
                
                $cod_estado = $rscidade['cod_estado'];
                $estado = $rscidade['sigla_estado'];
                
                
            }
            
            $botao = "Editar";
            
            
        }
        
        
    }
    
    if(isset($_POST['btnEnviar'])){
        
        $cidade = $_POST['txt_cidade'];
        $estado = $_POST['slt_estados'];
      
        /*INSERIR REGISTRO NO BANCO*/          
        if($_POST['btnEnviar'] == "Salvar"){
        
            $sql = "INSERT INTO tbl_cidades(cidade, cod_estado) VALUES ('".$cidade."', ".$estado.")";


        /*ATUALIZAR REGISTRO DO BANCO*/        
        }elseif($_POST['btnEnviar'] == "Editar"){
            
            $sql = "UPDATE tbl_cidades SET cidade ='".$cidade."', cod_estado = ".$estado." WHERE cod_cidade = ".$_SESSION['idregistro'];
            
            
            
        }
        
        if(mysqli_query($conexao,$sql)){

            header('location:cidades.php');
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
        <link href="css/cidades.css" type="text/css" rel="stylesheet">
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
                    
                    <div id="menu_cadastro">
                    
                        <a href="lojas.php">
                            <div class="menu menu_opcoes">

                                  Lojas

                            </div>
                        </a>  
                    
                    </div>
                    
                    <div class="cadastro">
                  
                        <div class="titulo_cidades"><h2>Cadastro de Cidades</h2></div>
                        <div class="cadastro_cidades center">
                            <form action="cidades.php" method="post" name="frm_cidades">

                                <!--AREA DA CIDADE-->
                                <div class="form_cidades">
                                    <div class="form_texto_usuario">
                                        <label>Cidade:</label>
                                    </div>


                                    <div class="form_caixa_usuario">
                                        <input name="txt_cidade" class="txtCidades" type="text" value="<?php echo($cidade);?>" onkeypress="return ValidarNumero(event);" required maxlength="100" autocomplete="off">
                                    </div>

                                </div>
                                <div class="form_estados">
                                    <div class="form_texto_usuario">
                                        <label>UF:</label>
                                    </div>
                                    <div class="form_caixa_usuario">

                                        <select name="slt_estados" class="txtEstados" required>

                                            <?php

                                                if($modo == 'buscar'){
                                            ?>

                                                 <option value="<?php echo($cod_estado)?>" selected><span><?php echo($estado)?></span></option>

                                            <?php

                                                }else{

                                            ?>


                                                <option value="" selected><span>...</span></option>

                                                <?php

                                                    }

                                                    $sql = "SELECT * FROM tbl_estados WHERE cod_estado <>".$cod_estado." ORDER BY sigla_estado";
                                                    $select = mysqli_query($conexao,$sql);

                                                    while($rsestados = mysqli_fetch_array($select)){



                                                ?>

                                                <option value="<?php echo($rsestados['cod_estado'])?>"><?php echo($rsestados['sigla_estado'])?></option>

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
                    
                        <div class="tabela center">
                            <div class="linha_titulo">
                                <div class="titulo_form">
                                    <h4>Cidade</h4>
                                </div>
                                <div class="titulo_estado">
                                    <h4>UF</h4>
                                </div>
                                <div class="titulo_opcoes">
                                    <h4>Opções</h4>
                                </div>

                            </div>

                                    <?php

                                        $sql = "SELECT tbl_cidades.*, tbl_estados.sigla_estado 
                                                FROM tbl_cidades INNER JOIN tbl_estados
                                                ON tbl_cidades.cod_estado = tbl_estados.cod_estado";

                                        $select = mysqli_query($conexao, $sql);

                                        while($rscidades = mysqli_fetch_array($select)){




                                    ?>
                            <div class="informacoes_form">
                                <div class="linha_informacao">
                                    <span><?php echo($rscidades['cidade']);?></span>
                                </div>

                                <div class="linha_estado">
                                    <span><?php echo($rscidades['sigla_estado']);?></span>
                                </div>
                                <div class="linha_botao">
                                    <figure class="editar">
                                        <a href="cidades.php?modo=buscar&id=<?php echo($rscidades['cod_cidade']);?>">
                                            <img src="img/iconfinder_new-24_103173.png" alt="Editar" title="Editar" class="botao " >
                                        </a>
                                    </figure>

                                    <figure class="excluir">
                                        <a href="cidades.php?modo=excluir&id=<?php echo($rscidades['cod_cidade']);?>">
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