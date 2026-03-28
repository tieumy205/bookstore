<?php
    session_start();
    class Cart {
        public static function getItemCart() {
            if(!isset($_SESSION['user'])) {
                throw new Exception("Người dùng chưa đăng nhập/ đăng ký");
            } 
            // var_dump($_SESSION['user']);
            // exit();
            $userID = $_SESSION['user']['id'];
            if(!$userID) {
                throw new Exception("Nguoi dung mat tiu11111");
            }
            $db = new Database();
            $conn = $db->connect();
            if(!$conn) {
                throw new Exception("Kết nối với cơ sở dữ liệu thất bại");
            }

            $stmt = $conn->prepare("
                SELECT u.userID, c.cartID, cd.editionID, cd.quantity, v.volumeName, v.imageURL, e.quotedPrice, e.salePrice, e.stockQuantity
                FROM users u
                JOIN carts c On c.userID = u.userID
                JOIN cartdetail cd on cd.cartID = c.cartID
                JOIN edition e on e.editionID = cd.editionID
                JOIN volumes v on v.volumeID = e.volumeID
                WHERE u.userID = ?
            ");
            $stmt->bindParam(1, $userID);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function remove($editionID) {
            $db = new Database();
            $conn = $db->connect();
            if(!$conn) {
                throw new Exception("Kết nối với cơ sở dữ liệu thất bại");
            }
            if(!isset($_SESSION['user'])) {
                throw new Exception("Người dùng chưa đăng nhập");
            }
            $userID = $_SESSION['user']['id'];

            // Lấy cartID của user bằng truy vấn riêng để tránh join trực tiếp trong câu DELETE,
            // phòng trường hợp trigger / function trên bảng carts gây lỗi 1442.
            $stmt = $conn->prepare("
                SELECT cartID
                FROM carts
                WHERE userID = ?
                LIMIT 1
            ");
            $stmt->bindParam(1, $userID, PDO::PARAM_INT);
            $stmt->execute();
            $cart = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$cart) {
                throw new Exception("Không tìm thấy giỏ hàng của người dùng");
            }

            $cartID = (int)$cart['cartID'];

            $stmtDel = $conn->prepare("
                DELETE FROM cartdetail
                WHERE cartID = ? AND editionID = ?
            ");
            $stmtDel->bindParam(1, $cartID, PDO::PARAM_INT);
            $stmtDel->bindParam(2, $editionID, PDO::PARAM_INT);
            $stmtDel->execute();
            return $stmtDel->rowCount() > 0;
        }

        public static function updateQuantity($editionID, $quantity) {
            if($quantity < 1) {
                throw new Exception("Số lượng không hợp lệ");
            }

            $db = new Database();
            $conn = $db->connect();
            if(!$conn) {
                throw new Exception("Kết nối với cơ sở dữ liệu thất bại");
            }
            if(!isset($_SESSION['user'])) {
                throw new Exception("Người dùng chưa đăng nhập");
            }
            $userID = $_SESSION['user']['id'];

            $stmt1 = $conn->prepare("
                SELECT stockQuantity
                FROM edition
                WHERE editionID = ?
            ");

            $stmt1->bindParam(1, $editionID, PDO::PARAM_INT);
            $stmt1->execute();

            $row = $stmt1->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                throw new Exception("Không tìm thấy sản phẩm");
            }

            $stockQuantity = (int)$row['stockQuantity'];
            if($quantity > $stockQuantity) {
                throw new Exception("Số lượng vượt quá tồn kho");
            }

            // Tách bước lấy cartID riêng để tránh join trực tiếp với bảng carts
            $stmt = $conn->prepare("
                SELECT cartID
                FROM carts
                WHERE userID = ?
                LIMIT 1
            ");
            $stmt->bindParam(1, $userID, PDO::PARAM_INT);
            $stmt->execute();
            $cart = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$cart) {
                throw new Exception("Không tìm thấy giỏ hàng của người dùng");
            }

            

            $cartID = (int)$cart['cartID'];

            $stmtUpd = $conn->prepare("
                UPDATE cartdetail
                SET quantity = ?
                WHERE cartID = ? AND editionID = ?
            ");
            $stmtUpd->bindParam(1, $quantity, PDO::PARAM_INT);
            $stmtUpd->bindParam(2, $cartID, PDO::PARAM_INT);
            $stmtUpd->bindParam(3, $editionID, PDO::PARAM_INT);
            $stmtUpd->execute();
            if ($stmtUpd->rowCount() > 0) {
                return true;
            }

            // Nếu rowCount = 0 có thể là do quantity không thay đổi, vẫn xem là thành công
            $stmtCheck = $conn->prepare("
                SELECT quantity
                FROM cartdetail
                WHERE cartID = ? AND editionID = ?
                LIMIT 1
            ");
            $stmtCheck->bindParam(1, $cartID, PDO::PARAM_INT);
            $stmtCheck->bindParam(2, $editionID, PDO::PARAM_INT);
            $stmtCheck->execute();
            $existing = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            if ($existing && (int)$existing['quantity'] === (int)$quantity) {
                return true;
            }
            return false;
        }

        public static function addItem($editionID) {
            $db = new Database();
            $conn = $db->connect();
            if(!$conn) {
                throw new Exception("Kết nối với cơ sở dữ liệu thất bại");
            }

            if(!isset($_SESSION["user"])) {
                throw new Exception("Người dùng chưa đăng nhập/đăng ký");
            }
            $userID = $_SESSION["user"]["id"];

            $stmt = $conn->prepare("
                SELECT cartID
                FROM carts
                WHERE userID = ?
            ");
            $stmt->bindParam(1, $userID);
            $stmt->execute();
            $cart = $stmt->fetch(PDO::FETCH_ASSOC);
            $cartID = (int)$cart["cartID"];

            $check = $conn->prepare("
                SELECT editionID
                FROM cartdetail
                WHERE editionID = ?
            ");
            $check->bindParam(1, $editionID);
            $check->execute();
            $row = $check->rowCount() > 0;
            if($row) {
                $stmt1=  $conn->prepare("
                    UPDATE cartdetail
                    SET quantity = quantity + 1
                    WHERE editionID = ? AND cartID = ?
                ");
                $stmt1->bindParam(1, $editionID);
                $stmt1->bindParam(2, $cartID);
                $stmt1->execute();
                if($stmt1->rowCount() > 0) {
                    return true;
                };
            } else {
                $stmt1= $conn->prepare("
                    INSERT INTO cartdetail (editionID, cartID, quantity)
                        VALUES(?, ?, 1)
    
                ");
                $stmt1->bindParam(1, $editionID);
                $stmt1->bindParam(2, $cartID);
                $stmt1->execute();
                if($stmt1->rowCount() > 0) {
                    return true;
                };
            }


        }
    }
?>