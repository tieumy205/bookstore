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

        public static function getNewBooks() {
            $db = new Database();
            $conn = $db->connect();
            if(!$conn) {
                throw new Exception("Lỗi kết nối với cơ sở dữ liệu");
            }

            $stmt = $conn->prepare("
                SELECT e.editionID, e.volumeID, e.publicationYear, e.quotedPrice, e.salePrice, 
                    v.volumeName, v.imageURL, b.bookName
                FROM edition e
                JOIN volumes v ON v.volumeID = e.volumeID
                JOIN books b ON b.bookID = v.bookID
                WHERE e.publicationYear = YEAR(NOW());
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function getBooks($limit, $page) {
            $db = new Database();
            $conn = $db->connect();
            $offset = ($page - 1) * $limit;

            if(!$conn) {
                throw new Exception("Lỗi kết nối với cơ sở dữ liệu");
            }

            $stmt = $conn->prepare("
                SELECT e.editionID, e.volumeID, e.quotedPrice, e.salePrice, v.volumeName, v.imageURL, b.bookName
                FROM edition e
                JOIN volumes v ON e.volumeID = v.volumeID
                JOIN books b ON v.bookID = b.bookID
                LIMIT :limit OFFSET :offset
            ");
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        }

        public static function countBooks() {
            $db = new Database();
            $conn = $db->connect();
            if(!$conn) {
                throw new Exception("Lỗi kết nối với cơ sở dữ liệu");
            }

            $stmt = $conn->prepare("
                SELECT COUNT(*) AS total
                FROM edition e
            ");
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return isset($row['total']) ? (int)$row['total'] : 0;
        }
    }
?>