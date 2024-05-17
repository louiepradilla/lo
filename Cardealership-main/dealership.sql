-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2022 at 03:09 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dealership`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddEmployee` (IN `Name` VARCHAR(20), IN `Pword` VARCHAR(255), IN `Number` INT(10), IN `Email` VARCHAR(255), IN `Address` VARCHAR(30), IN `Salary` DECIMAL(10,2), IN `Position` VARCHAR(20), IN `Gender` VARCHAR(15))   BEGIN
	DECLARE ID INTEGER;
	/* Determine whether Employee is new or at least has a distinct email */
    SELECT Employee.EmployeeID into ID FROM Employee WHERE Employee.email = Email;
    IF ID IS NULL THEN
        /* Insert Provider into Provider Table */
        INSERT INTO Employee (EmployeeName, Password, PhoneNumber, Email, Address, Salary, Position, Gender)
        	VALUES (Name, Pword, Number, Email, Address, Salary, Position, Gender);
        /* Get new Provider ID */
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DropEmployee` (IN `EmpID` INT(11))  DETERMINISTIC BEGIN	
    DELETE FROM employee WHERE EmployeeID = EmpID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `MakePurchase` (IN `Name` VARCHAR(20), IN `Address` VARCHAR(30), IN `Number` INT(10), IN `Type` VARCHAR(15), IN `PEmail` VARCHAR(30), IN `Make` VARCHAR(20), IN `Model` VARCHAR(20), IN `CarYear` INT(4), IN `CarVIN` VARCHAR(17), IN `Price` DECIMAL(10,2), IN `Miles` DECIMAL(11,2), IN `New` VARCHAR(3), IN `PurchaseDate` DATE, IN `EmpID` INT(11))  DETERMINISTIC BEGIN
	DECLARE ID INTEGER;
    DECLARE VIN VARCHAR(17);
	/* Determine whether Provider is new */
    SELECT Provider.ProvID into ID FROM Provider WHERE Provider.email = PEmail;
    IF ID IS NULL THEN
        /* Insert Provider into Provider Table */
        INSERT INTO Provider (ProvName, Address, PhoneNumber, ProvType, Email)
        	VALUES (Name, Address, Number, Type, PEmail);
        /* Get new Provider ID */
		SELECT Provider.ProvID into ID FROM Provider WHERE Provider.email = PEmail;
	END IF;
	
    /* Determine whether the vehicle is new to dealership */
    SELECT CarInventory.VIN into VIN FROM CarInventory WHERE CarInventory.VIN = CarVIN;
    IF VIN IS NULL THEN
        /* Insert Car into Inventory */
        INSERT INTO CarInventory VALUES (Make, Model, CarYear, CarVIN, Price, Miles, New, 'Yes');
        /* Get Car VIN */
        SET VIN = CarVIN;
    ELSE
    	/* If the car exists in the inventory already, set it as available for sale */
    	UPDATE CarInventory SET Available = 'Yes' WHERE VIN = CarInventory.VIN;
	END IF;
    
    INSERT INTO Transactions (CustomerID, ProvID, Employee, VIN, TheDate, SoB, Final_Price)
    	VALUES (NULL, ID, EmpID, VIN, PurchaseDate, 'Bought', Price);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `MakeSale` (IN `Name` VARCHAR(20), IN `Address` VARCHAR(30), IN `Number` INT(10), IN `Email` VARCHAR(30), IN `VIN` VARCHAR(17), IN `EmpID` INT(11), IN `SaleDate` DATE, IN `Price` DECIMAL(10,2))  DETERMINISTIC BEGIN
	DECLARE ID INTEGER;

	/* Check if a customer is already in our system */
    SELECT Customer.CustomerID into ID FROM Customer WHERE Customer.Email = Email;
    IF ID IS NULL THEN
        /* Insert Customer into Customer Table */
        INSERT INTO Customer (CustomerName, Address, PhoneNumber, Email)
        	VALUES (Name, Address, Number, Email);
        /* Grab the new Customer ID for future use */
		SELECT Customer.CustomerID into ID FROM Customer WHERE Customer.Email = Email;
	END IF;
	
    /* Set the car in the Inventory Table as unavailable for sale */
    UPDATE CarInventory SET Available = 'No' WHERE VIN = CarInventory.VIN;
    
    INSERT INTO Transactions (CustomerID, ProvID, Employee, VIN, TheDate, SoB, Final_Price)
    	VALUES (ID, NULL, EmpID, VIN, SaleDate, "Sold", Price);
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RaiseSalary` (IN `EmpID` INT(11), IN `raise` DECIMAL(3,2))   BEGIN
    UPDATE Employee SET Salary = salary + (salary * 0.01 * raise)  WHERE EmployeeID = EmpID;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `best_customer`
