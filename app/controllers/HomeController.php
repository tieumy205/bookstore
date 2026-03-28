<?php 
require_once __DIR__ . "/../models/Home.php";

class HomeController extends Controller {

    public function index() {
        $this->view("home");
    }

    public function getBestSeller() {
        try {

            $result = Home::getBestSeller();

            while(ob_get_level()) {
                ob_end_clean();
            }

            header('Content-Type: application/json; charset=utf-8');

            echo json_encode([
                'success' => true,
                'data' => $result
            ]);

            exit();

        } catch(Exception $e) {

            while(ob_get_level()) {
                ob_end_clean();
            }

            header('Content-Type: application/json; charset=utf-8');

            $msg = defined('DEBUG') && DEBUG
                ? 'Lỗi: ' . $e->getMessage()
                : 'Không thể lấy dữ liệu sách bán chạy nhất.';

            echo json_encode([
                'success' => false,
                'message' => $msg
            ]);

            exit();
        }
    }

    public function getNewBooks() {
        try{
            while(ob_get_level()) {
                ob_end_clean();
            }
            ob_start();
            header("Content-Type: application/json; charset=utf-8");
            
            $result = Home::getNewBooks();
            ob_end_clean();
            echo json_encode([
                'success' => true,
                'data' => $result
            ]);
            exit();

        } catch(Exception $e) {
            while(ob_get_level()) {
                ob_end_clean();
            }
            ob_start();
            header('Content-Type: application/json; charset=utf-8');
            $msg = defined('DEBUG') && DEBUG
                ? 'Lỗi: ' . $e->getMessage()
                : 'Không thể lấy dữ liệu sách bán chạy nhất. Vui lòng thử lại.';
            echo json_encode([
                'success' => false,
                'message' => $msg
            ]);
            exit();
       
        
        }
    }

    public function getBooks($page) {
        try {
            while(ob_get_level()) {
                ob_end_clean();
            }
            ob_start();
            header('Content-Type: application/json; charset=utf-8');
            $limit = 12;
            $data = Home::getBooks($limit, $page);
            $total = Home::countBooks();
            $totalPage = ceil($total / $limit);
            ob_end_clean();
            echo json_encode([
                'success' => true,
                'data' => $data,
                'pagination' => [
                    'currentPage' => $page,
                    'totalPage' => $totalPage
                ]
            ]);
            exit();
        } catch(Exception $e) {
            while(ob_get_level()) {
                ob_end_clean();
            }
            ob_start();
            header('Content-Type: application/json; charset=utf-8');
            $msg = defined('DEBUG') && DEBUG
                ? 'Lỗi: ' . $e->getMessage()
                : 'Không thể lấy dữ liệu sách. Vui lòng thử lại.';
            echo json_encode([
                'success' => false,
                'message' => $msg
            ]);
            exit();
        }
    }
}
?>