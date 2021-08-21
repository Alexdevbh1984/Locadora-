<?php




   require_once('db/conexao.php');
    
    $conexao = conexaoMysql();
    

    /*INSERÇÃO DOS COMENTÁRIOS PARA O BANCO DE DADOS*/
    if(isset($_GET['btnEnviar'])){
        
        $nome = $_GET['txt_nome'];
        $email = $_GET['txt_email'];
        $telefone = $_GET['txt_telefone'];
        $celular = $_GET['txt_celular'];
        $sexo = $_GET['txt_sexo'];
        $profissao = $_GET['txt_profissao'];
        $homePage = $_GET['txt_home_page'];
        $facebook = $_GET['txt_facebook'];
        $produto = $_GET['txt_produto'];
        $criticaSugestao = $_GET['txt_critica'];
        
        $sql = "INSERT INTO tbl_fale_conosco(
                        nome,email,telefone,celular,sexo,profissao,homePage,facebook,produto,criticaSugestao)
                        VALUES(
                        '".$nome."','".$email."','".$telefone."','".$celular."','".$sexo."','".$profissao."',
                        '".$homePage."','".$facebook."','".$produto."','".$criticaSugestao."'
                    )";
    
//        var_dump($sql);
        if(mysqli_query($conexao,$sql)){
           

            header('location:fale_conosco.php');
            
            
        }else{
            echo("<script>
                    
                        alert('Erro no cadastro!');

                </script>");
        }
    }
    

?>