-- (See below for the actual view)
--
CREATE TABLE `best_customer` (
`CustomerName` varchar(20)
,`COUNT(*)` bigint(21)
);

-- --------------------------------------------------------

--
-- Table structure for table `carinventory`
--

CREATE TABLE `carinventory` (
  `Make` varchar(20) NOT NULL,
  `Model` varchar(20) NOT NULL,
  `TheYear` int(4) NOT NULL,
  `VIN` varchar(17) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Miles` decimal(11,2) NOT NULL,
  `isNew` varchar(3) DEFAULT NULL CHECK (`isNew` in ('Yes','No')),
  `Available` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `carinventory`
--

INSERT INTO `carinventory` (`Make`, `Model`, `TheYear`, `VIN`, `Price`, `Miles`, `isNew`, `Available`) VALUES
('Audi', 'XQ7', 2018, 'GUNSTHEYTHINCR3TS', '62000.00', '130000.00', 'No', 'Yes'),
('Toyota', 'Camry', 2014, 'MBYHT6ETS43FRT56', '21000.00', '10000.00', 'Yes', 'No'),
('Kia', 'Optima', 2016, 'MFHTY6STE5TDFT7DH', '8500.00', '201052.00', 'No', 'No'),
('Toyota', 'Corolla', 2016, 'MG85YDHRT45AD3EW4', '18000.00', '13000.00', 'Yes', 'No'),
('Nissan', 'Sentra', 2014, 'MKVGTHYD65TSE34RA', '12000.00', '15000.00', 'Yes', 'No'),
('Nissan', 'Juke', 2017, 'MRTYHBFT46STER34A', '6000.00', '142222.00', 'No', 'No'),
('Toyota', 'Tundra', 2020, 'NFTERAFDRRT45TDRE', '45000.00', '12000.00', 'Yes', 'No');

--
-- Triggers `carinventory`
--
DELIMITER $$
CREATE TRIGGER `UPPCASE_VIN` BEFORE INSERT ON `carinventory` FOR EACH ROW SET new.VIN = UPPER(new.VIN)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CustomerName` varchar(20) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `Address` varchar(30) NOT NULL,
  `PhoneNumber` int(10) NOT NULL,
  `Email` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`CustomerName`, `CustomerID`, `Address`, `PhoneNumber`, `Email`) VALUES
