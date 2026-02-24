<?php
class Sdp {

    public static function hello()
    {
        echo 'Hello World';
    }

    public static function run()
    {
        $front=new Core_Controllers_Front ();
        $front->run();
    }
    
    public static function getModel($modelName)
    {
        $model=array_map('ucfirst',explode('/',$modelName));
        $className=sprintf("%s_Model_%s",
        $model[0],
        $model[1]);
        $modelObj=new $className();
        return $modelObj;
    }

    public static function getBlock($blockName)
    {
    $block=array_map('ucfirst',explode('/',$blockName));
    $className=sprintf("%s_Block_%s",
        ucfirst($block[0]),
        ucfirst($block[1]));
    $blockObj=new $className();
    return $blockObj;
    }
}
?>