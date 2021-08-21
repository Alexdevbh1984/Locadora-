//VALIDAR A NÃO ENTRADA DE LETRA
function ValidarLetra(caracter){
    //Verifica se o evento da tecla digita é proveniente de uma ação de janela
    if(window.Event){
        //transforma a tecla digitada para Ascii
        var letra = caracter.charCode;
    }else{
        //transforma a tecla digitada para Ascii
        var letra = caracter.which;
    }

    //Verifica através da tabela Ascii se a digitação está entre 48 e 57, que corresponde apenas aos numeros de 0 até 9
    if(letra < 48 || letra > 57){
        //Libera apenas o ponto para digitação
        if(letra < 40 || letra >41){
                if(letra != 45){
                    //utilizado para cancelar a função
                    return false;
                }
        }
    }
} 

//VALIDAR A NÃO ENTRADA DE NUMEROS
function ValidarNumero(caracter){
    //Verifica se o evento da tecla digita é proveniente de uma ação de janela
    if(window.Event){
        //transforma a tecla digitada para Ascii
        var letra = caracter.charCode;
    }else{
        //transforma a tecla digitada para Ascii
        var letra = caracter.which;
    }

    //Verifica através da tabela Ascii se a digitação está entre 65 e 90, que corresponde apenas aos numeros de A-Z
    if(letra < 65 || letra > 90){
        
        if(letra < 97 || letra > 122){
        
            //Libera apenas acentos
            if(letra < 128 || letra > 237){
                
                    if(letra != 32){
                        //utilizado para cancelar a função
                        return false;
                    }
            }
        }
    }
    
} 


//VALIDAR A NÃO ENTRADA DE LETRA
function ValidarPromocao(caracter){
    //Verifica se o evento da tecla digita é proveniente de uma ação de janela
    if(window.Event){
        //transforma a tecla digitada para Ascii
        var letra = caracter.charCode;
    }else{
        //transforma a tecla digitada para Ascii
        var letra = caracter.which;
    }

    //Verifica através da tabela Ascii se a digitação está entre 48 e 57, que corresponde apenas aos numeros de 0 até 9
    if(letra < 48 || letra > 57){
        //Libera apenas o ponto para digitação
        if(letra < 40 || letra >41){
                if(letra != 44){
                    //utilizado para cancelar a função
                    return false;
                }
        }
    }
} 

//VALIDAR A NÃO ENTRADA DE LETRA
function ValidarTempo(caracter){
    //Verifica se o evento da tecla digita é proveniente de uma ação de janela
    if(window.Event){
        //transforma a tecla digitada para Ascii
        var letra = caracter.charCode;
    }else{
        //transforma a tecla digitada para Ascii
        var letra = caracter.which;
    }

    //Verifica através da tabela Ascii se a digitação está entre 48 e 57, que corresponde apenas aos numeros de 0 até 9
    if(letra < 48 || letra > 57){
        //Libera apenas o ponto para digitação
        if(letra < 40 || letra >41){
             
                    return false;
              
        }
    }
} 