('Taylor Johnson', 3330001, '647 West Antonio St', 2147483647, 'tj@outlook.com'),
('Caleb Solem', 3330002, '214 East Luis, San Antonio, Tx', 2147483647, 'csolem@gmail.com'),
('Samantha Jeferson ', 3330003, '324 Anne, Waller, TX', 2147483647, 'sjeferson@gmail.com'),
('Maria Brown', 3330004, '425 Astra st', 2147483647, 'amft@outlook.com');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `EmployeeID` int(11) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `EmployeeName` varchar(20) NOT NULL,
  `Gender` varchar(15) DEFAULT NULL,
  `Address` varchar(30) NOT NULL,
  `PhoneNumber` int(10) NOT NULL,
  `Position` varchar(20) NOT NULL,
  `Salary` decimal(10,2) NOT NULL,
  `Email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`EmployeeID`, `Password`, `EmployeeName`, `Gender`, `Address`, `PhoneNumber`, `Position`, `Salary`, `Email`) VALUES
(2220000, '1234', 'ADMIN', 'Female', 'Ikunde 3', 0, 'CEO', '2000.00', 'admin@'),
(2220001, '0000', 'Luis Mba', 'Male', '300 University Dr, Prairie Vie', 2147483647, 'Salesman', '75000.00', 'Lmba@gmail.com'),
(2220002, '1111', 'Angel Smith', 'Male', 'St Mart, Houston, TX', 1236547896, 'Manager', '95000.00', 'Asmith@outlook.com'),
(2220003, '2222', 'Sandra Ocampos', 'Female', '950 Norman St, Dallas, TX', 2147483647, 'Assistant', '60000.00', 'OcamposS@gmail.com'),
(2220004, '3333', 'Silvia Cortez', 'Female', 'Peterson Blvd, Hempstead, TX', 1254786325, 'CEO', '120000.00', 'scote@gmail.com');

-- --------------------------------------------------------

--
-- Stand-in structure for view `largest_provider`
-- (See below for the actual view)
--
CREATE TABLE `largest_provider` (
`ProvName` varchar(20)
,`ProvType` varchar(15)
,`COUNT(*)` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `largest_source`
-- (See below for the actual view)
--
CREATE TABLE `largest_source` (
`ProvType` varchar(15)
,`COUNT(*)` bigint(21)
);

-- --------------------------------------------------------

--
-- Table structure for table `provider`
--

CREATE TABLE `provider` (
  `ProvID` int(11) NOT NULL,
  `provName` varchar(20) DEFAULT NULL,
  `Address` varchar(30) NOT NULL,
  `PhoneNumber` int(10) NOT NULL,
  `ProvType` varchar(15) NOT NULL,
  `Email` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `provider`
--

INSERT INTO `provider` (`ProvID`, `provName`, `Address`, `PhoneNumber`, `ProvType`, `Email`) VALUES
(1110001, 'Toyota', '5902 N Navarro St, Victoria, T', 2147483647, 'Manufacture', 'toyota@ty.com'),
(1110002, 'Nissan', '1777 North Central Expressway,', 2147483647, 'Manufacture', 'nissan@nss.org'),
(1110003, 'John Carter', '125 Mary Avenue, San Tomas, TX', 2147483647, 'Auction', 'Jcarter@gmail.com'),
(1110004, 'Anna Suarez', '621 St Clara, Sacramento, CA', 1472348956, 'Individual', 'annas@gmail.com'),
(1110005, 'Caleb Solem', '214 East Luis, San Antonio, Tx', 2147483647, 'Individual', 'csolem@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `TransactionID` int(11) NOT NULL,
  `CustomerID` int(10) DEFAULT NULL,
  `ProvID` int(10) DEFAULT NULL,
  `Employee` int(10) DEFAULT NULL,
  `VIN` varchar(17) NOT NULL,
  `TheDate` date DEFAULT NULL,
  `SoB` varchar(6) DEFAULT NULL CHECK (`SoB` in ('Sold','Bought')),
  `Final_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`TransactionID`, `CustomerID`, `ProvID`, `Employee`, `VIN`, `TheDate`, `SoB`, `Final_price`) VALUES
(4440001, NULL, 1110001, 2220000, 'NFTERAFDRRT45TDRE', '2022-11-01', 'Bought', '45000.00'),
(4440002, NULL, 1110001, 2220000, 'MBYHT6ETS43FRT56', '2022-11-02', 'Bought', '21000.00'),
(4440003, NULL, 1110001, 2220000, 'MG85YDHRT45AD3EW4', '2022-11-02', 'Bought', '18000.00'),
(4440004, NULL, 1110002, 2220000, 'MKVGTHYD65TSE34RA', '2022-11-03', 'Bought', '12000.00'),
(4440005, NULL, 1110003, 2220000, 'MRTYHBFT46STER34A', '2022-11-11', 'Bought', '6000.00'),
(4440006, NULL, 1110004, 2220000, 'MFHTY6STE5TDFT7DH', '2022-11-12', 'Bought', '8500.00'),
(4440007, 3330001, NULL, 2220000, 'NFTERAFDRRT45TDRE', '2022-11-13', 'Sold', '48000.00'),
(4440008, 3330001, NULL, 2220000, 'MG85YDHRT45AD3EW4', '2022-11-13', 'Sold', '20500.00'),
(4440009, 3330002, NULL, 2220000, 'MFHTY6STE5TDFT7DH', '2022-11-25', 'Sold', '11000.00'),
(4440010, 3330003, NULL, 2220000, 'MKVGTHYD65TSE34RA', '2022-11-17', 'Sold', '80000.00'),
(4440011, 3330004, NULL, 2220000, 'MBYHT6ETS43FRT56', '2022-11-15', 'Sold', '62000.00'),
(4440012, NULL, 1110005, 2220000, 'GUNSTHEYTHINCR3TS', '2022-11-11', 'Bought', '62000.00'),
(4440013, 3330004, NULL, 2220000, 'MRTYHBFT46STER34A', '2022-11-16', 'Sold', '8500.00');

