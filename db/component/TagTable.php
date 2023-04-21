<?php
include_once "Table.php";
class TagTable extends Table
{
    public static function addTable(mysqli $conn): bool
    {
        $sql = <<<SQL
            CREATE TABLE tag(
                tag_id int NOT NULL AUTO_INCREMENT,
                tag_type int,
                tag_name varchar(30) NOT NULL,
                PRIMARY KEY (tag_id),
                FOREIGN KEY (tag_type) REFERENCES tag_type(tag_type_id) ON DELETE CASCADE
            )
        SQL;
        return $conn->query($sql);
    }

    public function getName(): string
    {
        return "tag";
    }

    public function getById(mysqli $conn, int $id): Tag
    {
        $thisID = $id;
        $sql =<<<SQL
            SELECT * FROM Tag WHERE tag_id={$id}
        SQL;

        $data = $conn->query($sql)->fetch_all(1);



        return new Tag($data[0]['tag_id'], $data[0]['tag_type'], $data[0]['tag_name']);;
    }


}