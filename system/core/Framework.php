<?php

class Framework {
    private $elements;

    protected $registry;

    public function __construct() {
        $this->init();
        $this->registry = new Registry();

        $this->rewriteEngine();
        $this->autoload();
        echo $this->dispatch();
    }

    /*public  function run(){
        $this->init();
        $this->registry = new Registry();

        $this->rewriteEngine();
        $this->autoload();
        $this->dispatch();
    }*/

    private function init() {
        require_once "system/config/init_config.php";
    }

    private function rewriteEngine() {
        $path = trim(strtok($_SERVER['REQUEST_URI'], '?'), '/');

        $this->elements = $elements = array_filter(explode('/', $path));

        if(!isset($elements[1])){
            $elements = array_filter(explode('\\', $path)); // Local Windows fix
        }

        if(!isset($elements[0])){
            $elements[0] = 'common'; // Fix for undefined offset 0
        }

        $elements = $this->defineCurrDir();

        switch (count($elements)) {
            case 0:
                $path = 'common' . DS . 'index';
                break;
            case 1:
                $path = ($elements[0] ? $elements[0] : 'common') . DS . 'index';
                break;
            case 2:
                $path = $elements[0] . DS . ($elements[1] ? $elements[1] : 'index');
                break;
            default:
                $path = '';
                for($i=0; $i<count($elements)-1; $i++) {
                    $path .= $elements[$i] . DS;
                }
                $path = rtrim($path, DS);
                $method = end($elements);
                break;
        }

        if (!is_file(APP_PATH . CURR_DIR . CONTROLLER . strtolower($path) . ".php")) {
            if(($slug = $this->checkSlugDb()) === null) { // 404
                define("CURR_CONTROLLER", 'error' . DS . 'not_found');
                define("CURR_METHOD", 'index');
            } else {
                define("CURR_CONTROLLER", strtolower($slug['redirect']));
                define("CURR_METHOD", $slug['method'] != null ? strtolower($slug['method']) : 'index');
            }
        } else {
            define("CURR_CONTROLLER", strtolower($path));
            define("CURR_METHOD", isset($method) ? $method : 'index');
        }
    }

    private function autoload() {
        spl_autoload_register(array(__CLASS__,'load'));
    }

    private function load($classname) {
        /*if(CURR_CONTROLLER) {*/
        $file = APP_PATH . CURR_DIR . CONTROLLER . CURR_CONTROLLER . ".php";
        /*} else {
        }*/
        /*$path = preg_split('/(?=[A-Z])/', $classname);
        $file = APP_PATH . CURR_DIR;
        for($i = 1;$i < count($path); $i++)
            $file .= DS . lcfirst($path[$i]);
        $file .= '.php';
        $file = preg_replace('#/+#','/',$file);*/
        try {
            if(!is_file($file)) {
                throw new Exception ($classname . ' does not exist');
            }
            else
                require_once($file);
        } catch(Exception $e) { // TODO : create an exception handler
            echo "Message : " . $e->getMessage();
            echo "</br>";
            echo "Code : " . $e->getCode();
        }
    }

    private function checkSlugDb() {
        $path = trim(strtok($_SERVER['REQUEST_URI'], '?'), '/');
        $db = new Db('');

        $url_request = $db->select(DB_PREFIX . 'url_redirect')->where(["url_slug = '" . $db->escape($path) . "'"])->exec(0);

        if($url_request['url_slug'] === $path)
            return $url_request;
        else
            return null;
    }

    private function dispatch() {
        $controller_name = $this->transformPath(CURR_CONTROLLER);
        $action_name = CURR_METHOD ;
        $controller = new $controller_name($this->registry);
        try {
            if(method_exists($controller, $action_name)){
                return $controller->$action_name(); // lead controller method
            } else {
                throw new Exception('Class method does not exists'); // throw exception
            }
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n"; // echo the exception. TODO : update that to be logged and not displayed
            //$this->load('ControllerErrorNot_found');
        }
    }

    private function defineCurrDir() {
        $dir = isset($this->elements[0]) && $this->elements[0] === ADMIN_LINK ? ADMIN_PATH . DS : HOME_PATH . DS;
        define("CURR_DIR", $dir);
        if(isset($this->elements[0]) && $this->elements[0] === ADMIN_LINK) array_splice($this->elements, 0, 1);
        return $this->elements;
    }

    private function transformPath($path) {
        $elements = array_filter(explode('/', $path));
        if(!isset($elements[1])){
            $elements = array_filter(explode('\\', $path)); //Local Windows fix
        }
        $classname = trim(ucfirst(CONTROLLER), DS);
        foreach($elements as $element)
            $classname .= str_replace('_','', ucfirst($element));
        return $classname;
    }
}