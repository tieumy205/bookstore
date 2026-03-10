<?php
    class Home {
        public static function getBestSeller() {
            $db = new Database();
            $conn = $db->connect();
            if(!$conn) {
                throw new Exception("Kết nối cơ sở dữ liệu thất bại");
            }

            $stmt = $conn->prepare("
                SELECT e.editionID, e.volumeID, e.quotedPrice, e.salePrice, v.volumeName, v.imageURL, b.bookName, count(e.editionID) as total
                FROM edition e
                JOIN volumes v ON e.volumeID = v.volumeID
                JOIN books b ON v.bookID = b.bookID
                JOIN orderDetail od ON e.editionID = od.editionID
                GROUP BY e.editionID, e.volumeID, e.quotedPrice, e.salePrice, v.volumeName, v.imageURL, b.bookName
                ORDER BY total DESC
                LIMIT 10
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>