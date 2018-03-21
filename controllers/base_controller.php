<?php
class base_controller extends App{
    public $request;#Request params(post, get)
    public $params;#Route params
    protected $_security;#Security Object
    public $_helpers;
    
    function __construct($params, $request){
        $this->params = $params;
        $this->request = $request;
        $this->_security = new Security();
        $this->_helpers = new Helpers();
    }
}