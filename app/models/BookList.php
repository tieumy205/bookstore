<?php
    class BookList {
        

        public static function getBookBy($categoryID, $page) {
            $db = new Database();
            $conn = $db->connect();
            if(!$conn) {
                throw new Exception("Kết nối cơ sở dữ liệu thất bại");
            }
            $limit = 12;
            $offset = ($page - 1) * $limit;
            $stmt = $conn->prepare("
                SELECT e.editionID, e.volumeID, e.quotedPrice, e.salePrice, v.volumeName, v.imageURL, b.bookName
                FROM edition e
                JOIN volumes v ON e.volumeID = v.volumeID
                JOIN books b ON v.bookID = b.bookID
                JOIN book_category bc on bc.bookID = b.bookID
                WHERE bc.categoryID = :categoryID
                LIMIT :limit OFFSET :offset
            ");
            $stmt->bindValue(':categoryID', $categoryID, PDO::PARAM_STR);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function getCountBook($categoryID) {
            $db = new Database();
            $conn = $db->connect();

            if(!$conn) {
                throw new Exception("Kết nối cơ sở dữ liệu thất bại");
            }

            $stmt = $conn->prepare("
                SELECT count(*) as totalBook
                FROM edition e
                JOIN volumes v on v.volumeID = e.volumeID
                JOIN books b on b.bookID = v.bookID
                JOIN book_category bc on bc.bookID = b.bookID 
                WHERE bc.categoryID = ?
            ");
            $stmt->bindParam(1, $categoryID);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return isset($row['totalBook']) ? (int)$row['totalBook'] : 0;
        }

        public static function categories() {
            $db = new Database();
            $conn = $db->connect();

            if(!$conn) {
                throw new Exception("Kết nối cơ sở dữ liệu thất bại");
            }

            $stmt = $conn->prepare("
                SELECT *
                FROM categories
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>