-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for job_portal
CREATE DATABASE IF NOT EXISTS `job_portal` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `job_portal`;

-- Dumping structure for table job_portal.activity_logs
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `sr_no` int NOT NULL AUTO_INCREMENT,
  `activity_log_id` varchar(100) NOT NULL,
  `user_type` enum('superadmin','companyadmin') NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `action` varchar(255) NOT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sr_no`),
  UNIQUE KEY `activity_log_id` (`activity_log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table job_portal.activity_logs: ~0 rows (approximately)

-- Dumping structure for table job_portal.applications
CREATE TABLE IF NOT EXISTS `applications` (
  `sr_no` int NOT NULL AUTO_INCREMENT,
  `application_id` varchar(100) NOT NULL,
  `job_id` varchar(100) NOT NULL,
  `jobseeker_id` varchar(100) NOT NULL,
  `resume` varchar(255) DEFAULT NULL,
  `status` enum('pending','shortlisted','interview','rejected','hired') DEFAULT 'pending',
  `applied_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sr_no`),
  UNIQUE KEY `application_id` (`application_id`),
  KEY `job_id` (`job_id`),
  KEY `jobseeker_id` (`jobseeker_id`),
  CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE,
  CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`jobseeker_id`) REFERENCES `jobseekers` (`jobseeker_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table job_portal.applications: ~15 rows (approximately)
INSERT INTO `applications` (`sr_no`, `application_id`, `job_id`, `jobseeker_id`, `resume`, `status`, `applied_at`) VALUES
	(1, 'AP6191', 'JOB1754496071', 'JS5237', NULL, 'rejected', '2025-08-09 10:36:45'),
	(2, 'AP8520', 'JOB1754496209', 'JS5237', NULL, 'pending', '2025-08-09 10:41:36'),
	(3, 'AP3512', 'JOB1754622993', 'JS7222', NULL, 'pending', '2025-08-09 11:05:12'),
	(4, 'AP4297', 'JOB1754496816', 'JS7222', NULL, 'interview', '2025-08-09 11:08:02'),
	(5, 'AP7384', 'JOB1754622993', 'JS6027', NULL, 'pending', '2025-08-09 12:10:08'),
	(6, 'AP9828', 'JOB1754622993', 'JS5237', NULL, 'shortlisted', '2025-08-09 14:31:00'),
	(7, 'AP1268', 'JOB1754622993', 'JS8544', NULL, 'pending', '2025-08-09 16:02:18'),
	(8, 'AP7948', 'JOB1754496836', 'JS8544', NULL, 'interview', '2025-08-09 16:02:59'),
	(9, 'AP5712', 'JOB1754496816', 'JS8544', NULL, 'interview', '2025-08-09 16:03:13'),
	(10, 'AP3451', 'JOB1754496529', 'JS8544', NULL, 'hired', '2025-08-09 16:03:24'),
	(11, 'AP6980', 'JOB1754482871', 'JS8544', NULL, 'pending', '2025-08-09 16:03:37'),
	(12, 'AP5966', 'JOB1754496626', 'JS8544', NULL, 'shortlisted', '2025-08-09 17:00:50'),
	(13, 'AP8626', 'JOB1754483009', 'JS5237', NULL, 'rejected', '2025-08-10 04:03:43'),
	(14, 'AP5212', 'JOB1754482245', 'JS5237', NULL, 'pending', '2025-08-10 04:05:38'),
	(15, 'AP1915', 'JOB1754496816', 'JS5237', NULL, 'interview', '2025-08-10 04:37:28');

-- Dumping structure for table job_portal.companies
CREATE TABLE IF NOT EXISTS `companies` (
  `sr_no` int NOT NULL AUTO_INCREMENT,
  `company_id` varchar(100) NOT NULL,
  `name` varchar(150) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `description` text,
  `industry` varchar(100) DEFAULT NULL,
  `website` varchar(150) DEFAULT NULL,
  `address` text,
  `contact_email` varchar(100) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sr_no`),
  UNIQUE KEY `company_id` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table job_portal.companies: ~19 rows (approximately)
