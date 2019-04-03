-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Час створення: Квт 03 2019 р., 05:34
-- Версія сервера: 5.5.60-MariaDB
-- Версія PHP: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `notychweb_book`
--
CREATE DATABASE IF NOT EXISTS `notychweb_book` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `notychweb_book`;

-- --------------------------------------------------------

--
-- Структура таблиці `authors`
--

CREATE TABLE `authors` (
  `id` int(11) NOT NULL,
  `author` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='справочник авторов';

--
-- Дамп даних таблиці `authors`
--

INSERT INTO `authors` (`id`, `author`) VALUES
(4, 'Вільям Сомерсет Моем'),
(5, 'Джейн Остін'),
(2, 'Михайло Булгаков');

-- --------------------------------------------------------

--
-- Структура таблиці `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `book_title` varchar(512) NOT NULL,
  `short_description` varchar(1024) NOT NULL,
  `price` decimal(10,2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='таблица для книг';

--
-- Дамп даних таблиці `books`
--

INSERT INTO `books` (`id`, `book_title`, `short_description`, `price`) VALUES
(1, 'Доводы рассудка ', 'Энн Эллиот, дочь состоятельного сэра Уолтера, была красива и умна. Она могла бы познать счастье в браке с благородным лейтенантом Фредериком Уэнтуортом. С ним ее связывало глубокое и сильное чувство.', '45.00'),
(3, 'Мастер и Маргарита ', '«Мастер и Маргарита» — главный роман Булгакова, книга на все времена, которая ждала своего издания 50 лет. Это история о дьяволе и его свите, посетивших Москву 1930-х, о прокураторе Иудеи Понтии Пилате и нищем философе Иешуа Га-Ноцри, о талантливом мастере и его прекрасной и верной возлюбленной Маргарите. ', '115.00'),
(5, 'Чувство и чувствительность ', 'Сестры Дэшвуд всегда были опорой друг для друга, даже несмотря на свою непохожесть. Элинор, утонченная и сдержанная, никогда не показывала своих чувств на публике. А Марианна — пылкая и чувственная натура, мечтающая о романтике и страсти. ', '124.00'),
(6, 'Тягар пристрастей людських', 'Це історія Філіпа Кері, невтомного шукача сенсу життя. Усе почалося ще зі шкільних років, а потім — навчання в Німеччині, Лондоні, Парижі, мрія стати художником, химерне сплетіння слів богемного поета Кроншоу… Довгі розмови про вічне стають ковтком живої води, даючи йому сили не припиняти пошуків себе та істини. ', '140.00');

-- --------------------------------------------------------

--
-- Структура таблиці `books_authors`
--

CREATE TABLE `books_authors` (
  `id` int(11) NOT NULL,
  `id_books` int(11) NOT NULL,
  `id_authors` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='таблица связи автор  книга';

--
-- Дамп даних таблиці `books_authors`
--

INSERT INTO `books_authors` (`id`, `id_books`, `id_authors`) VALUES
(6, 1, 5),
(3, 3, 2),
(7, 5, 5),
(4, 6, 4);

-- --------------------------------------------------------

--
-- Структура таблиці `books_genres`
--

CREATE TABLE `books_genres` (
  `id` int(11) NOT NULL,
  `id_book` int(11) NOT NULL,
  `id_genre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='таблица связи книга жанр';

--
-- Дамп даних таблиці `books_genres`
--

INSERT INTO `books_genres` (`id`, `id_book`, `id_genre`) VALUES
(1, 1, 3),
(3, 3, 3),
(5, 5, 3),
(6, 5, 4);

-- --------------------------------------------------------

--
-- Структура таблиці `genres`
--

CREATE TABLE `genres` (
  `id` int(11) NOT NULL,
  `genre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='справочник жанров';

--
-- Дамп даних таблиці `genres`
--

INSERT INTO `genres` (`id`, `genre`) VALUES
(8, 'Детективи'),
(3, 'Класика'),
(6, 'Пригодницько-історичні романи'),
(4, 'Романи про кохання'),
(2, 'Сучасні автори');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `author` (`author`);

--
-- Індекси таблиці `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Індекси таблиці `books_authors`
--
ALTER TABLE `books_authors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_books` (`id_books`,`id_authors`),
  ADD KEY `id_books_2` (`id_books`),
  ADD KEY `id_authors` (`id_authors`);

--
-- Індекси таблиці `books_genres`
--
ALTER TABLE `books_genres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_book` (`id_book`,`id_genre`),
  ADD KEY `id_book_2` (`id_book`),
  ADD KEY `id_genre` (`id_genre`);

--
-- Індекси таблиці `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `genre` (`genre`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблиці `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблиці `books_authors`
--
ALTER TABLE `books_authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблиці `books_genres`
--
ALTER TABLE `books_genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблиці `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `books_authors`
--
ALTER TABLE `books_authors`
  ADD CONSTRAINT `ba_a` FOREIGN KEY (`id_authors`) REFERENCES `authors` (`id`),
  ADD CONSTRAINT `ba_b` FOREIGN KEY (`id_books`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `books_genres`
--
ALTER TABLE `books_genres`
  ADD CONSTRAINT `bg_b` FOREIGN KEY (`id_book`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bg_g` FOREIGN KEY (`id_genre`) REFERENCES `genres` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
