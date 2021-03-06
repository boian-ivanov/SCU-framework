<?php

class Loader{
    /*protected $registry;

    public function __construct($registry){
        $this->registry = $registry;
    }*/

    private $methods = array();

    public function __construct() {
        $this->autoload();
    }

    public function controller($path, $data = array()) {
        $path = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$path);
        $contr_name = trim(ucfirst(CONTROLLER), DS);
        $full_path = (explode('/', $path));

        if(count($full_path) > 2) {
            foreach ($full_path as $name) {
                if($name === end($full_path))
                    $method = $name;
                else
                    $contr_name .= ucfirst($name);
            }
        } else {
            foreach ($full_path as $name) {
                $contr_name .= ucfirst($name);
            }
            $method = "index";
        }
        $contr_name = str_replace('_', '', $contr_name);

        require_once APP_PATH . CURR_DIR . CONTROLLER . substr($path, 0, strrpos($path, '/')). ".php";
        $controller = new $contr_name();
        if(method_exists($controller, $method)) {
            return $controller->$method($data);
        } else {
            throw new Exception("Method \"$method\" does not exist!");
        }
    }

    /**
     * @param $model
     * @return $obj
     */
    public function model($model) {
        $model = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$model);
        require_once APP_PATH . CURR_DIR . MODEL . "$model.php";

        $class = trim(ucfirst(MODEL), DS);

        foreach (explode('/', $model) as $value) $class .= ucfirst($value);

        try{
            $obj = new $class;
            if(is_a($obj, 'Model'))
                return $obj;
            else
                throw new Exception('Object is not part of Model');
        } catch (Exception $e){
            return $e->getMessage();
        }
    }

    public function view($path, $_ = array()) {
        ob_start();
        if(!empty($_)) {
            foreach ($_ as $key => $result) {
                $$key = $result;
            }
        }

        require_once APP_PATH . CURR_DIR . VIEW . $path . ".view";
        return ob_get_clean();
    }

    public function __call($name, $arguments) {
        if(in_array($name, $this->methods)){
            if(empty($arguments)) {
                return $this->$name = new $name();
            } else {
                return $this->$name = new $name($arguments[0]);
            }
        }
    }

    public function __get($name) {
        return $this->$name();
    }

    private function autoload() {
        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(LIB_PATH));

        foreach ($it as $file) {
            if ($file->isDir()){
                continue;
            } else if($file->isFile()){
                require_once ($file->getPathname());
                $name = pathinfo($file->getPathname());
                $method = strtolower($name['filename']);
                if(!in_array($method, $this->methods))
                    $this->methods[] = $method;
            }
        }
    }
}