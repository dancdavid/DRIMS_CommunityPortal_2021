-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: infongd-us1182.rtr.schlund.de
-- Generation Time: Mar 02, 2020 at 12:55 PM
-- Server version: 5.5.60-0+deb7u1
-- PHP Version: 7.0.33-0+deb9u7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db667279724`
--

-- --------------------------------------------------------

--
-- Table structure for table `agency_logo`
--

CREATE TABLE `agency_logo` (
  `agency_id` int(11) NOT NULL,
  `filename` varchar(200) NOT NULL,
  `type` varchar(100) NOT NULL,
  `date_uploaded` datetime NOT NULL,
  `uploaded_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `agency_logo`
--

INSERT INTO cp_agency_logo (`agency_id`, `filename`, `type`, `date_uploaded`, `uploaded_by`) VALUES
(5, 'Trac_multi_rgb.jpg', 'image/jpeg', '2017-09-25 22:59:30', '8'),
(20, 'LPG_Logo.jpg', 'image/jpeg', '2018-08-02 15:22:44', '159'),
(21, 'DRIMS_logo_20191001_250px.jpg', 'image/jpeg', '2020-02-09 18:21:39', '128'),
(39, 'ccht_logo_copy.jpg', 'image/jpeg', '2017-09-20 14:50:53', '149'),
(53, 'UHN_LOGO_bigger.jpg', 'image/jpeg', '2017-09-27 15:03:16', '465'),
(91, 'Bayou_Community_Foundation_Logo_FINAL_color.jpg', 'image/jpeg', '2017-09-26 17:29:07', '499');

-- --------------------------------------------------------

--
-- Table structure for table `committee_contact`
--

