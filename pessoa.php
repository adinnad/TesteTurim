<?php


class Pessoa
{  
    private int $id;
    private string $nome;

    public function __construct(){    
    
    }
    public function getId(){return $this->id;}
    public function getNome(){return $this->nome;}
    

    public function setId($id){
        $this->id=$id;
    }
    public function setNome($nome){
        $this->nome=$nome;
    }
    
}
?>