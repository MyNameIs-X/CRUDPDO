<?php


/*
*
* CRUD + MVC 
* Por Pedro Henrique
* Contato: pedro.progsantos@gmail.com
*
*/

namespace Lib;

use \Lib\Config;

class Database{

    private $con;

    public function __construct(){

        try{
            $config = new Config();
            $config::carregar();


            $this->con = new \PDO('mysql:host='.$config::get('pdo.host').';dbname='.$config::get('pdo.dbname'), $config::get('pdo.user'), $config::get('pdo.pass'));
            $this->con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->con->exec('set names utf8');


        }catch (\PDOException $e){

            echo 'PDO EXCEPTION -> '. $e->getMessage();

            return false;

        }
    }

    public function dbSelect($tabela, $campos = '*', $condicion = ''){

        $query = "SELECT ".$campos." FROM ".$tabela;
        $query .= empty($condicion) ? '' : ' '.$condicion ;

        //echo $query . '<br/>';

        $prepare = $this->con->prepare($query);

        try{

            $prepare->execute();

            if($prepare->rowCount()){
                
                return $prepare->fetchAll();

            }else{

                return false;

            }

        }catch(PDOException $e){

            echo $e->getMessage();
            return false;

        }
    }

    public function dbUpdate($tabela, $id, $campos = array()){

        $query = "UPDATE ".$tabela." SET ";

        $i = 0;

        foreach ($campos as $campo => $valor) {

            $query .= $campo . " = '"  . $valor . "'";

            if($i+ 1 != count($campos)){

                $query .= ', ';
            }

            $i++;

        }

        $query .= " WHERE id = '{$id}'";

        $prepare = $this->con->prepare($query);

        try{

            $prepare->execute();

            if($prepare->rowCount()){

                return true;

            }else{
                
                return false;

            }

        }catch(PDOException $e){

            echo $e->getMessage();
            return false;

        }
    }

    public function dbInsert($tabela, $campos = array()){

        $query = "INSERT INTO " . $tabela . " (";

        $i = 0;

        $camposT = array_keys($campos);

        foreach ($camposT as $c) {

            $query .= $c;
            
            if($i + 1 != count($camposT)){

                $query .= ", ";

            }

            $i++;

        }
        
        $query .= ') VALUES (';

        $i = 0;

        foreach ($campos as $valor) {
            
            $query .= "'".$valor."'";

            if($i + 1 != count($campos)){
                $query .= ', ';
            }

            $i++;
        }

        $query .= ')';

        $prepare = $this->con->prepare($query);

        try{

            $prepare->execute();

            if($prepare->rowCount()){
                
                return true;

            }else{

                return false;

            }

        }catch(PDOException $e){

            return $e->getMessage();

        }

    }

    public function dbDelete($tabela, $condicion){

        $query = "DELETE FROM ".$tabela.' WHERE '. $condicion;

        $prepare = $this->con->prepare($query);

        try{

            $prepare->execute();

            if($prepare->rowCount()){

                return true;

            }else{

                return false;

            }

        }catch(PDOException $e){

            echo $e->getMessage();
            return false;

        }
    }
}