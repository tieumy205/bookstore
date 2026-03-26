<?php
session_start();

class Order {
    public static function addOrder($addressID, $paymentID, $books) {
        $db = new Database();
        $conn = $db->connect();

        if (!$conn) throw new Exception("Kết nối DB thất bại");
        if (!isset($_SESSION['user'])) throw new Exception("Chưa đăng nhập");

        $userID = $_SESSION['user']['id'];

        try {
            $conn->beginTransaction();

            // 1. insert order
            $stmt = $conn->prepare("
                INSERT INTO orders(addressID, paymentID, createAt, status, userID, shipCost)
                VALUES(?, ?, NOW(), 'processing', ?, 25000)
            ");

            $stmt->execute([$addressID, $paymentID, $userID]);

            $orderID = $conn->lastInsertId();

            // 2. lấy list ID
            $ids = array_column($books, 'editionID');

            if (empty($ids)) {
                throw new Exception("Giỏ hàng trống");
            }

            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            $stmt1 = $conn->prepare("
                SELECT editionID, salePrice, AverageCost
                FROM edition
                WHERE editionID IN ($placeholders)
            ");

            $stmt1->execute($ids);
            $editions = $stmt1->fetchAll(PDO::FETCH_ASSOC);

            if (!$editions) throw new Exception("Không tìm thấy sách");

            // 3. map edition theo id
            $map = [];
            foreach ($editions as $e) {
                $map[$e['editionID']] = $e;
            }

            // 4. insert orderdetail (LOOP)
            $stmt2 = $conn->prepare("
                INSERT INTO orderdetail(orderID, editionID, quantity, salePrice, costPrice)
                VALUES(?, ?, ?, ?, ?)
            ");

            foreach ($books as $item) {
                $editionID = $item['editionID'];
                $quantity = $item['quantity'];

                if (!isset($map[$editionID])) continue;

                $salePrice = $map[$editionID]['salePrice'];
                $costPrice = $map[$editionID]['AverageCost'];

                $stmt2->execute([
                    $orderID,
                    $editionID,
                    $quantity,
                    $salePrice,
                    $costPrice
                ]);
            }

            $conn->commit();
            return $orderID;

        } catch (Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    public static function getOrder($orderID) {
        $db = new Database();
        $conn = $db->connect();
        if (!$conn) throw new Exception("Kết nối DB thất bại");
        if (!isset($_SESSION['user'])) throw new Exception("Chưa đăng nhập");
        $userID = $_SESSION['user']['id'];
        $stmt =$conn->prepare("
            SELECT o.orderID, o.addressID, p.payment, o.status, o.createAt, o.totalPrice, o.shipCost,
                a.numberPhone, a.consigneeName, a.province, a.district, a.detailAddress, od.orderDetailID, 
                od.editionID, od.quantity, od.salePrice, v.volumeName, v.imageURL, b.bookName
            FROM orders o
            JOIN payments p ON p.paymentID = o.paymentID
            JOIN address a ON a.addressID = o.addressID
            JOIN orderdetail od ON od.orderID = o.orderID
            JOIN edition e ON e.editionID = od.editionID
            JOIN volumes v ON v.volumeID = e.volumeID
            JOIN books b ON b.bookID = v.bookID
            WHERE o.orderID = ?
        ");
        $stmt->bindParam(1, $orderID);
        $stmt->execute();
        $order = [];
        $currentOrder = null;
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if(!$currentOrder || $currentOrder['orderID'] !== $row['orderID']) {
                if($currentOrder) {
                    $order[] = $currentOrder;
                }
                $currentOrder = [
                    "consigneeName" => $row['consigneeName'],
                    "numberPhone" => $row['numberPhone'],
                    "payment" => $row['payment'],
                    "address" =>$row['detailAddress'] . $row['district'] . $row['province'],
                    "shipCost" => $row['shipCost'],
                    "status" => $row['status'],
                    "totalPrice" => $row['totalPrice'],
                    "orderID" => $row['orderID'],
                    "items" => []
                ];
            };
            $currentOrder['items'][] = [
                "orderDetailID" => $row['orderDetailID'],
                "imageURL" => $row['imageURL'],
                "volumeName" => $row['volumeName'],
                "bookName" => $row['bookName'],
                "salePrice" => $row['salePrice'],
                "quantity" => $row['quantity']
            ];
        }
        $order = $currentOrder;
        return $order;
    }
}
?>