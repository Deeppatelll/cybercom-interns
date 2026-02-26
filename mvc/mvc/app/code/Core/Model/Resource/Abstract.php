<?php 
   class Core_Model_Resource_Abstract{
    protected $_tabelName=null;
    protected $_primaryKey=null;
      
     protected function _init($tableName,$primaryKey){
        $this->_tableName=$tableName;
        $this->_primaryKey=$primaryKey;
    }
    public function getTablename(){
     return $this->_tableName;
    }
    public function getPrimaryKey(){
        return $this->_primaryKey;
    }
     public function load($model,$value,$field)
         {
            $field=(is_null($field)) ?$this->getPrimaryKey():$field;
            $mysql= Sdp::getModel('core/connection_Mysql');
            $query= "select * from {$this->getTableName()} where {$field}={$value}";
            $data=$mysql->fetchOne($query);    
            return $data;    
            // echo "*select * from {$this->getTableName()}";
            // echo "*select * from {$this->getTableName()}where {$field}={$value}";
         }
   }
?>