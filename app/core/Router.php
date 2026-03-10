<?php 
    class Router{
        public static function dispatch($url) {
            
            $parts = explode('/', trim($url, '/'));
            $controllerName = !empty($parts[0]) ? ucfirst($parts[0]) . 'Controller' : 'HomeController';
            $actionName = !empty($parts[1]) ? $parts[1] : 'index';
            
            $param = !empty($parts[2]) ? $parts[2] : null;

            $controllerFile = "app/controllers/$controllerName.php";
            if(file_exists($controllerFile)) {
                require_once $controllerFile;
                if(class_exists($controllerName)) {
                    $controller = new $controllerName();
                    if(method_exists($controller, $actionName)) {
                        if($param) {
                            $controller->$actionName($param);
                        } else {
                            $controller->$actionName();
                        }
                    } else {
                        echo "Action $actionName không tồn tại trong controller $controllerName";
                    }
                } else {
                    echo "Controller $controllerName không tồn tại";
                }
            } else {
                http_response_code(404);
                header("Location:" . BASE_URL . "404.php");
                exit();
            }
        }}
?>