INSERT INTO `companies` (`sr_no`, `company_id`, `name`, `logo`, `description`, `industry`, `website`, `address`, `contact_email`, `contact_phone`, `created_at`) VALUES
	(7, 'comp001', 'TechNova', 'SkyNet.jpg', 'Leading IT solutions', 'IT', 'https://technova.com', 'Delhi', 'hr@technova.com', '9876543210', '2025-08-05 06:40:38'),
	(8, 'comp002', 'MediCare Ltd', 'SkyNet.jpg', 'Healthcare services', 'Healthcare', 'https://medicare.com', 'Mumbai', 'jobs@medicare.com', '9876543212', '2025-08-05 06:40:38'),
	(9, 'comp003', 'EcoBuild', 'Ecobild.jpg', 'Eco-friendly construction', 'Construction', 'https://ecobuild.com', 'Bangalore', 'careers@ecobuild.com', '9876543212', '2025-08-05 06:40:38'),
	(13, 'comp007', 'GreenTech', 'AutoPro.jpg', 'Green energy innovation', 'Energy', 'https://greentech.com', 'Pune', 'team@greentech.com', '9876543216', '2025-08-05 06:40:38'),
	(14, 'comp008', 'StyleWear', 'Ecobild.jpg', 'Fashion & Apparel', 'Retail', 'https://stylewear.com', 'Jaipur', 'careers@stylewear.com', '9876543217', '2025-08-05 06:40:38'),
	(15, 'comp009', 'AutoPro', 'AutoPro.jpg', 'Automotive solutions', 'Automotive', 'https://autopro.com', 'Nagpur', 'jobs@autopro.com', '9876543218', '2025-08-05 06:40:38'),
	(16, 'comp010', 'BookHive', 'Medicare.jpg', 'Online Bookstore', 'E-commerce', 'https://bookhive.com', 'Ahmedabad', 'hello@bookhive.com', '9876543219', '2025-08-05 06:40:38'),
	(17, 'comp011', 'SkyNet Solutions', 'Medicare.jpg', 'IoT and Smart Devices', 'Tech', 'https://skynetsol.com', 'Lucknow', 'admin@skynetsol.com', '9880010011', '2025-08-05 06:40:38'),
	(18, 'comp012', 'HealthKart', 'AutoPro.jpg', 'Healthcare and fitness', 'Healthcare', 'https://healthkart.com', 'Noida', 'support@healthkart.com', '9880010012', '2025-08-05 06:40:38'),
	(19, 'comp013', 'TravelWings', 'SkyNet.jpg', 'Travel booking services', 'Travel', 'https://travelwings.com', 'Goa', 'hr@travelwings.com', '9880010013', '2025-08-05 06:40:38'),
	(20, 'comp014', 'EduPrime', 'AutoPro.jpg', 'Online Courses Platform', 'Education', 'https://eduprime.com', 'Patna', 'contact@eduprime.com', '9880010014', '2025-08-05 06:40:38'),
	(21, 'comp015', 'SmartPay', 'Ecobild.jpg', 'Online Payment Gateway', 'Fintech', 'https://smartpay.com', 'Indore', 'jobs@smartpay.com', '9880010015', '2025-08-05 06:40:38'),
	(22, 'comp016', 'CodeCrush', 'Medicare.jpg', 'Coding Bootcamp', 'Education', 'https://codecrush.com', 'Bhopal', 'hello@codecrush.com', '9880010016', '2025-08-05 06:40:38'),
	(23, 'comp017', 'FreshKart', 'Medicare.jpg', 'Online Grocery Delivery', 'Retail', 'https://freshkart.com', 'Surat', 'jobs@freshkart.com', '9880010017', '2025-08-05 06:40:38'),
	(24, 'comp018', 'NewsX', 'SkyNet.jpg', 'News & Media Agency', 'Media', 'https://newsx.com', 'Raipur', 'editor@newsx.com', '9880010018', '2025-08-05 06:40:38'),
	(25, 'comp019', 'BuildMaster', 'logo.jpg', 'Construction Software', 'Tech', 'https://buildmaster.com', 'Chandigarh', 'hr@buildmaster.com', '9880010019', '2025-08-05 06:40:38'),
	(26, 'comp020', 'FarmConnect', 'Medicare.jpg', 'AgriTech Startup', 'Agriculture', 'https://farmconnect.com', 'Ranchi', 'contact@farmconnect.com', '9880010020', '2025-08-05 06:40:38'),
	(42, 'CMP6329', 'Test', 'samosa.png', 'eui31', 'Test', 'http://localhost/job_portal_project/super_admin/add_company.php', 'MOHANLALGANJ', 'test@abc.com', '9456789234', '2025-08-13 10:00:56'),
	(43, 'CMP4268', 'Test', 'samosa.png', 'eui31', 'Test', 'http://localhost/job_portal_project/super_admin/add_company.php', 'MOHANLALGANJ', 'test@abc.com', '9456789234', '2025-08-13 10:02:03');

