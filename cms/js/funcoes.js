//filtro para as cidades
$(document).ready(function(){
            
    //quando o select for selecionado ira fazer uma mudança no select de cidades
    $('#sltEstados').live('change',function(){

      $.ajax({

        type:"POST",

        url:"filtro.php",

        data:{codigo:document.getElementById('sltEstados').value},

        success:function(dados){
          /*alert(dados)*/;

          $('#sltCidades').html(dados);

        }

      });

    });
  
    // $('#sltCategorias').live('change',function(){

    //   $.ajax({

    //     type:"POST",

    //     url:"filtro.php",

    //     data:{id_categoria:document.getElementById('sltCategorias').value},

    //     success:function(dados){
    //       /*alert(dados)*/;

    //       $('#sltSubcategorias').html(dados);

    //     }

    //   });

    // });
    
  
  
  });