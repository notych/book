<?php

class Teditbooks extends EditTable
{
    function __construct($db1)
    {
        parent::__construct($db1);
        $this->SetTable("books");
        $this->key = "id";
        $this->class_table="editbooks";
        $this->text_add = "Добавить книгу";
        $this->fields = array("id", "book_title", "short_description", "price", "avtors", "genres", "_del", "_edit");
        $this->table_header = array(
            "id" => "№",
            "book_title" => "Название книги",
            "short_description" => "Краткое описание книги",
            "price" => "Цена",
            "avtors" => "Автора",
            "genres" => "Жанры",
            "_del" => "Удалить",
            "_edit" => "Редактировать");
    }

    function a_view()
    {
        $menu = "<a href='?table=editauthor'>Редактировани списка авторов</a>, \n";
        $menu .= "<a href='?table=editgenre'>Редактировани списка жанров</a>, \n";

        $content = parent::a_view();
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
        } elseif ($field == "_del") {
            $content = "<a href='?table=editbooks&action=del&id=" . $this->mas['id'] . "'>Удалить</a>";
        } elseif ($field == "_edit") {
            $content = "<a href='?table=editbooks&action=formaeditbook&id=" . $this->mas['id'] . "'>Изменить</a>";
        } else {
            $content = ViewTable::get_field($field);
        }
        return $content;
    }
    // Форма для добавления книги
    function a_formadd()
    {
        $menu = "<a href='?table=editbooks'>Редактирование книг</a>, \n";
        $menu .= "<a href='?table=editauthor'>Редактировани списка авторов</a>, \n";
        $menu .= "<a href='?table=editgenre'>Редактировани списка жанров</a>, \n";
        $content = "<b>Добавление книги<b>\n";
        $content .= "<form action='?table=editbooks&action=addbook' method = 'post'>\n";
        $content .= "Название книги <input type = 'text' size = '50' name = 'book_title'><br>\n";
        $content .= "Описание книги<br><textarea name='short_description' cols='80' rows='5'></textarea><br>\n";
        $content .= "Цена книги <input type = 'text' name = 'price'><br>\n";

        $content .= "Автор:";
        $content .= "<select name='avthor'>\n";
        $content .= "<option value='not'>Нет автора</option>\n";
        $result = $this->db->query("SELECT * FROM authors ORDER BY author");
        while ($mas = $this->db->fetch($result)) {
            $content .= "<option value='" . $mas["id"] . "'>" . $mas["author"] . "</option>\n";
        };
        $content .= "</select>\n<br>";

        $content .= "Жанр:";
        $content .= "<select name='genre'>\n";
        $content .= "<option value='not'>Нет жанра</option>\n";
        $result = $this->db->query("SELECT * FROM genres ORDER BY genre ");
        while ($mas = $this->db->fetch($result)) {
            $content .= "<option value='" . $mas["id"] . "'>" . $mas["genre"] . "</option>\n";
        };
        $content .= "</select>\n<br><br>";

        $content .= "<input type = 'submit' value = 'Добавить'>\n";
        $content .= "</form>\n";
        return array("menu" => $menu, "content" => $content);
    }
    // Добавление книги в базу
    function a_addbook()
    {
        $this->db->exec("INSERT INTO books(book_title, short_description, price) " .
           "VALUES('" . $_POST['book_title'] . "','" . $_POST['short_description'] .
            "','". (is_numeric($_POST['price'])?$_POST['price']:0) . "')");

        $last = $this->db->lastInsertId();

        if ($_POST["avthor"] != "not") {
            $this->db->exec("INSERT INTO books_authors(id_books, id_authors) " .
                "VALUES(" . $last . ", " . $_POST["avthor"] . ")");
        }
        if ($_POST["genre"] != "not") {
            $this->db->exec("INSERT INTO books_genres(id_book, id_genre) " .
                "VALUES(" . $last . ", " . $_POST["genre"] . ")");
        }
        return $this->a_view();
    }
    // Форма для редактирования книги
    function a_formaeditbook()
    {
        $menu = "<a href='?table=editbooks'>Редактирование книг</a>, \n";
        $menu .= "<a href='?table=editauthor'>Редактировани списка авторов</a>, \n";
        $menu .= "<a href='?table=editgenre'>Редактировани списка жанров</a>, \n";

        $id = $_GET['id'];
        $result = $this->db->query("SELECT * FROM books WHERE ID='" . $id . "'");
        $mas = $this->db->fetch($result);
        $content = "<b>Редактировать книгу</b>";
        $content .= "<form action='?table=editbooks&action=updatebook' method='post'>\n";
        $content .= "<input type='hidden' name='id' value='" . $id . "'>\n";
        $content .= "Редактировать название ";
        $content .= "<input type='text' name='book_title' value='" . $mas['book_title'] . "'><br>\n";
        $content .= "Редактировать описание <br>";
        $content .= "<textarea cols='80' rows='5' name='short_description'>" . $mas['short_description'] .
            "</textarea><br>\n";
        $content .= "Редактировать цену ";
        $content .= "<input type='text' name='price' value='" . $mas['price'] . "'><br>\n";

        $result1 = $this->db->query("SELECT books_authors.id, authors.author " .
            "FROM books_authors, authors " .
            "WHERE books_authors.id_books='" . $id . "' AND authors.id=books_authors.id_authors");
        $content .= "<b>Автор</b><br>";
        if ($mas1 = $this->db->fetch($result1)) {
            $content .= "<table border='1'> \n";

            do {
                $content .= "<tr><td>" . $mas1["author"] . "</td>";
                $content .= "<td><a href='?table=editbooks&action=delbooks_authors&idbooks_authors=" . $mas1['id'] . "&id=" .
                    $id . "'>Удалить</a></td></tr>";
            } while ($mas1 = $this->db->fetch($result1));
            $content .= "</table>\n";
        } else {
            $content .= "Нет авторов<br>";
        }
        $content .= "<a href='?table=editbooks&action=viewaddauthors&id=" . $id . "'>Добавить автора</a><br>";
        $content .= "<b>Жанр</b><br>";
        $result1 = $this->db->query("SELECT books_genres.id, genres.genre " .
            "FROM books_genres, genres  WHERE books_genres.id_book='" . $id . "' " .
            "AND genres.id=books_genres.id_genre");
        if ($mas1 = $this->db->fetch($result1)) {
            $content .= "<table border='1'> \n";
            do {

                $content .= "<tr><td>" . $mas1["genre"] . "</td>";
                $content .= "<td><a href='?table=editbooks&action=delbooks_genres&idbooks_genres=" .
                    $mas1['id'] . "&id=" . $id . "'>Удалить</a></td>";
                $content .= "</tr>";
            } while ($mas1 = $this->db->fetch($result1));
            $content .= "</table>";
        } else {
            $content .= "Нет жанров<br>";
        }
        $content .= "<a href='?table=editbooks&action=viewaddgenres&id=" . $id . "'>Добавить жанр</a><br>";
        $content .= '<input type="submit" value="Сохранить изменения">';
        $content .= '</form><br>';
        return array("menu" => $menu, "content" => $content);
    }
    // Обновить информацию о книге в базе
    function a_updatebook()
    {
        $sr_query = "UPDATE books SET book_title='" . $_POST['book_title'] .
            "', short_description='" . $_POST['short_description'] .
            "', price='" . (is_numeric($_POST['price'])?$_POST['price']:0) .
            "' WHERE ID='" . $_POST['id'] . "'";
        $this->db->exec($sr_query);
        return $this->a_view();
    }
    // Удалить автора
    function a_delbooks_authors()
    {
        $id = $_GET['idbooks_authors'];
        $this->db->exec("DELETE FROM books_authors WHERE id=" . $id);
        return $this->a_formaeditbook();
    }
    // Удалить жанр
    function a_delbooks_genres()
    {
        $id = $_GET['idbooks_genres'];
        $this->db->exec("DELETE FROM books_genres WHERE id = " . $id);
        return $this->a_formaeditbook();
    }
    // Форма для добавления автора книги
    function a_viewaddauthors()
    {
        $menu = "<a href='?table=editbooks'>Редактирование книг</a>, \n";
        $menu .= "<a href='?table=editauthor'>Редактировани списка авторов</a>, \n";
        $menu .= "<a href='?table=editgenre'>Редактировани списка жанров</a>, \n";

        $id = $_GET['id'];
        $result = @$this->db->query("SELECT * FROM books WHERE id='" . $id . "'");
        $mas = $this->db->fetch($result);
        $content = "<b>Добавление автора</b><br>";
        $content .= "<b>Книга: </b>" . $mas['book_title'] . "<br>\n" .
            "<b>Описание: </b>" . $mas['short_description'] . "<br>\n" .
            "<b>Цена:</b>" . $mas['price'] . "<br>\n";
        $content .= "Жанры: ";
        $result1 = $this->db->query("SELECT books_genres.id, genres.genre " .
            "FROM books_genres, genres " .
            "WHERE books_genres.id_book='" . $id .
            "' AND genres.id=books_genres.id_genre");
        if ($mas1 = $this->db->fetch($result1)) {
            $content .= "<table border='1'> \n";
            do {
                $content .= "<tr><td>" . $mas1["genre"] . "</td></tr>";
            } while ($mas1 = $this->db->fetch($result1));
            $content .= "</table><br>";
        } else {
            $content .= "Нет жанров<br>";
        }
        $content .= "Автора: ";
        $result1 = @$this->db->query("SELECT books_authors.id, authors.author " .
            "FROM books_authors, authors  WHERE books_authors.id_books='" . $id .
            "' AND authors.id=books_authors.id_authors");
        if ($mas1 = $this->db->fetch($result1)) {
            $content .= "<table border='1'> \n";
            do {
                $content .= "<tr><td>" . $mas1["author"] . "</td></tr >";
            } while ($mas1 = $this->db->fetch($result1));
            $content .= " </table ><br > ";
        } else {
            $content .= "Нет авторов";
        }

        $content .= "Таблица для добавления новых <br>";
        $content .= "<table border = '1'>\n<tr><th>№</th><th> Автор</th><th>Добавить</th ></tr>\n";
        $result = @$this->db->query("SELECT * FROM authors WHERE id NOT IN " .
            "(SELECT books_authors . id_authors FROM books_authors WHERE books_authors.id_books = '" . $id . "')");
        while ($mas = $this->db->fetch($result)) {
            $content .= "<tr>";
            $content .= "<td > " . $mas["id"] . " </td > " . "<td> " . $mas["author"] . " </td> ";
            $content .= "<td ><a href = '?table=editbooks&action=addauthors&idauthor=" . $mas['id'] . "&id=" .
                $_GET['id'] . "' > Добавить</a ></td> ";
            $content .= "</tr>\n";
        }
        $content .= " </table>\n";

        return array("menu" => $menu, "content" => $content);
    }

    //добавление информации о новом авторе в базу данных
    function a_addauthors()
    {
        $id = $_GET['id'];
        $idauthor = $_GET['idauthor'];
        $this->db->exec("INSERT INTO books_authors(id_books, id_authors) VALUES(" .
            $id . ", " . $idauthor . ")");
        return $this->a_formaeditbook();
    }

    //Форма для добавления жанра
    function a_viewaddgenres()
    {
        $menu = "<a href='?table=editbooks'>Редактирование книг</a>, \n";
        $menu .= "<a href='?table=editauthor'>Редактировани списка авторов</a>, \n";
        $menu .= "<a href='?table=editgenre'>Редактировани списка жанров</a>, \n";
        $id = $_GET['id'];
        $result = @$this->db->query("SELECT * FROM books WHERE ID = '" . $id . "'");
        $mas = $this->db->fetch($result);
        $content = "<b>Добавление жанра</b><br>";
        $content .= "<b>Книга:</b> " . $mas['book_title'] . " <br>\n" .
            "<b>Описание:</b> " . $mas['short_description'] . " <br>\n" .
            "<b>Цена:</b> " . $mas['price'] . " <br>\n";

        $result1 = $this->db->query("SELECT books_authors.id, authors.author " .
            "FROM books_authors, authors " .
            "WHERE books_authors.id_books='" . $id . "' AND authors.id=books_authors.id_authors");

        $content .= "Автора: \n";
        if ($mas1 = $this->db->fetch($result1)) {
            $content .= "<table border ='1'>\n";
            do {
                $content .= "<tr><td>" . $mas1["author"] . " </td></tr> ";
            } while ($mas1 = $this->db->fetch($result1));
            $content .= "</table><br> ";
        } else {
            $content .= "Нет авторов <br>\n";
        }
        $result1 = $this->db->query("SELECT books_genres.id, genres.genre " .
            "FROM books_genres, genres " .
            "WHERE books_genres.id_book='" . $id .
            "' AND genres.id=books_genres.id_genre");

        $content .= "Жанры: ";
        if ($mas1 = $this->db->fetch($result1)) {
            $content .= "<table border='1'> \n";
            do {
                $content .= "<tr><td>" . $mas1["genre"] . "</td></tr>";
            } while ($mas1 = $this->db->fetch($result1));
            $content .= "</table><br>";
        } else {
            $content .= "Нет жанров";
        }

        $result = @$this->db->query("SELECT * FROM genres " .
            "WHERE id NOT IN(SELECT books_genres.id_genre FROM books_genres " .
            "WHERE books_genres.id_book = '" . $id . "')");

        $content .= "Таблица для добавления новых <br>";
        $content .= " <table border = '1' > \n <tr > <th > №</th > <th > Жанр</th > <th > Добавить</th > </tr > ";
        while ($mas = $this->db->fetch($result)) {
            $content .= '<tr>';
            $content .= "<td > " . $mas["id"] . " </td > " . "<td > " . $mas["genre"] . " </td > ";
            $content .= "<td ><a href = '?table=editbooks&action=addgenres&idgenre=" . $mas['id'] . "&id=" . $id . "' > Добавить</a ></td > ";
            $content .= "</tr > ";
        }
        $content .= "</table > ";
        return array("menu" => $menu, "content" => $content);
    }

    //добавление жанра книги в базу
    function a_addgenres()
    {
        $id = $_GET['id'];
        $idgenre = $_GET['idgenre'];
        $this->db->exec("INSERT INTO books_genres(id_book, id_genre)" .
            " VALUES(" . $id . ", " . $idgenre . ")");
        return $this->a_formaeditbook();
    }


}
class Teditauthor extends EditTable
{

