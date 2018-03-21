<?php
class App extends Core{

    protected $BASEDIR  = "http://javier-cart.kitecweb.com/";
    protected $BASEDIRAPI  = "http://node-epay.braincorepro.com:1500/";
    
    /**
    * sanitize url
    * @param String $url
    * @return String sanitize url
    */
    protected function sanitizeURL($u){
        $sanitize = filter_var($u, FILTER_SANITIZE_URL);
        $sanitize = str_replace('\\', '/', $sanitize);
        $sanitize = str_replace('//', '/', $sanitize);
        $sanitize = ltrim($sanitize, '/');
        $sanitize = rtrim($sanitize, '/');
        return $sanitize;
    }

    /**
    * Call the contoller
    */
    protected function callController($request){
        /*var_dump($request);*/
        $route = self::sanitizeURL($request['route']);
  
        unset($request['route']);
        $params = [];
        
        $r = explode('/', $route);
        #controller
        $controller = (isset($r[$this->posCtrl]) && $this->posCtrl > -1) ? $r[$this->posCtrl] : 'index';
        #action
        $action = (isset($r[$this->posAction])) ? $r[$this->posAction] : 'index';
        
        /*echo $controller ." ". $action;die();*/

        unset($r[$this->posCtrl]); unset($r[$this->posAction]);

        foreach($r as $p){ array_push($params, $p); }

        $classname = strtolower($controller) . '_controller';
       /* echo $classname;die();*/

        if(class_exists($classname)){
            if(method_exists($classname, $action)){
                $v = new $classname($params, $request);
                $v->$action();
            }else if($action == "redes-sociales"){
                $action = "redesSociales";
                $v = new servicios_controller($params, $request);
                $v->$action();
            }else if($action == "metricas-digitales"){
                $action = "metricasDigitales";
                $v = new servicios_controller($params, $request);
                $v->$action();
            }else if($action == "aplicativos-moviles"){
                $action = "moviles";
                $v = new servicios_controller($params, $request);
                $v->$action();
            }else if($action == "desarrollo-web"){
                $action = "web";
                $v = new servicios_controller($params, $request);
                $v->$action();
            }else{
                $_helpers = new Helpers();
                $_helpers->throwErrorPage(['msg' => 'Action <b>'. $action .'</b> not exists in <b>'. $controller .'</b> controller']);
            }
        }else{
            $_helpers = new Helpers();
            $_helpers->throwErrorPage(['msg' => 'Page not found']);
        }
    }

    /**
    * Render a complete view
    * @param String $name
    * @param String $extension
    */
    public function render($name, $extension = 'php'){
        require_once APPDIR::Views . $name . '.' . $extension;
    }

    /**
    * Render a partial view
    * @param String $name
    * @return String partial
    */
    public function renderPartial($name, $extension = 'php'){
        $extension = (empty($extension)) ? 'php' : $extension;
        require_once APPDIR::Views . 'partials/_' . $name . '.' . $extension;
    }
    
    

    /**
    * Return a json response
    * @param Array $data
    * @return json object
    */
    public function responseJSON($data){
        $dt = [];

        $dt = $this->md_array_map('utf8_encode', $data);

        return json_encode($dt);
    }
}
