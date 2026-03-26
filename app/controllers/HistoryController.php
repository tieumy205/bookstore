<?php
    require_once __DIR__ . "/../models/History.php";
    class HistoryController extends Controller {
        public function index() {
            $this->view('history');
        }

        public function getOrder() {
            try{
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
                $data = History::getOrder();
                ob_end_clean();
                
                echo json_encode([
                    "success" => true,
                    "data" => [
                        "orders" => $data
                    ]
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
                    : "Lỗi tải dữ liệu lịch sử các đơn hàng";
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