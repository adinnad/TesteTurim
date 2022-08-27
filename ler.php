<?php

    //confiuração do botão "Ler"
include_once 'daoPessoa.php';
include_once 'pessoa.php';
include_once 'daoFilho.php';
include_once 'filho.php';
require_once 'bd.php';


    //pegar informações do banco de dados
$instanciaConexaoAtiva= Bd::getInstancia();

$sqlStmt = "SELECT p.nome as pessoas, GROUP_CONCAT(f.nome) as filhos from pessoa p left join filho f on f.idPessoa = p.id GROUP by p.nome;";
$dados=[];
try {
   $operacao = $instanciaConexaoAtiva->query($sqlStmt);         
   $dados = $operacao->fetchAll(PDO::FETCH_ASSOC);
   tojson($dados);
   
} catch( PDOException $excecao ){
   return $excecao->getMessage();
}

    
 function jsonSerialize($object)
{
     $filhos = explode(",", $object['filhos']);
     $arrayFilho = [];
     if(count($filhos) > 0){

     foreach ($filhos as $key => $value) {
       array_push($arrayFilho, ["filho" => $value]);
     }
    }
        return [
            
                "nome" => $object['pessoas'],
                "filhos" => 
                    $arrayFilho
                ,
                         
        ];
        // var_dump($object);
}     
    //mostrar dados no json, trazidos do banco de dados
 function tojson($dadosBuscados)
{

    $array = [ 'pessoas' => [] ];
    foreach($dadosBuscados as $key=> $value)
    {
        array_push($array['pessoas'],jsonSerialize($value));
    }
    // var_dump($array);
    echo json_encode($array);
}


?>