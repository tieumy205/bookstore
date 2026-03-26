<?php
    require_once __DIR__ ."/../models/Checkout.php";
    
    class CheckoutController extends Controller {
        public function index() {
            $this->view('checkout');
        }

        public function getEditions() {
            try{
                
                while(ob_get_level()) {
                    ob_end_clean();
                }
                
                ob_start();
                header("Content-Type: application/json; charset=utf-8");
                
                $selectedItems = isset($_POST['selectedItems'])
                    ? json_decode($_POST['selectedItems'], true)
                    : [];
                if (!is_array($selectedItems)) {
                    throw new Exception("selectedItems không hợp lệ");
                }
                
                $data = Checkout::getEditions($selectedItems);
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
                    : "Không tải được dữ liệu sách";
                ob_end_clean();
                echo json_encode([
                    "success" => false,
                    "message" => $msg
                ]);
                exit();
            }
        }

        public function getEditionCheckout($editionID) {
            try {
                $isAJAX = isset($_SERVER["HTTP_X_REQUESTED_WITH"]) 
                    && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) === "xmlhttprequest";
                if( !$isAJAX) {
                    $this->index();
                    exit();
                }
                while(ob_get_level()) {
                    ob_end_clean();
                }
                ob_start();
                header("Content-Type: application/json; charset=utf-8");
                $data = Checkout::getEditionCheckout($editionID);
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
                    : "Không tải được dữ liệu sách";
                ob_end_clean();
                echo json_encode([
                    "success" => false,
                    "message" => $msg
                ]);
                exit();
             }
        }

        public function getUserInfo() {
            try {
                while(ob_get_level()) {
                    ob_end_clean();
                }
                ob_start();
                header("Content-Type: application/json; chartset=utf-8");
                $data = Checkout::getUserInfo();
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
                header("Content-Type: application/json; chartset=utf-8");
                $msg = defined("DEBUG") && DEBUG
                    ? "Lỗi " . $e->getMessage()
                    : "Lỗi tải dữ liệu người dung";
                ob_end_clean();
                echo json_encode([
                    "success" => false,
                    "message" => $msg
                ]);
                exit();
            }
        }

        public function addAddress() {
            try {
                while(ob_get_level()) {
                    ob_end_clean();
                }
                ob_start();
                header("Content-Type: application/json; charset=utf-8");
                $consigneeName = $_POST['consigneeName'] ?? '';
                $numberPhone = $_POST['numberPhone'] ?? '';
                $province = $_POST['province'] ?? '';
                $district = $_POST['district'] ?? '';
                $detailAddress = $_POST['detailAddress'] ?? '';
                $data = Checkout::addAddress($consigneeName, $numberPhone, $province, $district, $detailAddress);
                ob_end_clean();
                echo json_encode([
                    "success" => $data
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

        public function updateAddress() {
            try {
                while(ob_get_level()) {
                    ob_end_clean();
                }
                ob_start();
                header("Content-Type: application/json; charset=utf-8");
                $consigneeName = $_POST['consigneeName'] ?? '';
                $numberPhone = $_POST['numberPhone'] ?? '';
                $province = $_POST['province'] ?? '';
                $district = $_POST['district'] ?? '';
                $detailAddress = $_POST['detailAddress'] ?? '';
                $addressID = $_POST['addressID'] ?? '';
                $data = Checkout::updateAddress($consigneeName, $numberPhone, $province, $district, $detailAddress, $addressID);
                ob_end_clean();
                echo json_encode([
                    "success" => $data
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
    }
?>