<?php
include_once "Table.php";
class TagTypeTable extends Table
{
    public static function addTable(mysqli $conn): bool
    {
        $sql = <<<SQL
            CREATE TABLE tag_type(
                tag_type_id int NOT NULL AUTO_INCREMENT,
                tag_type_name varchar(30) NOT NULL,
                PRIMARY KEY (tag_type_id)
            )
        SQL;
        return $conn->query($sql);
    }

    public function getName(): string
    {
        return "tag_type";
    }


}