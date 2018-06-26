-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2018 at 10:23 PM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 7.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clinc`
--

-- --------------------------------------------------------

--
-- Table structure for table `clinc_categories`
--

CREATE TABLE `clinc_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(60) DEFAULT NULL,
  `url_name` varchar(200) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clinc_categories`
--

INSERT INTO `clinc_categories` (`id`, `name`, `url_name`, `description`) VALUES
(1, 'Tidak Terkategori', 'tidak_terkategori', 'Tidak Terkategori'),
(2, 'Emma Furlong', 'emma_furlong', 'Emma Furlong'),
(3, 'AI For Banks', 'ai_for_banks', 'AI For Banks'),
(4, 'Conversational AI', 'conversational_ai', 'Conversational AI'),
(5, 'Financial Services', 'financial_services', 'Financial Services'),
(6, 'Fintech', 'fintech', 'Fintech');

-- --------------------------------------------------------

--
-- Table structure for table `clinc_comments`
--

CREATE TABLE `clinc_comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `author` varchar(50) DEFAULT NULL,
  `author_email` varchar(100) DEFAULT NULL,
  `author_ip` varchar(100) NOT NULL,
  `content` text,
  `date` datetime DEFAULT '0000-00-00 00:00:00',
  `modded` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `clinc_contacts`
--