-- Dumping structure for table job_portal.company_admins
CREATE TABLE IF NOT EXISTS `company_admins` (
  `sr_no` int NOT NULL AUTO_INCREMENT,
  `company_admin_id` varchar(100) NOT NULL,
  `company_id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sr_no`),
  UNIQUE KEY `company_admin_id` (`company_admin_id`),
  UNIQUE KEY `email` (`email`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `company_admins_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table job_portal.company_admins: ~17 rows (approximately)
INSERT INTO `company_admins` (`sr_no`, `company_admin_id`, `company_id`, `name`, `email`, `password`, `created_at`) VALUES
	(21, 'admin001', 'comp001', 'Ravi Sharma', 'ravi@technova.com', 'password123', '2025-08-05 06:43:08'),
	(22, 'admin002', 'comp002', 'Anjali Mehta', 'anjali@medicare.com', 'password123', '2025-08-05 06:43:08'),
	(23, 'admin003', 'comp003', 'Arjun Verma', 'arjun@ecobuild.com', 'password123', '2025-08-05 06:43:08'),
	(27, 'admin007', 'comp007', 'Rohit Shetty', 'rohit@greentech.com', 'password123', '2025-08-05 06:43:08'),
	(28, 'admin008', 'comp008', 'Neha Kapoor', 'neha@stylewear.com', 'password123', '2025-08-05 06:43:08'),
	(29, 'admin009', 'comp009', 'Amit Khanna', 'amit@autopro.com', 'password123', '2025-08-05 06:43:08'),
	(30, 'admin010', 'comp010', 'Divya Rao', 'divya@bookhive.com', 'password123', '2025-08-05 06:43:08'),
	(31, 'admin011', 'comp011', 'Deepak Nair', 'deepak@skynetsol.com', 'password123', '2025-08-05 06:43:08'),
	(32, 'admin012', 'comp012', 'Shruti Bansal', 'shruti@healthkart.com', 'password123', '2025-08-05 06:43:08'),
	(33, 'admin013', 'comp013', 'Kunal Patil', 'kunal@travelwings.com', 'password123', '2025-08-05 06:43:08'),
	(34, 'admin014', 'comp014', 'Meena Joshi', 'meena@eduprime.com', 'password123', '2025-08-05 06:43:08'),
	(35, 'admin015', 'comp015', 'Aditya Rathi', 'aditya@smartpay.com', 'password123', '2025-08-05 06:43:08'),
	(36, 'admin016', 'comp016', 'Tanvi Singh', 'tanvi@codecrush.com', 'password123', '2025-08-05 06:43:08'),
	(37, 'admin017', 'comp017', 'Ramesh Yadav', 'ramesh@freshkart.com', 'password123', '2025-08-05 06:43:08'),
	(38, 'admin018', 'comp018', 'Sneha Iyer', 'sneha@newsx.com', 'password123', '2025-08-05 06:43:08'),
	(39, 'admin019', 'comp019', 'Rahul Dev', 'rahul@buildmaster.com', 'password123', '2025-08-05 06:43:08'),
	(40, 'admin020', 'comp020', 'Kriti Arora', 'kriti@farmconnect.com', 'password123', '2025-08-05 06:43:08');

-- Dumping structure for table job_portal.interview_schedule
CREATE TABLE IF NOT EXISTS `interview_schedule` (
  `sr_no` int NOT NULL AUTO_INCREMENT,
  `interview_id` varchar(100) NOT NULL,
  `application_id` varchar(100) NOT NULL,
  `interview_date` datetime NOT NULL,
  `mode` enum('online','offline') NOT NULL,
  `location_or_link` text,
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sr_no`),
  UNIQUE KEY `interview_id` (`interview_id`),
  KEY `application_id` (`application_id`),
  CONSTRAINT `interview_schedule_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`application_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table job_portal.interview_schedule: ~0 rows (approximately)
