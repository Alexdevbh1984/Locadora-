<?php

  require_once('db/conexao.php');
  $conexao = conexaoMysql();

  session_start();


  //SE A VARIAVEL DE SESSAO ERRO EXISITR, QUER DIZER QUE O USUARIO OU SENHA ESTÃO INCORRETOS, LOGO APARECERÁ UM ALERT
  if(isset($_GET['erro'])){
    
    echo("<script> alert('Usúario e/ou senha incorretos!') </script>");
    
  }

?>
<!--MENU 100%-->
<header id="caixa_menu">
    
    <!--MENU COM TODAS AS INFORMACOES-->
    <div id="corpo_menu" class="center">
        
        <!--LOGO DA EMPRESA-->
        <div class="logo"><a href="index.php"><div id="caixa_logo"></div></a></div>
        
        <!--MENU-->
        <div id="imagem_menu">
        
        </div>
        <nav id="menu_mobile">
            
              <!--LINK DO MENU ITEM INICIO-->
              <a href="index.php"><div class="menu_item_mobile">Home</div></a>

              <!--LINK DO ITEM MENU PROMOCAO-->
              <a href="promocao.php"><div class="menu_item_mobile">Promoções</div></a>

              <!--LINK DO ITEM MENU FILME DO MÊS-->
              <a href="filme_mes.php"><div class="menu_item_mobile">Filme do Mês </div></a>
              
              <!--LINK DO ITEM MENU ATOR DO MÊS-->
              <a href="ator_mes.php"><div class="menu_item_mobile">Ator do Mês </div></a>

              <!--LINK DO ITEM MENU LOJAS-->
              <a href="lojas.php"><div class="menu_item_mobile">Lojas</div></a>

              <!--LINK DO ITEM MENU FALE CONOSCO-->
              <a href="fale_conosco.php"><div class="menu_item_mobile">Contato</div></a>
              
              <!--LINK DO ITEM MENU SOBRE-->
              <a href="sobre.php"><div class="menu_item_mobile">Sobre</div></a>

          </nav>
      
        <!--MENU DO DESKTOP-->
        <nav id="menu">
            
            <!--LINK DO MENU ITEM INICIO-->
            
            <!--LINK DO ITEM MENU PROMOCAO-->
            <a href="promocao.php"><div class="menu_item">Promoções</div></a>
            
            <!--ITEM MENU DESTAQUE-->
            <div class="menu_item">Destaques
                
                <!--SUBMENU DO ITEM MENU DESTAQUE-->
                <div id="submenu_destaque">
                    
                    <!--LINK DO SUB ITEM FILME DO MÊS-->
                    <a href="filme_mes.php"><div class="submenu_item_destaque">
                        Filme
                    </div></a>
                    
                    <!--LINK DO SUB ITEM ATOR DESTAQUE-->
                    <a href="ator_mes.php"><div class="submenu_item_destaque">
                        Ator
                    </div></a>
                </div>
            </div>
              
            
            
            
            
            <!--LINK DO SUB ITEM DAS LOJAS-->
            <a href="lojas.php"><div class="menu_item">Lojas</div></a>
            
            <!--ITEM MENU ACME-->
            <div class="menu_item">A Acme
                
                <!--SUB MENU ACME-->
                <div id="submenu_acme">
                    
                    
                    <!--LINK DO SUB ITEM DE FALE CONOSCO-->
                    <a href="fale_conosco.php"><div class="submenu_item_acme">
                        Contato
                    </div></a>
                    
                    <!--LINK DO SUB ITEM DO SOBRE-->
                    <a href="sobre.php"><div class="submenu_item_acme">
                        Sobre
                    </div></a>
                </div>
            </div>
        
        </nav>
        
        <!--LOGIN-->
        <div id="caixa_login">
            
            <!--FORMULARIO DE LOGIN-->
            <form name="frm_login" method="post" action="login.php">
                
                <!--CAIXA DE TEXTO USUARIO-->
                <div class="caixa_texto">
                    <div class="texto">
                        <label>Usuário</label>
                    </div>
                    <div class="login">
                        <input type="email" name="txtUsuario" value="" class="inputText" placeholder="Digite seu usuário" autocomplete="off" required >
                    </div>
                </div>
                
                <!--CAIXA DE TEXTO COM SENHA-->
                <div class="caixa_texto">
                    <div class="texto">
                        <label>Senha</label>
                    </div>
                    <div class="login">
                        <input type="password" name="txtSenha" value="" class="inputText" placeholder="Digite sua senha" autocomplete="off" required>
                    </div>
                </div>
                
                <!--BOTAO DO FORMULARIO-->
                <div id="caixa_botao">
                    <input type="submit" name="btnOk" value="Ok" class="inputBotao">
                </div>
            </form>
        </div>
    </div>
</header>

<!--SOB O MENU-->
<div class="div_embaixo_menu"> </div>