--
-- Database: `hippocampus`
--

DROP TABLE IF EXISTS `config`;
DROP TABLE IF EXISTS `users-1auth`;
DROP TABLE IF EXISTS `user-sessions`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `watchdog`;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `varkey` varchar(64) NOT NULL,
  `value` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`varkey`, `value`) VALUES
('site.maintenance', '0'),
('site.name', 'Hippocampus'),
('site.theme', 'default'),
('site.url', '?'),
('db.version', '10');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'root'),
(2, 'administrator'),
(3, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `user-sessions`
--

CREATE TABLE `user-sessions` (
  `userid` int(11) NOT NULL,
  `device` varchar(64) NOT NULL,
  `alc` varchar(64) NOT NULL COMMENT 'Auto Login Cookie',
  `dvc` varchar(64) NOT NULL COMMENT 'Dynamic Value Cookie',
  `ip` varchar(45) NOT NULL,
  `activeSession` tinyint(1) NOT NULL,
  `firstUseSession` int(11) NOT NULL,
  `lastUseSession` int(11) NOT NULL,
  `firstUseCoordLat` double NOT NULL,
  `firstUseCoordLong` double NOT NULL,
  `useragent` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `confirmedEmail` tinyint(1) NOT NULL,
  `secretToken` varchar(32) NOT NULL,
  `role` int(11) NOT NULL,
  `boxesconfig` varchar(128) NOT NULL DEFAULT '[["none", "none", "none"]]',
  `name` varchar(32) NOT NULL DEFAULT 'NAME',
  `surname` varchar(32) NOT NULL DEFAULT 'SURNAME',
  `profilepic` varchar(32) NOT NULL DEFAULT 'PROFILEPICPATH'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

-- --------------------------------------------------------

--
-- Table structure for table `users-1auth`
--

CREATE TABLE `users-1auth` (
  `id` int(11) NOT NULL,
  `pw` char(64) NOT NULL,
  `salt` varchar(32) NOT NULL,
  `csalt` varchar(32) NOT NULL COMMENT 'Salt sent to the client. The password is hashed once in the client with this salt. Then it is hashed again in the server.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Dumping data for table `users-1auth`
--

-- --------------------------------------------------------

--
-- Table structure for table `watchdog`
--

CREATE TABLE `watchdog` (
  `id` int(11) NOT NULL,
  `sessionid` int(11) DEFAULT NULL,
  `level` varchar(16) NOT NULL,
  `message` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`varkey`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user-sessions`
--
ALTER TABLE `user-sessions`
  ADD PRIMARY KEY (`userid`,`alc`) USING BTREE;

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role` (`role`);

--
-- Indexes for table `users-1auth`
--
ALTER TABLE `users-1auth`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `watchdog`
--
ALTER TABLE `watchdog`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `user-sessions`
--
ALTER TABLE `user-sessions`
  ADD CONSTRAINT `user-sessions_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`role`) REFERENCES `roles` (`id`);

--
-- Constraints for table `users-1auth`
--
ALTER TABLE `users-1auth`
  ADD CONSTRAINT `users-1auth_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
