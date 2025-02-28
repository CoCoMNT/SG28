<?php namespace Phpcmf\Controllers;

class Sfjnd extends \Phpcmf\Common
{

    public function index() {

        echo $this->getFirstChar('this');
        echo "<br>";
        echo $this->getLastChar('this');
    }
   
}
