<?php
    require_once __DIR__ . "/../models/Cart.php";
    class CartController extends Controller {
        public function index() {
            $this->view('cart');
        }

        public function getItemCart() {
            try{
                while(ob_get_level()) {
                ob_end_clean();
                }
                ob_start();
                header("Content-Type: application/json; charset=utf-8");
                $data = Cart::getItemCart();
                ob_end_clean();
                echo json_encode([
                    'success' => true,
                    'data' => $data
                ]);
                exit();
            } catch(Exception $e) {
                while(ob_get_level()) {
                    ob_end_clean();
                }
                ob_start();
                header("Content-Type: application/json; charset= utf-8");
                $msg = defined('DEBUG') && DEBUG
                    ? "Lỗi " . $e->getMessage()
                    : "Lỗi tải danh sách các item trong giỏ hàng" ;
                ob_end_clean();
                echo json_encode([
                    'success' => false,
                    'message' => $msg
                ]);
                exit();
            }
            
        }

        public function remove($editionID) {
            try {
                while(ob_get_level()) {
                    ob_end_clean();
                }
                ob_start();
                header("Content-Type: application/json; charset=utf-8");
                $result = Cart::remove($editionID);
                ob_end_clean();
                echo json_encode([
                    'success' => $result
                ]);
                exit();
            } catch(Exception $e) {
                while(ob_get_level()) {
                    ob_end_clean();
                }
                ob_start();
                header("Content-Type: application/json; charset= utf-8");
                $msg = defined('DEBUG') && DEBUG 
                    ? 'Lỗi ' . $e->getMessage()
                    : 'Lôi xóa item trong giỏ hảng.';
                ob_end_clean();
                echo json_encode([
                    'success' => false,
                    'message' => $msg

                ]);
                exit();
            }
        }

        public function updateQuantity($editionID) {
            try {
                while(ob_get_level()) {
                    ob_end_clean();
                }
                ob_start();
                header("Content-Type: application/json; charset=utf-8");

                if($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    ob_end_clean();
                    echo json_encode([
                        'success' => false,
                        'message' => 'Method not allowed'
                    ]);
                    exit();
                }

                $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
                $result = Cart::updateQuantity($editionID, $quantity);
                ob_end_clean();
                echo json_encode([
                    'success' => $result
                ]);
                exit();
            } catch(Exception $e) {
                while(ob_get_level()) {
                    ob_end_clean();
                }
                ob_start();
                header("Content-Type: application/json; charset= utf-8");
                $msg = defined('DEBUG') && DEBUG 
                    ? 'Lỗi ' . $e->getMessage()
                    : 'Lỗi cập nhật số lượng.';
                ob_end_clean();
                echo json_encode([
                    'success' => false,
                    'message' => $msg
                ]);
                exit();
            }
        }

        public function addItem($editionID) {
            try {
                while(ob_get_level()) {
                    ob_end_clean();
                }
                ob_start();
                header("Content-Type: application/json; charset=utf-8");
                $data = Cart::addItem($editionID);
                
                header("Location:" . BASE_URL . "home");
                exit();
            } catch(Exception $e) {
                while(ob_get_level()) {
                    ob_end_clean();
                }
                ob_start();
                header("Content-Type: application/json; charset=utf-8");
                $msg = defined("DEBUG") && DEBUG
                    ? "Lỗi: " . $e->getMessage()
                    : "Lỗi không thể thêm sản phẩm vào giỏ hàng";
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