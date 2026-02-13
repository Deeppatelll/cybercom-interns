<?php

spl_autoload_register(function(string $classss)
{
  $base=__DIR__.'\\';
  $file = str_replace('_', '/', $classss);
  $sprintf=sprintf('%s.php',$base.$file);
  require_once($sprintf);
});
?>

