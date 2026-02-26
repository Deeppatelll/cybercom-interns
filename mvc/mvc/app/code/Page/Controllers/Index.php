<?php 
     class Page_Controllers_Index
     {
            public function indexAction() 
            {
               
                 //$head=Sdp::getBlock('page/head'); 
                // echo "<pre>";
               //  print_r($head);
                 //$header=Sdp::getBlock('page/header');
                 //echo "<pre>";
                 //print_r($header);
                 //echo "</pre>";
                 $root=Sdp::getBlock('page/root');
                 $home=Sdp::getBlock('page/home');
                 $root->getChild('content')->addChild('home', $home);
                 $root->toHtml();
                 //echo "<pre>";
                 //print_r($root);

           }
     }
?>