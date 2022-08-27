<?php
include_once 'daoPessoa.php';
include_once 'daoFilho.php';

    //delletar dados da pessoa
if(@$_POST['pessoa'])
{
    $daopessoa = new DaoPessoa;
    $daopessoa->delete($_POST['pessoa']);
// $daoFilho =  new DaoFilho;
 echo json_encode("deletados");
 die;

    //delletar dados do filho
}elseif(@$_POST['filho'])
{
    $daoFilho =  new DaoFilho;
    $daoFilho->delete($_POST['filho']);
// $daoFilho =  new DaoFilho;
echo json_encode("deletados");
die;
}

echo "dados não deletados"





?>