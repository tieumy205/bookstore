<?php
class AdminOrderModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getAllOrders($statusFilter = '') {
        if (!$this->conn) return [];
        $sql = "
            SELECT o.*, 
                   u.userName,
                   a.consigneeName, a.numberPhone, a.detailAddress, a.province, a.district,
                   p.payment
            FROM orders o
            JOIN users u ON o.userID = u.userID
            LEFT JOIN address a ON o.addressID = a.addressID
            LEFT JOIN payments p ON o.paymentID = p.paymentID
        ";
        if ($statusFilter) {
            $sql .= " WHERE o.status = :status ";
        }
        $sql .= " ORDER BY o.createAt DESC";

        $stmt = $this->conn->prepare($sql);
        if ($statusFilter) {
            $stmt->bindValue(':status', $statusFilter, PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateOrderStatus($orderID, $newStatus) {
        if (!$this->conn) return false;
        
        $validStatuses = ['processing', 'confirmed', 'delivering', 'completed', 'canceled'];
        if (!in_array($newStatus, $validStatuses)) return false;

        try {
            $stmt = $this->conn->prepare("UPDATE orders SET status = ? WHERE orderID = ?");
            return $stmt->execute([$newStatus, $orderID]);
        } catch (Exception $e) {
            error_log("Update Order Status Error: " . $e->getMessage());
            return false;
        }
    }
}
?>
