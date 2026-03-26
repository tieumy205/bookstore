<?php
    session_start();
    class History {
        public static function getOrder() {
            

                $db = new Database();
                $conn = $db->connect();
                if(!$conn) {
                    throw new Exception("Kết nối với cơ sở dữ liệu thất bại");
                }
                if(!isset($_SESSION['user'])) {
                    throw new Exception("Không có user111111");
                } 
    
                $userID = $_SESSION["user"]["id"];
                if(!$userID) {
                    throw new Exception("Người dùng chưa đăng nhập/đăng ký");
                }
                
                $stmt = $conn->prepare("
                    SELECT o.orderID, od.quantity, od.salePrice, v.volumeName,
                        b.bookName, b.authorName, v.imageURL, o.totalPrice, 
                        o.status
                    FROM orders o
                    JOIN orderdetail od on od.orderID = o.orderID
                    JOIN edition e on e.editionID = od.editionID
                    JOIN volumes v on v.volumeID = e.editionID
                    JOIN books b on b.bookID = v.bookID
                    WHERE o.userID = ?
                    ORDER BY o.createAt DESC
                ");
                $stmt->bindParam(1, $userID, PDO::PARAM_INT);
                $stmt->execute();
                
                $orders = [];
                $currentOrder = null;
                while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
                    if(!$currentOrder || $currentOrder['orderID'] != $row['orderID']) {
                        if($currentOrder) {
                            $orders[] = $currentOrder;
                        }
                        $currentOrder = [
                            "orderID" => $row["orderID"],
                            "status" => $row["status"],
                            "totalPrice" => $row["totalPrice"],
                            "items" => []
                        ];
                    }
    
                    $currentOrder["items"][] = [
                        "bookName" => $row["bookName"],
                        "volumeName" => $row["volumeName"],
                        "quantity" => $row["quantity"],
                        "salePrice" => $row["salePrice"],
                        "authorName" => $row["authorName"],
                        "imageURL" => $row["imageURL"]
                    ];
    
                }
                if($currentOrder) {
    
                    $orders[] = $currentOrder;
                }
                return $orders;
            

            
        }
    }
?>