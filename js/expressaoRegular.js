//VARIÁVEIS QUE PEGAM UM ELEMENTO DO HTML
let txtCelular = document.getElementById("txt-celular");
let txtTelefone = document.getElementById("txt-telefone");
let txtDataNascimento = document.getElementById("txt-data-nasc");
let txtCep = document.getElementById("txt-cep");
let txtPreco = document.getElementById("txt-preco");



//FUNCAO PARA A MASCARA DO CELULAR
function mascaraCelular(){

    //aceita apenas no maximo 14 caracteres
    txtCelular.maxLength="14";
    
    let texto = txtCelular.value;

    texto = texto.replace(/[^0-9]/g, "");//tudo que for diferente de número troca por nada
    texto = texto.replace(/(.)/, "($1");//no começo conta um e conservando o grupo acrescenta antes o (
    texto = texto.replace(/(...)/,"$1)")
    texto = texto.replace(/(.{9})/,"$1-")
    
    

    txtCelular.value = texto;
    
    
}

//FUNCAO PARA A MASCARA DO TELEFONE
function mascaraTelefone() {

    //aceita apenas no maximo 13 caracteres
    txtTelefone.maxLength="13";
    let texto = txtTelefone.value;

    texto = texto.replace(/[^0-9]/g, "");//tudo que for diferente de número substitui po nada
    texto = texto.replace(/(.)/, "($1");//qualquer caracter colocado acrescenta logo em seguida o "("
    texto = texto.replace(/(...)/,"$1)")//depois de três caracteres acrescenta logo em seguida o ")"
    texto = texto.replace(/(.{8})/,"$1-")//depois de 9 caracteres acrescenta logo em seguida o "-"

    txtTelefone.value = texto; 

}


function mascaraDataNascimento(){
    
    txtDataNascimento.maxLength="10";
    let texto = txtDataNascimento.value;
    
    texto = texto.replace(/[^0-9]/g, "");
    texto = texto.replace(/(..)/,"$1/");
    texto = texto.replace(/(.{5})/,"$1/");
    
    txtDataNascimento.value = texto;
}


function mascaraCep(){
    
    txtCep.maxLength="9";
    let texto = txtCep.value;
    
    texto = texto.replace(/[^0-9]/g, "");
    texto = texto.replace(/(.{5})/,"$1-");
        
    txtCep.value = texto;
}

function mascaraPreco(){
  
    txtPreco.maxLength="5";
    let texto = txtPreco.value;
    
    texto = texto.replace(/[^0-9]/g, "");
    texto = texto.replace(/(.{1,3})(.{2})/,"$1,$2");
        
    txtPreco.value = texto;
  
  
}



//EVENTO DAS CAIXAS DE TEXTO
txtCelular.addEventListener('keyup', mascaraCelular);
txtTelefone.addEventListener('keyup', mascaraTelefone);
txtDataNascimento.addEventListener('keyup',mascaraDataNascimento);
txtCep.addEventListener('keyup',mascaraCep);
txtPreco.addEventListener('keyup',mascaraPreco);