<!doctype html>
<html lang="pt-br">
    <head>
        <title>Fale Conosco</title>
        <link rel="stylesheet" href="css/fale_conosco.css" type="text/css">
        <link rel="stylesheet" href="css/menu.css" type="text/css">
        <link rel="stylesheet" href="css/rodape.css" type="text/css">
        <script src="js/valida.js"></script>
      <script src="js/jquery.js"></script>
        <script src="js/modal.js"></script>
      <meta charset="utf-8">
        
    </head>
    <body>
        <!--MENU-->
        <?php include("menu.php")?>
        
        <!--CONTEUDO 100%-->
        <div id="caixa_conteudo">
            
            <!--TODAS AS INFOMRACOES SERAO COLOCADAS NESSA DIV-->
            <div id="conteudo" class="center">
                
                <!--TITULO DO FALE CONOSSCO-->
                <div id="titulo" class="center">
                    <h2>Fale Conosco</h2>
                </div>
                
                <!--PARAGRAFO RELACIONADAS A DUVIDAS-->
                <div class="paragrafo">
                    <p class="center">Dúvidas? Preencha o formulário abaixo, em breve retornaremos o contato.</p>
                </div>
                
                <!--REDES SOCIAIS-->
                <div id="caixa_redes">
                    <div class="facebook"></div>
                    <div class="insta"></div>
                    <div class="twitter"></div>
                </div>
                
                <!--CAIXA PARA COLOCAR AS INFORMACOES DO FORMULARIO-->
                <div id="caixa_fale_conosco" class="center">
                    
                    <!--FORMULARIO-->
                    <form name="frm_fale_concosco" action="fale_conosco.php" method="get">
                        
                        <!--NOME NO FORMULARIO-->
                        <div id="form_nome">
                            <div class="form_texto">
                                <label>Nome:<span class="obrigatorio">**</span></label>
                            </div>
                            <div class="form_caixa_texto">
                                <input name="txt_nome" class="txtNome" type="text" onkeypress="return ValidarNumero(event);" required >
                            </div>
                        </div>
                        
                        <!--EMAIL NO FORMULARIO-->
                        <div id="form_email">
                            <div class="form_texto">
                                <label>Email:<span class="obrigatorio">**</span></label>
                            </div>
                            <div class="form_caixa_texto">
                                <input name="txt_email" class="txtEmail" type="email" placeholder="acme@tunes.br" required>
                            </div>
                        </div>
                        
                        <!--TELEFONE NO FORMULARIO-->
                        <div id="form_telefone">
                            <div class="form_texto">
                                <label>Telefone:</label>
                            </div>
                            <div class="form_caixa_telefone">
                                <input name="txt_telefone" id="txt-telefone" class="txtTelefone" type="tel" placeholder="(00)0000-0000"  onkeypress="return ValidarLetra(event);" pattern="^[(]\d{2}[)]\d{4}-\d{4}$" onkeyup="return mascaraCelular();" title="(00)0000-0000">
                            </div>
                        </div>
                        
                        <!--CELULAR NO FORMULARIO-->
                        <div id="form_celular">
                            <div class="form_texto">
                                <label>Celular:<span class="obrigatorio">**</span></label>
                            </div>
                            <div class="form_caixa_celular">
                                <input name="txt_celular" id="txt-celular" class="txtCelular" type="tel" placeholder="(00)00000-0000" required pattern="^[(]\d{2}[)]\d{5}-\d{4}$"  onkeypress="return ValidarLetra(event);" onkeyup="return mascaraCelular();"
                                title="(00)00000-0000">
                            </div>
                        </div>
                        
                        <!--SEXO NO FORMULARIO-->
                        <div id="form_sexo">
                            <div class="form_texto">
                                <label>Sexo:<span class="obrigatorio">**</span></label>
                            </div>
                            <div class="form_caixa_texto">
                                <select name="txt_sexo" class="txtSexo" required>
                                    <option value="">Selecione...</option>
                                    <option value="F">Feminino</option>
                                    <option value="M">Masculino</option>
                                </select>
                            </div>
                        </div>
                        
                        <!--PROFISSAO NO FORMULARIO-->
                        <div id="form_profissao">
                            <div class="form_texto">
                                <label>Profissão:<span class="obrigatorio">**</span></label>
                            </div>
                            <div class="form_caixa_texto">
                                <input name="txt_profissao" class="txtProfissao" type="text" onkeypress="return ValidarNumero(event);"  required>
                            </div>
                        </div>
                        
                        <!--HOME PAGE NO FORMULARIO-->
                        <div id="form_home_page">
                            <div class="form_texto">
                                <label>Home Page:</label>
                            </div>
                            <div class="form_caixa_texto">
                                <input name="txt_home_page" class="txtHomePage" type="url" placeholder="http://www.acmetunes.com">
                            </div>
                        </div>
                        
                        <!--FACEBOOK NO FORMULARIO-->
                        <div id="form_facebook">
                            <div class="form_texto">
                                <label>Link no Facebook:</label>
                            </div>
                            <div class="form_caixa_texto">
                                <input name="txt_facebook" class="txtFacebook" type="url" placeholder="http://www.acmetunes.com">
                            </div>
                        </div>
                        <!--INFORMACOES DE PRODUTOS NO FORMULARIO-->
                        <div id="form_informacoes_produtos">
                            <div class="form_texto">
                                <label>Informações de Produtos:</label>
                            </div>
                            <div class="form_informacoes_produtos">
                                <textarea name="txt_produto" class="txtMensagem"></textarea>
                            </div>
                        </div>
                        
                        <!--CRITICA/SUGESTAO NO FORMULARIO-->
                        <div id="form_critica">
                            <div class="form_texto">
                                <label>Críticas/Sugestões:</label>
                            </div>
                            <div class="form_caixa_critica">
                                <textarea name="txt_critica" class="txtMensagem"></textarea>
                            </div>
                        </div>
                        
                        <!--INFORMANDO O ITEM OBRIGATORIO NO FORMULARIO-->
                        <div class="item_obrigatorio">
                            <p class="item">
                                <b><span class="obrigatorio">**</span> Itens obrigatórios</b>
                            </p>
                        </div>
                        
                        <!--BOTAO NO FORMULARIO-->
                        <div class="botao">
                            <input type="submit" name="btnEnviar" class="form_btn" value="Enviar Mensagem">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!--RODAPE-->
        <?php include("rodape.php")?>
        
        <script src="js/expressaoRegular.js"></script>
    </body>
</html>