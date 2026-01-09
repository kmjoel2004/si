-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 09, 2026 at 08:49 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aspironet_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_settings`
--

CREATE TABLE `admin_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_settings`
--

INSERT INTO `admin_settings` (`id`, `setting_key`, `setting_value`, `created_at`) VALUES
(1, 'site_name', 'Aspironet Solutions', '2026-01-08 12:33:14'),
(2, 'contact_email', 'info@aspironet.com', '2026-01-08 12:33:14'),
(3, 'contact_phone', '(123) 456-7890', '2026-01-08 12:33:14'),
(4, 'site_address', '123 Tech Street, City, State 12345', '2026-01-08 12:33:14'),
(5, 'admin_email', 'admin@aspironet.in', '2026-01-08 12:33:14');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `mode` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT 0.00,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `category`, `description`, `duration`, `mode`, `price`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Full Stack Development', 'Technology', 'Learn full stack web development with modern frameworks', '12 Weeks', 'Online', 499.99, 'active', '2026-01-08 12:33:14', '2026-01-08 12:33:14'),
(2, 'Data Science & AI', 'Technology', 'Master data science, machine learning and artificial intelligence', '16 Weeks', 'Hybrid', 599.99, 'active', '2026-01-08 12:33:14', '2026-01-08 12:33:14'),
(3, 'Cloud Computing (AWS/Azure)', 'Technology', 'Cloud computing certification and deployment strategies', '10 Weeks', 'Online', 449.99, 'active', '2026-01-08 12:33:14', '2026-01-08 12:33:14'),
(4, 'Leadership Excellence', 'Corporate', 'Develop leadership skills for organizational success', '6 Weeks', 'Offline', 399.99, 'active', '2026-01-08 12:33:14', '2026-01-08 12:33:14'),
(5, 'Cybersecurity Fundamentals', 'Technology', 'Learn essential cybersecurity concepts and practices', '8 Weeks', 'Online', 549.99, 'active', '2026-01-08 12:33:14', '2026-01-08 12:33:14'),
(6, 'Project Management', 'Corporate', 'Master project management methodologies and tools', '10 Weeks', 'Hybrid', 449.99, 'active', '2026-01-08 12:33:14', '2026-01-08 12:33:14');

-- --------------------------------------------------------

--
-- Table structure for table `email_settings`
--

CREATE TABLE `email_settings` (
  `id` int(11) NOT NULL,
  `company_email` varchar(100) NOT NULL,
  `email_subject` varchar(200) NOT NULL,
  `email_template` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_settings`
--

INSERT INTO `email_settings` (`id`, `company_email`, `email_subject`, `email_template`, `created_at`, `updated_at`) VALUES
(1, 'info@aspironet.com', 'New Course Enrollment - Aspironet Solutions', 'A new student has enrolled in a course.\n\nStudent Details:\nName: {student_name}\nEmail: {student_email}\nPhone: {student_phone}\nCourse: {course_title}\nEnrollment Date: {enrollment_date}\n\nPlease contact the student to proceed with the enrollment process.', '2026-01-08 13:08:19', '2026-01-08 13:08:19');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `course_id` int(11) NOT NULL,
  `enrollment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','completed','cancelled') DEFAULT 'active',
  `email_sent` tinyint(1) DEFAULT 0,
  `enrollment_code` varchar(50) DEFAULT NULL,
  `payment_status` enum('pending','paid','cancelled') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `google_id` varchar(100) DEFAULT NULL,
  `user_type` enum('student','professional','corporate') DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `address`, `profile_picture`, `bio`, `password`, `google_id`, `user_type`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@aspironet.com', NULL, NULL, NULL, NULL, '$2y$10$8habP2MXfr1ChCoBg6nXoeHGSlZ.HUsnyWG6bcj8rGjHf4vMJi87u', NULL, 'student', '2026-01-08 12:33:14', '2026-01-08 12:33:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_settings`
--
ALTER TABLE `admin_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_settings`
--
ALTER TABLE `email_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_settings`
--
ALTER TABLE `admin_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `email_settings`
--
ALTER TABLE `email_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
