<?php 
    require_once 'daoPessoa.php';
    require_once 'Pessoa.php';
    $p = new DaoPessoa;


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>

    <meta charset="UTF-8">
    <title>Teste</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</head>
<body>

        <?php
            if(isset($_POST['nome'])){
            //------------------botão "incluir"-----------------------
                if (isset($_GET['up']) && !empty($_GET['up'])){

                        //não pegar as informações e guardar diretamente, falta de segurança (usar addslashes - faz proteção de código malicioso)
                    $up = addslashes($_GET['up']);
                    $nome = addslashes($_POST['nome']);
    
                    if(!empty($nome)){

                            //atualizar
                        $p->atualizar($up, $nome);
                        header("location: index.php");
    
                    }
                }
            //-------------------botão guardar---------------------
                else {
        
                        //não pegar as informações e guardar diretamente, falta de segurança (usar addslashes - faz proteção de código malicioso)
                    $nome = addslashes($_POST['guardar']);
                    if(!empty($nome)){

                            //cadastrar
                        $pessoa = new Pessoa();
                        $pessoa->setNome($nome);
                        if($p->create($pessoa)){

                        }

                    }
                }
            }
        ?>

            <!-- formulário para inserir dados pessoa -->
    <section class="esquerda">
        <form method="POST" id="teste" action="envia.php">
                <input type="button" id="guardar" value="Guardar" name="guardar">

                <input type="button" id="ler" value="Ler">

            </br></br>

                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" value="<?php if(isset($res)){echo $res['nome'];} ?>">
            
                <input type="button" value="Incluir" id="incluir">
            
            </br></br>
        </form>
    </section>
            <!-- tabela para mostrar os dados cadastrados -->
    <section id="titulo">
    <table id="table">
        <thead >
            <tr>
                <td id="p">Pessoas</td> 
            </tr>
        </thead>
        <tbody id="dados">

        </tbody>
    </table>    
    </section>
        <!-- local para mostrar o json -->
    <section id="direita">
       <textarea id="cmd">
            
       </textarea>
    </section>
</body>
</html>

<?php

    if(isset($_GET['id'])){
        $id_p = addslashes($_GET['id']);
        $p->excluir($id_p);
        header("location: index.php");
    }

?>

<script>
    
var pessoas = [];
var data = { "pessoas" : {}};

if(sessionStorage.getItem('chave') != '') {
    var data = JSON.parse(sessionStorage.getItem('chave')) 
}
    
    
//função para pegar dados
$(function(){
    pegaDados();
});

//tabela que pega os dados do json para mostrar na tela
function table(){
    var tr="";
    var dados = JSON.parse(sessionStorage.getItem('chave'));
    console.log(dados.pessoas.length)
    for(let i =0; i < dados.pessoas.length; i++){
        tr += "<tr>";
        tr += "<td>";
        tr += dados.pessoas[i].nome
        tr += "</td>";
        tr += "<td>";
        let rm = "'"+ dados.pessoas[i].nome + "'";
        tr += '<input type="button" id="remover" onclick="rmPessoa('+rm+'); pegaDados();" value="remover">'
        tr += "</td>";
        if(dados.pessoas[i].filhos.length > 0 ){
            dados.pessoas[i].filhos.forEach( element => {
                if(element.filho != '')  {
                    tr += "<tr>";
                    tr += "<td>";
                    let rmf = "'"+ element.filho + "'";
                    let pessoa = "'" + dados.pessoas[i].nome + "'";
                    tr += element.filho
                    tr += "</td>";
                    tr += "<td>";
                    tr += '<input type="button" id="removerf" onclick="removerFilho('+rmf+')" value="remover filho">'
                    tr += "</td>";
                    tr += "<tr>";
                    tr += "<tr>" 
                }
            });
        }
        tr += "<tr>";
        tr += "<tr>"   
        tr += '<td colspan="2">';
        tr += '<input type="button" onclick="addFilho('+i+')" value="Adicionar filho">'
        tr += "</td>"; 
        tr += "<tr>";
        $('#dados').html(tr);
    }
}
    
    //função para quando clicar no botão "incluir"
 $('#incluir').click(function(){
        
        var nome = $('#nome').val();
        
        data.pessoas.push({"nome" : nome, "filhos" : []});

        sessionStorage.setItem('chave',JSON.stringify(data));
        table();
        
        $('#nome').val('');
        $('#cmd').val(JSON.stringify(data));
    });
 
    //adicionar dados do filho
 function  addFilho(params) {
    var filho = prompt("Digite seu filho: ");

    
    var newDados = [];
    for (let index = 0; index < data.pessoas.length; index++) {
    
        // console.log(dados);
       if(index == params)
       {        
        
        data.pessoas[index].filhos.push({filho})

       }     
    }    

        //mostrar os dados do json no textarea na tela
    sessionStorage.setItem('chave',JSON.stringify(data));
        table();
        
        $('#nome').val('');
        $('#cmd').val(JSON.stringify(data));
    }

        //função ao clicar no botão "guardar"
    $('#guardar').click(function(){

        var frm = $(document.teste);
        var dat = ('chave',JSON.stringify(data));

            //guardar os dados no banco de dados
       $.ajax({
            url: 'http://localhost/testeturim/envia.php',
            method: 'post',
            dataType: 'json',
            data: { data: dat},
            success: function(res) {

                console.log(res);
                
            }
       });


    });

        //função ao clicar o botão "ler"
 $('#ler').click(function(){

        pegaDados();

});

    //pegar dados do arquivo "ler.php" e mostrar na tela
function pegaDados()
{
    $.ajax({
            url: 'http://localhost/testeTurim/ler.php',
            method: 'post',
            dataType: 'json',
            success: function(res) {

                console.log(res);
                $('#cmd').val(JSON.stringify(res));
                sessionStorage.setItem('chave',JSON.stringify(res));
                table();
                
            }
});
}
    //remover dados da pessoa
function rmPessoa(dados)
{
    $.ajax({
            url: 'http://localhost/testeTurim/delete.php',
            method: 'post',
            dataType: 'json',
            data: { 'pessoa' : dados },
            success: function(res) {

                alert(res);
                window.location.reload();
            }
            
});

pegaDados();

}
    //remover dados do filho
function removerFilho(filho)
{
    $.ajax({
            url: 'http://localhost/testeTurim/delete.php',
            method: 'post',
            dataType: 'json',
            data: { 'filho' : filho },
            success: function(res) {
                alert(res)
                window.location.reload();
                
            }
           
});

}
</script>