    function __construct($db1)
    {
        parent::__construct($db1);
        $this->SetTable("authors");
        $this->key = "id";
        $this->class_table="editauthor";
        $this->table_header["id"]= "№";
        $this->table_header["author"]= "Автор";
        $this->text_add = "Добавить автора";
    }
    function a_view()
    {
        $menu = "<a href='?table=editbooks'>Редактирование книг</a>, \n";
        $menu .= "<a href='?table=editgenre'>Редактировани списка жанров</a>, \n";
        $menu .= "<a href='?table=editbooks&action=formaddbook'>Добавить книгу</a><br>\n";

        $content = parent::a_view();

        return array("menu" => $menu, "content" => $content);
    }
    function a_formadd()
    {
        $menu = "<a href='?table=editbooks'>Редактирование книг</a>, \n";
        $menu .= "<a href='?table=editgenre'>Редактировани списка жанров</a>, \n";
        $menu .= "<a href='?table=editbooks&action=formaddbook'>Добавить книгу</a><br>\n";
        $content =parent::a_formadd();
        return array("menu" => $menu, "content" => $content);

    }
    function a_formedit()
    {

        $menu = "<a href='?table=editbooks'>Редактирование книг</a>, \n";
        $menu .= "<a href='?table=editauthor'>Редактировани списка авторов</a>, \n";
        $menu .= "<a href='?table=editbooks&action=formaddbook'>Добавить книгу</a><br>\n";

        $content = parent::a_formedit();

        return array("menu" => $menu, "content" => $content);
    }
}
class Teditgenre extends EditTable
{

