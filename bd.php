<?php 
    class Bd{
            //conexão com banco de dados
        private static $instancia;
        private $pdo;  
        private static $dbtype="mysql";
        private static $host="localhost";
        private static $port="3306";
        private static $user="root";
        private static $password="";
        private static $db="pessoa";  
            
            //contrutor, ponto de partida do código, primeiro código será executado (conexção com bd)
        public function __construct(){
            
        }

        public static function getInstancia() {
            if(!isset(self::$instancia)) {
                 try {                        
                     
                     // Instânciado um novo objeto PDO informando o DSN e parâmetros de Array
                     self::$instancia=new PDO(self::$dbtype.":host=".self::$host.";port=".self::$port.";dbname=".self::$db,self::$user,self::$password);
                     
                     // Gerando um excessão do tipo PDOException com o código de erro
                     self::$instancia->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                 
                 } catch ( PDOException $excecao ){
                     echo $excecao->getMessage();
                     // Encerra aplicativo
                     exit();
                 }
             }
             return self::$instancia;
            }
            //metodo para buscar no banco de dado e colocar na tela
        
    }

?>