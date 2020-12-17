
--
-- Table structure for table `cp_agency_instance`
--

CREATE TABLE `cp_agency_instance` (
  `id` int(11) NOT NULL,
  `agency_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `cp_directory_contact_contact_id` int(11) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `instance_name` varchar(45) DEFAULT NULL,
  `custom_url` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cp_directory_contact`
--

CREATE TABLE `cp_directory_contact` (
  `contact_id` int(11) NOT NULL,
  `agency_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cp_agency_instance`
--
ALTER TABLE `cp_agency_instance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cp_directory_contact`
--
ALTER TABLE `cp_directory_contact`
  ADD PRIMARY KEY (`contact_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cp_agency_instance`
--
ALTER TABLE `cp_agency_instance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cp_directory_contact`
--
ALTER TABLE `cp_directory_contact`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT;


--
-- Pre populate cp_directory_contact (for old values)
--
INSERT INTO cp_directory_contact (user_id, agency_id , created_at, updated_at)
SELECT org_users.id as user_id , org_users.agency_id, now() as created_at , now() as updated_at 
FROM org_users
JOIN cp_directory_agency ON cp_directory_agency.agency_id = org_users.agency_id
WHERE org_users.agency_id  IS NOT NULL;


ALTER TABLE `org_information` ADD `related_to_agency` INT NULL DEFAULT NULL AFTER `custom_login_url`;