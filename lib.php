<?php
include "libtable.php";

class Tbooks extends ViewTable
{
    function __construct($db1)
    {
        parent::__construct($db1);
        parent::SetTable("books");
        $this->fields = array("id", "book_title", "price", "avtors", "genres", "short", "zakaz");
        $this->table_header = array(
            "id" => "№",
            "book_title" => "Название книги",
            "price" => "Цена",
            "avtors" => "Автора",
            "genres" => "Жанры",
            "short" => "Краткое описание",
            "zakaz" => "Заказать");

    }

    function a_view()
    {
        $menu = "<a href='?table=author'>Выбор по автору</a>, ";
        $menu .= "<a href='?table=genre'>Выбор по жанру</a>";
        $content = "Общий список книг<br>\n";
        $content .= parent::a_view();
        return array("menu" => $menu, "content" => $content);
    }

    function get_field($field)
    {
        if ($field == "avtors") {
            $sql = "SELECT authors.author " .
                "FROM books_authors, authors " .
                "WHERE books_authors.id_books='" . $this->mas['id'] . "' " .
                "AND authors.id=books_authors.id_authors";
            $result1 = $this->db->query($sql);
            $content = "";
            if ($mas1 = $this->db->fetch($result1)) {
                $content .= "<ul>\n";
                do {
                    $content .= "<li>" . $mas1["author"] . "</li>\n";
                } while ($mas1 = $this->db->fetch($result1));
                $content .= "</ul>\n";
            } else {
                $content .= "<center>Нет авторов</center>";
            }
        } elseif ($field == "genres") {
            $sql = "SELECT genres.genre FROM books_genres, genres " .
                "WHERE books_genres.id_book='" . $this->mas['id'] . "' " .
                "AND genres.id=books_genres.id_genre";
            $result1 = $this->db->query($sql);
            $content = "";
            if ($mas1 = $this->db->fetch($result1)) {
                $content .= "<ul>\n";
                do {
                    $content .= "<li>" . $mas1["genre"] . "</li>\n";
                } while ($mas1 = $this->db->fetch($result1));

                $content .= "</ul>\n";
            } else {
                $content .= "<center>Нет жанров</center>";
            }
        } elseif ($field == "short") {
            $content = "<a href='?table=books&action=viewbook&id=" . $this->mas['id'] .
                "'>Краткое описание</a>";
        } elseif ($field == "zakaz") {
            $content = "<a href='?table=books&action=vieworder&id=" . $this->mas['id'] .
                "'>Заказать книгу</a>\n";
        } else {
            $content = ViewTable::get_field($field);
        }
        return $content;
    }

    function a_vieworder()
    {
        $menu = "<a href='index.php'>Общий список книг</a>, ";
        $menu .= "<a href='?table=author'>Выбор по автору</a>, ";
        $menu .= "<a href='?table=genre'>Выбор по жанру</a>";

        $result = $this->db->query("SELECT * FROM books WHERE ID='" . $_GET['id'] . "'");
        $mas = $this->db->fetch($result);
        $content = "<b>Оформление заказа</b><br>";
        $content .= "<form action='?table=books&action=sendorder' method='post'>" . "\n";
        $content .= "<input type='hidden' name='id' value='" . $mas['id'] . "'" . ">\n";
        $content .= "<b>Название: </b>" . $mas['book_title'] . "<br>" . "\n";
        $content .= "<b>Описание: </b>" . $mas['short_description'] . "<br>\n";
        $content .= "<b>Цена: </b>" . $mas['price'] . "<br>\n";

        $result1 = $this->db->query("SELECT books_authors.id, authors.author " .
            "FROM books_authors, authors  WHERE books_authors.id_books='" . $_GET['id'] .
            "' AND authors.id=books_authors.id_authors");
        $content .= "Автора: ";
        if ($mas1 = $this->db->fetch($result1)) {
            $content .= "<ul>\n";
            do {
                $content .= "<li>" . $mas1["author"] . "</li>\n";
            } while ($mas1 = $this->db->fetch($result1));
            $content .= "</ul>\n";
        } else {
            $content .= "Нет авторов<br>\n";
        }

        $result1 = $this->db->query("SELECT books_genres.id, genres.genre " .
            "FROM books_genres, genres  WHERE books_genres.id_book='" . $_GET['id'] .
            "' AND genres.id=books_genres.id_genre");
        $content .= "Жанры: ";
        if ($mas1 = $this->db->fetch($result1)) {
            $content .= "<ul> \n";
            do {
                $content .= "<li>" . $mas1["genre"] . "</li>\n";
            } while ($mas1 = $this->db->fetch($result1));
            $content .= "</ul>\n";
        } else {
            $content .= "Нет жанров<br>\n";
        }

        $content .= "ФИО <input type = 'text' name = 'fio'><br>\n" .
            "Адрес <input type = 'text' name = 'adres'>\n" .
            "mail <input type = 'text' name = 'mail'><br>\n" .
            "телефон <input type = 'text' name = 'telefon'><br>\n" .
            "количество книг <input type = 'text' name = 'col'><br>\n" .
            "<input type = 'submit' value = 'Отправить заказ'>\n" .
            "</form>\n";
        return array("menu" => $menu, "content" => $content);
    }

