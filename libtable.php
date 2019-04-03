<?php

class ViewTable
{
    protected $db;
    protected $table; // название таблицы
    protected $mas;   //результат запроса, строка, асоциативный масив
    protected $sql;   // SQL запрос
    public $fields;   // список полей
    public $table_header; // название столбцов таблици

    function __construct($db1)
    {
        $this->db = $db1;
    }

    function SetTable($table)
    {
        $this->table = $table;
        $this->sql = "SELECT * FROM " . $table;

        $result = $this->db->query($this->sql);
        $mas = $this->db->fetch($result);
        foreach ($mas as $key => $value) {
            $this->fields[] = $key;
            $this->table_header[$key] = $key;
        }
    }

    function get_field($field)
    {
        if (isset($this->mas[$field]))
            return $this->mas[$field];
        else return "";
    }

    function get_table_header($field)
    {
        if (isset($this->table_header[$field]))
            return $this->table_header[$field];
        else return "";
    }

    function a_view()
    {
        $content = "<table border = '1'>\n<tr>\n";
        foreach ($this->fields as $value) {
            $content .= "<th>" . $this->get_table_header($value) . "</th>\n";
        };
        $content .= "</tr>\n";
        $result = $this->db->query($this->sql);
        while ($this->mas = $this->db->fetch($result)) {
            $content .= "<tr>\n";
            foreach ($this->fields as $value) {
                $content .= "<td>" . $this->get_field($value) . "</td>\n";
            };
            $content .= "</tr>\n";
        }
        $content .= "</table>\n";
        return $content;
    }
}

class EditTable extends ViewTable
{
    protected $class_table;
    protected $key;
    protected $text_add;

    function __construct($db1)
    {
        parent::__construct($db1);
        $this->class_table = "EditTable";
        $this->text_add = "Добавить";
    }

    function SetTable($table)
    {
        parent::SetTable($table);
        $this->key = "id";
        $this->fields[] = "_del";
        $this->fields[] = "_edit";
        $this->table_header["_del"] = "Удалить";
        $this->table_header["_edit"] = "Редактировать";
    }

    function get_field($field)
    {
        if ($field == "_del") {
            $content = "<a href='?table=" . $this->class_table . "&action=del&id=" . $this->mas['id'] . "'>Удалить</a>";
        } elseif ($field == "_edit") {
            $content = "<a href='?table=" . $this->class_table . "&action=formedit&id=" . $this->mas['id'] . "'>Изменить</a>";
        } else {
            $content = ViewTable::get_field($field);
        }
        return $content;
    }

    function a_view()
    {
        $content = "<a href='?table=" . $this->class_table . "&action=formadd'>" . $this->text_add . "</a><br>";
        $content .= parent::a_view();
        return $content;
    }

    function a_del()
    {
        $id = $_GET['id'];
        $this->db->exec("DELETE FROM " . $this->table . " WHERE " . $this->key . "=" . $id);
        return $this->a_view();
    }

    function a_formadd()
    {
        $content = "<b>" . $this->text_add . "</b>\n";
        $content .= "<form action='?table=" . $this->class_table . "&action=add' method = 'post'>\n";

        $result = $this->db->query($this->sql);
        $mas = $this->db->fetch($result);
        foreach ($mas as $key => $value) {
            if ($key != $this->key) {
                $content .= $this->table_header[$key] . " <input type = 'text' size = '50' name = " .
                    $key . "><br>\n";
            }
        }

        $content .= "<input type = 'submit' value = 'Добавить'>\n";
        $content .= "</form>\n";
        return $content;
    }

    // Добавление в базу
    function a_add()
    {
        $sql = "INSERT INTO " . $this->table . "(";
        $result = $this->db->query($this->sql);
        $mas = $this->db->fetch($result);
        foreach ($mas as $key => $value) {
            if ($key != $this->key) {
                $sql .= $key . ", ";
            }
        }
        $sql = substr($sql, 0, -2);
        $sql .= ") VALUES(";
        foreach ($mas as $key => $value) {
            if ($key != $this->key) {
                $sql .= "'" . $_POST[$key] . "', ";
            }
        }
        $sql = substr($sql, 0, -2);
        $sql .= ")";
        $this->db->exec($sql);
        return $this->a_view();
    }

    function a_formedit()
    {
        $content = "<b>Редактировать</b>\n";
        $content .= "<form action='?table=" . $this->class_table . "&action=update' method = 'post'>\n";
        $id = $_GET['id'];
        $content .= "<input type='hidden' name='id' value='" . $id . "'>\n";
        $result = $this->db->query($this->sql. " WHERE ".$this->key."=".$id);
        $mas = $this->db->fetch($result);
        print_r($mas);
        foreach ($mas as $key => $value) {
            if ($key != $this->key) {
                $content .= $this->table_header[$key] . " <input type = 'text' size = '50' name = '" .
                    $key . "' value='".$value."'><br>\n";
            }
        }

        $content .= "<input type = 'submit' value = 'Изменить'>\n";
        $content .= "</form>\n";
        return $content;
    }
    function a_update()
    {
        $sqlupdate = "UPDATE ". $this->table ." SET ";
         $result = $this->db->query($this->sql);
        $mas = $this->db->fetch($result);
        foreach ($mas as $key => $value) {
            if ($key != $this->key) {
                $sqlupdate .= $key . "='".$_POST["$key"]."', ";
            }
        }
        $sqlupdate = substr($sqlupdate, 0, -2);
        $sqlupdate .=  " WHERE ".$this->key."='" . $_POST['id'] . "'";
        $this->db->exec($sqlupdate);
        return $this->a_view();
    }
}

?>