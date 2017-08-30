<?phpclass Loader{    /*protected $registry;    public function __construct($registry){        $this->registry = $registry;    }*/    public function model($model) {        $model = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$model);        include MODEL_PATH . CURR_DIR . "$model.php";        $class = 'Model';        foreach (explode('/', $model) as $value) $class .= ucfirst($value);        try{            return new $class;        } catch (Exception $e){            return $e;        }    }    public function view($path, $_ = array()) {        //$full_dir = VIEW_PATH . CURR_DIR . $path . ".phtml";        ob_start();        foreach($_ as $key=>$result){            $$key = $result;        }        include VIEW_PATH . CURR_DIR . $path . ".view";        return ob_get_clean();    }    public function library($lib) {        $lib = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$lib);        include LIB_PATH . "$lib.class.php";    }    public function helper($helper) {        $helper = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$helper);        include HELPER_PATH . "{$helper}_helper.php";    }}