<?php 
class Controller {
    protected function view($path, $data = []) {
        extract($data);
        require_once __DIR__ . "/../views/$path.php";
    }
}

?>