    function a_sendorder()
    {
        $result = $this->db->query("SELECT * FROM books WHERE ID='" . $_POST['id'] . "'");
        $this->mas = $this->db->fetch($result);
        $textmail = "Номер в каталоге: " . $this->mas['id'] . "\r\n" .
            "Название книги: " . $this->mas['book_title'] . "\r\n" .
            "Описание книги: " . $this->mas['short_description'] . "\r\n" .
            "Цена книги: " . $this->mas['price'] . "\r\n";
        $result1 = $this->db->query("SELECT books_authors.id, authors.author " .
            "FROM books_authors, authors  WHERE books_authors.id_books='" . $_POST['id'] .
            "' AND authors.id=books_authors.id_authors");
        $textmail .= "Автора: ";
        if ($mas1 = $this->db->fetch($result1)) {

            do {
                $textmail .= $mas1["author"] . ", ";
            } while ($mas1 = $this->db->fetch($result));
            $textmail .= "\r\n";
        } else {
            $textmail .= "Нет авторов\r\n";
        }
        $result1 = $this->db->query("SELECT books_genres.id, genres.genre " .
            "FROM books_genres, genres  WHERE books_genres.id_book='" . $_POST['id'] .
            "' AND genres.id=books_genres.id_genre");
        $textmail .= "Жанры: ";
        if ($mas1 = $this->db->fetch($result1)) {
            do {
                $textmail .= $mas1["genre"] . ", ";
            } while ($mas1 = $this->db->fetch($result1));
            $textmail .= "\r\n";
        } else {
            $textmail .= "Нет жанров\r\n";
        }
        $textmail .= "\r\n";
        $textmail .= "ФИО: " . $_POST['fio'] . "\r\n" .
            "Адрес: " . $_POST['adres'] . "\r\n" .
            "Мail: " . $_POST['mail'] . "\r\n" .
            "Телефон: " . $_POST['telefon'] . "\r\n" .
            "Количество книг: " . $_POST['col'] . "\r\n";
        mail(MAIL_SEND, "Заказ книги", $textmail, "From: notych@notych.pp.ua \r\n");
        return $this->a_view();
    }

    function a_viewbook()
    {
        $menu = "<a href='index.php'>Общий список книг</a>, ";
        $menu .= "<a href='?table=author'>Выбор по автору</a>, ";
        $menu .= "<a href='?table=genre'>Выбор по жанру</a>";

        $result = $this->db->query("SELECT * FROM books WHERE ID='" . $_GET['id'] . "'");
        $this->mas = $this->db->fetch($result);
        $content = "<b>Краткое описание</b><br><br>\n";
        $content .= "№ " . $this->mas['id'] . "<br><br>\n";
        $content .= "<b>Название: </b>" . $this->mas['book_title'] . "<br><br>\n";
        $content .= "<b>Описание: </b>" . $this->mas['short_description'] . "<br><br>\n";
        $content .= "<b>Цена: </b>" . $this->mas['price'] . "<br><br>\n";

        $result1 = $this->db->query("SELECT books_authors.id, authors.author " .
            "FROM books_authors, authors  WHERE books_authors.id_books='" . $_GET['id'] .
            "' AND authors.id=books_authors.id_authors");
        $content .= "Автора: ";
        if ($mas1 = $this->db->fetch($result1)) {
            $content .= "<ul> \n";
            do {
                $content .= "<li>" . $mas1["author"] . "</li>\n";
            } while ($mas1 = $this->db->fetch($result1));
            $content .= "</ul>\n";
        } else {
            $content .= "Нет авторов<br>";
        }

        $result1 = $this->db->query("SELECT books_genres.id, genres.genre " .
            "FROM books_genres, genres  WHERE books_genres.id_book='" . $_GET['id'] .
            "' AND genres.id=books_genres.id_genre");
        $content .= "Жанры: ";
        if ($mas1 = $this->db->fetch($result1)) {
            $content .= "<ul>\n";
            do {
                $content .= "<li>" . $mas1["genre"] . "</li>\n";
            } while ($mas1 = $this->db->fetch($result1));
            $content .= "</ul>\n";
        } else {
            $content .= "Нет жанров<br>\n";
        }
        return array("menu" => $menu, "content" => $content);
    }

