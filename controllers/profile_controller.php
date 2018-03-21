<?php
class profile_controller extends base_controller{

    public function login(){
        $response = array('message' => 'success');
        $id_user = (isset($this->request['id_user'])) ? $this->request['id_user'] : NULL ;
        $_SESSION["id_de_la_sesion"]=$id_user;

        echo $this->responseJSON($response);
    }
    
    public function logout(){
        session_destroy();
        $response = array('message' => 'success');
        
        require_once $this->_helpers->linkTo('login.php', 'Views', 'require');
    }
	
}