CREATE TABLE `committee_contact` (
  `committee_contact_id` int(10) NOT NULL,
  `committee_id` varchar(255) NOT NULL DEFAULT '',
  `contact_id` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `committee_contact`
--

INSERT INTO cp_committee_contact (`committee_contact_id`, `committee_id`, `contact_id`) VALUES
(16, '2', '89'),
(128, '12', '266'),
(9, '2', '74'),
(7, '2', '95'),
(5, '2', '41'),
(4, '2', '19'),
(3, '2', '87'),
(105, '2', '149'),
(100, '5', '289'),
(29, '4', '8'),
(99, '9', '214'),
(126, '11', '128'),
(22, '3', '74'),
(21, '3', '19'),
(34, '3', '8'),
(93, '7', '128'),
(37, '3', '139'),
(92, '6', '8'),
(49, '2', '200'),
(127, '11', '149'),
(70, '5', '139'),
(69, '5', '8'),
(79, '5', '200'),
(80, '5', '149'),
(89, '2', '8'),
(123, '6', '128'),
(129, '12', '492'),
(124, '8', '128'),
(125, '8', '8'),
(130, '12', '470'),
(131, '2', '128'),
(132, '2', '128'),
(133, '11', '8'),
(134, '9', '8'),
(135, '12', '128'),
(136, '7', '8'),
(137, '12', '515');

-- --------------------------------------------------------

--
-- Table structure for table `committee_directory`
--

CREATE TABLE `committee_directory` (
  `committee_id` int(10) NOT NULL,
  `committee_name` varchar(255) NOT NULL DEFAULT '',
  `committee_description` longtext NOT NULL,
  `email1` varchar(255) NOT NULL DEFAULT '',
  `email2` varchar(255) NOT NULL DEFAULT '',
  `email3` varchar(255) NOT NULL DEFAULT '',
  `email4` varchar(255) NOT NULL DEFAULT '',
  `email5` varchar(255) NOT NULL DEFAULT '',
  `email6` varchar(255) NOT NULL DEFAULT '',
  `email7` varchar(255) NOT NULL DEFAULT '',
  `email8` varchar(255) NOT NULL DEFAULT '',
  `email9` varchar(255) NOT NULL DEFAULT '',
  `email10` varchar(255) NOT NULL DEFAULT '',
  `email11` varchar(255) NOT NULL DEFAULT '',
  `email12` varchar(255) NOT NULL DEFAULT '',
  `email13` varchar(255) NOT NULL DEFAULT '',
  `email14` varchar(255) NOT NULL DEFAULT '',
  `email15` varchar(255) NOT NULL DEFAULT '',
  `email16` varchar(255) NOT NULL DEFAULT '',
  `email17` varchar(255) NOT NULL DEFAULT '',
  `email18` varchar(255) NOT NULL DEFAULT '',
  `email19` varchar(255) NOT NULL DEFAULT '',
  `email20` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `committee_directory`
--

INSERT INTO cp_committee_directory (`committee_id`, `committee_name`, `committee_description`, `email1`, `email2`, `email3`, `email4`, `email5`, `email6`, `email7`, `email8`, `email9`, `email10`, `email11`, `email12`, `email13`, `email14`, `email15`, `email16`, `email17`, `email18`, `email19`, `email20`) VALUES
(6, 'UNMET NEEDS COMMITTEE', 'Needs assessment , needs review, and recovery fulfilment of\"unmet needs\" from Hurricanes Katrina & Rita         \r\n\r\nFORMED:  January 18, 2006', 'pegcase@trac4la.com', 'ahgranier@uwsla.com', 'michelleliner@trac4la.com', 'brdupre@tpcg.org', 'rgorman@htdiocese.org', 'sdudek@htdiocese.org', 'donaldnaquin@bellsouth.net', '1stpresb@bellsouth.net', 'dianae@tcoa-la.org', 'stormrelief3@bellsouth.net', 'maryaldon3@yahoo.com', ' Geraldine.Blazek@dhs.gov', 'dcc1@mobiletel.com', 'kelleylynda@yahoo.com', 'joan.lewin@dhs.gov', 'mybisco@yahoo.com', 'pointauxchenes@mds.mennonite.net', 'courtneypellegrin@yahoo.com', 'simo7977@bellsouth.net', 'nicetoserve@yahoo.com');

-- --------------------------------------------------------

--
-- Table structure for table `committee_list`
--

CREATE TABLE `committee_list` (
  `committee_id` int(10) NOT NULL,
  `committee_name` varchar(255) NOT NULL DEFAULT '',
  `committee_description` longtext NOT NULL,
  `committee_status` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `committee_list`
--

INSERT INTO cp_committee_list (`committee_id`, `committee_name`, `committee_description`, `committee_status`) VALUES
(4, 'CLIENT ADVOCACY', 'Committee consists of case managers working in long-term disaster relief', 'ACTIVE'),
(3, 'TRANSPORTATION COMMITTEE', 'Committee established to survey the evacuation needs in Terrebonne Parish. ', 'ACTIVE'),
(2, 'UNMET NEEDS COMMITTEE', 'Provides assistance for individuals/families that cannot reocver on their own.  Due to client  confidentiality issues only client advocates and giving agency representatives are allowed.  ', 'ACTIVE'),
(5, 'Volunteer Agency Emergency Operation Center', 'Committee established to develop the Emergency Operations Center for Terrebonne Parish', 'ACTIVE'),
(6, 'Technology Committee', 'This committee reviews technologies that can make disaster preparedness and recovery efficient and cost effective.', 'ACTIVE'),
(7, 'MEDIA', 'PRINT, TV, CABLE, RADIO MEDIA CONTACTS PROVIDING CRITICAL INFORMAITION TO THE COMMUNITIES AT LARGE', 'ACTIVE'),
(8, 'DONATIONS', 'Agencies working to provide and procedure donations for recovery', 'ACTIVE'),
(9, 'SHELTER AND EVACUATION', 'All agencies or indiviuduals interested in volunteering to help with the evacuation and or sheltering of Terrebonne parish. ', 'ACTIVE'),
(11, 'Here is the Judges Committee', 'description', 'ACTIVE'),
(12, 'Test Committee 2', 'Here is my test committee description', 'ACTIVE');

-- --------------------------------------------------------

--
-- Table structure for table `directory_agency`
--

CREATE TABLE `directory_agency` (
  `agency_id` int(10) NOT NULL,
  `agency_name` varchar(255) NOT NULL DEFAULT '',
  `agency_address` varchar(255) NOT NULL DEFAULT '',
  `agency_city` varchar(255) NOT NULL DEFAULT '',
  `agency_state` varchar(255) NOT NULL DEFAULT '',
  `agency_zipcode` varchar(255) NOT NULL DEFAULT '',
  `agency_telephone` varchar(255) NOT NULL DEFAULT '',
  `agency_fax` varchar(255) NOT NULL DEFAULT '',
  `agency_url` varchar(255) NOT NULL DEFAULT '',
  `agency_uname` varchar(255) NOT NULL DEFAULT '',
  `agency_password` varchar(255) NOT NULL DEFAULT '',
  `agency_sessionid` varchar(255) NOT NULL DEFAULT '',
  `user_type` varchar(255) NOT NULL DEFAULT '',
  `disaster_address` varchar(255) NOT NULL DEFAULT '',
  `disaster_city` varchar(255) NOT NULL DEFAULT '',
  `disaster_state` varchar(255) NOT NULL DEFAULT '',
  `disaster_zipcode` varchar(255) NOT NULL DEFAULT '',
  `a_longitude` varchar(255) NOT NULL DEFAULT '',
  `a_latitude` varchar(255) NOT NULL DEFAULT '',
  `agency_gps` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(50) DEFAULT NULL,
  `description` longtext
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `directory_agency`
--

INSERT INTO cp_directory_agency (`agency_id`, `agency_name`, `agency_address`, `agency_city`, `agency_state`, `agency_zipcode`, `agency_telephone`, `agency_fax`, `agency_url`, `agency_uname`, `agency_password`, `agency_sessionid`, `user_type`, `disaster_address`, `disaster_city`, `disaster_state`, `disaster_zipcode`, `a_longitude`, `a_latitude`, `agency_gps`, `status`, `description`) VALUES
(46, 'Houma Elks Lodge 1193', '7883 Main Street', 'Houma', 'LA', '70360', '985.876.5784', '', 'http://test', 'barr', 'recovery', '256647', 'BASIC', '', '', '', '', '-90.721326', '29.597476', 'N29?35.83854, W090?43.36266', 'ACTIVE', NULL),
(44, 'Bayou Grace Community Services', '5228 B Hwy 56', 'Chauvin', 'LA', '70344', '985.594.5350', '', 'www.bayougrace.org', 'barr', 'recovery', '785369', 'BASIC', '', '', '', '', '', '', 'N29?27.78714, W090?35.361', 'ACTIVE', NULL),
(42, 'FEMA - Federal Emergency Management Agency', 'Individual Assistance Branch', 'Baton Rouge', 'LA', '', '800.621.FEMA', '225.334.7768', 'http://www.fema.gov', 'barr', 'recovery', '666547', 'BASIC', '', '', '', '', '', '', '', 'ACTIVE', NULL),
(41, 'Dularge Volunteer Fire Dept.', '1038 Falgout Canal Road', 'Theriot', 'LA', '70397', '985.872.0976', '985.872.0977', 'http://', 'barr', 'recovery', '911365', 'BASIC', '', '', '', '', '', '', 'N29?24.58476, W090?46.79508', 'ACTIVE', NULL),
(40, 'Dulac Community Center', '125 Coast Guard Road', 'Dulac', 'LA', '70353', '985.563.7483', '985.563.7826', 'http://dulaccommunitycenter.org', 'barr', 'recovery', '111456', 'BASIC', '', '', '', '', '', '', 'N29?22.40124, W090?42.90846', 'ACTIVE', NULL),
(38, 'BISCO (Bayou Interfaith Shared Community Organizations)', '3002 Poplar Cir', 'Houma', 'LA', '70363', '985-228-0577', '985-228-0577', 'http://bisco-la.org', 'barr', 'recovery', '297314', 'BASIC', '', '', '', '', '', '', 'N29?48.73272, W090?52.03152', 'ACTIVE', ''),
(37, 'Bayou Emergency Amateur Radio Service', '708 Front', 'Morgan City', 'LA', '', '985.385.0730', '', 'http://', 'barr', 'recovery', '123987', 'BASIC', '', '', '', '', '-91.210361', '29.695675', 'N29?41.74896, W091?12.61758', 'ACTIVE', NULL),
(36, 'Bayou Cane Fire Dept.', '6166 W. Main Street', 'Houma', 'LA', '70360', '985.580.7230', '985.580.7238', 'http://www.bayoucanefd.org', 'barr', 'recovery', '562314', 'BASIC', '', '', '', '', '-90.755227', '29.628661', 'N29?37.71438, W090?45.31602', 'ACTIVE', NULL),
(35, 'Bayou Area Habitat For Humanity', '1087 Hwy 3185 / PO Box 691', 'Thibodaux', 'LA', '70302', '985.447.6999', '985.447.5167', 'http://www.bayouhabitat.org', 'barr', 'recovery', '782231', 'BASIC', '', '', '', '', '-90.782482', '29.730500', 'N29?47.15376, W090?51.49344', 'ACTIVE', NULL),
(34, 'Terrebonne Council on Aging, Inc.', '995 West Tunnel Road', 'Houma', 'LA', '70360', '985-868-8411', '985-868-7806', 'http://www.terrebonnecoa.org/', 'barr', 'recovery', '985471', 'BASIC', '', '', '', '', '', '', 'N29?35.5419, W090?44.12814', 'ACTIVE', ''),
(33, 'American Red Cross - Southeast Louisiana Chapter - Bayou / River Region', '171 Keller Street', 'Hahnville', 'LA', '70057', '985-331-3027', '', 'http://www.redcross.org', 'barr', 'recovery', '789521', 'BASIC', '', '', '', '', '', '', 'N29?47.7312, W090?49.35174', 'ACTIVE', NULL),
(32, 'Terrebonne Parish Shelter Coordinator', '1115 Franklin Avenue', 'Houma', 'LA', '70364', '985.851.7057', '', 'http://', 'barr', 'recovery', '698514', 'BASIC', '', '', '', '', '-90.726788', '29.607805', 'N29?36.51846, W090?43.62342', 'ACTIVE', NULL),
(31, 'Options For Independence', '5593 Hwy 311', 'Houma', 'LA', '70363', '985.868.2620', '985.868.8547', 'http://', 'barr', 'recovery', '589147', 'BASIC', '', '', '', '', '', '', 'N29?35.5344, W090?44.11374', 'ACTIVE', NULL),
(28, 'MIT-SIGUS Housing Design Team', 'Room 7-337', 'Cambridge', 'MA', '02139', '', '', 'http://web.mit.edu/sigus', 'barr', 'recovery', '123478', 'BASIC', '', '', '', '', '-71.100830', '42.373725', 'N42?22.0074, W071?6.36114', 'ACTIVE', NULL),
(25, 'Louisiana Interchurch Conference', '527 North Blvd., 4th Floor', 'Baton Rouge', 'LA', '70802', '225.344.0134', '225.344.0142', 'http://www.lainterchurch.org', 'barr', 'recovery', '548921', 'BASIC', '', '', '', '', '-91.187147', '30.451779', 'N30?26.84784, W091?11.10738', 'ACTIVE', NULL),
(23, 'Lafourche Parish Sheriff Office', '200 Patrick St', 'Thibodaux', 'LA', '70301', '985.449.4442', '985.447.1854', 'http://www.lpso.net', 'barr', 'recovery', '123985', 'BASIC', '', '', '', '', '', '', 'N29?47.7312, W090?49.35174', 'ACTIVE', NULL),
(22, 'Lafourche Parish Office of Community Action Agency', 'PO Box 425', 'Raceland', 'LA', '70375', '985.537.7683', '985.537.7707', 'http://www.lafourchegov.org/lafourchegov/OCA_Programs.aspx', 'barr', 'recovery', '564789', 'BASIC', '', '', '', '', '-90.5210565', '29.6778685', 'N29?42.68568, W090?35.70246', 'ACTIVE', NULL),
(21, 'DRIMS', '216 South Ave W', 'Missoula', 'MT', '59801', '888-374-6787', '888-374-6787', 'http://www.drims.org', 'browneyeblue', 'beb', '555664', 'ADMIN', '', '', '', '', '-78.784996', '35.635650', 'N35?38.11968, W078?47.07792', 'ACTIVE', 'WHAT IS DRIMS?\r\nDRIMS is a secure system that enables organizations and agencies to efficiently deploy people, equipment, materials, services and financial resources in a coordinated way to serve people in need. \r\n\r\nDRIMS also provides a single point of contact for people who need support. \r\n\r\nDRIMS can be used by organizations that support disasters, social work, homelessness, domestic violence, foster care, prisoner reintegration to society, pandemics and more.'),
(20, 'Lafourche Parish OEP', 'P.O. Box 425', 'Mathews', 'Louisiana', '70375', '985-537-7603', '985-532-8292', 'http://www.lafourchegov.org', 'barr', 'recovery', '332144', 'BASIC', '', '', '', '', '-90.529984', '29.717294', 'N29?42.68568, W090?35.70246', 'ACTIVE', ''),
(19, 'Salvation Army', '5539 West Main St', 'Houma', 'LA', '70363', '985-262-1871', '', 'http://www.salvationarmy.org', 'barr', 'recovery', '951847', 'BASIC', '', '', '', '', '-90.7622794', '29.6445362', '', 'ACTIVE', NULL),
(70, 'Terrebonne Parish Consolidated Government - FEDERAL PROGRAMS', '809 Barrow St', 'Houma', 'LA', '70360', '985-873-6591', '', 'http://', 'barr', 'recovery', '21342355666', 'BASIC', '', '', '', '', '-90.719916', '29.592539', '', 'ACTIVE', NULL),
(16, 'J2K - Ministerial Alliance', '522 Green Street', 'Thibodaux', 'LA', '70301', '985.447.7539', '985.447.1795', 'http://www.bayoupres.org', 'barr', 'recovery', '987654', 'BASIC', '', '', '', '', '-90.819939', '29.796151', 'N29?47.76492, W090?49.2048', 'ACTIVE', NULL),
(11, 'Louisiana Works Career Solutions Center, Work Connection, Inc.', '807 Barrow Street - Front Door 821 Bond Street', 'Houma', 'LA', '70360', '985-876-8990', '985-858-2985', 'http://', 'barr', 'recovery', '875541', 'BASIC', '', '', '', '', '', '', 'N29?35.5455, W090?43.18896', 'ACTIVE', ''),
(10, 'Voice of the Wetlands', '', '', 'LA', '', '985.790.0682', '', 'http://www.voiceofthewetlands.com', 'barr', 'recovery', '147825', 'BASIC', '', '', '', '', '-92.1450245', '31.2448234', '', 'ACTIVE', NULL),
(9, 'United Way - South Louisiana', '600 Academy Street / PO Box 1868', 'Houma', 'LA', '70360', '985-879-2461', '985-872-9615', 'http://www.uwsla.com', 'barr', 'recovery', '555419', 'BASIC', '', '', '', '', '-90.720582', '29.597704', 'N29?35.83026, W090?43.33122', 'ACTIVE', ''),
(7, 'United Houma Nation', '20986 Hwy 1', 'Golden Meadow', 'LA', '70357', '', '', 'http://www.unitedhoumanation.org', 'barr', 'recovery', '251436', 'BASIC', '', '', '', '', '-90.205608', '29.245600', 'N29?21.30288, W090?15.03606', 'ACTIVE', NULL),
(89, 'Lafourche Council on Aging, Inc', '4876 Hwy 1', 'Raceland', 'LA', '70394', '985-532-0457', '985-532-0462', 'http://www.lafourchecoa.org', '', '', '', 'BASIC', '', '', '', '', '', '', '', 'ACTIVE', NULL),
(5, 'TRAC - Terrebonne Readiness & Assistance Coalition', '1220 Aycock Street', 'Houma', 'LA', '70360', '985-851-2952', '', 'http://www.trac4la.com', 'trac', 'chocolate', '888999', 'ADMIN', '1220 Aycock St', 'Houma', 'LA', '70360', '-90.718588', '29.588868', 'N29?35.3256, W090?43.12542', 'ACTIVE', ''),
(4, 'Terrebonne Parish Consolidated Government', 'PO Box 2768', 'Houma', 'LA', '70361', '985.873.6569', '', 'http://www.tpcg.org', 'barr', 'recovery', '147654', 'BASIC', '', '', '', '', '-90.719700', '29.595600', 'N29?35.8398, W090?43.14774', 'ACTIVE', NULL),
(2, 'Terrebonne Parish Office Of Homeland Security And Emergency Preparednss', '101 Government Street', 'Gray', 'LA', '70359', '985-873-6357', '985-850-4643', 'http://www.tohsep.com', 'barr', 'recovery', '985632', 'BASIC', '', '', '', '', '', '', '', 'ACTIVE', ''),
(1, 'St. Vincent De Paul Society', '107 Point Street', 'Houma', 'LA', '70360', '985.872.9373', '', 'http://', 'barr', 'recovery', '582631', 'BASIC', '', '', '', '', '-90.725471', '29.593250', 'N29?35.59932, W090?43.52364', 'ACTIVE', NULL),
(47, 'Houma Fire Dept.', '600 Wood Street', 'Houma', 'LA', '70360', '985-873-6391', '985-873-6398', 'http://houmafire.com', 'barr', 'recovery', '888526', 'BASIC', '', '', '', '', '-90.721247', '29.594360', 'N29?35.65344, W090?43.26786', 'ACTIVE', ''),
(48, 'Knights of Columbus - Bourg Council & TRAC', '4009 Benton Drive', 'Bourg', 'LA', '70343', '985.872.0847', '985.872.0847', 'http://', 'barr', 'recovery', '851473', 'BASIC', '', '', '', '', '-90.640653', '29.557707', 'N29?33.45762, W090?38.41284', 'ACTIVE', NULL),
(51, 'Acadian Ambulance Service', '1018 Bond Street', 'Houma', 'LA', '70360', '985-637-0693', '985-876-8719', 'http://www.acadian.com', 'barr', 'recovery', '225478', 'BASIC', '', '', '', '', '-90.718250', '29.590755', 'N29?35.43732, W090?43.08684', 'ACTIVE', ''),
(82, 'LOUISIANA VOAD', '215 E. Pinhook Rd', 'Lafayette', 'LA', '70501', '(337) 706-1222', '(337) 233-8380 fax', 'http://www.lavoad.org', 'barr', 'recovery', '', 'BASIC', '', '', '', '', '-92.0077710', '30.2151420', '', 'ACTIVE', NULL),
(53, 'United Houma Nation', '20986 Hwy 1', 'Golden Meadow', 'LA', '70357', '985-223-3093', '985-223-3093', 'http://unitedhoumanation.org', 'barr', 'recovery', '222559', 'BASIC', '', '', '', '', '', '', 'N29?42.47802, W090?34.43268', 'ACTIVE', 'The United Houma Nation today is composed of a very proud and independent people who have close ties to the water and land of their ancestors. The unique history of our people has shaped our tribe today and the culture and way of life are a lifeline to that history'),
(54, 'Amateur Radio Emergency Services-Region 3', 'Remote Office', 'Houma', 'LA', '70364', '717-377-1987', '', 'http://laarrl.org/ares', 'barr', 'recovery', '988756', 'BASIC', '', '', '', '', '', '', 'N29?32.71926, W090?40.73916', 'ACTIVE', ''),
(55, 'Salvation Army Team Emergency Radio Network (SATERN)', '1607 Division Ave.', 'Houma', 'LA', '70360-6315', '(985) 217-4006', '', 'http://www.satern.org', 'barr', 'recovery', '362591', 'BASIC', '', '', '', '', '-90.7171350', '29.5849410', '', 'ACTIVE', NULL),
(56, 'Houma-Terrebonne Chamber of Commerce', '6133 Hwy. 311', 'Houma', 'LA', '70360', '985.876.5600', '985.876.5611', 'http://www.houmachamber.com', 'barr', 'recovery', '124789', 'BASIC', '', '', '', '', '', '', 'N29?34.87728, W090?43.41258', 'ACTIVE', NULL),
(59, 'Lafourche Parish Community Service', 'Lafourche Parish Government Complex (PO Box 425)', 'Mathews', 'LA', '70375', '985-537-7603', '985-537-7707', 'http://', 'barr', 'recovery', '842156', 'BASIC', '1612 Highway 182', 'Raceland', 'LA', '70394', '-90.5210565', '29.6778685', 'N29?42.68568, W090?35.70246', 'ACTIVE', NULL),
(58, 'Office of Public Health - Region 3 _LA Department of Health', '1434 Tiger Drive', 'Thibodaux', 'LA', '70301', '985.447.0916', '985.447.0920', 'http://10.57.130.228/', 'barr', 'recovery', '000589', 'BASIC', '', '', '', '', '', '', 'N29?46.25238, W090?50.76498', 'ACTIVE', NULL),
(60, 'Terrebonne Parish Sheriff Office', 'PO Box 1670/7856 Main Street, Courthouse Annex, Suite 121', 'Houma', 'LA', '70361', '985-876-2500', '985-857-0298', 'http://', 'barr', 'recovery', '98755264', 'BASIC', '', '', '', '', '', '', 'N29?35.81904, W090?43.40508', 'ACTIVE', NULL),
(64, 'Bayou Baptist Association', '4494 W. Main', 'Gray', 'LA', '70359', '985-868-7352', '985-872-3671', 'http://www.bayoubaptistassociation.com', 'barr', 'recovery', '897CG98723', 'BASIC', '', '', '', '', '', '', 'N29?35.13864, W090?43.37754', 'ACTIVE', ''),
(69, 'First United Methodist Church', '6109 Hwy 311', 'Houma', 'LA', '70360', '985-868-7787', '985-868-7086', 'http://www.fumchouma,org', 'barr', 'recovery', '00909877454', 'BASIC', '', '', '', '', '', '', '', 'ACTIVE', NULL),
(72, 'Terrebonne Parish Health Unit', '600 Polk Street', 'Houma', 'LA', '70360', '985-857-3601', '985-857-3607', 'http://', 'barr', 'recovery', '12345553321', 'BASIC', '', '', '', '', '-90.733838', '29.593787', '', 'ACTIVE', NULL),
(83, 'Project Learn LA Terre', '1570 Bayou Blue Road', 'Houma', 'LA', '70364', '985-226-8980', '', 'http://', 'barr', 'recovery', '', 'BASIC', '', '', '', '', '-90.6747652', '29.6321054', '', 'ACTIVE', NULL),
(76, 'Gulf Coast Teaching Family Services, Inc.', '320 Progressive Blvd.', 'Houma', 'LA', '70360-4069', '985-851-4488/800-947-7645', '985-872-0985', 'http://', 'barr', 'recovery', '89755521', 'BASIC', '', '', '', '', '-90.7369602', '29.5916465', '', 'ACTIVE', NULL),
(77, 'Isle de Jean Charles Band of Biloxi-Chitimacha', '100 Dennis Street', 'Montegut', 'LA', '70377', '985-232-1286', '', 'http://biloxi-chitimacha.com/isle_de_jean_charles_.htm', 'barr', 'recovery', '89445117', 'BASIC', '', '', '', '', '-90.5224454', '29.4942808', '', 'ACTIVE', NULL),
(91, 'Bayou Community Foundation', 'P.O. Box 582', 'Houma', 'LA', '70361', '985-790-1150', '', 'http://www.BayouCF.org', '', '', '', 'BASIC', '', '', '', '', '', '', '', 'ACTIVE', 'Bayou Community Foundation (BCF) is the only charitable foundation solely focused on building and sustaining the communities of Lafourche Parish, Terrebonne Parish, and Grand Isle, Louisiana.   BCF provides a way for donors to invest in our precious Bayou Region and support issues they care about through gifts to the BCF Grants Fund, Bayou Recovery Fund, and Quasi-Endowed Fund.  ALL gifts to these BCF funds are put to work right here in the Bayou Region!\r\n\r\nThrough the Grants Fund, BCF awards grants to Lafourche, Terrebonne, and Grand Isle nonprofits each year for programs that addressing critical needs in our area such as education, health care, food and medicine, mental health, programs for at-risk youth and the elderly, and coastal preservation.  \r\n\r\nThrough the Bayou Recovery Fund, BCF provides grants to local nonprofits for work that prepares our community for the next disaster and to help our community recover and rebuild following a storm or other natural disaster. \r\n\r\n'),
(80, 'Living Foundation Community Outreach Ministry, Inc.', '', '', 'LA', '', '', '', 'http://', 'barr', 'recovery', '36598', 'BASIC', '', '', '', '', '-92.1450245', '31.2448234', '', 'ACTIVE', NULL),
(39, 'Catholic Charities Of The Diocese Of Houma-Thibodaux', '1220 Aycock St.', 'Houma', 'LA', '70360', '985-876-0490', '985-876-7751', 'http://www.htdiocese.org/catholic-charities', 'barr', 'recovery', '654782', 'BASIC', '', '', '', '', '', '', 'N29?35.3256, W090?43.12542', 'ACTIVE', 'Providing compassionate service and empowering people in need through faithfulness to the Gospel.\r\n'),
(86, 'Terrebonne ARC', '#1 McCord Road', 'Houma', 'LA', '70363', '985-876-4465', '985-223-7387', 'http://www.terrebonnearc.org', '', '', '', 'BASIC', '', '', '', '', '', '', '', 'ACTIVE', ''),
(85, 'South Central Planning & Development Commission ', '5058 West Main St', 'Houma', 'LA', '70360', '985-851-2922', '985-851-4472', 'www.htmpo.org', '', '', '', 'BASIC', '', '', '', '', '', '', '', 'ACTIVE', NULL),
(87, 'LA DOTD', '', '', 'LA', '', '', '', 'http://', '', '', '', 'BASIC', '', '', '', '', '', '', '', 'ACTIVE', NULL),
(90, 'Church of Jesus Christ of Latter-Day Saints - SOUTHEASTERN LOUISIANA', '2349 Saint Mary St', 'Thibodaux', 'LA', '70301', ' ', '', 'http://www.justserve.org', '', '', '', 'BASIC', '', '', '', '', '', '', '', 'ACTIVE', NULL),
(258, 'Dan Testing New Agency', '1234 Test St', 'Gilbert', 'AZ', '88888', '888-888-8888', '777-777-7777', 'http://google.com', '', '', '', '', '', '', '', '', '123123.123123123', '1213123.123123123', '123123.12312312', 'IN-ACTIVE', NULL),
(259, 'Dan Testing New Agency', '1234 Test St', 'Gilbert', 'AZ', '85296', '778-788-7888', '877-878-8877', 'http://', '', '', '', '', '', '', '', '', '', '', '', 'IN-ACTIVE', NULL),
(260, 'Test Agency', '123 street', 'Houma', 'LA', '70360', '555-555-5555', '', 'http://www.google.com', '', '', '', '', '', '', '', '', '', '', '', 'ACTIVE', ''),
(261, 'Test Agency', '123 street', 'Houma', 'LA', '70360', '555-555-5555', '', 'http://www.google.com', '', '', '', '', '', '', '', '', '', '', '', 'ACTIVE', '');

-- --------------------------------------------------------

--
-- Table structure for table `directory_contact`
--

CREATE TABLE `directory_contact` (
  `contact_id` int(10) NOT NULL,
  `agency_id` varchar(255) NOT NULL DEFAULT '',
  `contact_name` varchar(255) NOT NULL DEFAULT '',
  `contact_telephone` varchar(255) NOT NULL DEFAULT '',
  `contact_cellphone` varchar(255) NOT NULL DEFAULT '',
  `contact_fax` varchar(255) NOT NULL DEFAULT '',
  `contact_pager` varchar(255) NOT NULL DEFAULT '',
  `contact_email` varchar(255) NOT NULL DEFAULT '',
  `contact_type` varchar(255) NOT NULL DEFAULT '',
  `user_type` varchar(50) DEFAULT NULL,
  `user_status` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `salt` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `directory_contact`
--

INSERT INTO cp_directory_contact (`contact_id`, `agency_id`, `contact_name`, `contact_telephone`, `contact_cellphone`, `contact_fax`, `contact_pager`, `contact_email`, `contact_type`, `user_type`, `user_status`, `password`, `salt`) VALUES
(28, '11', 'Leah Lina', '985-873-6855', '', '985.873.6876', '', 'llina@lwc.la.gov', 'ALTERNATE CONTACT', 'USER', 'ACTIVE', '$1$20262855$Ti7iMALpFc1hVE4cUgvhc1', '$1$20262855015b7460639025d8.93904484'),
(27, '11', 'Karen Maloz', '985-873-6855', '985-232-7784', '985.873.6876', '', 'kmaloz@ldol.state.la.us', 'PRIMARY CONTACT', 'USER', 'IN-ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(495, '19', 'Christy Pitre-Allen', '985-262-1871', '', '', '', 'angela_brown@uss.salvationarmy.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(25, '11', 'Frank Lewis', '985.580.7249', '', '', '', 'frank@internet8.net', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(107, '11', 'Lorey Owens ', '985-876-8990', '', '(985) 873-6876', '', 'lowens@lwc.la.gov', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(23, '10', 'Tommy Lyons', '985-790-0682', '985-790-0682', '', '', 'retreaux@gmail.com', 'PRIMARY CONTACT', 'USER', 'IN-ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(132, '51', 'Susan D. Szush', '985.876.8713', '985.637.0693', '985.876.8719', '1.800.256.5600 ID#0639', 'sszush@acadian.com', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$17687607$Bxh68BVplZc171B4fCHfu/', '$1$17687607515b6caea80624b8.44085272'),
(474, '31', 'Barry Chauvin', '', '985-870-4220', '', '', 'bchauvin@op4in.com', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(428, '2', 'Ben Walker', '985-873-6357', '', '', '', 'bwalker@tpcg.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$11022737$ugJAivZ4okF4FRCrSe.GG/', '$1$110227379559ceb5ea5f24c3.03553417'),
(222, '11', 'John Cadiere', '985.873.6855', '', '985.873.6876', '', '', 'PRIMARY CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(128, '21', 'Tom Donohue', '919-601-3425', '919-601-3425', '877.626.1324', '', 'tom.donohue@drims.org', 'PRIMARY CONTACT;VOLUNTEER COORDINATOR', 'ADMIN', 'ACTIVE', '$1$16995108$ROznAfmhWp65ha.XXnqTI1', '$1$16995108165e284973ef2eb3.16225179'),
(464, '38', 'Sharon Foret', '985-228-0577', '985-228-0577', '', '', 'biscosharonforet@yahoo.com', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$17037460$f.vcp8WcGfgzw0tLcmLIV.', '$1$170374602959cbf470176235.74339112'),
(458, '101', 'Kimberly Durow', '504-556-9774', '225-454-2202', '', '', 'kimberly.durow@LA.gov', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(160, '20', 'Chris Boudreaux', '985-537-7603', '', '', '', 'chrisb@lafourchegov.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(476, '87', 'Mike Watts', '225-379-3059', '', '', '', 'Mike.Watts@LA.GOV', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(266, '4', 'Anne Picou, Downtown Coordinator', '985.873.6408', '', '', '', 'apicou@tpcg.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(39, '15', 'Frank W. Kidd', '985.879.3673', '985.209.1355', '', '', '', 'PRIMARY CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(375, '74', 'Rev. Curtiss Eden', '985-868-8232', '504-723-3336', '', '', 'houmadisaster@att.net', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(140, '35', 'Lisa Smith', '985-447.6999', '985.228.1866', '985.447.5167', '', '', 'ALTERNATE CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(139, '34', '1. Diana N. Edmonson ', '985-665-6100', '985-876-4019', '985.868.7806', '', 'dianae@terrebonnecoa.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$40138964$P8.y0P7NPK257ekNm2mYc/', '$1$4013896455b5f5cdb9f8a64.51044161'),
(138, '34', '2. Darla Cantrelle', '985-217-0733', '985-217-0733', '985.868.7806', '', 'darlac@terrebonnecoa.org', 'ALTERNATE CONTACT', 'USER', 'ACTIVE', '$1$91375312$kIzNpjzbTmBC0ZzSoZLxS1', '$1$9137531265b295351a9e878.49598886'),
(331, '6', 'Stephanie Verdin', '', '', '', '', 'dulacstation@bellsouth.net', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(480, '7', 'Thomas Dardar', '', '985-665-4085', '', '', 'thomas.dardar@unitedhoumanation.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(55, '25', 'Dan Krutz', '225.344.0134', '225-938-0414', '225.344.0142', '', 'lainterchurch@aol.com', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(159, '20', 'Eric Benoit', '985-537-7603', '', '', '', 'benoiteg@lafourchegov.org', 'ALTERNATE CONTACT', 'USER', 'ACTIVE', '$1$36390864$Whq8eDgZqZqhUwPc58PD./', '$1$3639086475b63595a506657.02536218'),
(158, '20', 'Chris Boudreaux', '985.532-8174', '', '985.537.7297', '', '', 'PRIMARY CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(177, '12', 'Michael Delaney', '', '', '', '', '', 'ALTERNATE CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(162, '23', 'Maj. Marty Dufrene', '985.537.2263', '985.696.3425', '985.447.1854', '985.672.4047', 'marty-dufrene@lpso.net', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(463, '33', 'Jamie Segura', '504-620-3115', '985-789-7904', '', '', 'jamie.segura2@redcross.org', 'ALTERNATE CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(171, '28', 'Reinhard Goethert', '978.371.8271', '978.505.2142', '', '', 'rgoethert@aol.com', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(214, '32', 'H. Rene Rhodes', '985.851.7057', '985.209.8744', '', '', 'HRRhodes@aol.com', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(473, '44', 'Mary Gueniot Biegler', '985-594-5350', '404-386-6713', '', '', 'bayougrace@bayougrace.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(465, '53', 'Bette Billiot', '985-223-3093', '985-688-6122', '', '', 'bette.billiot@unitedhoumanation.org', 'ALTERNATE CONTACT;DISASTER SERVICES CONTACT;VOLUNTEER COORDINATOR;DONATIONS COORDINATOR', 'USER', 'ACTIVE', '$1$20662177$U47lLmJYthE30VUKwFxp11', '$1$20662177015b98101af23ae6.75881908'),
(67, '31', 'Roosevelt Thomas', '985.876-8630', '985-868-2620', '985-868-8547', '', 'rthomas@op4in.com', 'ALTERNATE CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(183, '16', 'Bill Crawford', '985.447.7539', '985.859.5782', '', '', '', 'PRIMARY CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(251, '37', 'Jackie Price', '985-385-0730/985-384-3875', '985-397-3447', '', '', 'jelprice@att.net', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(452, '40', 'Mary Billiot', '985-563-7483', '', '985-563-7826', '', 'mary@dulaccommunitycenter.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15233623$LIZBN/wnr67v0fCtk2j.W/', '$1$15233623245b6b19058a6af2.97783106'),
(184, '16', 'Church', '985.447.7539', '', '985.447.1795', '', '', 'PRIMARY CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(90, '41', 'Quint Liner Sr.', '', '985.665.2240', '', '', '', 'PRIMARY CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(322, '72', 'Kim Dillard', '985-857-3601', '985-852-1274', '985-857-3607', '', 'kdillard@dhh.la.gov', 'ALTERNATE CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(420, '34', 'Randy Manning', '', '985-860-0865', '', '', '', 'ALTERNATE CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(403, '58', 'Robin Williams', '985-447-0916', '985-991-4845', '985-447-0920', '', 'Robin.Williams@la.gov', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(227, '1', 'Cullen Boudreaux', '985.876.7055', '', '', '', '', 'PRIMARY CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(373, '73', 'Nadia Joseph, Area Manager', '504-202-3450', '504-202-3450', '', '', '', 'PRIMARY CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(482, '88', 'Gayle Feibel', '', '985-209-1649', '', '', '', 'PRIMARY CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(99, '45', 'John Grantham', '', '985.804.0502', '', '', 'jgrantham36@yahoo.com', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(434, '55', 'Ken Standard - AD5XJ', '985-217-4006', '', '', '', 'ad5xj@arrl.net', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(487, '34', '3. Randy Manning', '985-860-0865', '985-860-0865', '', '', 'randym@terrebonnecoa.org', 'ALTERNATE CONTACT', 'USER', 'ACTIVE', '$1$11734267$plq51gj29akaWvq1XxzWc1', '$1$117342673459cbf3d14e8237.49587063'),
(166, '26', 'Dale Peercy', '', '937.403.3293', '', '', 'dalepeercy@aol.com', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(215, '50', 'Suncere Ali Shakur', '', '985.913.86.93', '', '', 'mec_freebreakfast@yahoo.com', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(163, '6', 'Barbara Abshire', '337.898.1022', '', '', '', '', 'ALTERNATE CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(353, '2', 'Earl Eues', '985-873-6357', '985-855-4594', '', '', 'eeues@tpcg.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(223, '11', 'Judy Mire ', '985-876-8990', '', '985.873.6876', '', 'jmire@lwc.la.gov', 'ALTERNATE CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(481, '7', 'Bette Billiot', '985-223-3093', '985-688-6122', '', '', '', 'PRIMARY CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(198, '9', 'Alina Merlos', '985-879-2461', '985-226-0655', '', '', 'amerlos@uwsla.com', 'ALTERNATE CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(224, '11', 'Valerie Campbell', '985.872.6708', '985.860.0461', '985.873.6876', '', '', 'PRIMARY CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(193, '5', '2. Michelle Liner, Volunteer Management', '985-860-9700', '985-860-9700', '985.851.1401', '', 'michelleliner@trac4la.com', 'VOLUNTEER COORDINATOR', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(462, '19', 'Angela Brown', '985-262-1871', '985-713-3789', '985-262-1918', '', 'angela_brown@uss.salvationarmy.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(8, '5', '1. Peg Case, TRAC Director', '985-855-2515', '985-855-2515', '985.851.1401', '', 'pegcase@trac4la.com', 'PRIMARY CONTACT;DISASTER SERVICES CONTACT;VOLUNTEER COORDINATOR;DONATIONS COORDINATOR', 'ADMIN', 'ACTIVE', '$1$13838483$XDMDE9Ug.YldM5.eIJAGW.', '$1$13838483885b6373df9eca38.44538573'),
(329, '4', 'Linda Henderson', '', '', '', '', 'lhenderson@tpcg.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(278, '58', 'Paul Landry', '985.447.0916 ext. 332', '337.278.7124', '985.447.0920', '', 'Paul.Landry@la.gov', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(460, '38', 'Anitra Woods', '985-208-6559', '985-208-6559', '', '', '', 'VOLUNTEER COORDINATOR', 'USER', 'ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(148, '38', 'Sharon Gauthe', '985-446-5364', '985-438-2148', '985.446.5364', '', 'ssgauthe@gmail.com', 'VOLUNTEER COORDINATOR', 'USER', 'IN-ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(149, '39', 'Robert Gorman', '985-805-0372', '', '985.876.7751', '', 'rgorman@htdiocese.org', 'PRIMARY CONTACT;DISASTER SERVICES CONTACT', 'USER', 'ACTIVE', '$1$14782089$CCX9Jzp2a8/i4Odrl6XrN/', '$1$147820896959c2b68d1673c5.65889070'),
(422, '83', 'Natalie Bergeron', '985-226-8980', '', '', '', 'bergeronnat@yahoo.com', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(494, '19', 'Jeri Billiot', '985-262-1871', '', '', '', 'angela_brown@uss.salvationarmy.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(500, '90', 'Scott Conlin', '504-220-2738', '504-220-2738', '', '', 'scott.n.conlin@conlinkrewe.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(264, '17', 'Dick Krajeski', '', '', '', '', '', 'PRIMARY CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(154, '43', 'Jonathan Clifton', '', '703.254.9444', '', '', '', 'PRIMARY CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(156, '46', 'Beulah Rodrigue', '985.876.5784', '985.860.0396', '', '', 'rod_beul@bellsouth.net', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(157, '48', 'Donald Naquin', '985.872.0847', '985.855.1189', '985.872.0847', '', 'donaldnaquin@bellsouth.net', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(3, '1', 'Norman Simon', '985.851.2432', '', '985.851.2432', '', 'simo7977@bellsouth.net', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(200, '9', 'Martha Verdin', '985-879-2461', '985-209-2048', '', '', 'mverdin@uwsla.com', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$20134691$9Vi3i8n.Id2YbTQIsuxZC1', '$1$20134691505b4e4a4ea4f526.13235920'),
(202, '57', 'Hank Breaux', '504.584.1667', '', '', '', 'Hank.Breaux@Road2LA.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(344, '66', 'Jane Arnette', '', '985-860-9797', '', '', 'scindustrial@bellsouth.net', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(260, '58', 'Rhonda Lombas', '985-447-0916 ext. 364', '', '', '', 'Rhonda.Lombas@la.gov', 'ALTERNATE CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(272, '6', 'Regan Wooley', '985-257-1086/985-857-2205', '', '', '', '', 'ALTERNATE CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(270, '64', 'Stella Thibodeaux', '985.868.7352', '', '985.872.3671', '', 'bayoubaptistassn@bellsouth.net', 'ALTERNATE CONTACT', 'USER', 'ACTIVE', '$1$12273226$UaGIvZny7695JY0ZIagIe0', '$1$122732266059c2c0fd0415b0.66061074'),
(210, '6', 'Landie Thompson', '', '', '', '', 'lthompsonab@bellsouth.net', 'ALTERNATE CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(501, '90', 'martha McKay', '925-324-0977', '925-324-0977', '', '', 'marthamckay@ldspublicaffairs.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(478, '86', 'Mary Lynn Bisland', '', '985-209-6714', '', '', '', 'PRIMARY CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(497, '58', 'Al Russell', '985-447-0916 _ ext 335', '985-855-1739', '', '', 'Al.Russell@la.gov', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(498, '2', 'Thomas Long', '985-873-6357', '', '', '', 'tlong@tpcg.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(244, '35', 'Audry Winston', '985-447.6999', '985.228.1868', '985.447.5167', '', 'awinston@bayouhabitat.org', 'ALTERNATE CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(489, '16', 'Rev Jim Duck', '985-209-2701', '', '', '', 'jim-duck@lpso.net', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$85016716$NlSSOWhxzXAnnRN7gecZn.', '$1$850167165b6349eb118557.70111136'),
(269, '64', 'Rev. Joe Arnold', '985-856-2224', '985-856-2224', '985.872.3671', '', 'bayoubaptistbj@bellsouth.net', 'DISASTER SERVICES CONTACT', 'USER', 'ACTIVE', '$1$18049710$iyrLCbp4b6pdo0cbsBBnm0', '$1$180497105459c2bc063e6c54.65011156'),
(290, '6', 'Darryl Guy', '985-257-1086', '', '', '', 'darrylguyab@bellsouth.net', 'ALTERNATE CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(289, '7', 'Lanor Curole', '(985)223-3093', '(985)696-8899', '(985)223-3095', '', 'lanor.curole@unitedhoumanation.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(298, '53', 'Thomas Dardar', '985-868-0403', '985-665-4085', '', '', 'thomas.dardar@unitedhoumanation.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(299, '16', 'Rev. Wayne E. Hunt', '985-876-6089', '', '985-876-6992', '', 'coteaubcpastor@bellsouth.net', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(301, '39', 'Jennifer Gaudet', '985-876-0490', '', '985-876-7751', '', 'jgaudet@htdiocese.org', 'ALTERNATE CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(493, '54', 'Miriam Barrett', '717-377-1987', '717-377-1987', '', '', 'KG5BNH@gmail.com', 'PRIMARY CONTACT;DISASTER SERVICES CONTACT;VOLUNTEER COORDINATOR', 'USER', 'ACTIVE', '$1$32792276$By/zq2tBHqEwNVKWWPDgr1', '$1$32792276959cbf14d571ca6.39409054'),
(394, '73', 'Beryl \"Gwen\" Billiot', '', '985-860-3588', '', '', '', 'ALTERNATE CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(349, '36', 'Kenny Hill', '985-580-7730 Ext. 12', '', '985-580-7238', '', 'khill@bayoucanefd.org', 'ALTERNATE CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(359, '6', 'Rev. Curtiss Eden', '', '504-723-3336', '', '', '', 'PRIMARY CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(372, '73', 'Henry Harris', '504-202-3450', '504-202-2666', '', '', '', 'PRIMARY CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(369, '69', 'R. Don Ross', '985-868-7787', '985-991-9462', '', '', 'rdon.ross@gmail.com', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(370, '76', 'Kim Adams', '985-851-4488/800-947-7645', '', '', '', '', 'PRIMARY CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(371, '73', 'Anya Carter', '504-202-3450', '504-202-5193', '', '', 'anyac@bellsouth.net', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(378, '74', 'Barbara Abshire', '337-898-1022', '', '', '', '', 'PRIMARY CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(380, '74', 'Landie Thompson', '337-898-1022', '', '', '', '', 'PRIMARY CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(381, '70', 'Darryl Waire', '985-873-6591', '', '', '', 'dwwaire@tpcg.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(382, '70', 'Kelly Cunningham', '985-873-6591', '985-856-9869', '', '', 'kcunningham@tpcg.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(427, '36', 'John Poiencot', '985-580-7730', '', '985-580-7238', '', 'jpoiencot@bayoucanefd.org', 'ALTERNATE CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(470, '85', 'Cassie Parker', '985-851-2922', '', '985-851-4472', '', 'cassie@scpdc.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(386, '77', 'Chief Albert Naquin', '985-232-1286', '985-232-1286', '', '', 'whitebuffaloa@netscape.net', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(387, '77', 'Sheila Billiot', '985-232-1286', '', '', '', '', 'PRIMARY CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(388, '78', 'Deb Meyer', '', '504-481-1656', '', '', '', 'PRIMARY CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(389, '71', 'Lesli Remaly-Netter', '202-373-9090', '', '305-255-5509', '', '', 'PRIMARY CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(483, '58', 'Sedonia Montgomery', '985-447-0916', '504-458-4218', '', '', 'Sedonia.Montgomery@la.gov', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(392, '80', 'Rev. Joseph Johnson, Jr', '985-805-0193', '', '', '', '', 'PRIMARY CONTACT', 'USER', 'IN-ACTIVE', '$1$57224754$t88qu9.M7qLPqoo0hyVaO1', '$1$5722475445dd96745731ee5.71227815'),
(468, '33', 'Pauline Dillie', '985-872-1774', '985-790-3531', '', '', 'paulinedillie@att.net', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(499, '91', 'Jennifer Armand', '985-790-1150', '985-790-1150', '', '', 'armandj@bayoucf.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$11503258$mGhZArwMN5GfyzLpHA03h1', '$1$11503258259cac459d42e48.66204281'),
(411, '81', 'Greg Harding', '985-873-8061', '985-688-0052', '', '', 'gregdutharding@aol.com', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(439, '82', 'Jessica Vermilyea', '504-376-9121', '504-376-9121', '504-708-2885', '', 'Vermilyea.jessica@gmail.com', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(404, '58', 'Kayla Guerrero', '985-447-0916', '', '985-447-0920', '', 'Kayla.Guerrero@la.gov', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(459, '38', 'Donald Bogen, JR', '985-859-1192', '985-859-1192', '', '', 'biscodonald@yahoo.com', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(461, '38', 'David Gauthe', '985-438-1609', '985-438-1609', '', '', 'biscodavid@yahoo.com', 'VOLUNTEER COORDINATOR', 'USER', 'IN-ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(466, '84', 'Denise Hugher', '', '985-226-0109', '', '', 'ddhughes.4u@gmail.com', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(467, '84', 'Cheryl Cavalier', '', '985-696-3600', '', '', 'ccavalier.4u@gmail.com', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(471, '85', 'Leo Marretta', '', '', '', '', 'leo@scpdc.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(490, '85', 'Adam Tatar', '', '', '', '', 'adam@scpdc.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(484, '23', 'Rev Jim Duck', '985-209-2701', '985-209-2701', '', '', 'jim-duck@lpso.net', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$85016716$NlSSOWhxzXAnnRN7gecZn.', '$1$850167165b6349eb118557.70111136'),
(485, '89', 'Charlene Rodriguez', '985-532-0457', '985-855-6996', '', '', 'lafcoadirector@viscom.net', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(486, '89', 'Lynette Billiot', '', '985-258-3871', '', '', 'homemaker@viscom.net', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(491, '39', 'Marjorie Duplantis', '985-876-0490', '985-856-7827', '', '', 'mduplantis@htdiocese.org', 'ALTERNATE CONTACT;VOLUNTEER COORDINATOR', 'USER', 'ACTIVE', '$1$15224048$luIOWLE3S5ht/6TbFuNw6/', '$1$152240480659cbf4a3946231.86620201'),
(492, '33', 'Carolanne Fernandez', '504-444-2792', '504-444-2792', '', '', 'carolanne.fernandez@redcross.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$15232280$/Q9mo1e6r2qAzqsbps4S9.', '$1$15232280658968fb7ca1680.29104661'),
(496, '90', 'Monica Stock', '985-209-2699', '985-209-2699', '', '', 'stockm@ldspublicaffairs.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$13867052$Rn/14rj4FCCX.bxt/cZbB1', '$1$1386705235b6870e69014b0.80252961'),
(506, '888', 'Dan David', '', '', '', '', 'dancdavid@icloud.com', '', 'ADMIN', 'ACTIVE', '$1$19941228$tGa60P/gVKCrW0sc4y1EI0', '$1$19941228085afee09ceb93d6.96749036'),
(511, '86', 'Mary Lynn Bisland', '985-876-4465', '985-209-6714', '', '', 'execdir@terrebonnearc.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$17437353$Pa571QjmkwJVIKUBQmdEu/', '$1$17437353305b6865a1d269b8.18521331'),
(509, '86', 'Patricia Chauvin', '985-876-4465', '', '', '', 'adminasst@terrebonnearc.org', 'ALTERNATE CONTACT', 'USER', 'ACTIVE', '$1$17553508$2bjHi97RtM/VXCRmMKL4./', '$1$17553508025b6863703ea484.06571265'),
(510, '86', 'Rodger Shelton', '985-876-4465', '985-209-8094', '', '', 'rshelton@terrebonnearc.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$11104487$XZXTCyXXSCWzJT3LSrE3t0', '$1$11104487145b69a25fe715e4.10000096'),
(512, '47', 'Keith Ward', '985-873-6391', '985-873-6391', '', '', 'kward@tpcg.org', 'PRIMARY CONTACT', 'USER', 'ACTIVE', '$1$14266420$kNS585gBlDmS8u3WLJ9fW0', '$1$14266420785b7ae24536c402.69935940'),
(513, '21', 'Test', '919-601-3425', '', '', '', 'test@test.com', 'DONATIONS COORDINATOR', 'USER', 'IN-ACTIVE', '$1$17599419$/Mjj4M6o54sFyLZFUYmMz0', '$1$17599419705b7b7cc5f311a2.71278080'),
(514, '21', 'Joe Smith', '123-123-1123', '', '', '', 'joe@smith.com', 'ALTERNATE CONTACT;DISASTER SERVICES CONTACT;VOLUNTEER COORDINATOR', 'USER', 'IN-ACTIVE', '$1$20298320$spVWzZxsmetP7NvvulpwF.', '$1$20298320815d9f2d15222e79.24510390'),
(515, '21', 'Brent Chapman', '123-123-1234', '', '', '', 'brent.chapman@drims.org', 'VOLUNTEER COORDINATOR;DONATIONS COORDINATOR', 'ADMIN', 'ACTIVE', '$1$19173900$UXZOWF8aKyJqQmFNEfTcr.', '$1$19173900465e3c7acbe2c8b6.16279824');

-- --------------------------------------------------------

--
-- Table structure for table `directory_service`
--

CREATE TABLE `directory_service` (
  `service_id` int(10) NOT NULL,
  `agency_id` varchar(255) NOT NULL,
  `service_type` varchar(255) NOT NULL,
  `service_description` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `directory_service`
--

INSERT INTO cp_directory_service (`service_id`, `agency_id`, `service_type`, `service_description`) VALUES
(1, '21', 'CURRENT SERVICE', 'Technical Consulting. (Website Development, Web-Based Application Development, Database Administration, Graphic Design)'),
(2, '23', 'CURRENT SERVICE', 'Housing for outside agencies.\r\nAll law enforcement activities.\r\nAssistance to affected law enforcement in other parishes with equipment and supplies.\r\nWas doing search and rescue,\r\nmanning shelters,\r\nassisting transportation of evacuees,\r\ndistribution of donated food and supplies to other agencies.'),
(3, '21', 'CURRENT SERVICE', 'Organizational Consulting. (Process Management/Efficency, Staff Utilization/Optimization, Growth Development)'),
(4, '55', 'VOLUNTEER SKILLS', 'SATERN is a team of trained radio operators for the purpose of supporting the Salvaion Army in their disaster response. SATERN members also provide a network of ham radio operators to forward health and welfare messages in the aftermath of an emergency.'),
(5, '5', 'CURRENT SERVICE', 'LongTerm Disaster Recovery and Case management - Terrebonne & Lafourche\r\nAdvocacy with The Road Home Program - \r\nProvide Campaign Storm Safe Louisiana Disaster Preparedness Programs in 21 South LA Parishes\r\nIdentify & Secure Resources (goods,financial,services) for current needs and projected recovery needs\r\n\r\n'),
(6, '44', 'CURRENT SERVICE', 'Construction, Volunteers, Ditribution, Medical, Family and Environmetnal Outreach, Community Organizing'),
(7, '37', 'PLANNED SERVICE', 'Plan to provide complete radio communications in our entire parish - St. Mary'),
(8, '38', 'PLANNED SERVICE', 'Contacts with local congregation members to share needs and possible location of resources (10 in Thibodaux, 18 in Houma)'),
(9, '20', 'CURRENT SERVICE', 'Temporary Housing'),
(10, '48', 'CURRENT SERVICE', 'Working Shelters and Cooking\r\nWorking with TRAC'),
(11, '11', 'CURRENT SERVICE', 'On-the-Job Training with reimbursement up to 50% to employers\r\n100%  paid work experience\r\nIssue Individual Training Accounts to pay tuition for certain trainings and specific training providers\r\nSupportive services i.e. childcare, transportation, tools, books, equipment to retain employment or complete an educational program'),
(12, '30', 'CURRENT SERVICE', 'Installing Blue Roofs for the needy'),
(13, '5', 'RECOVERY EFFORT GAP', 'In planning phase for a final assessment of the Recovery Need in Terrebonne and Lafourche Parishes.  Assessment scheculed to begin Dec 2007.'),
(14, '22', 'CURRENT SERVICE', 'Atmos-Entergy (Share the Warmth Program) \r\nIDA (Individual Development Account) \r\nEFSP/FEMA (Emergency Food and Shelter Program) \r\nEmergency Assistance (Medical, Rental, Utility, Homeless/Prevention) \r\nLIHEAP (Low Income Home Energy Assistance Program) \r\nOutreach and Referrals \r\nTeen Expo \r\nUSDA Commodities \r\nVITA/EITC (Volunteer Income Tax Assistance / Earn Income Tax Credit Program) \r\nWAP (Weatherization Program) \r\nSToP (Solution to Poverty) \r\nCACFP (Child/Adult Care Food Program) '),
(15, '33', 'CURRENT SERVICE', 'Preparedness information. Community recovery services \"Access to Care\" \"Means to Recovery\"'),
(16, '39', 'RECOVERY EFFORT GAP', '> Casework in shelters, FEMA trailer sites\r\n<br>\r\n> Work crews to rebuild'),
(17, '36', 'CURRENT SERVICE', 'Temp Housing, Quantity of 9-6.5 KW Generators, Emergency Medical, Other Emergency Services'),
(18, '35', 'PLANNED SERVICE', 'Applications for Habitat Housing\r\nVolunteer Information'),
(19, '32', 'PLANNED SERVICE', 'Continued assistance w/ evacuees. Presently, raising money for evacuees to replace appliances, refrigerators, washers, dryers, etc.'),
(20, '31', 'PLANNED SERVICE', 'The Options For Independence LA Spirit program plans to continue to provide disaster recovery services as community need dictates within the scope of the programs contracted services.'),
(21, '43', 'CURRENT SERVICE', 'Planning of long term community recovery - Head liason for Terrebonne Parish'),
(22, '31', 'CURRENT SERVICE', 'Options For Independence provides crisis counseling to individuals who were affected by Hurricane Katrina and Rita in the parishes of Terrebonne, Lafourche, St. Mary, St. James, St. John the Baptist, St. Charles, Assumption. Counselors and outreach workers are canvassing affected neighborhoods to provide information and support services to individuals. Counselors and outreach workers are also presenting stress management classes, puppet shows and display tables aimed at disaster recovery and hurricane prevention.'),
(23, '38', 'ADDITIONAL INFORMATION', 'BISCO is a faith based community organization association.'),
(24, '39', 'CURRENT SERVICE', '> Cleaning Supplies\r\n<br>\r\n> Project Starfish - Relocate people out of shelters\r\n<br>\r\n> Emergency Assistance - Rent payment/deposit for evacuees/host families\r\n<br>\r\n> Food Banks\r\n<br>\r\n> Emergency food stamp sign up at shelters\r\n<br>\r\n> Shelter supplies'),
(25, '54', 'VOLUNTEER SKILLS', 'Ham Radio Emergency Service for use where normal communication channels fail or become unusable. Fixed point and portable/mobile units are available for Government agencies, first responders, and relief organizations.'),
(26, '5', 'VOLUNTEER SKILLS', 'TRAC, Presbyterian Disaster Relief, Houma Elks Lodge have partnered to provide housing, volunteer managment, construction management. The Good Earth Volunteer Village located on Coteau Rd Houma houses 100 volunteers. '),
(27, '5', 'ADDITIONAL INFORMATION', 'TRAC, Oxfam-America, MIT/Sigus have partnered to design and build The LA Lift House - an affordable solution to Sustainable Housing on the LA Coast. Visit our website or call for more information.'),
(28, '14', 'CURRENT SERVICE', 'Provide volunteer housing. Good Earth Volunteer Village located at 1228 Coteau Rd. Houma, LA '),
(29, '10', 'CURRENT SERVICE', 'Sponsor \"The Voice of the Wetlands Festival\" annually.'),
(30, '58', 'ADDITIONAL INFORMATION', ''),
(31, '59', 'CURRENT SERVICE', ''),
(32, '51', 'PLANNED SERVICE', 'transportation of the infirmed'),
(33, '58', 'CURRENT SERVICE', 'Zika Virus Coordinator will provide updates & public education materials.  Parishes:  Terrebonne, Lafourche, St. Charles, James, John ,Mary. Coordinator is AL RUSSELL'),
(34, '258', 'RECOVERY EFFORT GAP', 'Testing Description'),
(35, '258', 'ADDITIONAL INFORMATION', 'Another Test'),
(36, '259', 'ADDITIONAL INFORMATION', 'Testing');

-- --------------------------------------------------------

--
-- Stand-in structure for view `directory_services_full`
-- (See below for the actual view)
--
CREATE TABLE `directory_services_full` (
`agency_id` int(11)
,`major` varchar(255)
,`minor` varchar(255)
,`terrebonne` varchar(10)
,`lafourche` varchar(10)
,`comments` longtext
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `directory_services_search`
-- (See below for the actual view)
--
CREATE TABLE `directory_services_search` (
`agency_id` int(10)
,`agency_name` varchar(255)
,`agency_address` varchar(255)
,`agency_city` varchar(255)
,`agency_state` varchar(255)
,`agency_zipcode` varchar(255)
,`agency_telephone` varchar(255)
,`status` varchar(50)
,`service_id` int(11)
,`terrebonne` varchar(10)
,`lafourche` varchar(10)
,`major` varchar(255)
,`minor` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `events_calendar`
--

CREATE TABLE `events_calendar` (
  `id` int(11) NOT NULL,
  `event_title` varchar(255) NOT NULL,
  `event_description` mediumtext NOT NULL,
  `event_address` varchar(255) NOT NULL,
  `event_city` varchar(100) NOT NULL,
  `event_state` varchar(100) NOT NULL,
  `event_zip` varchar(50) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `submitted_by` varchar(200) NOT NULL,
  `timestamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `events_calendar`
--

INSERT INTO cp_events_calendar (`id`, `event_title`, `event_description`, `event_address`, `event_city`, `event_state`, `event_zip`, `event_date`, `event_time`, `status`, `submitted_by`, `timestamp`) VALUES
(3, 'Testing Event Calendar', '<p>Includes:</p>\r\n<ul>\r\n<li>Games</li>\r\n<li>Rides</li>\r\n<li>Food</li>\r\n</ul>', '1234 E Gilbert Dr', 'Gilbert', 'AZ', '85296', '2017-02-11', '04:30 PM', 'DELETED', 'Dan David', '2017-02-09 22:34:07'),
(4, 'Test Event EDITED', '<p>here is the short description</p>', '216 South Ave W', 'Missoula', 'MT', '59801', '2017-02-28', '04:15 PM', 'DELETED', 'Tom Donohue', '2017-02-09 22:34:18'),
(5, 'Test Event #2', '<p>Here is my event decription.</p>', '1234 Cherry Tree Lane', 'Missoula ', 'MT', '59801', '2017-02-22', '12:00 PM', 'DELETED', 'Tom Donohue', '2017-02-15 18:12:18'),
(6, 'Test Event', '<p>Here is my event description.</p>', '216 South Ave W', 'Missoula', 'MT', '59801', '2017-03-31', '12:00 PM', 'DELETED', 'Tom Donohue', '2017-03-29 21:39:33'),
(7, 'Event 1234', '<p>Here is the description for my event.</p>', '123 Apple Tree Lane', 'Missoula', 'MT', '59801', '2017-04-03', '12:00 PM', 'DELETED', 'Tom Donohue', '2017-03-30 23:05:07'),
(8, 'My Event Title Here', '', '123 Helloworld Lane', 'Tempe', 'AZ', '12345', '2017-03-30', '12:00 PM', 'DELETED', 'Tom Donohue', '2017-03-30 23:06:13'),
(9, 'Test Title 123', '', '111 kiond', 'jungle', 'la', '99999', '2017-05-01', '12:00 PM', 'DELETED', 'Tom Donohue', '2017-04-25 11:32:18'),
(10, 'BARR Monthly Meeting', '<p>Training workshop for BARR Portal. &nbsp;</p>', '995 West Tunnel Blvd ', 'Houma', 'LA', '70360', '2017-09-27', '01:30 PM', 'DELETED', 'Peg Case, Director', '2017-09-20 21:34:59'),
(11, 'BARR Monthly Meeting', '<p>1st BARR meeting for 2018. Review of members EOP, and <a href=\"http://www.barr4bayous.org\">www.barr4bayous.org</a>&nbsp;</p>', 'Terrebonne Council on Aging  995 West Tunnel Blvd', 'Houma', 'LA', '70360', '2018-06-27', '01:00 PM', 'DELETED', '1. Peg Case, TRAC Director', '2018-06-20 19:52:05'),
(12, 'AUG 29,2018 - BARR Monthly Meeting', '<p>Agenda:</p>\r\n<p>1. Agency Representatives to provide their current disaster response plans.&nbsp; Including but not limited to Preparedness, Response, Recovery.&nbsp;</p>\r\n<p>2. Updating the BARR Portal.</p>\r\n<p>CONTACT: <a href=\"mailto:dianae@terrebonnecoa.org\">dianae@terrebonnecoa.org</a>&nbsp;</p>', 'Terrebonne COA Conference Room - 995 West Tunnel Blvd', 'Houma', 'LA', '70360', '2018-08-29', '01:00 PM', 'ACTIVE', '1. Peg Case, TRAC Director', '2018-08-02 17:25:16');

-- --------------------------------------------------------

--
-- Stand-in structure for view `full_committee_contacts`
-- (See below for the actual view)
--
CREATE TABLE `full_committee_contacts` (
`committee_contact_id` int(10)
,`committee_id` varchar(255)
,`contact_id` varchar(255)
,`agency_id` varchar(255)
,`contact_name` varchar(255)
,`contact_telephone` varchar(255)
,`contact_cellphone` varchar(255)
,`contact_fax` varchar(255)
,`contact_pager` varchar(255)
,`contact_email` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `message_board`
--

CREATE TABLE `message_board` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` longtext NOT NULL,
  `timestamp` datetime NOT NULL,
  `submitted_by` varchar(200) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `message_board`
--

INSERT INTO cp_message_board (`id`, `title`, `message`, `timestamp`, `submitted_by`, `status`) VALUES
(1, 'Testing Message board title', '<p><span style=\"color: #000000; font-family: \'Open Sans\', Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: #ffffff; display: inline !important; float: none;\"><strong>Lorem ipsum</strong> dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </span></p>\r\n<p><span style=\"color: #000000; font-family: \'Open Sans\', Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: #ffffff; display: inline !important; float: none;\">Duis aute irure dolor in reprehenderit in <strong><em>voluptate</em></strong> velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</span></p>', '2017-02-09 08:04:17', 'Tom Donohue', 'DELETED'),
(2, 'The standard Lorem Ipsum passage, used since the 1500s', '<p><span style=\"color: #000000; font-family: \'Open Sans\', Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: #ffffff; display: inline !important; float: none;\">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</span></p>', '2017-02-09 08:05:38', 'Tom Donohue', 'DELETED'),
(3, '1914 translation by H. Rackham', '<p><span style=\"color: #000000; font-family: \'Open Sans\', Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: #ffffff; display: inline !important; float: none;\">But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?</span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"color: #000000; font-family: \'Open Sans\', Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: #ffffff; display: inline !important; float: none;\">Helloworld.</span></p>', '2017-02-09 08:33:49', 'Tom Donohue', 'DELETED'),
(4, 'Helloworld EDITED by DD', '<p>Here is my HELLOWORLD message board</p>\r\n<p><em><strong>DD EDITED</strong></em> this message</p>', '2017-02-09 12:50:31', 'Tom Donohue', 'DELETED'),
(5, 'Happy Mardi Gras', '<p><strong>be safe&nbsp;</strong></p>\r\n<ol style=\"list-style-type: lower-alpha;\">\r\n<li style=\"text-align: left;\"><em><strong>23:49:38</strong></em></li>\r\n</ol>', '2017-02-28 00:51:23', 'Peg Case, Director', 'DELETED'),
(6, 'Here is my TITLE', '<p style=\"text-align: center;\"><strong>This is my message.</strong> <em>How do you like this.</em></p>', '2017-03-30 23:02:30', 'Tom Donohue', 'DELETED'),
(7, 'Tested BARR Portal today !  Its awesome', '<p>looking forward to Sept 27th training session&nbsp;</p>', '2017-09-20 21:31:24', 'Peg Case, Director', 'DELETED'),
(8, 'Testing signature', '<p>updated signature to include agency&nbsp;</p>', '2017-09-26 01:53:10', 'Peg Case, Director', 'DELETED'),
(9, 'TESTING SIGNATURE 2', '<p>STILL CHECKING&nbsp;</p>', '2017-09-26 02:03:40', 'Peg Case, TRAC Director', 'DELETED'),
(10, 'TEST Title', '<p>Test Message Here</p>\r\n<p>&nbsp;</p>\r\n<p style=\"text-align: justify;\">\"It was a sight that affected me greatly,\" a middl<em>e-aged lawye</em>r and resident from Ras al-Ain, who declined to be named, told CNN. \"We<strong> know the woman,</strong> and we knew she was mentally unwell.\"<br />The lawyer said he had been busy trying to</p>\r\n<ul style=\"list-style-type: square;\">\r\n<li style=\"text-align: justify;\">cram his children into a car leaving the village at</li>\r\n<li style=\"text-align: justify;\">the time. In the middle of their frenzied escape, no</li>\r\n<li style=\"text-align: justify;\">one from the village was able to help the help the woman, he said.</li>\r\n</ul>', '2019-10-17 12:56:46', 'Tom Donohue', 'DELETED'),
(11, 'TEST Message Title ', '<p>&nbsp;</p>\r\n<p>Here is my TEST message.</p>\r\n<p><strong>Put your important details here.</strong></p>\r\n<p><a href=\"http://www.msn.com\" target=\"_blank\" rel=\"noopener noreferrer\">HERE IS MY URL/LINK</a>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>', '2019-11-19 11:35:30', 'Tom Donohue', 'ACTIVE'),
(12, 'Test Message 2', '<p>Howdy, this is a message for <a href=\"#top\">DRIMS</a></p>\r\n<p>&nbsp;</p>', '2020-02-20 16:26:06', 'Brent Chapman', 'DELETED');

-- --------------------------------------------------------

--
-- Table structure for table `services_directory`
--

CREATE TABLE `services_directory` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `agency_id` int(11) NOT NULL,
  `terrebonne` varchar(10) DEFAULT NULL,
  `lafourche` varchar(10) DEFAULT NULL,
  `comments` longtext,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `services_directory`
--

INSERT INTO cp_services_directory (`id`, `service_id`, `agency_id`, `terrebonne`, `lafourche`, `comments`, `updated_by`, `updated_date`) VALUES
(265, 1, 21, 'YES', 'YES', 'this is great', 128, '2019-12-18 14:18:03'),
(266, 2, 21, 'YES', 'YES', '', 128, '2019-12-18 14:18:03'),
(267, 3, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(268, 4, 21, 'YES', 'YES', 'Hey This Is Great', 128, '2019-12-18 14:18:03'),
(269, 5, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(270, 6, 21, 'NO', 'YES', '', 128, '2019-12-18 14:18:03'),
(271, 7, 21, 'YES', 'YES', '', 128, '2019-12-18 14:18:03'),
(272, 8, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(273, 9, 21, 'NO', 'YES', '', 128, '2019-12-18 14:18:03'),
(274, 10, 21, 'YES', 'NO', '', 128, '2019-12-18 14:18:03'),
(275, 11, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(276, 12, 21, 'YES', 'YES', '', 128, '2019-12-18 14:18:03'),
(277, 13, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(278, 14, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(279, 15, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(280, 16, 21, 'NO', 'YES', '', 128, '2019-12-18 14:18:03'),
(281, 17, 21, 'YES', 'NO', '', 128, '2019-12-18 14:18:03'),
(282, 18, 21, 'YES', 'NO', '', 128, '2019-12-18 14:18:03'),
(283, 19, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(284, 20, 21, 'NO', 'YES', 'here you go', 128, '2019-12-18 14:18:03'),
(285, 21, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(286, 22, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(287, 23, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(288, 24, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(289, 25, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(290, 26, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(291, 27, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(292, 28, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(293, 29, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(294, 30, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(295, 31, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(296, 32, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(297, 33, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(298, 34, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(299, 35, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(300, 36, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(301, 37, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(302, 38, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(303, 39, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(304, 40, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(305, 41, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(306, 42, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(307, 43, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(308, 44, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(309, 45, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(310, 46, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(311, 47, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(312, 48, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(313, 49, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(314, 50, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(315, 51, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(316, 52, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(317, 53, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(318, 54, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(319, 55, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(320, 56, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(321, 57, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(322, 58, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(323, 59, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(324, 60, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(325, 61, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(326, 62, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(327, 63, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(328, 64, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(329, 65, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(330, 66, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(331, 67, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(332, 68, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(333, 69, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(334, 70, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(335, 71, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(336, 72, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(337, 73, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(338, 74, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(339, 75, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(340, 76, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(341, 77, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(342, 78, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(343, 79, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(344, 80, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(345, 81, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(346, 82, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(347, 83, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(348, 84, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(349, 85, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(350, 86, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(351, 87, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(352, 88, 21, 'NO', 'NO', '', 128, '2019-12-18 14:18:03'),
(353, 1, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(354, 2, 51, 'YES', 'YES', '', 128, '2017-03-07 00:56:15'),
(355, 3, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(356, 4, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(357, 5, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(358, 6, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(359, 7, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(360, 8, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(361, 9, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(362, 10, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(363, 11, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(364, 12, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(365, 13, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(366, 14, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(367, 15, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(368, 16, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(369, 17, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(370, 18, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(371, 19, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(372, 20, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(373, 21, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(374, 22, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(375, 23, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(376, 24, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(377, 25, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(378, 26, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(379, 27, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(380, 28, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(381, 29, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(382, 30, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(383, 31, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(384, 32, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(385, 33, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(386, 34, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(387, 35, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(388, 36, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(389, 37, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(390, 38, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(391, 39, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(392, 40, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(393, 41, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(394, 42, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(395, 43, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(396, 44, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(397, 45, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(398, 46, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(399, 47, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(400, 48, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(401, 49, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(402, 50, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(403, 51, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(404, 52, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(405, 53, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(406, 54, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(407, 55, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(408, 56, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(409, 57, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(410, 58, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(411, 59, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(412, 60, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(413, 61, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(414, 62, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(415, 63, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(416, 64, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(417, 65, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(418, 66, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(419, 67, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(420, 68, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(421, 69, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(422, 70, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(423, 71, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(424, 72, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(425, 73, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(426, 74, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(427, 75, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(428, 76, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(429, 77, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(430, 78, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(431, 79, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(432, 80, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(433, 81, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(434, 82, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(435, 83, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(436, 84, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(437, 85, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(438, 86, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(439, 87, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(440, 88, 51, 'NO', 'NO', '', 128, '2017-03-07 00:56:15'),
(441, 1, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(442, 2, 39, 'YES', 'YES', 'Matthew 25 volunteers from local Catholic churches', 149, '2017-09-20 16:02:37'),
(443, 3, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(444, 4, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(445, 5, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(446, 6, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(447, 7, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(448, 8, 39, 'YES', 'YES', 'Supply kits for local shelters - first aid kit, disposable diapers, flashlight, coloring books, crayons, feminine hygiene, paper plates, cups', 149, '2017-09-20 16:02:37'),
(449, 9, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(450, 10, 39, 'YES', 'YES', 'Small warehouses in Thibodaux and Galliano', 149, '2017-09-20 16:02:37'),
(451, 11, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(452, 12, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(453, 13, 39, 'YES', 'YES', 'Matthew 25 volunteers in local Catholic churches', 149, '2017-09-20 16:02:37'),
(454, 14, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(455, 15, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(456, 16, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(457, 17, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(458, 18, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(459, 19, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(460, 20, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(461, 21, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(462, 22, 39, 'YES', 'YES', 'To a limited extent based on warehouse space', 149, '2017-09-20 16:02:37'),
(463, 23, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(464, 24, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(465, 25, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(466, 26, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(467, 27, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(468, 28, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(469, 29, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(470, 30, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(471, 31, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(472, 32, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(473, 33, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(474, 34, 39, 'YES', 'YES', '', 149, '2017-09-20 16:02:37'),
(475, 35, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(476, 36, 39, 'YES', 'YES', '', 149, '2017-09-20 16:02:37'),
(477, 37, 39, 'YES', 'YES', '', 149, '2017-09-20 16:02:37'),
(478, 38, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(479, 39, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(480, 40, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(481, 41, 39, 'YES', 'YES', 'Matthew 25 volunteers and Catholic church parking lots are available to distribute food, supplies, cooked meals in local communities', 149, '2017-09-20 16:02:37'),
(482, 42, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(483, 43, 39, 'NO', 'YES', 'At local food banks in Thibodaux, Raceland and Galliano', 149, '2017-09-20 16:02:37'),
(484, 44, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(485, 45, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(486, 46, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(487, 47, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(488, 48, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(489, 49, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(490, 50, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(491, 51, 39, 'YES', 'YES', 'clean up items such as mops, brooms, disinfectant, gloves, sponges, etc', 149, '2017-09-20 16:02:37'),
(492, 52, 39, 'YES', 'YES', 'Catholic Charities information and referral directory at https://www.htdiocese.org/documents/2017/6/2017%20Support%20Group%20List.pdf', 149, '2017-09-20 16:02:37'),
(493, 53, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(494, 54, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(495, 55, 39, 'YES', 'YES', 'Limited availability', 149, '2017-09-20 16:02:37'),
(496, 56, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(497, 57, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(498, 58, 39, 'NO', 'YES', 'Good Samaritan Thrift Stores in Raceland and Galliano', 149, '2017-09-20 16:02:37'),
(499, 59, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(500, 60, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(501, 61, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(502, 62, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(503, 63, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(504, 64, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(505, 65, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(506, 66, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(507, 67, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(508, 68, 39, 'YES', 'NO', 'St. Lucy Child Development Center 1224 Aycock Street Houma', 149, '2017-09-20 16:02:37'),
(509, 69, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(510, 70, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(511, 71, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(512, 72, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(513, 73, 39, 'YES', 'YES', 'Local Catholic priests and deacons', 149, '2017-09-20 16:02:37'),
(514, 74, 39, 'YES', 'YES', '', 149, '2017-09-20 16:02:37'),
(515, 75, 39, 'YES', 'YES', '', 149, '2017-09-20 16:02:37'),
(516, 76, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(517, 77, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(518, 78, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(519, 79, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(520, 80, 39, 'YES', 'YES', '', 149, '2017-09-20 16:02:37'),
(521, 81, 39, 'YES', 'YES', '', 149, '2017-09-20 16:02:37'),
(522, 82, 39, 'YES', 'YES', '', 149, '2017-09-20 16:02:37'),
(523, 83, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(524, 84, 39, 'YES', 'YES', '', 149, '2017-09-20 16:02:37'),
(525, 85, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(526, 86, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(527, 87, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(528, 88, 39, 'NO', 'NO', '', 149, '2017-09-20 16:02:37'),
(529, 1, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(530, 2, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(531, 3, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(532, 4, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(533, 5, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(534, 6, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(535, 7, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(536, 8, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(537, 9, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(538, 10, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(539, 11, 5, 'YES', 'NO', '', 8, '2017-09-26 00:21:07'),
(540, 12, 5, 'YES', 'NO', '', 8, '2017-09-26 00:21:07'),
(541, 13, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(542, 14, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(543, 15, 5, 'YES', 'YES', '', 8, '2017-09-26 00:21:07'),
(544, 16, 5, 'YES', 'YES', '', 8, '2017-09-26 00:21:07'),
(545, 17, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(546, 18, 5, 'YES', 'YES', '', 8, '2017-09-26 00:21:07'),
(547, 19, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(548, 20, 5, 'YES', 'NO', 'year round', 8, '2017-09-26 00:21:07'),
(549, 21, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(550, 22, 5, 'YES', 'NO', 'year round', 8, '2017-09-26 00:21:07'),
(551, 23, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(552, 24, 5, 'YES', 'NO', 'year round', 8, '2017-09-26 00:21:07'),
(553, 25, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(554, 26, 5, 'YES', 'NO', 'year round', 8, '2017-09-26 00:21:07'),
(555, 27, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(556, 28, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(557, 29, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(558, 30, 5, 'YES', 'YES', 'disaster event only', 8, '2017-09-26 00:21:07'),
(559, 31, 5, 'YES', 'YES', 'disaster event only', 8, '2017-09-26 00:21:07'),
(560, 32, 5, 'YES', 'YES', 'disaster event only', 8, '2017-09-26 00:21:07'),
(561, 33, 5, 'YES', 'YES', 'disaster event only', 8, '2017-09-26 00:21:07'),
(562, 34, 5, 'YES', 'NO', 'year round & disaster event specific', 8, '2017-09-26 00:21:07'),
(563, 35, 5, 'YES', 'NO', 'year round & disaster event specific', 8, '2017-09-26 00:21:07'),
(564, 36, 5, 'YES', 'YES', 'year round & disaster event specific', 8, '2017-09-26 00:21:07'),
(565, 37, 5, 'YES', 'YES', 'disaster event only', 8, '2017-09-26 00:21:07'),
(566, 38, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(567, 39, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(568, 40, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(569, 41, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(570, 42, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(571, 43, 5, 'YES', 'YES', 'disaster event only', 8, '2017-09-26 00:21:07'),
(572, 44, 5, 'YES', 'YES', 'advocate through case management - disaster event only', 8, '2017-09-26 00:21:07'),
(573, 45, 5, 'YES', 'YES', 'advocate through case management - disaster event only', 8, '2017-09-26 00:21:07'),
(574, 46, 5, 'YES', 'YES', 'advocate through case management - disaster event only', 8, '2017-09-26 00:21:07'),
(575, 47, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(576, 48, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(577, 49, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(578, 50, 5, 'YES', 'YES', 'disaster event only ', 8, '2017-09-26 00:21:07'),
(579, 51, 5, 'YES', 'YES', 'disaster event only ', 8, '2017-09-26 00:21:07'),
(580, 52, 5, 'YES', 'YES', 'disaster event only', 8, '2017-09-26 00:21:07'),
(581, 53, 5, 'YES', 'YES', 'disaster event only', 8, '2017-09-26 00:21:07'),
(582, 54, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(583, 55, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(584, 56, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(585, 57, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(586, 58, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(587, 59, 5, 'YES', 'YES', 'if available - for survivors in disaster case management', 8, '2017-09-26 00:21:07'),
(588, 60, 5, 'YES', 'YES', 'if available - for survivors in disaster case management', 8, '2017-09-26 00:21:07'),
(589, 61, 5, 'YES', 'YES', 'if available - for survivors in disaster case management', 8, '2017-09-26 00:21:07'),
(590, 62, 5, 'YES', 'YES', 'if available - for survivors in disaster case management', 8, '2017-09-26 00:21:07'),
(591, 63, 5, 'YES', 'YES', 'if available - for survivors in disaster case management', 8, '2017-09-26 00:21:07'),
(592, 64, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(593, 65, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(594, 66, 5, 'YES', 'YES', ' for survivors in disaster case management', 8, '2017-09-26 00:21:07'),
(595, 67, 5, 'YES', 'YES', 'upon request by local emergency management', 8, '2017-09-26 00:21:07'),
(596, 68, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(597, 69, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(598, 70, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(599, 71, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(600, 72, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(601, 73, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(602, 74, 5, 'YES', 'YES', '', 8, '2017-09-26 00:21:07'),
(603, 75, 5, 'YES', 'YES', '', 8, '2017-09-26 00:21:07'),
(604, 76, 5, 'YES', 'YES', '', 8, '2017-09-26 00:21:07'),
(605, 77, 5, 'YES', 'YES', '', 8, '2017-09-26 00:21:07'),
(606, 78, 5, 'YES', 'YES', '', 8, '2017-09-26 00:21:07'),
(607, 79, 5, 'YES', 'YES', '', 8, '2017-09-26 00:21:07'),
(608, 80, 5, 'YES', 'YES', '', 8, '2017-09-26 00:21:07'),
(609, 81, 5, 'YES', 'YES', '', 8, '2017-09-26 00:21:07'),
(610, 82, 5, 'YES', 'YES', 'disaster preparedness materials available upon request', 8, '2017-09-26 00:21:07'),
(611, 83, 5, 'YES', 'YES', '', 8, '2017-09-26 00:21:07'),
(612, 84, 5, 'YES', 'YES', '', 8, '2017-09-26 00:21:07'),
(613, 85, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(614, 86, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(615, 87, 5, 'NO', 'NO', '', 8, '2017-09-26 00:21:07'),
(616, 88, 5, 'YES', 'YES', '', 8, '2017-09-26 00:21:07'),
(617, 1, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(618, 2, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(619, 3, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(620, 4, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(621, 5, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(622, 6, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(623, 7, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(624, 8, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(625, 9, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(626, 10, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(627, 11, 91, 'YES', 'YES', 'Donations to the Bayou Recovery Fund  https://www.bayoucf.org/disaster-recovery/', 499, '2017-09-26 17:32:03'),
(628, 12, 91, 'YES', 'YES', 'Donations to the Bayou Recovery Fund  https://www.bayoucf.org/disaster-recovery/', 499, '2017-09-26 17:32:03'),
(629, 13, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(630, 14, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(631, 15, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(632, 16, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(633, 17, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(634, 18, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(635, 19, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(636, 20, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(637, 21, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(638, 22, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(639, 23, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(640, 24, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(641, 25, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(642, 26, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(643, 27, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(644, 28, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(645, 29, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(646, 30, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(647, 31, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(648, 32, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(649, 33, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(650, 34, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(651, 35, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(652, 36, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(653, 37, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(654, 38, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(655, 39, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(656, 40, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(657, 41, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(658, 42, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(659, 43, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(660, 44, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(661, 45, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(662, 46, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(663, 47, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(664, 48, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(665, 49, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(666, 50, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(667, 51, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(668, 52, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(669, 53, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(670, 54, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(671, 55, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(672, 56, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(673, 57, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(674, 58, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(675, 59, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(676, 60, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(677, 61, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(678, 62, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(679, 63, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(680, 64, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(681, 65, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(682, 66, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(683, 67, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(684, 68, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(685, 69, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(686, 70, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(687, 71, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(688, 72, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(689, 73, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(690, 74, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(691, 75, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(692, 76, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(693, 77, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(694, 78, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(695, 79, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(696, 80, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(697, 81, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(698, 82, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(699, 83, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(700, 84, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(701, 85, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(702, 86, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(703, 87, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(704, 88, 91, 'NO', 'NO', '', 499, '2017-09-26 17:32:03'),
(705, 1, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(706, 2, 64, 'YES', 'YES', '', 270, '2017-09-27 15:07:36'),
(707, 3, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(708, 4, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(709, 5, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(710, 6, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(711, 7, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(712, 8, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(713, 9, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(714, 10, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(715, 11, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(716, 12, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(717, 13, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(718, 14, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(719, 15, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(720, 16, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(721, 17, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(722, 18, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(723, 19, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(724, 20, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(725, 21, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(726, 22, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(727, 23, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(728, 24, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(729, 25, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(730, 26, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(731, 27, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(732, 28, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(733, 29, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(734, 30, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(735, 31, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(736, 32, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(737, 33, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(738, 34, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(739, 35, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(740, 36, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(741, 37, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(742, 38, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(743, 39, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(744, 40, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(745, 41, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(746, 42, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(747, 43, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(748, 44, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(749, 45, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(750, 46, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(751, 47, 64, 'YES', 'YES', '', 270, '2017-09-27 15:07:36'),
(752, 48, 64, 'YES', 'YES', '', 270, '2017-09-27 15:07:36'),
(753, 49, 64, 'YES', 'YES', '', 270, '2017-09-27 15:07:36'),
(754, 50, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(755, 51, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(756, 52, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(757, 53, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(758, 54, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(759, 55, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(760, 56, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(761, 57, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(762, 58, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(763, 59, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(764, 60, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(765, 61, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(766, 62, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(767, 63, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(768, 64, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(769, 65, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(770, 66, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(771, 67, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(772, 68, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(773, 69, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(774, 70, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(775, 71, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(776, 72, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(777, 73, 64, 'YES', 'YES', '', 270, '2017-09-27 15:07:36'),
(778, 74, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(779, 75, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(780, 76, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(781, 77, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(782, 78, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(783, 79, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(784, 80, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(785, 81, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(786, 82, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(787, 83, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(788, 84, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(789, 85, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(790, 86, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(791, 87, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(792, 88, 64, 'NO', 'NO', '', 270, '2017-09-27 15:07:36'),
(793, 1, 53, 'YES', 'YES', '', 465, '2017-09-27 15:13:24'),
(794, 2, 53, 'YES', 'YES', '', 465, '2017-09-27 15:13:24'),
(795, 3, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(796, 4, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(797, 5, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(798, 6, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(799, 7, 53, 'YES', 'YES', '', 465, '2017-09-27 15:13:24'),
(800, 8, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(801, 9, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(802, 10, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(803, 11, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(804, 12, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(805, 13, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(806, 14, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(807, 15, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(808, 16, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(809, 17, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(810, 18, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(811, 19, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(812, 20, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(813, 21, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(814, 22, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(815, 23, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(816, 24, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(817, 25, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(818, 26, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(819, 27, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(820, 28, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(821, 29, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(822, 30, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(823, 31, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(824, 32, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(825, 33, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(826, 34, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(827, 35, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(828, 36, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(829, 37, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(830, 38, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(831, 39, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(832, 40, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(833, 41, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(834, 42, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(835, 43, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(836, 44, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(837, 45, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(838, 46, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(839, 47, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(840, 48, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(841, 49, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(842, 50, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(843, 51, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(844, 52, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(845, 53, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(846, 54, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(847, 55, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(848, 56, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(849, 57, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(850, 58, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(851, 59, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(852, 60, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(853, 61, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(854, 62, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(855, 63, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(856, 64, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(857, 65, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(858, 66, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(859, 67, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(860, 68, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(861, 69, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(862, 70, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(863, 71, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(864, 72, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(865, 73, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(866, 74, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(867, 75, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(868, 76, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(869, 77, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(870, 78, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(871, 79, 53, 'YES', 'YES', '', 465, '2017-09-27 15:13:24'),
(872, 80, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(873, 81, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(874, 82, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(875, 83, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(876, 84, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(877, 85, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(878, 86, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(879, 87, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24'),
(880, 88, 53, 'NO', 'NO', '', 465, '2017-09-27 15:13:24');

-- --------------------------------------------------------

--
-- Table structure for table `services_list`
--

CREATE TABLE `services_list` (
  `id` int(11) NOT NULL,
  `major` varchar(255) DEFAULT NULL,
  `minor` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `services_list`
--

INSERT INTO cp_services_list (`id`, `major`, `minor`) VALUES
(1, 'Emergency Sheltering', 'Supplies'),
(2, 'Emergency Sheltering', 'Volunteers'),
(3, 'Emergency Sheltering', 'Food'),
(4, 'Emergency Sheltering', 'Bedding'),
(5, 'Transportation', 'Evacuation to Local Shelters'),
(6, 'Transportation', 'Evacuation to  Staging Area'),
(7, 'Transportation', 'Post-Disaster'),
(8, 'Warehousing', 'Permanent w/inventory of disaster supplies & donated items'),
(9, 'Warehousing', 'Permanent - accepting new inventory'),
(10, 'Warehousing', 'Temporary - short-term/disaster supplies & donated items'),
(11, 'Donations ', 'Coordination'),
(12, 'Donations ', 'Management'),
(13, 'Volunteers', 'Recruitment'),
(14, 'Volunteers', 'Housing'),
(15, 'Volunteers', 'Management, Coordination '),
(16, 'Volunteers', 'Worksite Supervision'),
(17, 'Volunteers', 'Reception Center Host'),
(18, 'Volunteers', 'Interpreters Spanish, Vietnamese, Cajun French '),
(19, 'Volunteers', 'Chaplains'),
(20, 'Volunteers-Will Accept ', 'Administrative'),
(21, 'Volunteers-Will Accept ', 'Financial'),
(22, 'Volunteers-Will Accept ', 'Donated Goods (Inventory, Warehouse, Logistics, Distribution)'),
(23, 'Volunteers-Will Accept ', 'Cooking/Feeding'),
(24, 'Volunteers-Will Accept ', 'General'),
(25, 'Volunteers-Will Accept ', 'Medical'),
(26, 'Volunteers-Will Accept ', 'Legal'),
(27, 'Volunteers-Will Accept ', 'Animal Care'),
(28, 'Volunteers-Will Accept ', 'Child Care'),
(29, 'Volunteers-Will Accept ', 'Elderly Care'),
(30, 'Volunteers-Will Accept ', 'Response Chainsaw Crew'),
(31, 'Volunteers-Will Accept ', 'Response Muck-Out/Demolition'),
(32, 'Volunteers-Will Accept ', 'Response Tarping/Roofing'),
(33, 'Volunteers-Will Accept ', 'Recovery Construction'),
(34, 'Volunteers-Will Accept ', 'Social Media  PR, Aggregating Data, Pages Setup'),
(35, 'Volunteers-Will Accept ', 'Information Technology Technicians '),
(36, 'Volunteers-Will Accept ', 'Photo/Video field documentation'),
(37, 'Volunteers-Will Accept ', 'Interpreters Spanish, Vietnamese, Cajun French '),
(38, 'Volunteers-Will Accept ', 'Chaplains '),
(39, 'Emergency Food', 'Cooking'),
(40, 'Emergency Food', 'Transport'),
(41, 'Emergency Food', 'Volunteers Food Servers'),
(42, 'Emergency Food', 'Interagency Coordinator for Supply & Demand '),
(43, 'Emergency Food', 'Disaster Food Stamp Application Assistance'),
(44, 'Emergency Housing', 'Rental Assistance'),
(45, 'Emergency Housing', 'Hotel Vouchers'),
(46, 'Emergency Housing', 'Air B & B'),
(47, 'Cleanup & Debris Removal', 'Chainsaw Crews/Tree Removal'),
(48, 'Cleanup & Debris Removal', 'Debris Removal'),
(49, 'Cleanup & Debris Removal', 'Muck Out/Mold Remediation'),
(50, 'Cleanup & Debris Removal', 'Tarps: Distribution and/or installation'),
(51, 'Cleanup & Debris Removal', 'Cleaning Supplies: Donation and/or Distribution'),
(52, 'Cleanup & Debris Removal', 'Referral Coordination'),
(53, 'Cleanup & Debris Removal', 'In-kind Donation Tracking (FEMA Public Assistance Match)'),
(54, 'Gift Cards/Vouchers', 'Food'),
(55, 'Gift Cards/Vouchers', 'Gas'),
(56, 'Gift Cards/Vouchers', 'Phone'),
(57, 'Gift Cards/Vouchers', 'General'),
(58, 'Personal /Household Goods', 'Clothing'),
(59, 'Personal /Household Goods', 'Bedding, Linens, Towels'),
(60, 'Personal /Household Goods', 'Hygiene Supplies'),
(61, 'Personal /Household Goods', 'Kitchen Supplies'),
(62, 'Personal /Household Goods', 'Furniture'),
(63, 'Personal /Household Goods', 'Appliances'),
(64, 'Personal /Household Goods', 'Baby Supplies: Food, Clothing, Hygiene'),
(65, 'Personal /Household Goods', 'Essential Tools'),
(66, 'Damage Assessments', 'Inspections and/or Estimates per household'),
(67, 'Damage Assessments', 'Post Disaster by geographic area'),
(68, 'Childcare/ Adultcare Services', 'Childcare '),
(69, 'Childcare/ Adultcare Services', 'Adultcare'),
(70, 'Mental Health Counseling', 'Crisis Counseling'),
(71, 'Mental Health Counseling', 'Mental Health Assessment & Referral'),
(72, 'Mental Health Counseling', 'Supportive Services'),
(73, 'Mental Health Counseling', 'Chaplains'),
(74, 'Long-Term Recovery', 'Funding / Resource Development'),
(75, 'Long-Term Recovery', 'South LA Long Term Recovery Committee (SOLA-LTRC)'),
(76, 'Long-Term Recovery', 'Reconstruction'),
(77, 'Long-Term Recovery', 'Mitigation elevation, floodproofing,relocation'),
(78, 'Long-Term Recovery', 'Housing Replacement'),
(79, 'Long-Term Recovery', 'Advocacy-applications, appeals, legal '),
(80, 'Long-Term Recovery', 'Disaster Case Management Response'),
(81, 'Long-Term Recovery', 'Disaster Case Management Long Term Recovery'),
(82, 'Disaster Preparedness  & Mitigation & Recovery ', 'Education Programs & Materials '),
(83, 'Disaster Preparedness  & Mitigation & Recovery ', 'Mitigation Program Referrals'),
(84, 'Disaster Preparedness  & Mitigation & Recovery ', 'Recovery Program Referrals'),
(85, 'Communication ', 'Social Media  PR, Aggregating Data, Pages Setup'),
(86, 'Communication ', 'Information Technology Technicians '),
(87, 'Communication ', 'Photo/Video field documentation'),
(88, 'Communication ', 'Interpreters Spanish, Vietnamese, Cajun French ');

-- --------------------------------------------------------

--
-- Table structure for table `volunteers`
--

CREATE TABLE `volunteers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zipcode` varchar(50) NOT NULL,
  `categories` longtext NOT NULL,
  `status` varchar(50) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `submitter_ip` varchar(50) NOT NULL,
  `submitted_date` datetime NOT NULL,
  `notes` longtext,
  `updated_date` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `volunteers`
--

INSERT INTO cp_volunteers (`id`, `first_name`, `last_name`, `email`, `phone`, `address`, `city`, `state`, `zipcode`, `categories`, `status`, `start_date`, `end_date`, `submitter_ip`, `submitted_date`, `notes`, `updated_date`, `updated_by`) VALUES
(2, 'Dan', 'David', 'dancdavid@icloud.com', '480-747-1223', '3299 E Windsor Dr', 'Gilbert', 'Gilbert', '85296', 'ADMINISTRATIVE;CHILD CARE;PHOTO/VIDEO', 'IN-ACTIVE', '2017-03-27', '2017-06-30', '2600:8800:2500:8b7:2d3e:4106:5058:70c1', '2017-03-01 15:35:22', '', '2017-03-04 02:09:21', 128),
(3, 'Thomas', 'Donohue', 'thomas.edward.donohue@gmail.com', '919-601-3425', '34327 Pebble Beach Lane', 'Polson', 'MT', '59860', 'ADMINISTRATIVE;ELDERLY CARE;CHILD CARE;SOCIAL MEDIA;IT TECHNICIAN;PHOTO/VIDEO;CHAINSAW CREW', 'NEW VOLUNTEER', '2017-03-27', '2017-06-30', '64.102.249.10', '2017-03-04 02:07:23', NULL, NULL, NULL),
(4, 'Dan', 'David', 'dandavid7@icloud.com', '480-747-1223', '1234 Test St', 'Gilbert', 'Gilbert', '85296', 'ADMINISTRATIVE;GENERAL;IT TECHNICIAN;PHOTO/VIDEO', 'IN-ACTIVE', '2017-03-27', '2017-06-30', '2600:8800:2500:8b7:cd5d:2647:5030:2321', '2017-03-16 10:46:14', '', '2017-07-02 18:23:47', 128),
(5, 'Thomas', 'Donohue', 'thomas.edward.donohue@gmail.com', '919-601-3425', '34327 PEBBLE BEACH LANE', 'POLSON', 'MT', '59860', 'GENERAL;LEGAL;ELDERLY CARE;IT TECHNICIAN;MOCK OUT;ROOFING', 'NEW VOLUNTEER', '2017-03-27', '2017-06-30', '2001:420:c0c8:1002::344', '2017-03-17 00:42:34', NULL, NULL, NULL),
(6, 'Thomas', 'Donohue', 'thomas.edward.donohue@gmail.com', '919-601-3425', '1234 Cherry Tree Lane', 'Missoula', 'MT', '59801', 'ADMINISTRATIVE;ELDERLY CARE;SOCIAL MEDIA;IT TECHNICIAN;PHOTO/VIDEO;CHAINSAW CREW', 'NEW VOLUNTEER', '2017-03-27', '2017-06-30', '2001:420:c0c8:1008::233', '2017-03-19 22:24:07', NULL, NULL, NULL),
(7, 'Test', 'User', 'thomas.edward.donohue@gmail.com', '919-601-3425', '123 Cherry Tree Lane', 'Missoula', 'MT', '59801', 'ADMINISTRATIVE;ELDERLY CARE;SOCIAL MEDIA;CHAINSAW CREW', 'NEW VOLUNTEER', '2017-04-01', '2017-04-30', '72.174.134.51', '2017-03-26 12:21:10', NULL, NULL, NULL),
(8, 'CAMERON', 'CASE', 'pegcase704@gmail.com', '985-991-1022', '704 Maple Ave', 'Houma', 'LA', '70364', 'ADMINISTRATIVE;LEGAL;TARPING', 'NEW VOLUNTEER', '2017-11-15', '2017-11-30', '2602:306:c5d1:4b60:91cf:57d1:f2ad:5313', '2017-09-26 02:26:32', NULL, NULL, NULL),
(9, 'Kevin', 'Gebhart', 'kevin@strongneighborhood.org', '318-528-9776', '1416 5th Street', 'Alexandria', 'LA', '71360', 'SOCIAL MEDIA', 'NEW VOLUNTEER', '2019-09-11', '2019-10-03', '199.38.60.2', '2019-09-18 12:50:30', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure for view `directory_services_full`
--
DROP TABLE IF EXISTS `directory_services_full`;

CREATE VIEW `directory_services_full`  AS  select `services_directory`.`agency_id` AS `agency_id`,`services_list`.`major` AS `major`,`services_list`.`minor` AS `minor`,`services_directory`.`terrebonne` AS `terrebonne`,`services_directory`.`lafourche` AS `lafourche`,`services_directory`.`comments` AS `comments` from (cp_services_directory join cp_services_list on((`services_list`.`id` = `services_directory`.`service_id`))) where ((`services_directory`.`terrebonne` = 'YES') or (`services_directory`.`lafourche` = 'YES')) ;

-- --------------------------------------------------------

--
-- Structure for view `directory_services_search`
--
DROP TABLE IF EXISTS `directory_services_search`;

CREATE VIEW `directory_services_search`  AS  select `directory_agency`.`agency_id` AS `agency_id`,`directory_agency`.`agency_name` AS `agency_name`,`directory_agency`.`agency_address` AS `agency_address`,`directory_agency`.`agency_city` AS `agency_city`,`directory_agency`.`agency_state` AS `agency_state`,`directory_agency`.`agency_zipcode` AS `agency_zipcode`,`directory_agency`.`agency_telephone` AS `agency_telephone`,`directory_agency`.`status` AS `status`,`services_directory`.`service_id` AS `service_id`,`services_directory`.`terrebonne` AS `terrebonne`,`services_directory`.`lafourche` AS `lafourche`,`services_list`.`major` AS `major`,`services_list`.`minor` AS `minor` from ((cp_directory_agency join cp_services_directory on((`services_directory`.`agency_id` = `directory_agency`.`agency_id`))) join cp_services_list on((`services_list`.`id` = `services_directory`.`service_id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `full_committee_contacts`
--
DROP TABLE IF EXISTS `full_committee_contacts`;

CREATE VIEW `full_committee_contacts`  AS  select `committee_contact`.`committee_contact_id` AS `committee_contact_id`,`committee_contact`.`committee_id` AS `committee_id`,`committee_contact`.`contact_id` AS `contact_id`,`directory_contact`.`agency_id` AS `agency_id`,`directory_contact`.`contact_name` AS `contact_name`,`directory_contact`.`contact_telephone` AS `contact_telephone`,`directory_contact`.`contact_cellphone` AS `contact_cellphone`,`directory_contact`.`contact_fax` AS `contact_fax`,`directory_contact`.`contact_pager` AS `contact_pager`,`directory_contact`.`contact_email` AS `contact_email` from (cp_committee_contact join cp_directory_contact on((`directory_contact`.`contact_id` = `committee_contact`.`contact_id`))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agency_logo`
--
ALTER TABLE cp_agency_logo
  ADD PRIMARY KEY (`agency_id`);

--
-- Indexes for table `committee_contact`
--
ALTER TABLE cp_committee_contact
  ADD PRIMARY KEY (`committee_contact_id`);

--
-- Indexes for table `committee_directory`
--
ALTER TABLE cp_committee_directory
  ADD PRIMARY KEY (`committee_id`);

--
-- Indexes for table `committee_list`
--
ALTER TABLE cp_committee_list
  ADD PRIMARY KEY (`committee_id`);

--
-- Indexes for table `directory_agency`
--
ALTER TABLE cp_directory_agency
  ADD PRIMARY KEY (`agency_id`);

--
-- Indexes for table `directory_contact`
--
ALTER TABLE cp_directory_contact
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `directory_service`
--
ALTER TABLE cp_directory_service
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `events_calendar`
--
ALTER TABLE cp_events_calendar
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message_board`
--
ALTER TABLE cp_message_board
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services_directory`
--
ALTER TABLE cp_services_directory
  ADD PRIMARY KEY (`id`),
  ADD KEY `agency_id_idx` (`agency_id`),
  ADD KEY `service_id_idx` (`service_id`);

--
-- Indexes for table `services_list`
--
ALTER TABLE cp_services_list
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `volunteers`
--
ALTER TABLE cp_volunteers
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `committee_contact`
--
ALTER TABLE cp_committee_contact
  MODIFY `committee_contact_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT for table `committee_directory`
--
ALTER TABLE cp_committee_directory
  MODIFY `committee_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `committee_list`
--
ALTER TABLE cp_committee_list
  MODIFY `committee_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `directory_agency`
--
ALTER TABLE cp_directory_agency
  MODIFY `agency_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=262;

--
-- AUTO_INCREMENT for table `directory_contact`
--
ALTER TABLE cp_directory_contact
  MODIFY `contact_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=516;

--
-- AUTO_INCREMENT for table `directory_service`
--
ALTER TABLE cp_directory_service
  MODIFY `service_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `events_calendar`
--
ALTER TABLE cp_events_calendar
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `message_board`
--
ALTER TABLE cp_message_board
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `services_directory`
--
ALTER TABLE cp_services_directory
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=881;

--
-- AUTO_INCREMENT for table `volunteers`
--
ALTER TABLE cp_volunteers
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
