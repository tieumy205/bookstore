-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2026 at 01:51 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `addressID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `detailAddress` text NOT NULL,
  `province` varchar(255) NOT NULL,
  `district` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`addressID`, `userID`, `detailAddress`, `province`, `district`) VALUES
(1, 1, 'đường Nguyễn Văn Linh', 'Tỉnh Hà Giang', 'Huyện Mèo Vạc'),
(2, 2, 'đường 111, ấp 45', 'Tỉnh Cao Bằng', 'Huyện Bảo Lạc');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `bookID` int(11) NOT NULL,
  `bookName` text DEFAULT NULL,
  `authorName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`bookID`, `bookName`, `authorName`) VALUES
(1, 'Đừng Bao Giờ Buông Dao', 'Patrick Ness'),
(2, 'Đèn Nhỏ Và Những Đứa Con Của Biển', 'Annet Schaap'),
(3, 'Một Lít Nước Mắt', 'Kito Aya'),
(4, 'Hội Chứng E', 'Franck Thilliez'),
(5, 'Con Thỏ Nguyền Rủa', 'Chung Bora'),
(6, 'Gửi Những Người Không Được Bảo Vệ', 'Nakayama Shichiri'),
(7, 'KHÁCH SẠN MẶT NẠ', 'Higashino Keigo');

-- --------------------------------------------------------

--
-- Table structure for table `book_category`
--

