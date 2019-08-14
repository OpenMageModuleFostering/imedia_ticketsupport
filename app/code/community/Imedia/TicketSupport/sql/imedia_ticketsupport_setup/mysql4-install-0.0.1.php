<?php

$installer = $this;

$installer->startSetup();


$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('ticket_details')};

CREATE TABLE IF NOT EXISTS {$this->getTable('ticket_details')} (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `ticketid` int(5) NOT NULL,
  `sender` varchar(50) NOT NULL,
  `comments` varchar(300) NOT NULL,
  `commentdate` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


-- DROP TABLE IF EXISTS {$this->getTable('ticket_support')};

CREATE TABLE IF NOT EXISTS {$this->getTable('ticket_support')} (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `created_at` date NOT NULL,
  `title` varchar(100) NOT NULL,
  `dept` varchar(100) NOT NULL,
  `priority` varchar(50) NOT NULL,
  `customerid` int(5) NOT NULL,
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;



    ");


$installer->endSetup();