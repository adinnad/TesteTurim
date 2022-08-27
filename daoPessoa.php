<?php
 require_once 'bd.php';
 require_once 'pessoa.php';

class DaoPessoa {

    private $instanciaConexaoAtiva;
    private $tabela = "pessoa";

    public function __construct(){
        $this->instanciaConexaoAtiva= Bd::getInstancia();
        $this->tabela="pessoa";
    }
     
     public function getAll(){
        $sqlStmt = "SELECT p.nome, f.nome from {$this->tabela} ";
        $dados=[];
        try {
           $operacao = $this->instanciaConexaoAtiva->prepare($sqlStmt);         
           $operacao->execute();
           if($operacao->execute())
           {
               while ($rs = $operacao->fetchObject(Pessoa::class)) {
                $dados[] = $rs;
            }
           }
           if(count($dados)>0){
            return $dados;
           }
           return false;
           
        } catch( PDOException $excecao ){
           return $excecao->getMessage();
        }
     }

     public function create($objeto){
            $operacao="delete from {$this->tabela} ";
            $operacao=$this->instanciaConexaoAtiva->prepare($operacao);
         $nome = $objeto->getNome();        
         $id = $this->getNewIdBico();
         $operacao="INSERT INTO {$this->tabela} SET nome=:nome, id = :id";
         try {
             $operacao=$this->instanciaConexaoAtiva->prepare($operacao);
             $operacao->bindValue(":nome",$nome, PDO::PARAM_STR);
             $operacao->bindValue(":id", $id, PDO::PARAM_INT);
             if($operacao->execute()){
                if($operacao->rowCount() > 0) {                    
                    return $id;
                 } else {
                    return false;
                 }
                
             }
             else {
                return false;
          }

         } catch (\Throwable $th) {
            return $th->getMessage();
         }
     }

     public function delete($param){
        $operacao = "DELETE FROM {$this->tabela} WHERE nome=:nome";
        try {
            $operacao = $this->instanciaConexaoAtiva->prepare($operacao);
            $operacao->bindValue(":nome", $param, PDO::PARAM_STR);
            if($operacao->execute()){
                if($operacao->rowCount()>0) {
                      return true;
                } else {
                      return false;
                }
             } else {
                return false;
             }
        } catch (PDOException $th) {
            echo $th->getMessage();
        }

     }


     private function getNewIdBico(){
        $sqlStmt = "SELECT MAX(id) AS id FROM {$this->tabela}";
        try {
           $operacao = $this->instanciaConexaoAtiva->prepare($sqlStmt);
           if($operacao->execute()) {
              if($operacao->rowCount() > 0){
                 $getRow = $operacao->fetch(PDO::FETCH_OBJ);
                 $idReturn = (int) $getRow->id + 1;
                 return $idReturn;
              } else {
                 throw new Exception("Ocorreu um problema com o banco de dados");
                 exit();
              }
           } else {
              throw new Exception("Ocorreu um problema com o banco de dados");
              exit();
            }
        } catch (PDOException $excecao) {
           return $excecao->getMessage();
        }
     }

}

?>