INSERT INTO `interview_schedule` (`sr_no`, `interview_id`, `application_id`, `interview_date`, `mode`, `location_or_link`, `notes`, `created_at`) VALUES
	(3, 'IN3693', 'AP7948', '2025-08-14 00:00:00', 'offline', 'test', 'test', '2025-08-13 16:34:02'),
	(4, 'IN5610', 'AP1915', '2025-08-15 00:00:00', 'offline', 'Lucknow', 'Scheduled Interview', '2025-08-13 17:32:50'),
	(5, 'IN6784', 'AP7948', '2025-08-18 00:00:00', 'online', 'googlemeet.com', 'Scheduled', '2025-08-14 04:01:09');

-- Dumping structure for table job_portal.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `sr_no` int NOT NULL AUTO_INCREMENT,
  `job_id` varchar(100) NOT NULL,
  `company_id` varchar(100) NOT NULL,
  `posted_by` varchar(100) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `skills_required` text,
  `location` varchar(100) DEFAULT NULL,
  `salary_min` decimal(10,2) DEFAULT NULL,
  `salary_max` decimal(10,2) DEFAULT NULL,
  `employment_type` enum('full-time','part-time','internship','contract') NOT NULL,
  `experience_level` enum('fresher','junior','mid','senior') NOT NULL,
  `deadline` date DEFAULT NULL,
  `status` enum('pending','approved','rejected','expired') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sr_no`),
  UNIQUE KEY `job_id` (`job_id`),
  KEY `company_id` (`company_id`),
  KEY `posted_by` (`posted_by`),
  CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE CASCADE,
  CONSTRAINT `jobs_ibfk_2` FOREIGN KEY (`posted_by`) REFERENCES `company_admins` (`company_admin_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table job_portal.jobs: ~15 rows (approximately)
