<?php
 require_once 'bd.php';
 require_once 'filho.php';

class DaoFilho {

    private $instanciaConexaoAtiva;
    private $tabela = "filho";

    public function __construct(){
        $this->instanciaConexaoAtiva= Bd::getInstancia();
        $this->tabela="filho";
    }
      //adicionar filho em uma pessoa
     public function getIdPessoa($object){
        $sqlStmt = "SELECT * from {$this->tabela} where idPessoa = :idPessoa ";
        $dados=[];
        try {
           $operacao = $this->instanciaConexaoAtiva->prepare($sqlStmt);   
           $operacao->bindValue(":idPessoa",$ob->getId(), PDO::PARAM_INT);     
           $operacao->execute();
           if($operacao->execute())
           {
               while ($rs = $operacao->fetchObject(Filho::class)) {
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

      //adicionar o dado do filho no banco de dados
     public function create($objeto){
         $nome = $objeto->getNome();        
         $id = $this->getNewIdBico();
         $idPessoa = $objeto->getIdPessoa();
         $operacao="INSERT INTO {$this->tabela} SET nome=:nome, idPessoa = :pessoa, id = :id";
         try {
             $operacao=$this->instanciaConexaoAtiva->prepare($operacao);
             $operacao->bindValue(":nome",$nome, PDO::PARAM_STR);
             $operacao->bindValue(":pessoa",$idPessoa, PDO::PARAM_INT);
             $operacao->bindValue(":id",$id, PDO::PARAM_INT);
             if($operacao->execute()){
                if($operacao->rowCount() > 0) {                    
                    return true;
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


     //deletar um dado do filho do banco de dados
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