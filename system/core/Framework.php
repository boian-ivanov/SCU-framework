<?phpclass Framework {    public static function run(){        self::init();        self::rewriteEngine();        self::autoload();        self::dispatch();    }    private static function init() {        include "system/config/init_config.php";    }    private static function rewriteEngine() {        $path = ltrim($_SERVER['REQUEST_URI'], '/');        $elements = array_filter(explode('/', $path));        define("CURR_DIR", $elements[0] === ADMIN_LINK ? ADMIN_PATH . DS : HOME_PATH . DS);        if($elements[0] === ADMIN_LINK) array_splice($elements, 0, 1);        switch (count($elements)) {            case 0:                $path = 'common' . DS . 'index';                break;            case 1:                $path = ($elements[0] ? $elements[0] : 'common') . DS . 'index';                break;            case 2:                $path = $elements[0] . DS . ($elements[1] ? $elements[1] : 'index');                break;            default:                $path = '';                for($i=0; $i<count($elements)-1; $i++) {                    $path .= $elements[$i] . DS;                }                $path = rtrim($path, DS);                $method = end($elements);                break;        }        define("CURR_CONTROLLER", strtolower($path));        define("CURR_METHOD", isset($method) ? $method : 'index');    }    private static function autoload() {        spl_autoload_register(array(__CLASS__,'load'));    }    private static function load($classname) {        try {            if (! @include_once(CONTROLLER_PATH . CURR_DIR .  CURR_CONTROLLER . ".php"))                throw new Exception (CURR_DIR . CURR_CONTROLLER . '.php does not exist');            if (!file_exists(CONTROLLER_PATH . CURR_DIR . CURR_CONTROLLER . ".php"))                throw new Exception ($classname . ' does not exist');            else                require_once CONTROLLER_PATH . CURR_DIR .  CURR_CONTROLLER . ".php";        } catch(Exception $e) {            echo "Message : " . $e->getMessage();            echo "</br>";            echo "Code : " . $e->getCode();        }    }    private static function dispatch() {        $controller_name = self::transformPath(CURR_CONTROLLER);        $action_name = CURR_METHOD ;        $controller = new $controller_name;        $controller->$action_name();    }    private static function transformPath($path) {        $elements = array_filter(explode('/', $path));        $classname = 'Controller';        foreach($elements as $element)            $classname .= ucfirst($element);        return $classname;    }}