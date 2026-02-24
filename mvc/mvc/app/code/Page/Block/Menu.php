<?php 
 class Page_Block_menu extends Core_Block_Template
    {
        public function __construct(){
            
            $this->setTemplate("Page/View/menu.phtml");
        }
        public function getMenuArray(){
            return [
                "url1"=>"category1",
                "url2"=>"category2",
                "url3"=>"category3"
                ];
        }
         
    }