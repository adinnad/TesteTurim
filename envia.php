<?php
include_once 'daoPessoa.php';
include_once 'pessoa.php';
include_once 'daoFilho.php';
include_once 'filho.php';

    //pegar dados do json e enviar para o banco de dados
$dados = json_decode($_POST['data']);

$pessoa = new Pessoa;
$daopessoa = new DaoPessoa;
$filho =  new Filho;
$daoFilho =  new DaoFilho;
foreach($dados->pessoas as $item){

    $pessoa->setNome($item->nome);
    $res = $daopessoa->create($pessoa);
     
    foreach($item->filhos as $i)
    {
        $filho->setNome($i->filho);
        $filho->setIdPessoa($res);
        $daoFilho->create($filho);
    }
}

if($res)
{
    echo "cadastrados com sucesso!";
    
}



?>