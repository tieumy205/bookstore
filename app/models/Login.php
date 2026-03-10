<?php
    class Login {
        public static function auth($username, $password) {
            $db = new Database();
            $conn = $db->connect();
            if(!$conn) {
                return [
                    "success" => false,
                    "message" =>"Kết nối cơ sở dữ liệu thất bại"
                ];
            }

            if(!$username || !$password) {
                return [
                    "success" => false,
                    "message" =>"Vui lòng điền đầy đủ thông tin"
                ];
            }

            $stmt = $conn->prepare("SELECT * FROM users WHERE userName = ? LIMIT 1");
            $stmt->bindParam(1, $username);
            $stmt->execute();
            $user =$stmt->fetch(PDO::FETCH_ASSOC);
            if($user && password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['userName'],
                    'role' => $user['role']
                ];
                return [
                    "status" => "success",
                    "message" => "Đăng nhập thành công"
                ];
            } else {
                return [
                    "status" => "false",
                    "message" => "Tên tài khoản hoặc mật khẩu không đúng"
                ];

            }
            
        }
    }
?>