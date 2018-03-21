<?php
class ticket_controller extends base_controller{
    
    public function create(){
        $id = $_SESSION["id_de_la_sesion"];
        require_once $this->_helpers->linkTo('create-ticket.php', 'Views', 'require');
    }
	
}