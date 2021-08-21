<?php
  
  /*VERIFICACAO DE TODAS AS PAGINAS PARA NÃO ENTRAR DIRETOR PELA URL NO CMS*/
    if(!isset($_SESSION['login'])){
    
    session_start();
      session_destroy();
      header('location:../index.php');
    
    }

  
/*sair do cms pelo logout*/
  if(isset($_GET['logout'])){
    
    session_start();
    session_destroy();
    header('location:../index.php');
    
  }


?>
<div class="cabecalho">
    <div class="titulo_subtitulo">
        <h1 class="titulo">CMS </h1><h2 class="subtitulo">- Sistema de Gerenciamento do Site</h2>
    </div>
    <figure class="logo">

    </figure>
</div>

<div class="adm">
    <div class="caixa_menu">
      
        <?php
          if($_SESSION['nivel'] == 3 || $_SESSION['nivel'] == 4){
            
        ?>

      
          <!--AREA DE ADMINISTRACAO DO CONTEUDO-->
          <div class="opcao_adm">
              <a href="adm_conteudo.php">
                  <figure class="img_adm center">
                      <img src="img/conteudo.png" class="icone_adm">
                  </figure>
                  <div class="texto_adm">
                      <span>Adm. Conteúdo</span>
                  </div>
              </a>
          </div>
      
        <?php
          }
      
          if($_SESSION['nivel'] == 3 || $_SESSION['nivel'] == 4){
            
        ?>
      
          <!--AREA DE ADMINISTRACAO DO FALE CONOSCO-->
        <div class="opcao_adm">
            <a href="adm_fale_conosco.php">
                <figure class="img_adm center">
                    <img src="img/fale_conosco.png" class="icone_adm">
                </figure>
                <div class="texto_adm">
                      <span>Adm. Fale Conosco</span> 
                </div>
            </a>
        </div>
      
        <?php
          }  
          
          if($_SESSION['nivel'] == 3 || $_SESSION['nivel'] == 2){
            
        ?>
      
          <!--AREA DE ADMINISTRACAO DOS PRODUTOS-->
          <div class="opcao_adm">
              <a href="adm_produtos.php">
                  <figure class="img_adm center">
                      <img src="img/product.png" class="icone_adm">
                  </figure>
                  <div class="texto_adm">
                        <span>Adm. Produtos</span> 
                  </div>
              </a>
          </div>
      
        <?php
          }  
          
          if($_SESSION['nivel'] == 3 || $_SESSION['nivel'] == 4){
            
        ?>
      
        <!--AREA DE ADMINISTRACAO DOS USUARIOS-->
        <div class="opcao_adm">
            <a href="adm_usuarios.php">
                <figure class="img_adm center">
                    <img src="img/video-call.png" class="icone_adm">
                </figure>
                <div class="texto_adm">
                      <span>Adm. Usuários</span> 
                </div>
            </a>
        </div>
        <?php
      
          }
        ?>
    </div>
  
    <!--AREA DE ADMINISTRACAO DO USUARIO LOGADO-->
    <div class="area_adm">
        <div class="bem_vindo">
            <a href="adm_conteudo.php"><span>Bem vinda(o), <?php echo($_SESSION['nome'])?>.</span></a>
        </div>
        <div class="logout">
            <a href="cabecalho_menu_adm.php?logout"><div class="caixa_logout"><span>Logout</span></div></a>
        </div>
    </div>
</div>