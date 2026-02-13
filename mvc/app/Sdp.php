<?php
class Sdp {
     public static function hello(){
        echo 'Hello World';
     }
    public static function run() {
       
         $request= new Core_Models_Request();
         $className=sprintf("%s_Controllers_%s",
         ucfirst($request->getModuleName()),
        ucfirst( $request->getControllerName()));
        $action=$request->getActionName().'Action';
        $classObj=new $className();
        $classObj->$action();
    }
}

?>