CREATE TABLE `clinc_contacts` (
  `id` int(11) NOT NULL,
  `fname` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `lname` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `company_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `job_title` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `aboutus` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `clinc_contacts`
--

INSERT INTO `clinc_contacts` (`id`, `fname`, `lname`, `email`, `company_name`, `job_title`, `aboutus`, `message`) VALUES
(1, 'aa', 'aa', 'admin@admin.com', 'aa', 'aa', 'aa', 'aa');

-- --------------------------------------------------------

--
-- Table structure for table `clinc_groups`
--

CREATE TABLE `clinc_groups` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  `protected` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clinc_groups`
--

INSERT INTO `clinc_groups` (`id`, `name`, `description`, `protected`) VALUES
(1, 'admin', 'Administrator', 1),
(2, 'members', 'General User', 1),
(3, 'contributors', 'Contributor', 1),
(4, 'editors', 'Editor', 1);

-- --------------------------------------------------------

--
-- Table structure for table `clinc_groups_perms`
--

CREATE TABLE `clinc_groups_perms` (
  `id` int(11) NOT NULL,
  `perms_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clinc_groups_perms`
--

INSERT INTO `clinc_groups_perms` (`id`, `perms_id`, `group_id`) VALUES
(1, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `clinc_group_permissions`
--

CREATE TABLE `clinc_group_permissions` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(150) NOT NULL,
  `protected` int(1) NOT NULL DEFAULT '0',
  `form_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clinc_group_permissions`
--

INSERT INTO `clinc_group_permissions` (`id`, `name`, `description`, `protected`, `form_name`) VALUES
(1, 'users', 'Users', 1, ''),
(2, 'posts', 'Posts', 1, ''),
(3, 'pages', 'Pages', 1, ''),
(4, 'links', 'Links', 1, ''),
(5, 'social', 'Social', 1, ''),
(6, 'comments', 'Comments', 1, ''),
(7, 'navigation', 'Navigation', 1, ''),
(9, 'settings', 'Settings', 1, ''),
(11, 'dashboard', 'Dashboard', 1, ''),
(12, 'cats', 'Categories', 1, ''),
(13, 'lang', 'Language', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `clinc_languages`
--

CREATE TABLE `clinc_languages` (
  `id` int(11) NOT NULL,
  `language` varchar(100) DEFAULT NULL,
  `abbreviation` varchar(3) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `author_website` varchar(255) NOT NULL,
  `is_default` enum('0','1') DEFAULT NULL,
  `is_avail` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clinc_languages`
--

INSERT INTO `clinc_languages` (`id`, `language`, `abbreviation`, `author`, `author_website`, `is_default`, `is_avail`) VALUES
(1, 'english', 'en', 'Enliven Applications', 'http://www.clinc.org', '0', 1),
(2, 'indonesian', 'id', 'Enliven Applications', 'http://www.clinc.org', '1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `clinc_links`
--

CREATE TABLE `clinc_links` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `target` varchar(20) DEFAULT '_blank',
  `description` varchar(100) DEFAULT NULL,
  `visible` enum('yes','no') DEFAULT 'yes',
  `position` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `clinc_login_attempts`
--

CREATE TABLE `clinc_login_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `clinc_migrations`
--

CREATE TABLE `clinc_migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clinc_migrations`
--

INSERT INTO `clinc_migrations` (`version`) VALUES
(20170123000001);

-- --------------------------------------------------------

--
-- Table structure for table `clinc_navigation`
--

CREATE TABLE `clinc_navigation` (
  `id` int(11) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `external` enum('0','1') NOT NULL DEFAULT '0',
  `position` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clinc_navigation`
--

INSERT INTO `clinc_navigation` (`id`, `title`, `description`, `url`, `external`, `position`) VALUES
(1, 'Home', 'Home', '', '0', '0'),
(3, 'About Us', 'Tentang Kami', 'about', '0', '2'),
(4, 'Career', 'Tentang informasi pekerjaan dibidang AI', 'career', '0', '3'),
(5, 'Blog', 'Blog', 'blog', '0', '4'),
(6, 'Press', 'Tentang informasi pers', 'press', '0', '5'),
(7, 'Services', 'Tentang informasi layanan', 'services', '0', '6'),
(8, 'Contact', 'Kontak kami apabila perlu', 'contact', '0', '7');

-- --------------------------------------------------------

--
-- Table structure for table `clinc_notifications`
--

CREATE TABLE `clinc_notifications` (
  `id` int(11) NOT NULL,
  `email_address` varchar(200) NOT NULL,
  `verify_code` varchar(200) NOT NULL,
  `verified` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `clinc_pages`
--

CREATE TABLE `clinc_pages` (
  `id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `url_title` varchar(200) DEFAULT NULL,
  `author` int(11) DEFAULT '0',
  `date` date NOT NULL,
  `content` text,
  `status` enum('active','inactive') DEFAULT 'active',
  `is_home` int(1) NOT NULL DEFAULT '0',
  `meta_title` varchar(200) NOT NULL,
  `meta_keywords` varchar(200) NOT NULL,
  `meta_description` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `clinc_posts`
--

CREATE TABLE `clinc_posts` (
  `id` int(11) NOT NULL,
  `author` int(11) NOT NULL DEFAULT '0',
  `date_posted` date NOT NULL DEFAULT '0000-00-00',
  `title` varchar(200) NOT NULL,
  `url_title` varchar(200) NOT NULL,
  `excerpt` text NOT NULL,
  `content` longtext NOT NULL,
  `feature_image` varchar(255) DEFAULT NULL,
  `allow_comments` enum('0','1') NOT NULL DEFAULT '1',
  `sticky` enum('0','1') NOT NULL DEFAULT '0',
  `status` enum('draft','published') NOT NULL DEFAULT 'published',
  `meta_title` varchar(200) NOT NULL,
  `meta_keywords` varchar(200) NOT NULL,
  `meta_description` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clinc_posts`
--

INSERT INTO `clinc_posts` (`id`, `author`, `date_posted`, `title`, `url_title`, `excerpt`, `content`, `feature_image`, `allow_comments`, `sticky`, `status`, `meta_title`, `meta_keywords`, `meta_description`) VALUES
(2, 1, '2018-06-26', 'AI, Customer Experience and Fintech Innovation', 'ai-customer-experience-and-fintech-innovation', 'Money 20/20 US, the world’s largest payments and fintech conference, drew more than 11,000 financial leaders and executives from over 4,500 companies. It was the perfect stage for Financial Tech Startups (fintechs) to explore new partnerships and for Financial Institutions (FIs) to scour the market for the latest innovations to stay ahead in an increasingly demanding consumer landscape.', 'Money 20/20 US, the world’s largest payments and fintech conference, drew more than 11,000 financial leaders and executives from over 4,500 companies. It was the perfect stage for Financial Tech Startups (fintechs) to explore new partnerships and for Financial Institutions (FIs) to scour the market for the latest innovations to stay ahead in an increasingly demanding consumer landscape.\r\n\r\nWhile the conference spanned a multitude of areas within the financial ecosystem, a few key themes echoed throughout the sessions.\r\n\r\n![](https://cdn-images-1.medium.com/max/800/1*U9fFEGitn8Qv_SMJPhbymA.png)\r\n\r\n**Tech Partnerships: A Requirement for Success\r\n**\r\nExecutives from several major financial institutions expressed the importance of collaborating with tech companies to reach new levels of innovation. Dan Schulman, CEO &amp;amp;amp; President of PayPal, stressed that strategic partnerships between FIs and Fintechs have the unique ability to create opportunities that provide broader, better offerings and expand to new customer segments.\r\n\r\nHow are these opportunities created? Financial institutions can leverage competition between tech companies to refine and select more advanced and increasingly innovative solutions.\r\n\r\nConversely, due to their size and narrowed focus, fintechs can take their products to market more quickly (and for less cost) than if their larger counterparts tried to build that same product in-house.\r\n\r\nThis sentiment was reiterated by Jack Stephenson, SVP of Digital Commerce Solutions for First Data, who argued,\r\n\r\n“Not only should payments organizations work together to innovate but they need the help of tech partnerships from startups to established software providers”\r\nUSAA, widely regarded as a first-adopter and predictor of new technology, served as a living example of these principles. Darrius Jones, SVP of Innovation at USAA took the Money 20/20 stage with Clinc, the conversational AI startup, to announce and demo their virtual financial assistant on an Amazon Alexa. Jones said of the partnership,\r\n\r\n“it’s been a great experience pushing the envelope with Clinc”\r\nThe rise of financial institutions’ partnership and investment in fintechs is indicative of the digital revolution disrupting the banking and payments industry. A revolution that is a direct response to an increasing amount of consumer demand for better, more empowering, simplified experiences. Forbes contributor, Nikolai Kuznetsov, articulated this consumer-shift earlier this year, when he argued,\r\n\r\n“Gone are the days when people are comfortable dealing with just a single entity for all their financial needs. People simply aren’t happy with how their banks treat them and have become increasingly receptive to new solutions”\r\nThroughout Money 20/20 it became clear that Fintech and FI collaboration is crucial for mutual success, and that consumer desires are the driving force behind the onset of those collaborations.\r\n\r\n \r\n\r\n**Customer Experience Drives Innovation\r\n**\r\nIf we learned one thing from the conference, it’s that banks are rearing to partner with fintechs because consumers are asking for experiences that require newer, more advanced technology; technology that, due to the structure and nature of large institutions, is difficult and expensive for banks to create themselves.\r\n\r\nIn the breakout session “Contextual Commerce, Pay Where You Are”, Patrick Gauthier, VP of Amazon Pay, insisted that,\r\n\r\n“customers want a frictionless experience”\r\nAnd according to the Global Web Index, that “frictionless experience” is increasingly voice-first,\r\n\r\n“1 in 3 (customers from) Generation Z are using virtual assistants with voice commands on mobile to complete tasks, and get information”\r\nAdopting a voice-first approach to finance enables banks to be more inclusive, and to expand globally. In addition to their partnership with USAA, Clinc took the stage with Isbank, the nation’s largest private bank, to deliver\r\n\r\n“an omnichannel conversational banking experience to customers in Turkey, in the Turkish language” — Isbank.\r\nWith this announcement, Clinc became the first conversational AI platform to deploy such a robust multi-langauge solution for personal finance, supporting over 80 languages with active deployments in 6 countries.\r\n\r\nCompanies like USAA and Isbank are leading the charge with their strategic partnerships in a field that is exploding with hype.From voice-first banking, to biometric authentication, AI is permeating almost every conceivable corner of the financial services industry, to deliver better customer experiences and to give autonomy back to users.\r\n\r\n \r\n\r\n**AI: An Inevitable and Necessary Future\r\n**\r\nMoney 20/20 added a new category to the conference this year, AI Deep Dive, aimed at facilitating discussion around the future of AI in banking and payments, and the ways in which it can transform those industries.\r\n\r\n![](https://cdn-images-1.medium.com/max/800/1*HvHVtSEw1N_Jli9YJ_V-Ug.jpeg)\r\n\r\nAI sessions spanned from fraud protection to innovative payment solutions and called big names like Steve Wozniak and Dr. Michio Kaku to the stage.\r\n\r\nWozniak insisted “AI is happening” and that anyone who tries to get in its way is just “getting in front of the steamroller”. Similarly, Dr. Matt Wood, GM of Deep Learning and AI at Amazon Web Services, argued,\r\n\r\n“machine learning is the future of business growth”\r\nThe mentality around AI at Money 20/20 was one of necessary adoption for growth, progress, and customer satisfaction. However, AI isn’t just critical to growth and innovation, innovation but to competition and survival as well.\r\n\r\nIn a recent interview, Spiros Margaris, VC and award-winning fintech influencer argued,\r\n\r\n“It is becoming increasingly clear that Artificial Intelligence, with the new advancements in machine learning algorithms that mimic the human brain, will change how most businesses stay competitive”\r\nThe necessity for AI in business is indicative of the onset of what many are calling the “digital revolution” Andrew Ng, founder of the Google Brain Deep Learning Project, said of the revolution,\r\n\r\n“AI has advanced to the point where it has the power to transform every major sector in coming years”\r\nWhen considering the applications for AI in banking, you quickly realize how inextricably tied they are to customer experience. While certain FIs may try and produce AI solutions on their own, it is indubitably more efficient, and cost effective to collaborate with fintechs whose sole mission is creating and distributing great AI.\r\n\r\nThere is no clearer example this than the AI used in the partnerships and innovations announced at Money 20/20. Clinc’s omnichannel, multi-language, conversational AI serves as a metaphor for the ways in which partnerships, AI and a deliberate focus on improving user experiences can lead to faster, better innovations and ultimately, the digital revolution.', 'img1.jpg', '1', '0', 'published', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `clinc_posts_to_categories`
--

CREATE TABLE `clinc_posts_to_categories` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clinc_posts_to_categories`
--

INSERT INTO `clinc_posts_to_categories` (`id`, `post_id`, `category_id`) VALUES
(2, 2, 3),
(3, 2, 4);

-- --------------------------------------------------------

--
-- Table structure for table `clinc_redirects`
--

CREATE TABLE `clinc_redirects` (
  `id` int(11) NOT NULL,
  `old_slug` varchar(200) NOT NULL,
  `new_slug` varchar(200) NOT NULL,
  `type` varchar(4) NOT NULL DEFAULT 'post',
  `code` varchar(3) NOT NULL DEFAULT '301'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `clinc_settings`
--

CREATE TABLE `clinc_settings` (
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `tab` varchar(50) NOT NULL,
  `field_type` varchar(50) NOT NULL,
  `options` varchar(200) NOT NULL,
  `required` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clinc_settings`
--

INSERT INTO `clinc_settings` (`name`, `value`, `tab`, `field_type`, `options`, `required`) VALUES
('admin_email', 'wicayudha@wicayudha.com', 'email', 'text', '', 1),
('allow_comments', '1', 'comments', 'dropdown', '1=yes|0=no', 1),
('allow_registrations', 'true', 'users', 'dropdown', 'true=yes|false=no', 1),
('base_controller', 'blog', 'general', 'dropdown', 'blog=blog|pages=pages', 1),
('blog_description', 'Conversational AI Platform For Enterprise', 'general', 'text', '', 0),
('category_list_limit', '10', 'categories', 'dropdown', '10=10|20=20|30=30', 1),
('email_activation', 'true', 'users', 'dropdown', 'true=yes|false=no', 1),
('links_per_box', '10', 'links', 'dropdown', '10=10|20=20|30=30', 1),
('mail_protocol', 'mail', 'email', 'dropdown', 'mail=mail|smtp=smtp|sendmail=sendmail', 1),
('manual_activation', 'false', 'users', 'dropdown', 'true=yes|false=no', 1),
('mod_non_user_comments', '1', 'comments', 'dropdown', '1=yes|0=no', 1),
('mod_user_comments', '0', 'comments', 'dropdown', '1=yes|0=no', 1),
('months_per_archive', '10', 'archives', 'dropdown', '10=10|20=20|30=30', 1),
('posts_per_page', '10', 'blog', 'dropdown', '10=10|20=20|30=30', 1),
('recaptcha_private_key', '', 'captcha', 'text', '', 0),
('recaptcha_site_key', '', 'captcha', 'text', '', 0),
('sendmail_path', '/usr/sbin/sendmail', 'email', 'text', '', 0),
('server_email', 'wicayudha@wicayudha.com', 'email', 'text', '', 1),
('site_name', 'Clinc', 'general', 'text', '', 1),
('smtp_host', '', 'email', 'text', '', 0),
('smtp_pass', '', 'email', 'text', '', 0),
('smtp_port', '', 'email', 'text', '', 0),
('smtp_user', '', 'email', 'text', '', 0),
('use_honeypot', '0', 'captcha', 'dropdown', '1=yes|0=no', 1),
('use_recaptcha', '0', 'captcha', 'dropdown', '1=yes|0=no', 1);

-- --------------------------------------------------------

--
-- Table structure for table `clinc_sidebar`
--

CREATE TABLE `clinc_sidebar` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `file` varchar(100) NOT NULL,
  `status` enum('enabled','disabled') NOT NULL,
  `position` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clinc_sidebar`
--

INSERT INTO `clinc_sidebar` (`id`, `title`, `file`, `status`, `position`) VALUES
(1, 'Search', 'search', 'enabled', '1'),
(2, 'Archive', 'archive', 'enabled', '2'),
(3, 'Categories', 'categories', 'enabled', '3'),
(4, 'Tag_cloud', 'tag_cloud', 'enabled', '4'),
(5, 'Feeds', 'feeds', 'enabled', '5'),
(6, 'Links', 'links', 'enabled', '6'),
(7, 'Other', 'other', 'enabled', '7');

-- --------------------------------------------------------

--
-- Table structure for table `clinc_social`
--

CREATE TABLE `clinc_social` (
  `id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `enabled` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clinc_social`
--

INSERT INTO `clinc_social` (`id`, `name`, `url`, `enabled`) VALUES
(1, 'Facebook', NULL, 0),
(2, 'Twitter', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `clinc_templates`
--

CREATE TABLE `clinc_templates` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(200) NOT NULL,
  `author` varchar(100) DEFAULT NULL,
  `author_email` varchar(100) NOT NULL,
  `path` varchar(100) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `is_default` enum('0','1') DEFAULT '1',
  `is_active` varchar(1) NOT NULL DEFAULT '0',
  `is_admin` varchar(1) NOT NULL DEFAULT '0',
  `version` varchar(10) NOT NULL DEFAULT '1.0.0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clinc_templates`
--

INSERT INTO `clinc_templates` (`id`, `name`, `description`, `author`, `author_email`, `path`, `image`, `is_default`, `is_active`, `is_admin`, `version`) VALUES
(1, 'Default', 'The default theme for Clinc', 'Enliven Applications', 'info@clinc.org', 'default', 'default.png', '1', '1', '0', '1.0.0'),
(2, 'Default Admin', 'The default admin theme for Clinc', 'Enliven Applications', 'info@clinc.org', 'default_admin', 'default_admin.png', '1', '1', '1', '1.0.0');

-- --------------------------------------------------------

--
-- Table structure for table `clinc_users`
--

CREATE TABLE `clinc_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) UNSIGNED DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) UNSIGNED NOT NULL,
  `last_login` int(11) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clinc_users`
--

INSERT INTO `clinc_users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES
(1, '127.0.0.1', 'wicayudha', '$2y$08$4CBhJa8i3ETMqDj1clrYk.BjYFZcpIauAHuGCsQ13GsmoijAG9wzK', 'XApuXc8O4J/GmIWxRVbtDO', 'wicayudha@wicayudha.com', NULL, NULL, NULL, '7/wUV3KvmgJosWEK.7r8cO', 1530023845, 1530023868, 1, 'Wica', 'Yudha', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `clinc_users_groups`
--

CREATE TABLE `clinc_users_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clinc_users_groups`
--

INSERT INTO `clinc_users_groups` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clinc_categories`
--
ALTER TABLE `clinc_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clinc_comments`
--
ALTER TABLE `clinc_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clinc_contacts`
--
ALTER TABLE `clinc_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clinc_groups`
--
ALTER TABLE `clinc_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clinc_groups_perms`
--
ALTER TABLE `clinc_groups_perms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clinc_group_permissions`
--
ALTER TABLE `clinc_group_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clinc_languages`
--
ALTER TABLE `clinc_languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clinc_links`
--
ALTER TABLE `clinc_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clinc_login_attempts`
--
ALTER TABLE `clinc_login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clinc_navigation`
--
ALTER TABLE `clinc_navigation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clinc_notifications`
--
ALTER TABLE `clinc_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clinc_pages`
--
ALTER TABLE `clinc_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clinc_posts`
--
ALTER TABLE `clinc_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clinc_posts_to_categories`
--
ALTER TABLE `clinc_posts_to_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clinc_redirects`
--
ALTER TABLE `clinc_redirects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clinc_settings`
--
ALTER TABLE `clinc_settings`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `clinc_sidebar`
--
ALTER TABLE `clinc_sidebar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clinc_social`
--
ALTER TABLE `clinc_social`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clinc_templates`
--
ALTER TABLE `clinc_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clinc_users`
--
ALTER TABLE `clinc_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clinc_users_groups`
--
ALTER TABLE `clinc_users_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_bp_users_groups` (`user_id`,`group_id`),
  ADD KEY `fk_bp_users_groups_users1_idx` (`user_id`),
  ADD KEY `fk_bp_users_groups_groups1_idx` (`group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clinc_categories`
--
ALTER TABLE `clinc_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `clinc_comments`
--
ALTER TABLE `clinc_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clinc_contacts`
--
ALTER TABLE `clinc_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `clinc_groups`
--
ALTER TABLE `clinc_groups`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `clinc_groups_perms`
--
ALTER TABLE `clinc_groups_perms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `clinc_group_permissions`
--
ALTER TABLE `clinc_group_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `clinc_languages`
--
ALTER TABLE `clinc_languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `clinc_links`
--
ALTER TABLE `clinc_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `clinc_login_attempts`
--
ALTER TABLE `clinc_login_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clinc_navigation`
--
ALTER TABLE `clinc_navigation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `clinc_notifications`
--
ALTER TABLE `clinc_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clinc_pages`
--
ALTER TABLE `clinc_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `clinc_posts`
--
ALTER TABLE `clinc_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `clinc_posts_to_categories`
--
ALTER TABLE `clinc_posts_to_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `clinc_redirects`
--
ALTER TABLE `clinc_redirects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clinc_sidebar`
--
ALTER TABLE `clinc_sidebar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `clinc_social`
--
ALTER TABLE `clinc_social`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `clinc_templates`
--
ALTER TABLE `clinc_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `clinc_users`
--
ALTER TABLE `clinc_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `clinc_users_groups`
--
ALTER TABLE `clinc_users_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clinc_users_groups`
--
ALTER TABLE `clinc_users_groups`
  ADD CONSTRAINT `fk_bp_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `clinc_groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_bp_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `clinc_users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
