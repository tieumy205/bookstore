<?php
    require_once __DIR__ . "/../models/BookList.php";

    class BookListController extends Controller {
        public function index() {
            $this->view('bookList');
        }

        public function getBooks() {
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
                
                $search = isset($_GET['search']) ? trim($_GET['search']) : '';
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $categoryID = isset($_GET['categoryID']) ? trim($_GET['categoryID']) : '';
                $minPrice = isset($_GET['minPrice']) ? trim($_GET['minPrice']) : '';
                $maxPrice = isset($_GET['maxPrice']) ? trim($_GET['maxPrice']) : '';
                $sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'newest';
                if ($page < 1) $page = 1;

                $result = BookList::getBookBy($search, $categoryID, $page, $minPrice, $maxPrice, $sort);
                $countBook = BookList::getCountBook($search, $categoryID, $minPrice, $maxPrice);
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