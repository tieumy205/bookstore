<?php
    class BookDetail{
        public static function getDetail($editionID) {
            $db = new Database();
            $conn = $db->connect();
            if(!$conn) {
                throw new Exception("Kết nối với cơ sở dữ liệu thất bại");
            }
            $stmt = $conn->prepare("
                SELECT e.editionID, e.volumeID, e.quotedPrice, e.salePrice, v.volumeName,
                     v.imageURL, v.description, b.bookName, b.authorName, e.publicationYear,
                     e.publisherName, e.coverType
                FROM edition e
                JOIN volumes v ON e.volumeID = v.volumeID
                JOIN books b ON v.bookID = b.bookID
                WHERE e.editionID = ?
                
            ");
            
            $stmt->bindParam(1, $editionID);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function getRelativeBook($editionID) {
            $db = new Database();
            $conn = $db->connect();
            if(!$conn) {
                throw new Exception("Kết nối với cơ sở dữ liệu thất bại");
            }
            $stmt = $conn->prepare("
                SELECT 
                    e.editionID,
                    b.bookName,
                    b.authorName,
                    e.salePrice,
                    e.quotedPrice,
                    v.imageURL,
                    v.volumeName,

                    (
                        CASE WHEN bc.categoryID = bc0.categoryID THEN 2 ELSE 0 END +
                        CASE WHEN b.authorName = b0.authorName THEN 1 ELSE 0 END
                    ) AS score

                FROM edition e
                JOIN volumes v ON e.volumeID = v.volumeID
                JOIN books b ON v.bookID = b.bookID
                JOIN book_category bc ON b.bookID = bc.bookID

                JOIN edition e0 ON e0.editionID = 3
                JOIN volumes v0 ON e0.volumeID = v0.volumeID
                JOIN books b0 ON v0.bookID = b0.bookID
                JOIN book_category bc0 ON b0.bookID = bc0.bookID

                WHERE e.editionID != ? AND (
                    bc.categoryID = bc0.categoryID
                    OR b.authorName = b0.authorName
                )
                HAVING score > 0
                ORDER BY score DESC
                

            ");
            $stmt->bindParam(1, $editionID);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>