--
-- Triggers `transactions`
--
DELIMITER $$
CREATE TRIGGER `Upper_case_VIN_Transactions` BEFORE INSERT ON `transactions` FOR EACH ROW SET new.VIN = UPPER(new.VIN)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `bonus` AFTER INSERT ON `transactions` FOR EACH ROW BEGIN
    IF new.SoB = 'Sold'
    THEN 
    	IF NEW.Final_price >= 50000 and NEW.Final_price < 90000
        THEN
        UPDATE employee
        SET salary = salary + 1000
        WHERE EmployeeID = new.Employee;
        ELSEIF NEW.Final_price > 90000
        THEN
        UPDATE employee
        SET salary = salary + 2000
        WHERE EmployeeID = new.Employee;
        
     END IF;
   END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `car_status_update` AFTER INSERT ON `transactions` FOR EACH ROW BEGIN
	if new.SoB = 'Sold' THEN
	UPDATE carinventory
        SET Available = 'No'
        WHERE VIN = new.VIN;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure for view `best_customer`
--
DROP TABLE IF EXISTS `best_customer`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `best_customer`  AS SELECT `customer`.`CustomerName` AS `CustomerName`, count(0) AS `COUNT(*)` FROM (`customer` left join `transactions` on(`customer`.`CustomerID` = `transactions`.`CustomerID`)) GROUP BY `customer`.`CustomerID` HAVING count(0) > 1 ORDER BY count(0) AS `DESCdesc` ASC  ;

-- --------------------------------------------------------

--
-- Structure for view `largest_provider`
--
DROP TABLE IF EXISTS `largest_provider`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `largest_provider`  AS SELECT `provider`.`provName` AS `ProvName`, `provider`.`ProvType` AS `ProvType`, count(0) AS `COUNT(*)` FROM (`provider` left join `transactions` on(`provider`.`ProvID` = `transactions`.`ProvID`)) GROUP BY `provider`.`ProvID` HAVING count(0) > 1 ORDER BY count(0) AS `DESCdesc` ASC  ;

-- --------------------------------------------------------

--
-- Structure for view `largest_source`
--
DROP TABLE IF EXISTS `largest_source`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `largest_source`  AS SELECT `provider`.`ProvType` AS `ProvType`, count(0) AS `COUNT(*)` FROM (`provider` left join `transactions` on(`provider`.`ProvID` = `transactions`.`ProvID`)) GROUP BY `provider`.`ProvType` ORDER BY count(0) AS `DESCdesc` ASC  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carinventory`
--
ALTER TABLE `carinventory`
  ADD PRIMARY KEY (`VIN`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CustomerID`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`EmployeeID`);

--
-- Indexes for table `provider`
--
ALTER TABLE `provider`
  ADD PRIMARY KEY (`ProvID`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`TransactionID`),
  ADD KEY `CustomerID` (`CustomerID`),
  ADD KEY `ProvID` (`ProvID`),
  ADD KEY `EmployeeID` (`Employee`),
  ADD KEY `transactions_ibfk_33` (`VIN`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3330005;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `EmployeeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2220005;

--
-- AUTO_INCREMENT for table `provider`
--
ALTER TABLE `provider`
  MODIFY `ProvID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1110006;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `TransactionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4440014;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`ProvID`) REFERENCES `provider` (`ProvID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_33` FOREIGN KEY (`VIN`) REFERENCES `carinventory` (`VIN`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_4` FOREIGN KEY (`Employee`) REFERENCES `employee` (`EmployeeID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
