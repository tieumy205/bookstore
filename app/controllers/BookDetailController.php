<?php
    require_once __DIR__ . "/../models/BookDetail.php";
    class BookDetailController extends Controller {
        public function index() {
            $this->view('bookDetail');
        }

        public function getDetail($editionID) {
            try {
                $isAJAX = isset($_SERVER["HTTP_X_REQUESTED_WITH"])
                    && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) === "xmlhttprequest";
                if(!$isAJAX) {
                    $this->index();
                    exit();
                }
                while(ob_get_level()) {
                    ob_end_clean();
                }
                ob_start();
                header("Content-Type: application/json; charset=utf-8");
                $data = BookDetail::getDetail($editionID);
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
                ob_end_clean();
                $msg = defined("DEBUG") && DEBUG
                    ? "Lỗi " . $e->getMessage()
                    : "Không tại được thông tin chi tiết sách. Vui lòng thử lại";
                echo json_encode([
                    "success" => false,
                    "message" => $msg
                ]);
                exit(); 
            }
        }

        public function getRelativeBook($editionID) {
            try {

                while(ob_get_level()) {
                    ob_end_clean();
                }
                ob_start();
                header("Content-Type: application/json; charset=utf-8");
                $data = BookDetail::getRelativeBook($editionID);
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
                $msg = defined("DEBUG") && DEBUG
                    ? "Lỗi: " . $e->getMessage()
                    : "Không tại được dữ liệu các sách liên quan";
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