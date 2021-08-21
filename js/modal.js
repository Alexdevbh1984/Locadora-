
//ABRIR MODAL DO FALE CONOSCO
$(document).ready(function () {
                
    $('.visualizar').click(function () {


        $('#container').fadeIn(500);
    });
  
                
    $('.titulo_detalhes').click(function () {


        $('#container').fadeIn(500);
        $("html,body").css({"overflow":"hidden"});
    });
  
    $('#imagem_menu').click(function(){
      
       $('#menu_mobile').slideToggle(1000);
      /*$('#menu_mobile').removeClass('menu_mobile_close');
      $('#menu_mobile').addClass('menu_mobile_open');*/
      
    });
  
    $('.menu_item_mobile').click(function(){
      
      $('#menu_mobile').slideToggle(1000);
      /*$('#menu_mobile').removeClass('menu_mobile_open');
      $('#menu_mobile').addClass('menu_mobile_close');*/
      
      
    });
  
    $('#imagem_favorito').click(function(){
      
       /* $('#menu_mobile').slideToggle(1000);*/
      $('#imagem_favorito').removeClass('imagem_favorito_close');
      $('#imagem_favorito').addClass('imagem_favorito_open');
      
    });

  

});


//ABRIR MODAL SOBRE O FILME
$(document).ready(function () {
                
    $('.detalhes').click(function () {

        $('#container').fadeIn(500);
      $("html,body").css({"overflow":"hidden"});
      
    });

});


//ABRIR MODAL DO MAPA
 $(document).ready(function(){
                
      $('.ver_mapa').click(function(){

          $('#container').fadeIn(800);
      });



  });

//VER OS REGISTROS DO FALE CONOSCO
function visualizarDados(idItem){


    $.ajax({

        type: "GET",

        url:"../modal/modal_fale_conosco.php",

        data:{codigo: idItem},

        success: function(dados){

            $('#modal').html(dados);
            /*alert("Olá Mundo");*/
        }


    });


}

//VER O MAPA DAS LOJAS
function visualizarMapa(idItem){
  
  $.ajax({
    
    type:"GET",
    url:"modal/modal_loja.php",
    data:{cod_loja: idItem},
    
    success: function(dados){
      
      $('#modal').html(dados);
      
     /* alert("Olá MUNDO!");*/
      
    }
    
    
  });
  
}


//MODAL PARA TRAZER OS DETALHES DOS FILMES
function detalhesFilme(idItem, modo){

    //AREA DA PROMOCAO
    if(modo == 'promocao'){
        
        $.ajax({

            type: "GET",

            url:"modal/modal_promocao.php",

            data:{cod_produto_filme: idItem},

            success: function(dados){

                $('#modal').html(dados);
                /*alert("Olá Mundo");*/
            }

        });
        
    //AREA DA PAGINA INICIAL   
    }else if(modo == 'home'){
        
        $.ajax({

            type: "GET",

            url:"modal/modal_home.php",

            data:{cod_produto_filme: idItem},

            success: function(dados){

                $('#modal').html(dados);
                /*alert("Olá Mundo");*/
            }
          
          
          

        });
        
    //AREA DO FILME MES
    }else if(modo == 'filme_mes'){
      
      
      $.ajax({
        
        type: "GET",
        
        url:"modal/modal_filme_mes.php",
        
        data:{cod_produto_filme: idItem},
        
        success: function(dados){
          
          $('#modal').html(dados);
          
         /* alert('DEU CERTO');*/
          
        }
        
        
        
      })
      
    }

}


/*
function estatistica(idItem){
  
  $.ajax({
    
        
        
        type: "GET",
        
        url:"estatistica.php",
        
        data:{cod_produto_filme: idItem},
        
        success: function(dados){
          
          $('#modal').html(dados);
          
          alert('DEU CERTO');
          
        }
        
        
        
      })
  
  
}*/
    
  
