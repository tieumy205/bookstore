<?php
class Register {

    public static function existUserName($conn, $username) {
        $stmt = $conn->prepare("SELECT 1 FROM users WHERE userName = ? LIMIT 1");
        $stmt->bindParam(1, $username);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public static function existNumberPhone($conn, $numberPhone) {
        $stmt = $conn->prepare("SELECT 1 FROM address WHERE numberPhone = ? LIMIT 1");
        $stmt->bindParam(1, $numberPhone);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public static function addUser($username, $password, $numberPhone, $province, $district, $detailAddress, $consigneeName) {

        $db = new Database();
        $conn = $db->connect();
        if(!$conn) {
            return [
                "status"=>"error",
                "message"=>"Kết nối cơ sở dữ liệu thất bại"
            ];
        }

        if(self::existUserName($conn, $username)) {
            return [
                "status"=>"error",
                "message"=>"Tên đăng nhập đã tồn tại"
            ];
        }

        if(self::existNumberPhone($conn, $numberPhone)) {
            return [
                "status"=>"error",
                "message"=>"Số điện thoại đã tồn tại"
            ];
        }

        try {

            $conn->beginTransaction();

            $stmt = $conn->prepare("
                INSERT INTO users (userName,password,status,role)
                VALUES (?,?, 'active', 'user')
            ");

            $stmt->bindParam(1,$username);
            $stmt->bindParam(2,$password);
            
            $stmt->execute();

            $userId = $conn->lastInsertId();
            echo "Inserted user ID: " . $userId; // Debugging line

            $stmt2 = $conn->prepare("
                INSERT INTO address (userId,detailAddress,province,district, isDefautl, consigneeName, numberPhone)
                VALUES (?,?,?,?, 1, ?, ?);
            ");

            $stmt2->bindParam(1,$userId);
            $stmt2->bindParam(2,$detailAddress);
            $stmt2->bindParam(3,$province);
            $stmt2->bindParam(4,$district);
            $stmt2->bindParam(5, $consigneeName);
            $stmt->bindParam(6,$numberPhone);
            $stmt2->execute();

            $conn->commit();

            return true;

        } catch(Exception $e) {

            $conn->rollBack();

            return [
                "status"=>"error",
                "message"=>"Đăng ký thất bại"
            ];
        }
    }
}
?>
