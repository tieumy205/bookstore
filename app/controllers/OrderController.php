<?php
    require_once __DIR__ . "/../models/Order.php";
    class OrderController extends Controller {
        public function index() {
            $this->view('order');
        }

        public function addOrder() {
            try {
                while(ob_get_level()) {
                    ob_end_clean();
                }
                ob_start();
                header("Content-Type: application/json; charset=utf-8");
                $addressID = $_POST['addressID'] ?? '';
                $paymentID = $_POST['paymentID'] ?? '';
                $books = isset($_POST['books'])
                    ? json_decode($_POST['books'], true)
                    : [];
                if(!is_array($books)) {
                    throw new Exception("Không có dữ liệu sách");
                }
                $data = Order::addOrder($addressID, $paymentID, $books);
                ob_end_clean();
                echo json_encode([
                    "success" => true,
                    "orderID" => $data
                ]);
                exit();
            } catch(Exception $e) {
                while(ob_get_level()) {
                    ob_end_clean();
                }
                ob_start();
                header("Content-Type: application/json; charset=utf-8");
                $msg = defined("DEBUG") && DEBUG        
                    ? "Lỗi " . $e->getMessage()
                    : "Lỗi truy cập tới database";
                ob_end_clean();
                echo json_encode([
                    "success" => false,
                    "message" => $msg
                ]);
                exit();
            }
        }

        public function getOrder($orderID) {
            try {
                $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH'])
                    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
                if (!$isAjax) {
                    $this->index();
                    exit();
                }
                while(ob_get_level()) {
                    ob_end_clean();
                }
                ob_start();
                header("Content-Type: application/json; charset=utf-8");
                $data = Order::getOrder($orderID);
                ob_end_clean();
                echo json_encode([
                    "success" => true,
                    "data" => $data
                ]);
                exit();
            } catch(Exception $e) {
                while(ob_get_level()) {
                    ob_end_clean();
                }
                ob_start();
                header("Content-Type: application/json; charset=utf-8");
                $msg = defined("DEBUG") && DEBUG
                    ? "Lỗi: " . $e->getMessage()
                    : "Lỗi tải dữ liệu đơn hàng";
                ob_end_clean();
                echo json_encode([
                    "success" => false,
                    "message" => $msg
                ]);
                exit();
            }
        }
    }
?>