<?php 
class AdminDashboardModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getOverviewStats() {
        $stats = [
            'total_users' => 0,
            'total_revenue' => 0,
            'total_stock' => 0,
            'total_orders' => 0
        ];

        if (!$this->conn) return $stats;

        // Total Users
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM users WHERE role = 'user'");
        $stmt->execute();
        $stats['total_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

        // Total Revenue
        $stmt = $this->conn->prepare("SELECT SUM(totalPrice) as total FROM orders WHERE status = 'completed'");
        $stmt->execute();
        $stats['total_revenue'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        // Total Stock
        $stmt = $this->conn->prepare("SELECT SUM(StockQuantity) as total FROM edition");
        $stmt->execute();
        $stats['total_stock'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        // Total Orders
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM orders");
        $stmt->execute();
        $stats['total_orders'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

        return $stats;
    }

    public function getRecentOrders($limit = 5) {
        if (!$this->conn) return [];
        $stmt = $this->conn->prepare("
            SELECT o.*, u.userName 
            FROM orders o 
            JOIN users u ON o.userID = u.userID 
            ORDER BY o.createAt DESC 
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
