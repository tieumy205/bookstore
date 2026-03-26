<?php
    session_start();
    class LogoutController {
        public function logout() {
            if(isset($_SESSION["user"])) {
                session_destroy();
                header("Location: " . BASE_URL . "login");
                exit();

            } else {
                throw new Exception("Người dùng chưa đăng nhập");
            }

        }
    }
?>