<?php


class Filho {
    private int $id; 
    private int $idPessoa;
    private string $nome;

    public function __construct(){    
    
    }
    public function getId(){return $this->id;}
    public function getIdPessoa(){return $this->idPessoa;}
    public function getNome(){return $this->nome;}
    

    public function setId($id){
        $this->id=$id;
    }
    public function setIdPessoa($pessoa){
        $this->idPessoa=$pessoa;
    }
    public function setNome($nome){
        $this->nome=$nome;
    }
}

?>