    function a_viewauthor()
    {
        $menu = "<a href='?table=books'>Общий список книг</a>, ";

        $result = $this->db->query("SELECT authors.author FROM authors WHERE authors.id=" .
            $_GET["idauthor"]);
        $content = "Cписок книг по автору: <b>" . $this->db->fetch($result)["author"] . "</b><br>";
        $oldsql = $this->sql;
        $this->sql = "SELECT books.* " .
            "FROM books, books_authors " .
            "WHERE books.id=books_authors.id_books AND books_authors.id_authors=" . $_GET["idauthor"] . " " .
            "ORDER BY book_title";
        $content1 = $this->a_view();
        $this->sql = $oldsql;
        return array("menu" => $menu . $content1["menu"], "content" => $content . $content1["content"]);
    }

    function a_viewgenre()
    {
        $menu = "<a href='?table=books'>Общий список книг</a>, ";

        $result = $this->db->query("SELECT genres.genre FROM genres WHERE genres.id=" .
            $_GET["idgenre"]);
        $content = "Cписок книг по жанру: " . $this->db->fetch($result)["genre"] . "<br>";
        $oldsql = $this->sql;
        $this->sql = "SELECT books.* " .
            "FROM books, books_genres " .
            "WHERE books.id=books_genres.id_book AND books_genres.id_genre=" . $_GET["idgenre"] . " " .
            "ORDER BY book_title";

        $content1 = $this->a_view();
        $this->sql = $oldsql;
        return array("menu" => $menu . $content1["menu"], "content" => $content . $content1["content"]);
    }
}

class Tauthor extends ViewTable
{
    function __construct($db1)
    {
        parent::__construct($db1);
        parent::SetTable("authors");
        $this->fields = array("id", "author", "vybor");
        $this->table_header = array(
            "id" => "№",
            "author" => "Автор",
            "vybor" => "Выбрать");

    }

    function a_view()
    {
        $menu = "<a href='?table=books'>Общий список книг</a>, ";
        $menu .= "<a href='?table=genre'>Выбор по жанру</a>";
        $content = "Вибор книг по автору<br>";
        $content .= parent::a_view();
        return array("menu" => $menu, "content" => $content);
    }

    function get_field($field)
    {
        if ($field == "vybor") {
            $content = "<a href='?table=books&action=viewauthor&idauthor=" . $this->mas['id'] .
                "'>Выбрать</a>";
        } else {
            $content = ViewTable::get_field($field);
        }
        return $content;
    }

}

class Tgenre extends ViewTable
{
    function __construct($db1)
    {
        parent::__construct($db1);
        parent::SetTable("genres");
        $this->fields = array("id", "genre", "vybor");
        $this->table_header = array(
            "id" => "№",
            "author" => "Жанр",
            "vybor" => "Выбрать");
    }

    function a_view()
    {
        $menu = "<a href='?table=books'>Общий список книг</a>, ";
        $menu .= "<a href='?table=author'>Выбор по автору</a>";
        $content = "Вибор книг по жанру<br>";
        $content .= parent::a_view();
        return array("menu" => $menu, "content" => $content);
    }

    function get_field($field)
    {
        if ($field == "vybor") {
            $content = "<a href='?table=books&action=viewgenre&idgenre=" . $this->mas['id'] .
                "'>Выбрать</a>";
        } else {
            $content = ViewTable::get_field($field);
        }
        return $content;
    }
}

?>