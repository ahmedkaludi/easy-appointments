-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 24, 2015 at 01:56 PM
-- Server version: 5.5.43-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.9

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

USE easyappointments;

TRUNCATE TABLE `wp_ea_fields`;
UPDATE `wp_ea_appointments` SET `status` = 'canceled';
TRUNCATE TABLE `wp_ea_connections`;
TRUNCATE TABLE `wp_ea_locations`;
TRUNCATE TABLE `wp_ea_options`;
TRUNCATE TABLE `wp_ea_services`;
TRUNCATE TABLE `wp_ea_staff`;

INSERT INTO `wp_ea_staff` (`id`, `name`, `description`, `email`, `phone`) VALUES
(1, 'John Smit', 'Worker 1', 'someemail@email.com', '123456'),
(2, 'Peter Dalas', 'Worker 2', 'dummy@email.com', '112233');

INSERT INTO `wp_ea_locations` (`id`, `name`, `address`, `location`, `cord`) VALUES
(1, 'New York', 'Street 1', 'New York', ''),
(2, 'Washington DC', 'Street 10', 'Wasington DC', '');

INSERT INTO `wp_ea_services` (`id`, `name`, `duration`, `price`) VALUES
(1, 'Car wash', 60, 25),
(2, 'Car polishing', 45, 10);

INSERT INTO `wp_ea_connections` (`id`, `group_id`, `location`, `service`, `worker`, `day_of_week`, `time_from`, `time_to`, `day_from`, `day_to`, `is_working`) VALUES
(1, 0, 2, 2, 2, 'Monday,Tuesday,Wednesday,Thursday,Friday', '07:00:00', '18:00:00', '2015-01-01', '2020-01-01', 1),
(2, 0, 2, 1, 2, 'Monday,Tuesday,Wednesday,Thursday,Friday', '07:00:00', '18:00:00', '2015-01-01', '2020-01-01', 1),
(3, 0, 1, 1, 2, 'Monday,Tuesday,Wednesday,Thursday,Friday', '07:00:00', '18:00:00', '2015-01-01', '2020-01-01', 1),
(4, 0, 1, 2, 2, 'Monday,Tuesday,Wednesday,Thursday,Friday', '07:00:00', '18:00:00', '2015-01-01', '2020-01-01', 1),
(5, 0, 2, 2, 1, 'Monday,Tuesday,Wednesday,Thursday,Friday', '07:00:00', '18:00:00', '2015-01-01', '2020-01-01', 1),
(6, 0, 1, 1, 1, 'Monday,Tuesday,Wednesday,Thursday,Friday', '07:00:00', '18:00:00', '2015-01-01', '2020-01-01', 1),
(7, 0, 2, 2, 1, 'Monday,Tuesday,Wednesday,Thursday,Friday', '07:00:00', '18:00:00', '2015-01-01', '2020-01-01', 1),
(8, 0, 2, 2, 1, 'Monday,Tuesday,Wednesday,Thursday,Friday', '07:00:00', '18:00:00', '2015-01-01', '2020-01-01', 1);


INSERT INTO `wp_ea_options` (`id`, `ea_key`, `ea_value`, `type`) VALUES
(1, 'mail.pending', 'pending', 'default'),
(2, 'mail.reservation', 'reservation', 'default'),
(3, 'mail.canceled', 'canceled', 'default'),
(4, 'mail.confirmed', 'confirmed', 'default'),
(5, 'trans.service', 'Service', 'default'),
(6, 'trans.location', 'Location', 'default'),
(7, 'trans.worker', 'Worker', 'default'),
(8, 'trans.done_message', 'Done', 'default'),
(9, 'time_format', 'am-pm', 'default'),
(10, 'trans.currency', '$', 'default'),
(11, 'pending.email', 'nikolanbg@gmail.com', 'default'),
(12, 'price.hide', '0', 'default'),
(13, 'datepicker', 'en-US', 'default'),
(14, 'send.user.email', '0', 'default'),
(15, 'custom.css', 'body .site-header{padding-top:0}body .entry-content .calendar a{box-shadow:0 0}#booking-overview table{width:100%;border-left:1px dotted #ccc;border-right:1px dotted #ccc;border-bottom:1px dotted #ccc}#booking-overview table td{padding:5px;border-top:1px dotted #ccc}.ea-standard.ea-standard .step input{margin-top:5px;width:63%}.ea-standard.ea-standard .step label{font-weight:400;width:37%}.ea-standard.ea-standard .step select{width:63%;margin-top:5px;display:inline-block} #ui-datepicker-div{display:none}', 'default'),
(16, 'show.iagree', '1', 'default'),
(17, 'cancel.scroll', 'calendar', 'default'),
(18, 'multiple.work', '1', 'default'),
(19, 'compatibility.mode', '0', 'default'),
(20, 'pending.subject.email', 'New Reservation #date# #id#', 'default'),
(21, 'send.from.email', '', 'default'),
(22, 'css.off', '0', 'default'),
(23, 'submit.redirect', '', 'default'),
(24, 'pending.subject.visitor.email', 'Reservation #id#', 'default'),
(25, 'block.time', '0', 'default'),
(26, 'max.appointments', '5', 'default'),
(27, 'pre.reservation', '0', 'default'),
(28, 'default.status', 'pending', 'default'),
(29, 'send.worker.email', '0', 'default')
;

SET FOREIGN_KEY_CHECKS=1;
COMMIT;

