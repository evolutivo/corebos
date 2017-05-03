<?php
class MysqlClass{
    
  // parametri per la connessione al database
  private $nomehost = "127.0.0.1";     
  private $nomeuser = "root";          
  private $password = "";
  // controllo sulle connessioni attive
  private $attiva = false;
  public $getDbList = "";
  // funzione per la connessione a MySQL
  // funzione per la connessione a MySQL

  public function connetti($db){
    if(!$this->attiva){
        if($connessione = mysql_connect($this->nomehost,$this->nomeuser,$this->password) or die (mysql_error())){
          // selezione del database
          mysql_select_db($db,$connessione) or die (mysql_error());
        }
        }else{
            return true;
        }
    }
    
    public function connettiMysql(){
    if(!$this->attiva){
       mysql_connect($this->nomehost,$this->nomeuser,$this->password) or die (mysql_error())or die (mysql_error());
        }
        else{
            return true;
        }
    }


// funzione per la chiusura della connessione
public function disconnettiMysql(){
        if($this->attiva){
                if(mysql_close()){
         $this->attiva = false; 
             return true; 
                }else{
                        return false; 
                }
        }
 }
  

       
    public function getTableList($db) {
    $i=0;
    $elencoTabelle = mysql_list_tables ($db);
    while ($i < mysql_num_rows ($elencoTabelle)){
    $table[$i] = mysql_tablename ($elencoTabelle, $i);
    $i++;
}
  
    return $table;
    }
    public function getFields($table, $db){

        $fields = mysql_list_fields($db, $table);
        $numero_colonne = mysql_num_fields($fields);
        for ($i = 0; $i < $numero_colonne; $i++){
            $arrayFields[$i]=mysql_field_name($fields,$i);
        }
        return $arrayFields;
}

}



?>
