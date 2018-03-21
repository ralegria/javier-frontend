<?php
class Security extends App{
    
    /**
    * CSRF security
    * @param String $form unique form name
    * @return Input hidden with token security generated
    */
    public function setCSRFToken($if_form = true){
        #random_compact
        require_once APPDIR::Libs . "paragonie_random_compact/random_compat.phar";
        
        if(isset($_SESSION)){
            
            if(isset($_SESSION['_csrfToken'])){
                $token = $_SESSION['_csrfToken'];
            }else{
                $token = $_SESSION['_csrfToken'] = $this->generateCSRFToken();
            }
            
            #meta tag (for ajax requestes)
            if($if_form){
                return "<input type = 'hidden' name = '_csrfToken' value = '{$token}'>";
            }else{
                return "<meta name = '_csrfToken' content = '{$token}' />";
            }
        }else{
            header('Location: ' . parent::$BASEDIR);
        }
    }
    
    /**
    * Validación del csrf token
    * @param String $token_request
    * @return Boolean
    */
    public function validateCSRFToken($token_request){
        if(is_string($token_request)){
            
            if(!function_exists('hash_equals')){
                return Core::hash_equals($_SESSION['_csrfToken'], $token_request);
            }else{
                return hash_equals($_SESSION['_csrfToken'], $token_request);
            }
        }else{
            return false;
        }
    }
    
    /**
    * Validación http 
    * @param String $require method
    * @return Boolean
    */
    public function checkHTTPMethod($require){
        if(isset($_SERVER['REQUEST_METHOD'])){
            return ($_SERVER['REQUEST_METHOD'] == $require);
        }else{
            return false;
        }
    }
    
    /**
    * Genera el token de seguridad
    * @return String $token
    */
    private function generateCSRFToken(){
        $now = time();
        
        try {
            $string = random_bytes(32);
            return bin2hex($string . $now);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}