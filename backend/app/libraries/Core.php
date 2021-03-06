<?php 

    class Core {

        protected $controller = "HomeController";
        protected $action = "index";
        protected $params = [];


        public function __construct()
        {
            $arr = $this->getUrl();
            if (isset($arr)) {
                if( file_exists("../app/controllers/" .$arr[0].".php")){
                    $this->controller = $arr[0];
                    unset($arr[0]);
                }
            }

            require_once "../app/controllers/".$this->controller.".php";
            $this->controller = new $this->controller;
            
            if(isset($arr[1])){
                if(method_exists($this->controller, $arr[1])){
                    $this->action = $arr[1];
                    unset($arr[1]);
                }
            }

            //xu ly params
            $this->params = $arr ? array_values($arr) : [];
            call_user_func_array([$this->controller, $this->action], $this->params);
        }


        public function getUrl(){
            if(isset($_GET['url'])){
              $arr = rtrim($_GET['url'], '/');
              $arr = filter_var($arr, FILTER_SANITIZE_URL);
              $arr = explode('/', $arr);
              return $arr;
            }                                                                                           
        }
    }
?>