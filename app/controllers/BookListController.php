<?php
    require_once __DIR__ . "/../models/BookList.php";

    class BookListController extends Controller {
        public function index() {
            $this->view('bookList');
        }

        public function getBooksBy($categoryID, $page) {
            try {
                // Nếu người dùng truy cập trực tiếp trên trình duyệt (không phải AJAX),
                // ưu tiên render trang (index). Dữ liệu sẽ được JS fetch sau.
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
                
                $limit = 12;
                
                $page = (int)$page;
                if ($page < 1) $page = 1;

                $result = BookList::getBookBy($categoryID, $page);
                $countBook = BookList::getCountBook($categoryID);
                $totalPage = ceil($countBook/$limit);
                ob_end_clean();
                echo json_encode([
                    'success' => true,
                    'data' => $result,
                    'pagination' => [
                        'totalPage' => $totalPage,
                        'currentPage' => $page,
                    ]
                ]);
                exit();
            } catch(Exception $e) {
                while(ob_get_level()) {
                    ob_end_clean();
                }
                ob_start();
                header("Content-Type: application/json; charset=utf-8");
                $msg = defined('DEBUG') && DEBUG 
                    ? "Lỗi " . $e->getMessage()
                    : "Không thể lấy dữ liệu sách. Vui lòng thử lại";
                echo json_encode([
                    'success' => false,
                    'message' => $msg
                ]);
                exit();
            }
        }

        public function categories() {
            try {
                while(ob_get_level()) {
                    ob_end_clean();
                }
                ob_start();
                header("Content-Type: application/json; charset=utf-8");
                $data = BookList::categories();
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
                $msg = defined('DEBUG') && DEBUG 
                    ? "Lỗi " . $e->getMessage()
                    : "Không thể lấy dữ liệu các thể loại sáchsách. Vui lòng thử lại";
                echo json_encode([
                    'success' => false,
                    'message' => $msg
                ]);
                exit();
            }
        }
    }
?>