    function __construct($db1)
    {
        parent::__construct($db1);
        $this->SetTable("genres");
        $this->key = "id";
        $this->class_table="editgenre";
        $this->table_header["id"]= "№";
        $this->table_header["genre"]= "Жанр";
        $this->text_add = "Добавить жанр";
    }
    function a_view()
    {
        $menu = "<a href='?table=editbooks'>Редактирование книг</a>, \n";
        $menu .= "<a href='?table=editauthor'>Редактировани списка авторов</a>, \n";
        $menu .= "<a href='?table=editbooks&action=formaddbook'>Добавить книгу</a><br>\n";

        $content = parent::a_view();

        return array("menu" => $menu, "content" => $content);
    }
    function a_formadd()
    {

        $menu = "<a href='?table=editbooks'>Редактирование книг</a>, \n";
        $menu .= "<a href='?table=editauthor'>Редактировани списка авторов</a>, \n";
        $menu .= "<a href='?table=editbooks&action=formaddbook'>Добавить книгу</a><br>\n";

        $content = parent::a_formadd();

        return array("menu" => $menu, "content" => $content);
    }
    function a_formedit()
    {

        $menu = "<a href='?table=editbooks'>Редактирование книг</a>, \n";
        $menu .= "<a href='?table=editauthor'>Редактировани списка авторов</a>, \n";
        $menu .= "<a href='?table=editbooks&action=formaddbook'>Добавить книгу</a><br>\n";

        $content = parent::a_formedit();

        return array("menu" => $menu, "content" => $content);
    }

}


?>