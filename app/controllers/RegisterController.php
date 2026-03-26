<?php
    require_once __DIR__ . "/../models/Register.php";

    class RegisterController extends Controller {
        public function index() {
            $this->view('register');
        }

        public function register() {
            // Discard any previous output (PHP notices/warnings) so response is valid JSON only
            while (ob_get_level()) {
                ob_end_clean();
            }
            ob_start();

            header('Content-Type: application/json; charset=utf-8');

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Method not allowed']);
                exit();
            }

            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $numberPhone = $_POST['numberPhone'] ?? '';
            $province = $_POST['province'] ?? '';
            $district = $_POST['district'] ?? '';
            $detailAddress = $_POST['detailAddress'] ?? '';
            $consigneeName = $_POST['consigneeName'] ?? '';

            if (!$username || !$password || !$numberPhone || !$province || !$district || !$detailAddress || !$consigneeName) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin']);
                exit();
            }

            $hash = password_hash($password, PASSWORD_DEFAULT);

            try {
                $result = Register::addUser($username, $hash, $numberPhone, $province, $district, $detailAddress, $consigneeName);
                if (is_array($result) && isset($result['status']) && $result['status'] === 'error') {
                    ob_end_clean();
                    echo json_encode(['success' => false, 'message' => $result['message']]);
                    exit();
                }
                if ($result) {
                    ob_end_clean();
                    echo json_encode(['success' => true, 'message' => 'Đăng ký thành công']);
                    exit();
                }
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Đăng ký thất bại']);
            } catch (Throwable $e) {
                ob_end_clean();
                $msg = defined('DEBUG') && DEBUG
                    ? 'Lỗi: ' . $e->getMessage()
                    : 'Đăng ký thất bại. Vui lòng thử lại.';
                echo json_encode(['success' => false, 'message' => $msg]);
            }
            exit();
        }
    }
?>