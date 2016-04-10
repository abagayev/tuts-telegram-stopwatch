-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Час створення: Квт 07 2016 р., 10:51
-- Версія сервера: 5.5.47-0ubuntu0.14.04.1
-- Версія PHP: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- База даних: `stopwatch`
--

-- --------------------------------------------------------

--
-- Структура таблиці `stopwatch`
--

CREATE TABLE IF NOT EXISTS `stopwatch` (
  `chat_id` int(10) unsigned NOT NULL,
  `timestamp` int(10) unsigned NOT NULL,
  PRIMARY KEY (`chat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
