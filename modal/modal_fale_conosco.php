<?php

    $sexo = null;

  
    
    if(isset($_GET['codigo'])){
        
        require_once('../db/conexao.php');
        
        $conexao = conexaoMysql();
        
        $codigo = $_GET['codigo'];
      
        //SELECT PARA TRAZER A MODAL COM AS INFORMAÇÕES DO FILME 
        $sql = 'SELECT * FROM tbl_fale_conosco WHERE codigo ='.$codigo;
        
        $select = mysqli_query($conexao, $sql);
        
        while($rscontato = mysqli_fetch_array($select)){
            
            $nome = $rscontato['nome'];
            $email = $rscontato['email'];
            $telefone = $rscontato['telefone'];
            $celular = $rscontato['celular'];
            $profissao = $rscontato['profissao'];
            $homePage = $rscontato['homePage'];
            $facebook = $rscontato['facebook'];
            $criticaSugestao = $rscontato['criticaSugestao'];
            $produto = $rscontato['produto'];
            
            if($rscontato['sexo'] == 'F'){
                $sexo = 'Feminino';
            }else{
                $sexo = 'Masculino';
            }
            
            
            
        }
        
        
    }
    

?>

<!--FECHAR MODAL-->
<script>
    $(document).ready(function(){

        $('#icone_fechar').click(function(){
            $('#container').fadeToggle(540);
        });

    })

</script>


<div id="fechar">
    <div id="icone_fechar">
    </div>
</div>

<div class="primeira_linha">
    
    <!--NOME NO FORMULARIO-->
    <div id="form_nome">
        <div class="form_texto">
            <label>Nome:</label>
        </div>
        <div class="form_caixa_texto">
            <?php echo($nome)?>
        </div>
    </div>

    <!--EMAIL NO FORMULARIO-->
    <div id="form_email">
        <div class="form_texto">
            <label>Email:</label>
        </div>
        <div class="form_caixa_texto">
            <?php echo($email)?>
        </div>
    </div>

    <!--TELEFONE NO FORMULARIO-->
    <div id="form_telefone">
        <div class="form_texto">
            <label>Telefone:</label>
        </div>
        <div class="form_caixa_telefone">
            <?php echo($telefone)?>
        </div>
    </div>
</div>
<div class="segunda_linha">
    
    <!--CELULAR NO FORMULARIO-->
    <div id="form_celular">
        <div class="form_texto">
            <label>Celular:</label>
        </div>
        <div class="form_caixa_celular">
            <?php echo($celular)?>
        </div>
    </div>
    
    <!--SEXO NO FORMULARIO-->
    <div id="form_sexo">
        <div class="form_texto">
            <label>Sexo:</label>
        </div>
        <div class="form_caixa_texto">
            <?php echo($sexo)?>
        </div>
    </div>

    <!--PROFISSAO NO FORMULARIO-->
    <div id="form_profissao">
        <div class="form_texto">
            <label>Profissão:</label>
        </div>
        <div class="form_caixa_texto">
            <?php echo($profissao)?>
        </div>
    </div>
</div>   
<div class="terceira_linha">
    
    <!--HOME PAGE NO FORMULARIO-->
    <div id="form_home_page">
        <div class="form_texto">
            <label>Home Page:</label>
        </div>
        <div class="form_caixa_texto">
            <?php echo($homePage)?>
        </div>
    </div>

    <!--FACEBOOK NO FORMULARIO-->
    <div id="form_facebook">
        <div class="form_texto">
            <label>Link do Facebook:</label>
        </div>
        <div class="form_caixa_texto">
            <?php echo($facebook)?>
        </div>
    </div>

</div>

<!--INFORMACOES DE PRODUTOS NO FORMULARIO-->
<div id="form_informacoes_produtos">
    <div class="form_texto">
        <label>Informações de Produtos:</label>
    </div>
    <div class="form_caixa_informacoes_produtos">
        <?php echo($produto)?>
    </div>
</div>

<!--CRITICA/SUGESTAO NO FORMULARIO-->
<div id="form_critica">
    <div class="form_texto">
        <label>Críticas/Sugestões:</label>
    </div>
    <div class="form_caixa_critica">
        <?php echo($criticaSugestao)?>
    </div>
</div>
                        
        
