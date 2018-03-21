<?php
class tienda_controller extends base_controller{

    public function catalogo(){
        $iduser = $this->params[0];
        $id = $_SESSION["id_de_la_sesion"];
        require_once $this->_helpers->linkTo('tienda.php', 'Views', 'require');
    }
	
}