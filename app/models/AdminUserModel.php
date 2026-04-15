<?php 
class AdminUserModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getAllUsers() {
        if (!$this->conn) return [];
        $stmt = $this->conn->prepare("
            SELECT u.userID, u.userName, u.status, u.role, COUNT(o.orderID) as totalOrders 
            FROM users u
            LEFT JOIN orders o ON u.userID = o.userID
            GROUP BY u.userID
            ORDER BY u.userID DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