INSERT INTO `jobs` (`sr_no`, `job_id`, `company_id`, `posted_by`, `title`, `description_title`, `description`, `skills_required`, `location`, `salary_min`, `salary_max`, `employment_type`, `experience_level`, `deadline`, `status`, `created_at`) VALUES
	(1, 'JOB1754482245', 'comp002', 'admin002', 'Full Stack Developer', 'We are looking for a passionate full stack developer to join our team and build scalable web applications.', 'We are looking for a skilled and passionate Full Stack Web Developer to join our growing team. As a Full Stack Developer, you will be responsible for developing and maintaining both the front-end and back-end of web applications. You should be comfortable working with PHP, MySQL, HTML, CSS, and JavaScript to build secure, scalable, and responsive web solutions.', 'HTML, CSS, JavaScript, PHP, MySQL, Laravel', 'Lucknow , Uttar Pradesh', 30000.00, 80000.00, 'full-time', 'fresher', '2025-08-06', 'pending', '2025-08-06 12:10:45'),
	(2, 'JOB1754482590', 'comp003', 'admin003', 'Frontend Developer', 'Build user-facing features with HTML, CSS, and JavaScript. Collaborate with backend developers and designers.\r\n\r\n', 'We are looking for a skilled and passionate Full Stack Web Developer to join our growing team. As a Full Stack Developer, you will be responsible for developing and maintaining both the front-end and back-end of web applications. You should be comfortable working with PHP, MySQL, HTML, CSS, and JavaScript to build secure, scalable, and responsive web solutions.', 'HTML, CSS, JavaScript, Bootstrap, React', 'Pune, Maharashtra', 25000.00, 120000.00, 'full-time', 'junior', '2025-08-03', 'pending', '2025-08-06 12:16:30'),
	(3, 'JOB1754482721', 'comp003', 'admin003', 'Backend Developer (PHP)', 'Maintain and develop backend APIs and databases. Strong knowledge of PHP and MySQL is essential.', 'We are looking for a skilled and passionate Full Stack Web Developer to join our growing team. As a Full Stack Developer, you will be responsible for developing and maintaining both the front-end and back-end of web applications. You should be comfortable working with PHP, MySQL, HTML, CSS, and JavaScript to build secure, scalable, and responsive web solutions.', 'PHP, MySQL, Rest API, Laravel', 'Delhi', 35000.00, 90000.00, 'full-time', 'mid', '2025-08-01', 'pending', '2025-08-06 12:18:41'),
	(4, 'JOB1754482871', 'comp003', 'admin003', 'Backend Developer', 'Maintain and develop backend APIs and databases', 'We are looking for a skilled and passionate Full Stack Web Developer to join our growing team. As a Full Stack Developer, you will be responsible for developing and maintaining both the front-end and back-end of web applications. You should be comfortable working with PHP, MySQL, HTML, CSS, and JavaScript to build secure, scalable, and responsive web solutions.', 'PHP, MySQL, Rest API, Laravel', 'Noida, Uttar Pradesh', 14000.00, 40000.00, 'internship', 'fresher', '2025-08-08', 'pending', '2025-08-06 12:21:11'),
	(5, 'JOB1754483009', 'comp003', 'admin003', 'Data Analyst Intern', 'Assist in data collection, analysis, and reporting. Work closely with senior analysts and stakeholders.', 'We are looking for a skilled and passionate Full Stack Web Developer to join our growing team. As a Full Stack Developer, you will be responsible for developing and maintaining both the front-end and back-end of web applications. You should be comfortable working with PHP, MySQL, HTML, CSS, and JavaScript to build secure, scalable, and responsive web solutions.', ' Excel, SQL, Python, Power BI', 'Bangalore, Karnataka ', 32000.00, 120000.00, 'full-time', 'senior', '2025-07-28', 'pending', '2025-08-06 12:23:29'),
	(7, 'JOB1754496071', 'comp009', 'admin009', 'Automotive Design Engineer', 'Design and develop components for electric vehicles.', 'We are looking for a skilled and passionate Full Stack Web Developer to join our growing team. As a Full Stack Developer, you will be responsible for developing and maintaining both the front-end and back-end of web applications. You should be comfortable working with PHP, MySQL, HTML, CSS, and JavaScript to build secure, scalable, and responsive web solutions.', 'SolidWorks, CATIA, EV Design', 'Gurgaon, Haryana', 40000.00, 180000.00, 'full-time', 'mid', '2025-07-09', 'pending', '2025-08-06 16:01:11'),
	(8, 'JOB1754496209', 'comp009', 'admin009', 'Vehicle Diagnostics Technician', 'Description: Diagnose and troubleshoot vehicle faults using diagnostic tools.', 'We are looking for a skilled and passionate Full Stack Web Developer to join our growing team. As a Full Stack Developer, you will be responsible for developing and maintaining both the front-end and back-end of web applications. You should be comfortable working with PHP, MySQL, HTML, CSS, and JavaScript to build secure, scalable, and responsive web solutions.', 'OBD tools, Vehicle Scanning, Fault Diagnosis', 'Chennai, India', 65000.00, 550000.00, 'full-time', 'senior', '2025-08-03', 'pending', '2025-08-06 16:03:29'),
	(9, 'JOB1754496529', 'comp009', 'admin009', 'Automotive Software Developer', 'Develop embedded software for in-vehicle systems.', 'We are looking for a skilled and passionate Full Stack Web Developer to join our growing team. As a Full Stack Developer, you will be responsible for developing and maintaining both the front-end and back-end of web applications. You should be comfortable working with PHP, MySQL, HTML, CSS, and JavaScript to build secure, scalable, and responsive web solutions.', 'C++, Embedded Systems, CAN Protocol', 'Delhi, India', 50000.00, 330000.00, 'full-time', 'mid', '2025-08-20', 'pending', '2025-08-06 16:08:49'),
	(10, 'JOB1754496626', 'comp009', 'admin009', 'Battery Systems Engineer', 'Design and test battery systems for EVs.', 'We are looking for a skilled and passionate Full Stack Web Developer to join our growing team. As a Full Stack Developer, you will be responsible for developing and maintaining both the front-end and back-end of web applications. You should be comfortable working with PHP, MySQL, HTML, CSS, and JavaScript to build secure, scalable, and responsive web solutions.', ' Battery Design, BMS, Lithium-ion Technology', 'Bangalore, India', 60000.00, 90000.00, 'full-time', 'junior', '2025-06-11', 'pending', '2025-08-06 16:10:26'),
	(11, 'JOB1754496816', 'comp009', 'admin009', 'Production Supervisor – Automotive Plant', 'Supervise production line and ensure process efficiency', 'We are looking for a skilled and passionate Full Stack Web Developer to join our growing team. As a Full Stack Developer, you will be responsible for developing and maintaining both the front-end and back-end of web applications. You should be comfortable working with PHP, MySQL, HTML, CSS, and JavaScript to build secure, scalable, and responsive web solutions.', ' Shift Handling, Lean Manufacturing, Quality Control', 'Gurugram, India', 20000.00, 60000.00, 'full-time', 'junior', '2025-08-04', 'pending', '2025-08-06 16:13:36'),
	(12, 'JOB1754496836', 'comp009', 'admin009', 'Automotive Quality Assurance Engineer', 'Ensure quality standards in vehicle parts and assembly lines.', 'We are looking for a skilled and passionate Full Stack Web Developer to join our growing team. As a Full Stack Developer, you will be responsible for developing and maintaining both the front-end and back-end of web applications. You should be comfortable working with PHP, MySQL, HTML, CSS, and JavaScript to build secure, scalable, and responsive web solutions.', ' ISO/TS 16949, Quality Audits, Root Cause Analysis', 'Kolkata, India', 40000.00, 80000.00, 'full-time', 'junior', '2025-08-09', 'pending', '2025-08-06 16:13:56'),
	(13, 'JOB1754622993', 'comp002', 'admin002', 'Software Engineer – Full Stack Developer', 'We are looking for a skilled Full Stack Software Engineer to develop and maintain web applications using React, Node.js, and MongoDB.', 'We are looking for a skilled and passionate Full Stack Web Developer to join our growing team. As a Full Stack Developer, you will be responsible for developing and maintaining both the front-end and back-end of web applications. You should be comfortable working with PHP, MySQL, HTML, CSS, and JavaScript to build secure, scalable, and responsive web solutions.', 'HTML, CSS, JavaScript, React.js, Node.js, REST APIs, MongoDB, MySQL', 'Hyderabaad, India', 28000.00, 90000.00, 'full-time', 'mid', '2025-08-07', 'pending', '2025-08-08 03:16:33'),
	(17, 'JOB8673', 'comp011', 'admin011', 'Software Engineer – Freshers', 'Entry-level role for fresh graduates in software development', 'We are looking for enthusiastic software engineers to join our dynamic team at TCS. You will work on modern web technologies, collaborate with cross-functional teams, and participate in coding, testing, and deployment', 'Java, MySQL, HTML/CSS, Problem Solving', 'Mumbai, India', 34000.00, 400000.00, 'full-time', 'senior', '2025-08-04', 'pending', '2025-08-10 15:18:19'),
	(18, 'JOB4173', 'comp011', 'admin011', 'Senior Frontend Developer', 'Build modern, responsive web applications.', 'SkyNet seeks an experienced frontend developer skilled in React, JavaScript, and UI/UX design principles. You will lead UI projects, mentor juniors, and work with backend teams to deliver scalable applications.', 'React, JavaScript, Tailwind CSS, REST APIs', 'Bangalore, India', 25000.00, 120000.00, 'full-time', 'junior', '2025-08-06', 'pending', '2025-08-10 15:21:04'),
	(19, 'JOB8087', 'comp011', 'admin011', 'Cloud Solutions Architect', 'Design and deploy secure, scalable cloud systems.', 'SkyNet hiring a Cloud Solutions Architect to create cloud migration strategies, manage deployment pipelines, and ensure system reliability using AWS, Azure, or GCP.', 'AWS, Azure, GCP, DevOps, Kubernetes', 'Hyderabad, India', 40000.00, 320000.00, 'full-time', 'junior', '2025-08-13', 'pending', '2025-08-10 15:23:02');

