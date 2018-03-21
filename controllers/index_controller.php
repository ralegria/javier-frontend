<?php
class index_controller extends base_controller{

    public function index(){
        if (empty($_SESSION["id_de_la_sesion"])){
            require_once $this->_helpers->linkTo('login.php', 'Views', 'require');
        }else{
            $id = $_SESSION["id_de_la_sesion"];
            require_once $this->_helpers->linkTo("create-ticket.php", "Views", "require");
        }
    }
	
}