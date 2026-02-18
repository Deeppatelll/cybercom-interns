<?php 
  class Page_Block_Root extends Core_Block_Template
  {
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate("Page/view/root.phtml");
    }
        public function _construct(){
        $footer=Sdp::getBlock('page/footer');
        $content=Sdp::getBlock('page/content');
        $header=Sdp::getBlock('page/header');
        $head=Sdp::getBlock('page/head');
        $this->addchild("head", $head);
        $this->addchild("header", $header);
        $this->addchild("content", $content);
        $this->addchild("footer", $footer);
    }
  }
?>