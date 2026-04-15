<?php 
class AdminBookModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getAllEditions() {
        if (!$this->conn) return [];
        $sql = "
            SELECT e.editionID, e.quotedPrice, e.salePrice, e.StockQuantity, e.status, e.publicationYear, e.publisherName, e.AverageCost,
                   v.volume, v.volumeName, v.imageURL,
                   b.bookName, b.authorName,
                   GROUP_CONCAT(c.categoryName SEPARATOR ', ') as categories
            FROM edition e
            JOIN volumes v ON e.volumeID = v.volumeID
            JOIN books b ON v.bookID = b.bookID
            LEFT JOIN book_category bc ON b.bookID = bc.bookID
            LEFT JOIN categories c ON bc.categoryID = c.categoryID
            GROUP BY e.editionID
            ORDER BY e.editionID DESC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCategories() {
        if (!$this->conn) return [];
        $stmt = $this->conn->prepare("SELECT * FROM categories ORDER BY categoryName ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addCompleteBook($bookName, $authorName, $categoryIDs, $volumeName, $description, $imageURL, $publicationYear, $publisherName, $quotedPrice, $salePrice, $coverType) {
        if (!$this->conn) return false;
        
        try {
            $this->conn->beginTransaction();

            // 1. Insert into books
            $stmt = $this->conn->prepare("INSERT INTO books (bookName, authorName) VALUES (?, ?)");
            $stmt->execute([$bookName, $authorName]);
            $bookID = $this->conn->lastInsertId();

            // 2. Insert into book_category
            if (!empty($categoryIDs) && is_array($categoryIDs)) {
                $stmtCat = $this->conn->prepare("INSERT INTO book_category (bookID, categoryID) VALUES (?, ?)");
                foreach ($categoryIDs as $catID) {
                    $stmtCat->execute([$bookID, $catID]);
                }
            }

            // 3. Insert into volumes
            $stmt = $this->conn->prepare("INSERT INTO volumes (bookID, volume, volumeName, imageURL, description) VALUES (?, 1, ?, ?, ?)");
            $stmt->execute([$bookID, $volumeName ? $volumeName : $bookName, $imageURL, $description]);
            $volumeID = $this->conn->lastInsertId();

            // 4. Insert into edition
            // For a new book, AverageCost = 0, StockQuantity = 0, salePrice = null (or quotedPrice), status = show
            $stmt = $this->conn->prepare("
                INSERT INTO edition (volumeID, publicationYear, publisherName, quotedPrice, status, coverType, AverageCost, StockQuantity, salePrice)
                VALUES (?, ?, ?, ?, 'show', ?, 0, 0, ?)
            ");
            $sale = $salePrice !== null ? $salePrice : $quotedPrice;
            $stmt->execute([$volumeID, $publicationYear, $publisherName, $quotedPrice, $coverType, $sale]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Add Complete Book Error: " . $e->getMessage());
            return false;
        }
    }
    public function getEditionFullDetails($editionID) {
        if (!$this->conn) return null;
        $sql = "
            SELECT e.*, v.volume, v.volumeName, v.imageURL, v.description, v.bookID, b.bookName, b.authorName
            FROM edition e
            JOIN volumes v ON e.volumeID = v.volumeID
            JOIN books b ON v.bookID = b.bookID
            WHERE e.editionID = ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$editionID]);
        $edition = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($edition) {
            $stmtCat = $this->conn->prepare("SELECT categoryID FROM book_category WHERE bookID = ?");
            $stmtCat->execute([$edition['bookID']]);
            $edition['categoryIDs'] = $stmtCat->fetchAll(PDO::FETCH_COLUMN);
        }

        return $edition;
    }

    public function updateCompleteBook($editionID, $bookID, $volumeID, $bookName, $authorName, $categoryIDs, $volumeName, $description, $imageURL, $publicationYear, $publisherName, $quotedPrice, $salePrice, $coverType, $status) {
        if (!$this->conn) return false;

        try {
            $this->conn->beginTransaction();

            // 1. Update books
            $stmt = $this->conn->prepare("UPDATE books SET bookName = ?, authorName = ? WHERE bookID = ?");
            $stmt->execute([$bookName, $authorName, $bookID]);

            // 2. Update book_category
            // Delete old categories
            $stmtDel = $this->conn->prepare("DELETE FROM book_category WHERE bookID = ?");
            $stmtDel->execute([$bookID]);
            // Insert new categories
            if (!empty($categoryIDs) && is_array($categoryIDs)) {
                $stmtCat = $this->conn->prepare("INSERT INTO book_category (bookID, categoryID) VALUES (?, ?)");
                foreach ($categoryIDs as $catID) {
                    $stmtCat->execute([$bookID, $catID]);
                }
            }

            // 3. Update volumes
            if ($imageURL) {
                $stmt = $this->conn->prepare("UPDATE volumes SET volumeName = ?, description = ?, imageURL = ? WHERE volumeID = ?");
                $stmt->execute([$volumeName ? $volumeName : $bookName, $description, $imageURL, $volumeID]);
            } else {
                $stmt = $this->conn->prepare("UPDATE volumes SET volumeName = ?, description = ? WHERE volumeID = ?");
                $stmt->execute([$volumeName ? $volumeName : $bookName, $description, $volumeID]);
            }

            // 4. Update edition
            // For editing, we update quotedPrice, salePrice, publicationYear, publisherName, coverType, status
            $stmt = $this->conn->prepare("
                UPDATE edition 
                SET publicationYear = ?, publisherName = ?, quotedPrice = ?, salePrice = ?, coverType = ?, status = ?
                WHERE editionID = ?
            ");
            $stmt->execute([$publicationYear, $publisherName, $quotedPrice, $salePrice, $coverType, $status, $editionID]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Update Complete Book Error: " . $e->getMessage());
            return false;
        }
    }
}
?>
