CREATE TABLE `nginteger` (
  `contentobject_attribute_id` int(11) NOT NULL default '0',
  `version` int(11) NOT NULL default '0',
  `first_number` int(11) NOT NULL default '0',
  `second_number` int(11) NOT NULL default '0',
  PRIMARY KEY ( `contentobject_attribute_id`, `version` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
