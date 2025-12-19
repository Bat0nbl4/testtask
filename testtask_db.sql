-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: MySQL-8.2
-- Время создания: Дек 19 2025 г., 23:39
-- Версия сервера: 8.2.0
-- Версия PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `testtask_db`
--
CREATE DATABASE IF NOT EXISTS `testtask_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `testtask_db`;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `login` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patronymic` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('M','W') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `role` enum('None','Admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'None',
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `login`, `name`, `surname`, `patronymic`, `gender`, `birthday`, `role`, `password`) VALUES
(1, 'admin', 'Oleg', 'Bogomolov', 'Olegovich', 'M', '2006-05-15', 'Admin', '$2y$12$.ta1LDc1emAdCsNUk4zsEO14DlSQe2oiVfen0WzK5xr2UtJISim2O'),
(15, 'ivan', 'Ivan', 'Ivanov', 'Ivanovich', 'M', '1998-01-01', 'None', '$2y$12$ZcHRDb2q5F8S1bjP1jcvMeDnb1oobU140.ZHkLnpdCZryAlJ0Pkyu'),
(24, 'FNG7cp', 'Andrew', 'Ivanov', 'Evgenevich', 'M', '1974-01-25', 'None', '$2y$12$ZcHRDb2q5F8S1bjP1jcvMeDnb1oobU140.ZHkLnpdCZryAlJ0Pkyu'),
(25, 'zXtlQh', 'Daniel', 'Eltsin', 'Aleksandrovich', 'M', '1981-02-23', 'None', '$2y$12$ZcHRDb2q5F8S1bjP1jcvMeDnb1oobU140.ZHkLnpdCZryAlJ0Pkyu'),
(26, 'ECbWqd', 'Elias', 'Chaikovsky', 'Aleksandrovich', 'M', '1970-05-05', 'None', '$2y$12$ZcHRDb2q5F8S1bjP1jcvMeDnb1oobU140.ZHkLnpdCZryAlJ0Pkyu'),
(27, '23Lfh1', 'Basil', 'Chaikovsky', 'Aleksandrovich', 'M', '1976-08-07', 'None', '$2y$12$ZcHRDb2q5F8S1bjP1jcvMeDnb1oobU140.ZHkLnpdCZryAlJ0Pkyu'),
(28, 'un8glr', 'Elias', 'Ivanov', 'Dmitrievich', 'M', '1978-12-01', 'None', '$2y$12$ZcHRDb2q5F8S1bjP1jcvMeDnb1oobU140.ZHkLnpdCZryAlJ0Pkyu'),
(29, 'WLFD6T', 'Andrew', 'Yushchenko', 'Vitalevich', 'M', '1975-12-31', 'None', '$2y$12$ZcHRDb2q5F8S1bjP1jcvMeDnb1oobU140.ZHkLnpdCZryAlJ0Pkyu'),
(30, 'b6iH9D', 'Andrew', 'Eltsin', 'Vitalevich', 'M', '1975-11-11', 'None', '$2y$12$ZcHRDb2q5F8S1bjP1jcvMeDnb1oobU140.ZHkLnpdCZryAlJ0Pkyu'),
(31, 'QF63vj', 'Andrew', 'Khrushchev', 'Evgenevich', 'M', '1979-09-09', 'None', '$2y$12$ZcHRDb2q5F8S1bjP1jcvMeDnb1oobU140.ZHkLnpdCZryAlJ0Pkyu'),
(32, 'qSAsPB', 'Basil', 'Chaikovsky', 'Aleksandrovich', 'M', '1984-09-29', 'None', '$2y$12$ZcHRDb2q5F8S1bjP1jcvMeDnb1oobU140.ZHkLnpdCZryAlJ0Pkyu'),
(33, '63v12n', 'Andrew', 'Ivanov', 'Valerevich', 'M', '1982-05-02', 'None', '$2y$12$ZcHRDb2q5F8S1bjP1jcvMeDnb1oobU140.ZHkLnpdCZryAlJ0Pkyu'),
(34, 'ys3oGw', 'Gregory', 'Eltsin', 'Vitalevich', 'M', '1975-01-27', 'None', '$2y$12$ZcHRDb2q5F8S1bjP1jcvMeDnb1oobU140.ZHkLnpdCZryAlJ0Pkyu'),
(35, 'WBladH', 'Basil', 'Ivanov', 'Evgenevich', 'M', '1978-01-29', 'None', '$2y$12$ZcHRDb2q5F8S1bjP1jcvMeDnb1oobU140.ZHkLnpdCZryAlJ0Pkyu'),
(36, '9DJZIi', 'Gregory', 'Yushchenko', 'Vitalevich', 'M', '1976-05-30', 'None', '$2y$12$ZcHRDb2q5F8S1bjP1jcvMeDnb1oobU140.ZHkLnpdCZryAlJ0Pkyu'),
(37, 'Ul4N1M', 'Basil', 'Yushchenko', 'Evgenevich', 'M', '1979-05-18', 'None', '$2y$12$ZcHRDb2q5F8S1bjP1jcvMeDnb1oobU140.ZHkLnpdCZryAlJ0Pkyu'),
(38, 'D20YE3', 'Andrew', 'Eltsin', 'Evgenevich', 'M', '1977-08-17', 'None', '$2y$12$ZcHRDb2q5F8S1bjP1jcvMeDnb1oobU140.ZHkLnpdCZryAlJ0Pkyu'),
(39, 'FgrO5f', 'Elias', 'Khrushchev', 'Evgenevich', 'M', '1970-02-24', 'None', '$2y$12$ZcHRDb2q5F8S1bjP1jcvMeDnb1oobU140.ZHkLnpdCZryAlJ0Pkyu'),
(40, 'EOwF4v', 'Daniel', 'Yushchenko', 'Dmitrievich', 'M', '1975-02-20', 'None', '$2y$12$ZcHRDb2q5F8S1bjP1jcvMeDnb1oobU140.ZHkLnpdCZryAlJ0Pkyu'),
(41, 'gUzj1u', 'Daniel', 'Eltsin', 'Evgenevich', 'M', '1984-12-09', 'None', '$2y$12$ZcHRDb2q5F8S1bjP1jcvMeDnb1oobU140.ZHkLnpdCZryAlJ0Pkyu'),
(42, 'dcuFTj', 'Andrew', 'Khrushchev', 'Vitalevich', 'M', '1977-05-08', 'None', '$2y$12$ZcHRDb2q5F8S1bjP1jcvMeDnb1oobU140.ZHkLnpdCZryAlJ0Pkyu'),
(43, 'NjHsXm', 'Gregory', 'Eltsin', 'Dmitrievich', 'M', '1974-05-06', 'None', '$2y$12$ZcHRDb2q5F8S1bjP1jcvMeDnb1oobU140.ZHkLnpdCZryAlJ0Pkyu'),
(44, 'AGJFVu', 'Andrew', 'Khrushchev', 'Aleksandrovich', 'M', '1977-06-04', 'None', '$2y$12$ZcHRDb2q5F8S1bjP1jcvMeDnb1oobU140.ZHkLnpdCZryAlJ0Pkyu'),
(45, 'LGhnX3', 'Basil', 'Ivanov', 'Vitalevich', 'M', '1979-05-03', 'None', '$2y$12$ZcHRDb2q5F8S1bjP1jcvMeDnb1oobU140.ZHkLnpdCZryAlJ0Pkyu'),
(46, 'JgpcNE', 'Daniel', 'Yushchenko', 'Dmitrievich', 'M', '1981-06-19', 'None', '$2y$12$ZcHRDb2q5F8S1bjP1jcvMeDnb1oobU140.ZHkLnpdCZryAlJ0Pkyu');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_login` (`login`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
