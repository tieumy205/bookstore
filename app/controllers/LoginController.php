<?php
    require_once __DIR__ . '/../models/Login.php';

    class LoginController extends Controller {
        public function index() {
            $this->view('login');
        }

        public function login() {
            while(ob_get_level()) {
                ob_end_clean();
            }
            ob_start();

            header('Content-Type: application/json; charset= utf-8');
            if($_SERVER['REQUEST_METHOD'] !== 'POST' ) {
                ob_end_clean();
                return [
                    'success' => false,
                    'message' => 'Method not allowed'
                ];
            }

            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $result = Login::auth($username, $password);
            if(is_array($result) && $result['status'] === 'false') {
                ob_end_clean();
                echo json_encode([
                    'success' => false,
                    'message' =>'Tên tài khoản hoặc mật khẩu không đúng'
                ]);
                exit();
            } elseif($result['status'] === 'success') {
                ob_end_clean();
                echo json_encode([
                    'success' => true,
                    'userID' => $result['user'],
                    'message' => "Đăng nhập thành công"
                ]);
                exit();
            }

            
        }
    }
?>