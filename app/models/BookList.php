<?php
    class BookList {
        

        public static function getBookBy($search, $categoryID, $page, $minPrice, $maxPrice, $sort = 'newest') {
            $db = new Database();
            $conn = $db->connect();
        
            if(!$conn) {
                throw new Exception("Kết nối cơ sở dữ liệu thất bại");
            }
        
            $limit = 12;
            $offset = ($page - 1) * $limit;
        
            $searchParam = $search ? "%$search%" : "";
            $sort = strtolower((string)$sort);
            $allowedSorts = ['newest', 'popular', 'price_asc', 'price_desc'];
            if (!in_array($sort, $allowedSorts, true)) {
                $sort = 'newest';
            }
            if ($sort === 'popular') {
                $sql = "
                    SELECT 
                        e.editionID,
                        e.volumeID,
                        e.quotedPrice,
                        e.salePrice,
                        v.volumeName,
                        v.imageURL,
                        b.bookName,
                        COALESCE(SUM(od.quantity), 0) AS soldQty
                    FROM edition e
                    JOIN volumes v ON e.volumeID = v.volumeID
                    JOIN books b ON v.bookID = b.bookID
                    JOIN book_category bc ON bc.bookID = b.bookID
                    LEFT JOIN orderdetail od ON od.editionID = e.editionID
                    LEFT JOIN orders o ON o.orderID = od.orderID
                    WHERE 
                        (:search = '' OR b.bookName LIKE :search OR b.authorName LIKE :search)
                        AND (:categoryID IS NULL OR bc.categoryID = :categoryID)
                        AND (:minPrice IS NULL OR e.salePrice >= :minPrice)
                        AND (:maxPrice IS NULL OR e.salePrice <= :maxPrice)
                    GROUP BY 
                        e.editionID, e.volumeID, e.quotedPrice, e.salePrice,
                        v.volumeName, v.imageURL, b.bookName
                    ORDER BY soldQty DESC, e.editionID DESC
                    LIMIT :limit OFFSET :offset
                ";
            } else {
                $orderBy = "e.editionID DESC";
                if ($sort === 'price_asc') {
                    $orderBy = "e.salePrice ASC";
                } elseif ($sort === 'price_desc') {
                    $orderBy = "e.salePrice DESC";
                }

                $sql = "
                    SELECT DISTINCT e.editionID, e.volumeID, e.quotedPrice, e.salePrice, 
                        v.volumeName, v.imageURL, b.bookName
                    FROM edition e
                    JOIN volumes v ON e.volumeID = v.volumeID
                    JOIN books b ON v.bookID = b.bookID
                    JOIN book_category bc ON bc.bookID = b.bookID
                    WHERE 
                        (:search = '' OR b.bookName LIKE :search OR b.authorName LIKE :search)
                        AND (:categoryID IS NULL OR bc.categoryID = :categoryID)
                        AND (:minPrice IS NULL OR e.salePrice >= :minPrice)
                        AND (:maxPrice IS NULL OR e.salePrice <= :maxPrice)
                    ORDER BY {$orderBy}
                    LIMIT :limit OFFSET :offset
                ";
            }
            $stmt = $conn->prepare($sql);
        
            $stmt->bindValue(':search', $searchParam, PDO::PARAM_STR);
            if ($categoryID !== '' && is_numeric($categoryID)) {
                $stmt->bindValue(':categoryID', (int)$categoryID, PDO::PARAM_INT);
            } else {
                $stmt->bindValue(':categoryID', null, PDO::PARAM_NULL);
            }
            $stmt->bindValue(':minPrice', $minPrice !== '' ? (int)$minPrice : null, PDO::PARAM_INT);
            $stmt->bindValue(':maxPrice', $maxPrice !== '' ? (int)$maxPrice : null, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
            $stmt->execute();
        
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function getCountBook($search, $categoryID, $minPrice, $maxPrice) {
            $db = new Database();
            $conn = $db->connect();
        
            if(!$conn) {
                throw new Exception("Kết nối cơ sở dữ liệu thất bại");
            }
        
            $searchParam = $search ? "%$search%" : "";
        
            $stmt = $conn->prepare("
                SELECT COUNT(DISTINCT e.editionID) as totalBook
                FROM edition e
                JOIN volumes v ON v.volumeID = e.volumeID
                JOIN books b ON b.bookID = v.bookID
                JOIN book_category bc ON bc.bookID = b.bookID 
                WHERE 
                    (:search = '' OR b.bookName LIKE :search OR b.authorName LIKE :search)
                    AND (:categoryID IS NULL OR bc.categoryID = :categoryID)
                    AND (:minPrice IS NULL OR e.salePrice >= :minPrice)
                    AND (:maxPrice IS NULL OR e.salePrice <= :maxPrice)
            ");
        
            $stmt->bindValue(':search', $searchParam, PDO::PARAM_STR);
            if ($categoryID !== '' && is_numeric($categoryID)) {
                $stmt->bindValue(':categoryID', (int)$categoryID, PDO::PARAM_INT);
            } else {
                $stmt->bindValue(':categoryID', null, PDO::PARAM_NULL);
            }
            $stmt->bindValue(':minPrice', $minPrice !== '' ? (int)$minPrice : null, PDO::PARAM_INT);
            $stmt->bindValue(':maxPrice', $maxPrice !== '' ? (int)$maxPrice : null, PDO::PARAM_INT);
        
            $stmt->execute();
        
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$row['totalBook'];
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