-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2019 at 06:59 PM
-- Server version: 10.1.35-MariaDB
-- PHP Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `note_maker`
--

-- --------------------------------------------------------

--
-- Table structure for table `note_adminmaster`
--

CREATE TABLE `note_adminmaster` (
  `Id` int(10) UNSIGNED NOT NULL,
  `RoleId` tinyint(2) UNSIGNED NOT NULL DEFAULT '2',
  `Email` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Password` varchar(1000) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Salt` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `Address` varchar(500) NOT NULL,
  `Image` varchar(200) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `MobileNo` varchar(20) NOT NULL,
  `Status` enum('Enable','Disable','Delete') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'Enable',
  `CreatedDate` datetime NOT NULL,
  `CreatedIp` int(10) UNSIGNED NOT NULL,
  `CreatedBy` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `note_adminmaster`
--

INSERT INTO `note_adminmaster` (`Id`, `RoleId`, `Email`, `Password`, `Salt`, `Name`, `Address`, `Image`, `MobileNo`, `Status`, `CreatedDate`, `CreatedIp`, `CreatedBy`) VALUES
(1, 1, 'admin@admin.com', '84fc63c9b51fb5c37444bd8fdd6ed7bc7cd681d6fc23d04cedd3b3854c4c4539', 'd9c', 'Admin', 'Address Shop No 10, Lake Primrose shopping complex, IRB Road, Chandivali Powai, Mumbai - 400076, Maharashtra, India.         ', '‪+91_84_60_503062‬_20140805_224104.jpg', '8460503062', 'Enable', '2017-01-20 00:00:00', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `note_admin_forgotpassword`
--

CREATE TABLE `note_admin_forgotpassword` (
  `UserId` int(10) UNSIGNED NOT NULL,
  `Token` varchar(200) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `CreatedDate` datetime NOT NULL,
  `CreatedIp` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `note_category`
--

CREATE TABLE `note_category` (
  `Id` int(10) UNSIGNED NOT NULL,
  `CategoryName` varchar(200) NOT NULL,
  `Status` enum('Enable','Disable','Delete') NOT NULL,
  `CreatedDate` datetime NOT NULL,
  `CreatedIp` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `note_logs`
--

CREATE TABLE `note_logs` (
  `Id` int(100) NOT NULL,
  `Details` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Action` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `TableName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `RecordId` int(10) UNSIGNED DEFAULT NULL,
  `Type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `UserId` int(100) UNSIGNED DEFAULT NULL COMMENT '(AdminId or CustomerId)',
  `Status` enum('Enable','Disable','Delete') COLLATE utf8_unicode_ci NOT NULL,
  `CreatedDate` datetime NOT NULL,
  `CreatedIp` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `note_mailsettings`
--

CREATE TABLE `note_mailsettings` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Title` varchar(100) NOT NULL,
  `Subject` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `FromText` varchar(100) NOT NULL,
  `Content` text NOT NULL,
  `Status` enum('Enable','Disable','Delete') NOT NULL DEFAULT 'Enable',
  `CreatedIp` int(10) UNSIGNED NOT NULL,
  `CreatedBy` int(10) UNSIGNED NOT NULL,
  `CreatedDate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `note_mailsettings`
--

INSERT INTO `note_mailsettings` (`Id`, `Title`, `Subject`, `Email`, `FromText`, `Content`, `Status`, `CreatedIp`, `CreatedBy`, `CreatedDate`) VALUES
(1, 'Admin Forgot Password', 'Notemaker: Reset your password', 'info@notemaker.com', 'Fraazo', '<p style=\"margin:5px auto;text-align: center;font-size:36px; font-weight:bold; \">Fraazo</p>\r\n\r\n<div style=\"font-family: arial, sans-serif; line-height: 20.99431800842285px; text-align: center; color: rgb(129, 129, 129); margin: 20px 0px 0px; font-size: 16px;\">\r\n<h2 style=\"color: rgb(50, 54, 69); margin-top: 0px;\">Password reset request</h2>\r\n\r\n<p>We received a request to change password for your [SITE_NAME] account.<br />\r\nTo change your password please click reset password button below:</p>\r\n\r\n<table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin: 40px auto 20px;\">\r\n	<tbody>\r\n		<tr>\r\n			<td align=\"center\" bgcolor=\"#5e94ff\" height=\"40\" style=\"font-family: arial, sans-serif; margin: 0px; display: block; font-size: 16px; line-height: 40px; border-bottom-width: 3px; border-bottom-style: solid; border-bottom-color: #5e94ff; font-weight: bold; border-top-left-radius: 2px; border-top-right-radius: 2px; border-bottom-right-radius: 2px; border-bottom-left-radius: 2px;\" width=\"200\"><a href=\"[LINK]\" style=\"color: rgb(255, 255, 255); text-decoration: none; width: 200px; display: inline-block;\" target=\"_blank\">Reset password</a></td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<p><small>If you didn&#39;t request password reset simply ignore this email.</small></p>\r\n</div>\r\n\r\n<div style=\"font-family: arial, sans-serif; font-size: 13.63636302947998px; line-height: 20.99431800842285px; text-align: center; color: rgb(129, 129, 129); padding-top: 20px;\"><small>Password reset link:&nbsp;[LINK]</small></div>\r\n', 'Enable', 0, 1, '0000-00-00 00:00:00'),
(4, 'Confirm Email', 'Notemaker - Confirm your email to get started', 'info@notemaker.com', 'Notemaker', '<p style=\"margin:5px auto;text-align: center\"><img alt=\"\" src=\"[SITE_LOGO]\" style=\"width: 150px; height: 52px;\" /></p>\r\n\r\n<div style=\"font-family: arial, sans-serif; line-height: 20.99431800842285px; text-align: center; color: rgb(129, 129, 129); margin: 20px 0px 0px; font-size: 16px;\">\r\n<h2 style=\"color: rgb(50, 54, 69); margin-top: 0px;\">Confirm Your Email Address</h2>\r\n\r\n<p>To start using with [SITE_NAME] you need to confirm your email address.</p>\r\n\r\n<table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin: 40px auto 20px;\">\r\n	<tbody>\r\n		<tr>\r\n			<td align=\"center\" bgcolor=\"#5e94ff\" height=\"40\" style=\"font-family: arial, sans-serif; margin: 0px; display: block; font-size: 16px; line-height: 40px; border-bottom-width: 3px; border-bottom-style: solid; border-bottom-color: #5e94ff; font-weight: bold; border-top-left-radius: 2px; border-top-right-radius: 2px; border-bottom-right-radius: 2px; border-bottom-left-radius: 2px;\" width=\"200\"><a href=\"[LINK]\" style=\"color: rgb(255, 255, 255); text-decoration: none; width: 200px; display: inline-block;\" target=\"_blank\">Confirm email</a></td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<p><small>If you didn&#39;t sign up for [SITE_NAME] simply ignore this email.</small></p>\r\n</div>\r\n\r\n<div style=\"font-family: arial, sans-serif; font-size: 13.63636302947998px; line-height: 20.99431800842285px; text-align: center; color: rgb(129, 129, 129); padding-top: 20px;\"><small>Confirmation link:&nbsp;[LINK]</small></div>\r\n', 'Enable', 0, 1, '0000-00-00 00:00:00'),
(2, 'User Forgot Password', 'Notemaker: Forgot password', 'info@notemaker.com', 'Fraazo', '<p style=\"margin:5px auto;text-align: center;font-size:36px; font-weight:bold; \">Fraazo</p><div style=\"font-family: arial, sans-serif; line-height: 20.99431800842285px; text-align: center; color: rgb(129, 129, 129); margin: 20px 0px 0px; font-size: 16px;\"><h2 style=\"color: rgb(50, 54, 69); margin-top: 0px;\">Password reset request</h2><p>We received a request to change password for your [SITE_NAME] account.<br />Your password is shown in below. You can try login with this password:</p><table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin: 40px auto 20px;\">	<tbody>		<tr>			<td align=\"center\" bgcolor=\"#5e94ff\" height=\"40\" style=\"font-family: arial, sans-serif; margin: 0px; display: block; font-size: 16px; line-height: 40px; border-bottom-width: 3px; border-bottom-style: solid; border-bottom-color: #5e94ff; font-weight: bold; border-top-left-radius: 2px; border-top-right-radius: 2px; border-bottom-right-radius: 2px; border-bottom-left-radius: 2px;\" width=\"200\"><a href=\"[LINK]\" style=\"color: rgb(255, 255, 255); text-decoration: none; width: 200px; display: inline-block;\" target=\"_blank\">[PASSWORD]</a></td>		</tr>	</tbody></table><p><small>If you didn&#39;t request password reset simply ignore this email.</small></p></div><div style=\"font-family: arial, sans-serif; font-size: 13.63636302947998px; line-height: 20.99431800842285px; text-align: center; color: rgb(129, 129, 129); padding-top: 20px;\">&nbsp;</div>', 'Enable', 0, 1, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `note_setting`
--

CREATE TABLE `note_setting` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Fieldname` varchar(200) NOT NULL,
  `Keytext` varchar(200) NOT NULL,
  `Value` text,
  `Setting_type` enum('General','Social_Seo','Email','Other','notseen') NOT NULL,
  `Status` enum('Enable','Disable') NOT NULL DEFAULT 'Enable',
  `CreatedDate` datetime NOT NULL,
  `CreatedIp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `note_setting`
--

INSERT INTO `note_setting` (`Id`, `Fieldname`, `Keytext`, `Value`, `Setting_type`, `Status`, `CreatedDate`, `CreatedIp`) VALUES
(1, 'Site Name', 'site_name', 'Freelance', 'General', 'Enable', '0000-00-00 00:00:00', 0),
(2, 'Contact Us Reciever E-Mail', 'site_email', 'info@freelance.com', 'General', 'Enable', '0000-00-00 00:00:00', 0),
(3, 'Admin Row Per Page Limit', 'site_admin_rowperpage', '20', 'General', 'Enable', '0000-00-00 00:00:00', 0),
(4, 'Copyright Text', 'site_copyright', '© Freelance', 'General', 'Enable', '0000-00-00 00:00:00', 0),
(5, 'Live/Test', 'live_test', '1', 'notseen', 'Disable', '0000-00-00 00:00:00', 1),
(6, 'Meta Keyword', 'meta_keyword', 'Meta Keyword', 'Social_Seo', 'Enable', '0000-00-00 00:00:00', 0),
(7, 'Meta Description', 'meta_description', 'Meta Description', 'Social_Seo', 'Enable', '0000-00-00 00:00:00', 0),
(8, 'Smtp Email', 'site_smtp_email', 'info@kpswebsolution.com', 'Email', 'Enable', '0000-00-00 00:00:00', 0),
(9, 'Smtp Password', 'site_smtp_pwd', 'kpsweb@2018', 'Email', 'Enable', '0000-00-00 00:00:00', 0),
(10, 'SMTP Host Name', 'smtp_host', 'kpswebsolution.com', 'Email', 'Enable', '0000-00-00 00:00:00', 0),
(11, 'SMTP Port', 'smtp_port', '25', 'Email', 'Enable', '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `note_users`
--

CREATE TABLE `note_users` (
  `Id` int(10) UNSIGNED NOT NULL,
  `FirstName` varchar(200) DEFAULT NULL,
  `LastName` varchar(200) DEFAULT NULL,
  `Email` varchar(200) NOT NULL,
  `Password` varchar(200) NOT NULL,
  `Salt` varchar(10) NOT NULL,
  `Image` varchar(200) NOT NULL,
  `Type` enum('App','Facebook','Google') NOT NULL,
  `Status` enum('Pending','Enable','Disable','Delete') NOT NULL,
  `ConfirmDate` date NOT NULL,
  `CreatedDate` datetime DEFAULT NULL,
  `CreatedIp` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `note_user_confirmation`
--

CREATE TABLE `note_user_confirmation` (
  `UserId` int(10) UNSIGNED NOT NULL,
  `Token` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `note_user_confirmation`
--

INSERT INTO `note_user_confirmation` (`UserId`, `Token`) VALUES
(1, 'aSnl9Pp5'),
(2, '2uV0hF3e'),
(3, 'K35FpQn0'),
(4, 'QaTs98yf'),
(5, 'sTGmfaTP'),
(6, 'JKXvOZzB'),
(7, 'JU9igHtL'),
(8, 'g7TKjO7X'),
(9, 'r6yavj01'),
(10, 'R12Jk9L3'),
(11, '4L73qux6'),
(12, 'kp2payun'),
(13, '7bXgqQrL'),
(14, 'gUX53aTP'),
(15, 'zEpvOgYS'),
(16, 'hukqAVrB'),
(17, 'p55Gt5i7');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `note_adminmaster`
--
ALTER TABLE `note_adminmaster`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `note_admin_forgotpassword`
--
ALTER TABLE `note_admin_forgotpassword`
  ADD PRIMARY KEY (`UserId`);

--
-- Indexes for table `note_category`
--
ALTER TABLE `note_category`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `note_logs`
--
ALTER TABLE `note_logs`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `note_mailsettings`
--
ALTER TABLE `note_mailsettings`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `note_setting`
--
ALTER TABLE `note_setting`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `note_users`
--
ALTER TABLE `note_users`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `note_user_confirmation`
--
ALTER TABLE `note_user_confirmation`
  ADD PRIMARY KEY (`UserId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `note_adminmaster`
--
ALTER TABLE `note_adminmaster`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `note_category`
--
ALTER TABLE `note_category`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `note_logs`
--
ALTER TABLE `note_logs`
  MODIFY `Id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `note_mailsettings`
--
ALTER TABLE `note_mailsettings`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `note_setting`
--
ALTER TABLE `note_setting`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `note_users`
--
ALTER TABLE `note_users`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
