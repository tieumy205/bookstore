<?php 
require_once __DIR__ . "/../models/Home.php";

class HomeController extends Controller {

    public function index() {

        $topBooks = Home::getBestSeller();

        $this->view('home', [
            'topBooks' => $topBooks
        ]);
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
}
?>