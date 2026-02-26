<?php 
   class Core_Model_Abstract{
    protected $_data=[];
    protected $_resource=null;
    public function init($resourceModel){
        
       $this->_resource= Sdp::getResourceModel($resourceModel);
    }
    public function __set($key,$value){
        $this->_data[$key]=$value;
        return $this;
    }

    public function __get($key){
        return $this->_data[$key];
    }
    public function __call($method, $args)
    {
        // echo "This is private or non existing method : $method <br>";
 
        // echo "<pre>";
        // print_r($args);
 
        // if (method_exists($this, $method)) {
        //     call_user_func_array([$this, $method], $args);
        // } else {
        //     echo "Method $method not found!";
        // }
 
 
        if (substr($method, 0, 3) == "set") {
            $property = strtolower(substr($method, 3));
            $this->$property = $args[0];
            return;
        }
 
        if (substr($method, 0, 3) == "get") {
            $property = strtolower(substr($method, 3));
            return $this->$property ?? null;
        }
 
        // echo "Method $method not found!";
    }
    public function addData($data=[])
    {
        $this->_data=$data;
        return $this;
    }
    public function getResource()
    {
       return $this->_resource;
    }
    public function load($value,$field=null)
    {
        // $mysql= Sdp::getModel('core/connection_Mysql');
        // $query= "select * from catalog_product";
        // $data=$mysql->fetchOne();
        $data=$this->getResource()->load($this,$value,$field);
        $this->_data=$data;

    }
    public function isEmpty()
    {
        return empty($this->_data);
    }
   }
?>