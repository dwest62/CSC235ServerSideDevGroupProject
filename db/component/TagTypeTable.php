<?php
include_once "Table.php";
class TagTypeTable extends Table
{
    public static function addTable(mysqli $conn): bool
    {
        $sql = <<<SQL
            CREATE TABLE tag_type(
                tag_type_id int NOT NULL AUTO_INCREMENT,
                tag_type_name varchar(30) UNIQUE NOT NULL,
                PRIMARY KEY (tag_type_id)
            )
        SQL;
        return $conn->query($sql);
    }

    public static function addTagType(DBHandler $dbh, string $tagTypeName): bool {
        $dbh->openConnection();
        $sql = <<<SQL
            INSERT INTO tag_type (tag_type_name) VALUES (?)
        SQL;
        $conn = $dbh->getConn();
        $stmt = $dbh->getConn()->prepare($sql);
        if(!$stmt) {
            return false;
        }
        $stmt->bind_param("s", $tagTypeName);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    public static function getTagTypeById(array $types, int $id): TagType {
        foreach ($types as $type) {
            if( (int) $type['tag_type_id'] == $id)
            {
                return new TagType((int) $type['tag_type_id'], $type['tag_type_name']);
            }
        }

        return new TagType();
    }

    public static function deleteTagType(DBHandler $dbh, int $id): bool
    {
        $dbh->openConnection();
        $sql = "DELETE FROM tag_type WHERE tag_type_id = ?";
        $conn=$dbh->getConn();
        $stmt = $conn->prepare($sql);
        if($stmt->error) {
            return false;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        return true;
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

    public static function updateTagType(DBHandler $dbh, int $id, string $name): bool
    {
        $dbh->openConnection();
        $sql = <<<SQL
            UPDATE tag_type SET tag_type_name = ? WHERE tag_type_id = ?
        SQL;
        $conn = $dbh->getConn();
        $stmt = $dbh->getConn()->prepare($sql);
        if(!$stmt) {
            return false;
        }
        $stmt->bind_param("si",$name, $id);
        echo $stmt->execute();
        $stmt->close();
        return true;
    }

}