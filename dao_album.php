<?php

require_once "cnx.php";
require_once "pojo_album.php";
//require_once "logsctrl.php";

class DaoAlbum {

    public static $instance;

    private function __construct() {
        //
    }

    public static function getInstance() {
        if (!isset(self::$instance))
            self::$instance = new DaoAlbum();

        return self::$instance;
    }

    public function Inserir(PojoAlbum $album) {
        try {
            $sql = "INSERT INTO album (nome) VALUES (:nome)";

            $p_sql = Conexao::getInstance()->prepare($sql);

            $p_sql->bindValue(":nome", $album->getNome());

            return $p_sql->execute();

        } catch (Exception $e) {
            print "Ocorreu um erro ao tentar executar esta ação, foi gerado um LOG do mesmo, tente novamente mais tarde: ".$e->getMessage();
            //salvarLog($e->getMessage());
        
        }
    }

    public function AddImg($nomeImg, $idAlbum) {
        try {
            $sql = "INSERT INTO foto (imagem,idalbum) VALUES (:nome,:idalbum)";

            $p_sql = Conexao::getInstance()->prepare($sql);

            $p_sql->bindValue(":nome", $nomeImg);
            $p_sql->bindValue(":idalbum", $idAlbum);

            return $p_sql->execute();

        } catch (Exception $e) {
            print "Ocorreu um erro ao tentar executar esta ação, foi gerado um LOG do mesmo, tente novamente mais tarde: ".$e->getMessage();
            //salvarLog($e->getMessage());
        
        }
    }

    public function Editar(PojoAlbum $album) {
        try {
            $sql = "UPDATE album set
		        nome = :nome,
                situacao = :situacao WHERE id = :idalbum";

            $p_sql = Conexao::getInstance()->prepare($sql);

            $p_sql->bindValue(":nome", $album->getNome());
            $p_sql->bindValue(":situacao", $album->getSituacao());
            $p_sql->bindValue(":idalbum", $album->getId());

            return $p_sql->execute();
        } catch (Exception $e) {
            print "Ocorreu um erro ao tentar executar esta ação, foi gerado um LOG do mesmo, tente novamente mais tarde: ".$e->getMessage();
            //salvarLog($e->getMessage());
        }
    }

    public function Deletar($idalbum) {
        try {

            $sql_at = "DELETE FROM foto WHERE idalbum = :idalbum";
            $p_sql_at = Conexao::getInstance()->prepare($sql_at);
            $p_sql_at->bindValue(":idalbum", $idalbum);
            if($p_sql_at->execute()){
                $sql = "DELETE FROM album WHERE id = :idalbum";
                $p_sql = Conexao::getInstance()->prepare($sql);
                $p_sql->bindValue(":idalbum", $idalbum);

                return $p_sql->execute();

            }else{
                return false;
            }

            
           
        } catch (Exception $e) {
            print "Ocorreu um erro ao tentar executar esta ação, foi gerado um LOG do mesmo, tente novamente mais tarde: ".$e->getMessage();
            //salvarLog($e->getMessage());
        }
    }

    public function BuscarPorID($idalbum) {
        try {
            $sql = "SELECT * FROM album WHERE id = :idalbum";
            $p_sql = Conexao::getInstance()->prepare($sql);
            $p_sql->bindValue(":idalbum", $idalbum);
            $p_sql->execute();
            return $p_sql->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            print "Ocorreu um erro ao tentar executar esta ação, foi gerado um LOG do mesmo, tente novamente mais tarde: ".$e->getMessage();
            //salvarLog($e->getMessage());
           
        }
    }
    public function Popula($row) {
        $pojo = new PojoAlbum;
        $pojo->setId($row['id']);
        $pojo->setNome($row['nome']);
        $pojo->setSituacao($row['situacao']);
        return $pojo;
    }

    public function BuscarTodos($sit) {
        try {
            if($sit==0){
                $sql = "SELECT * FROM album order by id desc";
            }else{
                $sql = "SELECT * FROM album where situacao= :sit order by id desc";
            }
           
            $p_sql = Conexao::getInstance()->prepare($sql);   
            if($sit!=0){        
                $p_sql->bindValue(":sit", $sit);
            }
            $p_sql->execute();
            return $p_sql->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            print "Ocorreu um erro ao tentar executar esta ação, foi gerado um LOG do mesmo, tente novamente mais tarde: ".$e->getMessage();
            //salvarLog($e->getMessage());
           
        }
    }

    public function UltimaAtt() {
        try {
            $sql = "SELECT max(id) FROM album";
            $p_sql = Conexao::getInstance()->prepare($sql);           
            $p_sql->execute();
            return intval($p_sql->fetchAll(PDO::FETCH_ASSOC)[0]['max(id)']);
        } catch (Exception $e) {
            print "Ocorreu um erro ao tentar executar esta ação, foi gerado um LOG do mesmo, tente novamente mais tarde: ".$e->getMessage();
            //salvarLog($e->getMessage());
           
        }
    }


    public function CarregarFotos($idalbum) {
        try {
            $sql = "SELECT * FROM foto where idalbum = :idalbum order by id desc";
            $p_sql = Conexao::getInstance()->prepare($sql); 
            $p_sql->bindValue(":idalbum", $idalbum);        
            $p_sql->execute();
            return $p_sql->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            print "Ocorreu um erro ao tentar executar esta ação, foi gerado um LOG do mesmo, tente novamente mais tarde: ".$e->getMessage();
            //salvarLog($e->getMessage());
           
        }
    }

    public function DeletarFoto($idfoto) {
        try {
            $sql = "DELETE from foto where id = :idfoto";
            $p_sql = Conexao::getInstance()->prepare($sql); 
            $p_sql->bindValue(":idfoto", $idfoto);                    
            return $p_sql->execute();
        } catch (Exception $e) {
            print "Ocorreu um erro ao tentar executar esta ação, foi gerado um LOG do mesmo, tente novamente mais tarde: ".$e->getMessage();
            //salvarLog($e->getMessage());
           
        }
    }
    public function SalvarTituloImg($idfoto,$titulo) {
        try {
            $sql = "UPDATE foto set titulo=:titulo where id = :idfoto";
            $p_sql = Conexao::getInstance()->prepare($sql); 
            $p_sql->bindValue(":titulo", $titulo);                    
            $p_sql->bindValue(":idfoto", $idfoto);                    
            return $p_sql->execute();
        } catch (Exception $e) {
            print "Ocorreu um erro ao tentar executar esta ação, foi gerado um LOG do mesmo, tente novamente mais tarde: ".$e->getMessage();
            //salvarLog($e->getMessage());
           
        }
    }


}

?>