-- Dumping structure for table job_portal.jobseekers
CREATE TABLE IF NOT EXISTS `jobseekers` (
  `sr_no` int NOT NULL AUTO_INCREMENT,
  `jobseeker_id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `resume` varchar(255) DEFAULT NULL,
  `specialization` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL,
  `education` varchar(100) DEFAULT NULL,
  `role` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `skills` text,
  `experience_level` enum('fresher','junior','mid','senior') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `bio` text,
  PRIMARY KEY (`sr_no`),
  UNIQUE KEY `jobseeker_id` (`jobseeker_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table job_portal.jobseekers: ~9 rows (approximately)
INSERT INTO `jobseekers` (`sr_no`, `jobseeker_id`, `name`, `email`, `password`, `phone`, `resume`, `specialization`, `course`, `education`, `role`, `skills`, `experience_level`, `created_at`, `bio`) VALUES
	(1, 'JS7808', 'Nishant', 'nk@gmail.com', '123456', '9456789246', NULL, 'Computer Science Engineering', 'PhD - Engineering', 'Masters/Post Graduation', NULL, NULL, 'fresher', '2025-08-07 10:02:33', 'Developing software, solving problems, and learning every day'),
	(2, 'JS5602', 'Prashant', 'prashant@gmail.com', '123456', '9693625867', '../uploads/resumes/JS5602_1754566829.pdf', 'Computer Science', 'BCA', 'Graduation/Diploma', 'Web designer', 'Abc', 'fresher', '2025-08-07 10:05:14', 'Developing software, solving problems, and learning every day'),
	(9, 'JS7222', 'prashant', 'pk@abc.com', '123456', '9456789244', '../uploads/resumes/1754657699_prashant kumar.pdf', 'Computer Science Engineering', 'BCA', 'Doctorate/PHD', 'Software Developer', 'HTML, CSS, JavaScript, Python, Java, MongoDB,C', 'fresher', '2025-08-07 10:44:41', 'Developing software, solving problems, and learning every day'),
	(11, 'JS8034', 'Chandan', 'chandan@gmail.com', '123456', '9693625834', '../uploads/resumes/JS8034_1754571869.pdf', 'Computer Science and Engineering', 'B.Tech', 'Masters/Post Graduation', 'Frontend Developer', 'HTML, CSS, JavaScript, PHP, MySQL', 'fresher', '2025-08-07 13:03:27', 'Developing software, solving problems, and learning every day'),
	(12, 'JS6027', 'Harsh', 'harsh+2@gmail.com', '123456', '9876543211', '../uploads/resumes/JS6027_1754576746.pdf', 'Computer Science Engineering', 'B.Tech', 'Graduation/Diploma', 'Software Developer', 'java , python', 'junior', '2025-08-07 14:25:23', 'Developing software, solving problems, and learning every day'),
	(13, 'JS8642', 'Prashant Bhardwaj', 'prashantkumarbgs22@gmail.com', '123456', '9998936490', '../uploads/resumes/JS8642_1754585071.pdf', 'Computer Science Engineering', 'B.Tech', 'Graduation/Diploma', 'Full Stack Developer', 'HTML, CSS, JavaScript, PHP, MySQL', 'junior', '2025-08-07 16:42:44', 'Developing software, solving problems, and learning every day'),
	(14, 'JS5237', 'Prashant Bhardwaj', 'bhardwaj@gmail.com', '123456', '9693625814', '../uploads/resumes/JS5237_1754638559.pdf', 'Computer Science and Engineering', 'B.Tech', 'Graduation/Diploma', 'Full Stack Developer', 'HTML, CSS, JavaScript, PHP, MySQL', 'junior', '2025-08-08 07:35:08', 'Developing software, solving problems, and learning every day'),
	(15, 'JS8544', 'Ravi Kumar', 'ravi@gmail.com', '123456', '9693625767', '../uploads/resumes/JS8544_1754754709.pdf', 'Computer Science Engineering', 'B.Tech', 'Graduation/Diploma', 'Software Developer', 'HTML, CSS, JavaScript Telwind, PHP, MySQL', 'mid', '2025-08-09 15:19:33', 'Developing software, solving problems, and learning every day');

-- Dumping structure for table job_portal.saved_jobs
CREATE TABLE IF NOT EXISTS `saved_jobs` (
  `sr_no` int NOT NULL AUTO_INCREMENT,
  `saved_job_id` varchar(100) NOT NULL,
  `jobseeker_id` varchar(100) NOT NULL,
  `job_id` varchar(100) NOT NULL,
  `saved_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sr_no`),
  UNIQUE KEY `saved_job_id` (`saved_job_id`),
  KEY `jobseeker_id` (`jobseeker_id`),
  KEY `job_id` (`job_id`),
  CONSTRAINT `saved_jobs_ibfk_1` FOREIGN KEY (`jobseeker_id`) REFERENCES `jobseekers` (`jobseeker_id`) ON DELETE CASCADE,
  CONSTRAINT `saved_jobs_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table job_portal.saved_jobs: ~3 rows (approximately)
INSERT INTO `saved_jobs` (`sr_no`, `saved_job_id`, `jobseeker_id`, `job_id`, `saved_at`) VALUES
	(14, 'SV1480', 'JS5237', 'JOB1754496816', '2025-08-10 07:30:53'),
	(15, 'SV9449', 'JS5237', 'JOB1754496626', '2025-08-10 07:30:56'),
	(16, 'SV7001', 'JS5237', 'JOB1754496529', '2025-08-10 07:31:00'),
	(17, 'SV3746', 'JS5237', 'JOB8673', '2025-08-10 16:23:00');

-- Dumping structure for table job_portal.superadmins
CREATE TABLE IF NOT EXISTS `superadmins` (
  `sr_no` int NOT NULL AUTO_INCREMENT,
  `superadmin_id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sr_no`),
  UNIQUE KEY `superadmin_id` (`superadmin_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table job_portal.superadmins: ~0 rows (approximately)
INSERT INTO `superadmins` (`sr_no`, `superadmin_id`, `name`, `email`, `password`, `created_at`) VALUES
	(1, 'SA1001', 'Prashant', 'admin@gmail.com', 'admin123', '2025-08-05 03:54:25');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