CREATE TABLE `book_category` (
  `bookID` int(11) NOT NULL,
  `categoryID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_category`
--

INSERT INTO `book_category` (`bookID`, `categoryID`) VALUES
(1, 3),
(2, 1),
(3, 1),
(4, 4),
(5, 3),
(6, 4),
(7, 4);

-- --------------------------------------------------------

--
-- Table structure for table `cartdetail`
--

CREATE TABLE `cartdetail` (
  `cartDetailID` int(11) NOT NULL,
  `editionID` int(11) NOT NULL,
  `cartID` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `cartdetail`
--
DELIMITER $$
CREATE TRIGGER `trg_cart_total_quantity` AFTER INSERT ON `cartdetail` FOR EACH ROW BEGIN
    UPDATE Carts
    SET totalQuantity = (
        SELECT SUM(quantity)
        FROM CartDetail
        WHERE cartID = NEW.cartID
    )
    WHERE cartID = NEW.cartID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `cartID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `totalQuantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categoryID` int(11) NOT NULL,
  `categoryName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categoryID`, `categoryName`) VALUES
(1, 'Văn học'),
(2, 'Tâm lý học'),
(3, 'Kinh dị'),
(4, 'Trinh thám');

-- --------------------------------------------------------

--
-- Table structure for table `edition`
--

CREATE TABLE `edition` (
  `editionID` int(11) NOT NULL,
  `volumeID` int(11) NOT NULL,
  `publicationYear` char(4) DEFAULT NULL,
  `publisherName` varchar(255) DEFAULT NULL,
  `quotedPrice` int(11) DEFAULT NULL,
  `status` enum('show','hide') DEFAULT NULL,
  `coverType` enum('hardCover','paperBack') DEFAULT NULL,
  `AverageCost` decimal(12,2) DEFAULT NULL,
  `StockQuantity` int(11) DEFAULT NULL,
  `salePrice` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `edition`
--

INSERT INTO `edition` (`editionID`, `volumeID`, `publicationYear`, `publisherName`, `quotedPrice`, `status`, `coverType`, `AverageCost`, `StockQuantity`, `salePrice`) VALUES
(1, 1, '2022', 'Nhã Nam', 120000, 'show', 'paperBack', 70000.00, 48, 120000.00),
(2, 2, '2021', 'Nhã Nam', 135000, 'show', 'paperBack', 80000.00, 39, 135000.00),
(3, 3, '2020', 'Kim Đồng', 90000, 'show', 'paperBack', 50000.00, 60, 90000.00),
(4, 3, '2023', 'Kim Đồng', 100000, 'show', 'paperBack', 90000.00, 30, 100000.00),
(5, 4, '2022', 'First News', 150000, 'show', 'paperBack', 60000.00, 45, NULL),
(6, 5, '2023', 'Nhã Nam', 110000, 'show', 'paperBack', 0.00, 0, NULL),
(7, 6, '2021', 'First News', 140000, 'show', 'paperBack', 0.00, 0, NULL),
(8, 7, '2022', 'Nhã Nam', 125000, 'show', 'paperBack', 0.00, 0, NULL),
(9, 7, '2023', 'Kim Đồng', 170000, 'show', 'hardCover', 0.00, 0, NULL),
(10, 8, '2023', 'Nhã Nam', 130000, 'show', 'paperBack', 0.00, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `goodreceivednote`
--

CREATE TABLE `goodreceivednote` (
  `grnID` int(11) NOT NULL,
  `supplierID` int(11) NOT NULL,
  `totalPrice` decimal(12,2) DEFAULT NULL,
  `createAt` datetime DEFAULT NULL,
  `status` enum('processing','completed') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goodreceivednote`
--

INSERT INTO `goodreceivednote` (`grnID`, `supplierID`, `totalPrice`, `createAt`, `status`) VALUES
(1, 1, 9700000.00, '2026-01-10 00:00:00', 'completed'),
(2, 2, 5400000.00, '2026-01-15 00:00:00', 'completed');

--
-- Triggers `goodreceivednote`
--
DELIMITER $$
CREATE TRIGGER `trg_inventory_import` AFTER UPDATE ON `goodreceivednote` FOR EACH ROW BEGIN
    IF NEW.status = 'completed' AND OLD.status <> 'completed' THEN

        INSERT INTO InventoryTransaction
        (editionID, transactionType, quantityChange, createAt, referenceTable, referenceID)
        SELECT
            editionID,
            'import',
            quantity,
            NOW(),
            'GoodReceivedNote',
            NEW.grnID
        FROM GRN_Detail
        WHERE grnID = NEW.grnID;

    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_update_stock_after_grn` AFTER UPDATE ON `goodreceivednote` FOR EACH ROW BEGIN
    IF NEW.status = 'completed' AND OLD.status <> 'completed' THEN
    
        UPDATE Edition e
        JOIN (
            SELECT editionID,
                   SUM(quantity) AS qty,
                   SUM(quantity * purchaseCost) AS totalCost
            FROM GRN_Detail
            WHERE grnID = NEW.grnID
            GROUP BY editionID
        ) d ON e.editionID = d.editionID
        SET 
            e.AverageCost = 
                ((e.AverageCost * e.StockQuantity) + d.totalCost)
                /
                (e.StockQuantity + d.qty),
            e.StockQuantity = e.StockQuantity + d.qty;

    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `grn_detail`
--

CREATE TABLE `grn_detail` (
  `grndID` int(11) NOT NULL,
  `grnID` int(11) NOT NULL,
  `editionID` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `purchaseCost` decimal(12,2) DEFAULT NULL,
  `replenishmentFrequence` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grn_detail`
--

INSERT INTO `grn_detail` (`grndID`, `grnID`, `editionID`, `quantity`, `purchaseCost`, `replenishmentFrequence`) VALUES
(1, 1, 1, 50, 70000.00, 1),
(2, 1, 2, 40, 80000.00, 1),
(3, 1, 3, 60, 50000.00, 1),
(4, 2, 4, 30, 90000.00, 1),
(5, 2, 5, 45, 60000.00, 1);

--
-- Triggers `grn_detail`
--
DELIMITER $$
CREATE TRIGGER `trg_grn_total_after_insert` AFTER INSERT ON `grn_detail` FOR EACH ROW BEGIN
    UPDATE GoodReceivedNote
    SET totalPrice = (
        SELECT SUM(quantity * purchaseCost)
        FROM GRN_Detail
        WHERE grnID = NEW.grnID
    )
    WHERE grnID = NEW.grnID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_replenishment_frequency` BEFORE INSERT ON `grn_detail` FOR EACH ROW BEGIN

    SET NEW.replenishmentFrequence = (
        SELECT COUNT(DISTINCT grnID)
        FROM GRN_Detail
        WHERE editionID = NEW.editionID
    ) + 1;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `inventorytransaction`
--

CREATE TABLE `inventorytransaction` (
  `transactionID` int(11) NOT NULL,
  `editionID` int(11) NOT NULL,
  `transactionType` varchar(255) DEFAULT NULL,
  `quantityChange` int(11) DEFAULT NULL,
  `createAt` datetime DEFAULT NULL,
  `referenceTable` varchar(255) DEFAULT NULL,
  `referenceID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventorytransaction`
--

INSERT INTO `inventorytransaction` (`transactionID`, `editionID`, `transactionType`, `quantityChange`, `createAt`, `referenceTable`, `referenceID`) VALUES
(1, 1, 'import', 50, '2026-03-09 15:42:01', 'GoodReceivedNote', 1),
(2, 2, 'import', 40, '2026-03-09 15:42:01', 'GoodReceivedNote', 1),
(3, 3, 'import', 60, '2026-03-09 15:42:01', 'GoodReceivedNote', 1),
(4, 4, 'import', 30, '2026-03-09 15:42:01', 'GoodReceivedNote', 2),
(5, 5, 'import', 45, '2026-03-09 15:42:01', 'GoodReceivedNote', 2),
(10, 1, 'sale', -2, '2026-03-09 15:52:21', 'Orders', 1),
(11, 2, 'sale', -1, '2026-03-09 15:52:21', 'Orders', 1),
(12, 3, 'sale', -3, '2026-03-09 15:52:21', 'Orders', 2),
(13, 4, 'sale', -1, '2026-03-09 15:52:21', 'Orders', 2),
(14, 3, 'cancel_sale', 3, '2026-03-09 15:54:10', 'Orders', 2),
(15, 4, 'cancel_sale', 1, '2026-03-09 15:54:10', 'Orders', 2);

-- --------------------------------------------------------

--
-- Table structure for table `orderdetail`
--

CREATE TABLE `orderdetail` (
  `orderDetailID` int(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  `editionID` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `salePrice` decimal(12,2) DEFAULT NULL,
  `costPrice` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderdetail`
--

INSERT INTO `orderdetail` (`orderDetailID`, `orderID`, `editionID`, `quantity`, `salePrice`, `costPrice`) VALUES
(5, 1, 1, 2, 120000.00, 70000.00),
(6, 1, 2, 1, 135000.00, 80000.00),
(7, 2, 3, 3, 90000.00, 50000.00),
(8, 2, 4, 1, 100000.00, 90000.00);

--
-- Triggers `orderdetail`
--
DELIMITER $$
CREATE TRIGGER `trg_check_sale_price` BEFORE INSERT ON `orderdetail` FOR EACH ROW BEGIN

    DECLARE quoted DECIMAL(12,2);

    -- lấy giá niêm yết
    SELECT quotedPrice
    INTO quoted
    FROM edition
    WHERE editionID = NEW.editionID;

    -- kiểm tra giá bán >= giá nhập
    IF NEW.salePrice < NEW.costPrice THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Sale price cannot be lower than cost price';
    END IF;

    -- kiểm tra giá bán <= giá niêm yết
    IF NEW.salePrice > quoted THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Sale price cannot exceed quoted price';
    END IF;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_check_stock_before_order` BEFORE INSERT ON `orderdetail` FOR EACH ROW BEGIN
    DECLARE currentStock INT;

    SELECT StockQuantity
    INTO currentStock
    FROM Edition
    WHERE editionID = NEW.editionID;

    IF currentStock < NEW.quantity THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Not enough stock';
    END IF;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_inventory_sale` AFTER INSERT ON `orderdetail` FOR EACH ROW BEGIN
    INSERT INTO InventoryTransaction
    (editionID, transactionType, quantityChange, createAt, referenceTable, referenceID)
    VALUES
    (NEW.editionID, 'sale', -NEW.quantity, NOW(), 'Orders', NEW.orderID);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_order_total_price` AFTER INSERT ON `orderdetail` FOR EACH ROW BEGIN
    UPDATE Orders
    SET totalPrice = (
        SELECT SUM(quantity * salePrice)
        FROM OrderDetail
        WHERE orderID = NEW.orderID
    )
    WHERE orderID = NEW.orderID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_reduce_stock_after_order` AFTER INSERT ON `orderdetail` FOR EACH ROW BEGIN
    UPDATE Edition
    SET StockQuantity = StockQuantity - NEW.quantity
    WHERE editionID = NEW.editionID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_set_cost_price` BEFORE INSERT ON `orderdetail` FOR EACH ROW BEGIN
    DECLARE avgCost DECIMAL(12,2);

    SELECT AverageCost
    INTO avgCost
    FROM Edition
    WHERE editionID = NEW.editionID;

    SET NEW.costPrice = avgCost;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderID` int(11) NOT NULL,
  `addressID` int(11) NOT NULL,
  `paymentID` int(11) NOT NULL,
  `totalPrice` decimal(12,2) DEFAULT NULL,
  `createAt` datetime DEFAULT NULL,
  `status` enum('processing','confirmed','delivering','completed','canceled') DEFAULT NULL,
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderID`, `addressID`, `paymentID`, `totalPrice`, `createAt`, `status`, `userID`) VALUES
(1, 1, 1, 375000.00, '2026-02-01 00:00:00', 'completed', 1),
(2, 2, 2, 370000.00, '2026-02-02 00:00:00', 'canceled', 2);

--
-- Triggers `orders`
--
DELIMITER $$
CREATE TRIGGER `trg_cancel_order_inventory` AFTER UPDATE ON `orders` FOR EACH ROW BEGIN
    IF NEW.status='canceled' AND OLD.status <> 'canceled' THEN

        INSERT INTO InventoryTransaction
        (editionID,transactionType,quantityChange,createAt,referenceTable,referenceID)
        SELECT 
            editionID,
            'cancel_sale',
            quantity,
            NOW(),
            'Orders',
            NEW.orderID
        FROM OrderDetail
        WHERE orderID = NEW.orderID;

    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_restore_stock_when_order_cancel` AFTER UPDATE ON `orders` FOR EACH ROW BEGIN
    IF NEW.status='canceled' AND OLD.status <> 'canceled' THEN
    
        UPDATE Edition e
        JOIN OrderDetail od
        ON e.editionID = od.editionID
        SET e.StockQuantity = e.StockQuantity + od.quantity
        WHERE od.orderID = NEW.orderID;

    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `paymentID` int(11) NOT NULL,
  `payment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`paymentID`, `payment`) VALUES
(1, 'cash'),
(2, 'bank_transfer'),
(3, 'online');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplierID` int(11) NOT NULL,
  `supplierName` varchar(255) DEFAULT NULL,
  `numberPhone` varchar(10) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplierID`, `supplierName`, `numberPhone`, `address`) VALUES
(1, 'Nhã Nam', '0900000001', 'Hà Nội'),
(2, 'Kim Đồng', '0900000002', 'Hà Nội'),
(3, 'First News', '0900000003', 'TP Hồ Chí Minh');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `userName` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL,
  `numberPhone` varchar(10) DEFAULT NULL,
  `fullName` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT NULL,
  `role` enum('user','admin') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `userName`, `password`, `numberPhone`, `fullName`, `status`, `role`) VALUES
(1, 'tieumy', '$2y$10$sBE8D67sj1wL4Vq9oMscI.6yIJ4iuail5BPI.Rdp.eTZzI1TW9Wzm', '0123456789', '', 'active', 'user'),
(2, 'taho', '$2y$10$OXKVxXPYACOXMbftpzu3Q.eaYCMsLivaPJX3XASmlTFMgvn1h9Jyu', '0999999991', '', 'active', 'user');

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `trg_create_cart_for_user` AFTER INSERT ON `users` FOR EACH ROW BEGIN
    INSERT INTO Carts(userID,totalQuantity)
    VALUES(NEW.userID,0);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `volumes`
--

CREATE TABLE `volumes` (
  `volumeID` int(11) NOT NULL,
  `bookID` int(11) NOT NULL,
  `volume` int(11) DEFAULT 1,
  `volumeName` text DEFAULT NULL,
  `imageURL` text DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volumes`
--

INSERT INTO `volumes` (`volumeID`, `bookID`, `volume`, `volumeName`, `imageURL`, `description`) VALUES
(1, 1, 1, 'Đừng Bao Giờ Buông Dao', 'app/assets/images/dungbaogiobuongdao.png', 'Chẳng có thị trấn nào giống như thị trấn Prentiss.\r\nNơi không còn bóng dáng phụ nữ.\r\nNơi mọi suy nghĩ trở thành Tiếng Ồn mà bất kỳ sinh vật nào cũng có thể nghe thấy.\r\nDòng chảy Tiếng Ồn ầm ĩ không bao giờ dứt.\r\nKhông tâm tư thầm kín nào còn là bí mật.\r\nNgay cả trong giấc ngủ.\r\nVậy mà khi sự im lặng không tưởng xuất hiện, vẫn có một bí mật tồi tệ đến mức khiến Todd, thằng nhóc chỉ còn một tháng nữa là trở thành đàn ông, thằng nhóc cuối cùng chưa trở thành đàn ông, phải bỏ chạy hòng giữ lấy mạng sống. Cùng con chó biết nói. Sự im lặng. Và con dao.\r\nNhưng làm sao để chạy thoát khi những kẻ săn đuổi có thể nghe được mọi suy nghĩ trong đầu ta?'),
(2, 2, 1, 'Đèn Nhỏ Và Những Đứa Con Của Biển', 'app/assets/images/dennhovanhungduaconcuabien.png', 'TÁC PHẨM PHIÊU LƯU NHUỐM ĐẦY MÀU SẮC CỔ TÍCH!\r\n\r\nGọi cô là Đèn Nhỏ vì cô là con gái người thắp đèn biển. Nhưng chính cô gái nhỏ lơ đễnh mà nhân hậu, kiên cường ấy mới là người chiều chiều, thay người cha què cụt say xỉn, leo lên thắp sáng ngọn hải đăng.\r\n\r\nCho đến một ngày, Đèn quên mua diêm. Hải đăng tắt ngúm. Thảm họa ập vào.\r\n\r\nNàng tiên cá đánh đổi giọng hát để được sống với người mình yêu, cánh cửa cấm kỵ trong dinh thự trăm phòng, quái vật dưới gầm giường, những dị nhân hội chợ, những cướp biển ngang tàn, những thủy quái khơi xa,… Cổ tích cứ thế đan dệt, như ngọn gió đại dương kéo ta băng băng qua những trang phiêu lưu kỳ dị mà bay bổng, u tối mà ấm lòng. Một câu chuyện mê đắm về lòng can đảm, sự trung thành, về tình cảm gia đình và tình bạn bất chấp khác biệt. Một bầu không khí mầu nhiệm mà ta muốn kéo dài mãi mãi.'),
(3, 3, 1, '', 'app/assets/images/motlitnuocmat.png', '“Hãy sống! Mình muốn hít thở thật sâu dưới trời xanh.”\r\n\r\nMột tâm hồn nhạy cảm.\r\n\r\nMột gia đình ấm áp.\r\n\r\nMột căn bệnh hiểm nghèo.\r\n\r\nMột cơ thể tật nguyền.\r\n\r\nĐó là những gì Kito Aya có trong hơn 20 năm cuộc đời. Với Aya, tương lai của cô là một con đường hẹp, và càng ngày nó càng trở nên hẹp hơn. Căn bệnh ngăn trở Aya khỏi tất cả những ước mơ và dự định, thậm chí việc tự mình bước ra ngoài phố để đi tới hiệu sách cũng trở thành một khao khát cháy bỏng. Hơn 6 năm kiên trì viết nhật ký, cô kể về những cảm nhận và suy tư của bản thân trong suốt quãng thời gian chứng kiến cơ thể mình từng bước từng bước gánh lấy một số phận đau đớn . Nhưng từ trong nước mắt và tật nguyền, cuộc tìm kiếm giá trị bản thân của cô đã làm rúng động cả Nhật Bản.'),
(4, 4, 1, '', 'app/assets/hoichunge.png', 'Một bộ phim ngắn bí ẩn và độc hại khiến người xem đột ngột bị mù... Vậy là đủ để đi tong toàn bộ kỳ nghỉ hè của trung úy cảnh sát Lucie Hennebelle. Năm xác chết bị cắt xẻ tàn bạo được tìm thấy trong tình trạng mất não, mất mắt, và phân hủy đến khó lòng nhận dạng... Chẳng cần gì hơn thế để mời gọi thanh tra trưởng Franck Sharko đang trong kỳ nghỉ cưỡng chế phải quay trở lại đội Hình sự. Hai hướng điều tra cho cùng một vụ án duy nhấ sẽ kết hợp Hennebelle và Sharko, đưa họ đi từ những khu ổ chuột nhơ nhớp ở Cairo đến các trại trẻ mồ côi ở Canada, để rồi đối mặt với một tội ác có một không hai, một thực tế tàn bạo, hé lộ sự thật rằng tất cả chúng ta ai cũng đều có thể phạm phải điều tồi tệ nhất. Với Hội chứng E, Franck Thilliez thêm một lần nữa giúp chúng ta hiểu thế nào là kinh hoàng khi đưa chúng ta vào tâm hồn con người, vào cội rễ của bạo lực và cái ác.'),
(5, 5, 1, '', 'app/assets/conthonguyenrua.png', 'Chiếc đèn hình thỏ mang sức mạnh nguyền rủa, cái đầu nhớp nhúa trồi lên từ bồn cầu, vụ tai nạn xe hơi ly kỳ giữa đầm lầy, con cáo chảy máu vàng ròng, những kẻ sống và người chết bị trói buộc trong dòng chảy thời gian...\r\n\r\nCon thỏ nguyền rủa là tập truyện ngắn đầy ám ảnh, hài hước, gớm ghiếc và ghê rợn về những cơn ác mộng của cuộc sống hiện đại, trong một thế giới \"nhìn chung là khốc liệt và xa lạ, đôi khi cũng đẹp và mê hoặc, nhưng ngay cả trong những giây phút đó, về cơ bản nó vẫn là một chốn man rợ.\"\r\n\r\nCuốn sách là tuyển tập 10 truyện ngắn kinh dị của nhà văn Hàn Quốc Chung Bora, tái hiện lại những cuộc đời cô độc giữa xã hội vô cảm lạnh lùng.'),
(6, 6, 1, '', 'app/assets/guinhungnguoikhongduocbaove.png', 'Hai công chức mẫu mực của tỉnh Miyagi lần lược được phát hiện đã bị giam giữ và chết đói. Cảnh sát không thể tìm ra bất cứ manh mối nào, dù là nhỏ nhất.\r\n\r\nTrong khi ấy, Tone Katsuhisa, một tên tù nhân cải tạo tốt vừa được phóng thích trước thời hạn và đang lần theo một nhân vật hắn từng liên quan trong quá khứ. Tone đang có kế hoạch gì? Và tại sao hai nạn nhân lại bị giết hại một cách tàn nhẫn như vậy? Vụ án liệu có dừng lại ở hai nạn nhân? Và trong vụ án này, thật ra ai mới là nạn nhân, ai mới cần được bảo vệ, còn ai là thủ phạm?\r\n\r\nGiận dữ, khổ đau, oán hận, xung đột, chính nghĩa... Giữa vô văn những điều hết sức thường nhật lại là một sự thật tàn khốc đến xé lòng...'),
(7, 7, 1, 'Khách Sạn Mặt Nạ Tập 1', 'app/assets/khachsanmatna1bia.webp', 'Một vụ giết người hàng loạt bí ẩn ở Tokyo. Hiện chưa rõ nghi phạm và mục tiêu tiếp theo. Điều duy nhất từ mật mã hung thủ để lại ám chỉ nơi sẽ diễn ra tội ác là khách sạn hạng nhất Cortesia Tokyo. Nitta Kousuke, viên cảnh sát hình sự trẻ tuổi, nhận lệnh cải trang làm nhân viên khách sạn để nằm vùng điều tra. Hướng dẫn nghiệp vụ cho anh là Yamagishi Naomi, một nữ lễ tân thông minh có óc quan sát sắc sảo. Liệu cả hai có thể lần ra chân tướng vụ án, trong khi những vị khách đáng ngờ cứ lần lượt ghé thăm!?'),
(8, 7, 2, 'Khách Sạn Mặt Nạ Tập 2', 'app/assets/khachsanmatna2.jpg', 'Yamagishi Naomi được điều đến đào tạo lễ tân cho khách sạn Cortesia Osaka mới khai trương, cô luôn có thói quen chú ý đến tấm mặt nạ của một vài vị khách. Trong khi đó, Nitta Kousuke đang điều tra một người đàn ông liên quan đến vụ án giết người xảy ra ở Tokyo, kẻ tuyên bố đã đến Osaka vào đêm xảy ra vụ án, nhưng nhất định không nói tên khách sạn. Điều gì buộc anh ta giữ kín ngay cả khi bị tình nghi giết người? Nếu công việc của Naomi là bảo vệ mặt nạ của khách hàng, thì nhiệm vụ của Nitta là lột bỏ mặt nạ của tội phạm. Khách sạn mặt nạ – Đêm trước lễ hội hóa trang là những câu chuyện thú vị được kể trước thời điểm hai con người ấy gặp nhau');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`addressID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`bookID`);

--
-- Indexes for table `book_category`
--
ALTER TABLE `book_category`
  ADD PRIMARY KEY (`bookID`,`categoryID`),
  ADD KEY `categoryID` (`categoryID`);

--
-- Indexes for table `cartdetail`
--
ALTER TABLE `cartdetail`
  ADD PRIMARY KEY (`cartDetailID`),
  ADD KEY `editionID` (`editionID`),
  ADD KEY `cartID` (`cartID`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`cartID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categoryID`);

--
-- Indexes for table `edition`
--
ALTER TABLE `edition`
  ADD PRIMARY KEY (`editionID`),
  ADD KEY `volumeID` (`volumeID`);

--
-- Indexes for table `goodreceivednote`
--
ALTER TABLE `goodreceivednote`
  ADD PRIMARY KEY (`grnID`),
  ADD KEY `supplierID` (`supplierID`);

--
-- Indexes for table `grn_detail`
--
ALTER TABLE `grn_detail`
  ADD PRIMARY KEY (`grndID`),
  ADD KEY `grnID` (`grnID`),
  ADD KEY `editionID` (`editionID`);

--
-- Indexes for table `inventorytransaction`
--
ALTER TABLE `inventorytransaction`
  ADD PRIMARY KEY (`transactionID`),
  ADD KEY `editionID` (`editionID`);

--
-- Indexes for table `orderdetail`
--
ALTER TABLE `orderdetail`
  ADD PRIMARY KEY (`orderDetailID`),
  ADD KEY `orderID` (`orderID`),
  ADD KEY `editionID` (`editionID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `addressID` (`addressID`),
  ADD KEY `paymentID` (`paymentID`),
  ADD KEY `fk_user_od` (`userID`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`paymentID`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplierID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `volumes`
--
ALTER TABLE `volumes`
  ADD PRIMARY KEY (`volumeID`),
  ADD KEY `bookID` (`bookID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `addressID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `bookID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cartdetail`
--
ALTER TABLE `cartdetail`
  MODIFY `cartDetailID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `cartID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `edition`
--
ALTER TABLE `edition`
  MODIFY `editionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `goodreceivednote`
--
ALTER TABLE `goodreceivednote`
  MODIFY `grnID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `grn_detail`
--
ALTER TABLE `grn_detail`
  MODIFY `grndID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `inventorytransaction`
--
ALTER TABLE `inventorytransaction`
  MODIFY `transactionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `orderdetail`
--
ALTER TABLE `orderdetail`
  MODIFY `orderDetailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `paymentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplierID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `volumes`
--
ALTER TABLE `volumes`
  MODIFY `volumeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `book_category`
--
ALTER TABLE `book_category`
  ADD CONSTRAINT `book_category_ibfk_1` FOREIGN KEY (`bookID`) REFERENCES `books` (`bookID`),
  ADD CONSTRAINT `book_category_ibfk_2` FOREIGN KEY (`categoryID`) REFERENCES `categories` (`categoryID`);

--
-- Constraints for table `cartdetail`
--
ALTER TABLE `cartdetail`
  ADD CONSTRAINT `cartdetail_ibfk_1` FOREIGN KEY (`editionID`) REFERENCES `edition` (`editionID`),
  ADD CONSTRAINT `cartdetail_ibfk_2` FOREIGN KEY (`cartID`) REFERENCES `carts` (`cartID`);

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `edition`
--
ALTER TABLE `edition`
  ADD CONSTRAINT `edition_ibfk_1` FOREIGN KEY (`volumeID`) REFERENCES `volumes` (`volumeID`);

--
-- Constraints for table `goodreceivednote`
--
ALTER TABLE `goodreceivednote`
  ADD CONSTRAINT `goodreceivednote_ibfk_1` FOREIGN KEY (`supplierID`) REFERENCES `suppliers` (`supplierID`);

--
-- Constraints for table `grn_detail`
--
ALTER TABLE `grn_detail`
  ADD CONSTRAINT `grn_detail_ibfk_1` FOREIGN KEY (`grnID`) REFERENCES `goodreceivednote` (`grnID`),
  ADD CONSTRAINT `grn_detail_ibfk_2` FOREIGN KEY (`editionID`) REFERENCES `edition` (`editionID`);

--
-- Constraints for table `inventorytransaction`
--
ALTER TABLE `inventorytransaction`
  ADD CONSTRAINT `inventorytransaction_ibfk_1` FOREIGN KEY (`editionID`) REFERENCES `edition` (`editionID`);

--
-- Constraints for table `orderdetail`
--
ALTER TABLE `orderdetail`
  ADD CONSTRAINT `orderdetail_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`),
  ADD CONSTRAINT `orderdetail_ibfk_2` FOREIGN KEY (`editionID`) REFERENCES `edition` (`editionID`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_user_od` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`),
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`addressID`) REFERENCES `address` (`addressID`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`paymentID`) REFERENCES `payments` (`paymentID`);

--
-- Constraints for table `volumes`
--
ALTER TABLE `volumes`
  ADD CONSTRAINT `volumes_ibfk_1` FOREIGN KEY (`bookID`) REFERENCES `books` (`bookID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
