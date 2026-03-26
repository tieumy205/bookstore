<?php
    session_start();
    class Checkout {
        public static function getEditionCheckout($editionID) {
            $db = new Database();
            $conn = $db->connect();
            if(!$conn) {
                throw new Exception("Kết nối với cơ sở dữ liệu thất bại");
            }

            $userID = $_SESSION["user"]["id"];
            if(!$userID) {
                throw new Exception("Người dùng chưa đăng ký/đăng nhập");
            }

            $stmt = $conn->prepare("
                SELECT e.editionID, e.volumeID, e.quotedPrice, e.salePrice, e.AverageCost as costPrice, v.volumeName,
                     v.imageURL, b.bookName, b.authorName
                FROM edition e
                JOIN volumes v ON e.volumeID = v.volumeID
                JOIN books b ON v.bookID = b.bookID
                WHERE e.editionID = ?
            ");
            $stmt->bindParam(1, $editionID);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function getEditions($selectedItems) {
            $db = new Database();
            $conn = $db->connect();
            if(!$conn) {
                throw new Exception("Kết nối với cơ sở dữ liệu thất bại");
            }

            $userID = $_SESSION["user"]["id"];
            if(!$userID) {
                throw new Exception("Người dùng chưa đăng ký/đăng nhập");
            }

            $ids = array_column($selectedItems, 'editionID');

            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            $stmt = $conn->prepare("
                SELECT e.editionID, e.volumeID, e.quotedPrice, e.salePrice, e.AverageCost as costPrice, v.volumeName,
                     v.imageURL, b.bookName, b.authorName
                FROM edition e
                JOIN volumes v ON e.volumeID = v.volumeID
                JOIN books b ON v.bookID = b.bookID
                WHERE editionID IN ($placeholders)
            ");

            $stmt->execute($ids);

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as &$row) {
                foreach ($selectedItems as $item) {
                    if ($item['editionID'] == $row['editionID']) {
                        $row['quantity'] = $item['quantity'];
                    }
                }
            }
            return $result;
        }

        public static function getUserInfo() {
            $db = new Database();
            $conn = $db->connect();
            if(!$conn) {
                throw new Exception("Kết nối với cơ sở dữ liệu thất bại");
            }
            $userID = $_SESSION["user"]["id"];
            if(!$userID) {
                throw new Exception("Người dùng chưa đăng ký/đăng nhập");
            }
            
            $stmt = $conn->prepare("
                SELECT a.userID, a.numberPhone, a.consigneeName, a.province, a.district, a.detailAddress, a.isDefautl,a.addressID
                FROM address a
                WHERE a.userID = ? 
            ");
            $stmt->bindParam(1, $userID);
            $stmt->execute();
            $user = null;

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                if ($user === null) {
                    $user = [
                       "userID" => $row['userID'],
                        "address" => []
                    ];
                }

                $user["address"][] = [
                    "id" => $row["addressID"],
                    "numberPhone" => $row["numberPhone"],
                    "consigneeName" => $row["consigneeName"],
                    "province" => $row["province"],
                    "district" => $row["district"], 
                    "detailAddress" => $row["detailAddress"]
                ];
            }

            return $user;
            
            
        }

        public static function addAddress($consigneeName, $numberPhone, $province, $district, $detailAddress) {
            $db = new Database();
            $conn = $db->connect();
            if(!$conn) {
                throw new Exception("Kết nối với cơ sở dữ liệu thất bại");
            }
            $userID = $_SESSION["user"]["id"];
            if(!$userID) {
                throw new Exception("Người dùng chưa đăng ký/đăng nhập");
            }

            $stmt = $conn->prepare("
                INSERT INTO address (userID, detailAddress, province, district, isDefautl, consigneeName, numberPhone)
                    VALUES (?, ?, ?, ?, 0, ?, ?)
            ");
            $stmt->bindParam(1, $userID);
            $stmt->bindParam(2, $detailAddress);
            $stmt->bindParam(3, $province);
            $stmt->bindParam(4, $district);
            $stmt->bindParam(5, $consigneeName);
            $stmt->bindParam(6, $numberPhone);
            $stmt->execute();
            return $stmt->rowCount() > 0 ;
        }

        public static function updateAddress($consigneeName, $numberPhone, $province, $district, $detailAddress, $addressID) {
            $db = new Database();
            $conn = $db->connect();
            if(!$conn) {
                throw new Exception("Kết nối với cơ sở dữ liệu thất bại");
            }
            $userID = $_SESSION["user"]["id"];
            if(!$userID) {
                throw new Exception("Người dùng chưa đăng ký/đăng nhập");
            }

            $stmt = $conn->prepare("
                UPDATE address  
                SET consigneeName = ?,
                    numberPhone = ?,
                    province = ?,
                    district = ?,
                    detailAddress = ?
                WHERE userID = ? AND addressID = ?;
            ");
            $stmt->bindParam(1, $consigneeName);
            $stmt->bindParam(2, $numberPhone);
            $stmt->bindParam(3, $province);
            $stmt->bindParam(4, $district);
            $stmt->bindParam(5, $detailAddress);
            $stmt->bindParam(6, $userID);
            $stmt->bindParam(7, $addressID);
            $stmt->execute();
            return $stmt->rowCount() >= 0 ;
        }
    }
?>