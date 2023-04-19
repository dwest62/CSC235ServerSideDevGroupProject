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

    public static function getTagTypes(DBHandler $dbh): array
    {
        $dbh->openConnection();
        $sql = "SELECT * FROM tag_Type";
        return $dbh->getConn()->query($sql)->fetch_all(1);
    }

    public static function getTagTypeById(array $types, int $id) {
        foreach ($types as $type) {
            if($type['tag_type_id'] == $id) {
                return new TagType($type['tag_type_id'], $type['tag_type_name']);
            }
        }
        return new TagType();
    }

}