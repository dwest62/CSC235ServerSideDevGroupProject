<?php
include_once "Table.php";

/**
 * TagTypTable.php - Class assists in handling Tag Type Table related operations
 * Written by: James West - westj4@csp.edu - April, 2023
 */
class TagTypeTable extends Table
{
    /**
     * @param mysqli $conn
     * @return bool
     */
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

    /**
     * @param DBHandler $dbh
     * @param string $tagTypeName
     * @return bool
     */
    public static function addTagType(DBHandler $dbh, string $tagTypeName): bool
    {
        $sql = <<<SQL
            INSERT INTO tag_type (tag_type_name) VALUES (?)
        SQL;
        $stmt = $dbh->getConn()->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("s", $tagTypeName);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    /**
     * @param DBHandler $dbh
     * @param int $id
     * @return bool
     */
    public static function deleteTagType(DBHandler $dbh, int $id): bool
    {
        $sql = "DELETE FROM tag_type WHERE tag_type_id = ?";
        $conn = $dbh->getConn();
        $stmt = $conn->prepare($sql);
        if ($stmt->error) {
            return false;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return "tag_type";
    }

    /**
     * @param array $types
     * @param int $id
     * @return TagType
     */
    public static function getTagTypeById(array $types, int $id): TagType
    {
        foreach ($types as $type) {
            if ((int)$type['tag_type_id'] == $id) {
                return new TagType((int)$type['tag_type_id'], $type['tag_type_name']);
            }
        }

        return new TagType();
    }

    /**
     * @param DBHandler $dbh
     * @return array
     */
    public static function getTagTypes(DBHandler $dbh): array
    {
        $sql = "SELECT * FROM tag_type";
        return $dbh->getConn()->query($sql)->fetch_all(1);
    }

    /**
     * @param DBHandler $dbh
     * @param int $id
     * @param string $name
     * @return bool
     */
    public static function updateTagType(DBHandler $dbh, int $id, string $name): bool
    {
        $sql = <<<SQL
            UPDATE tag_type SET tag_type_name = ? WHERE tag_type_id = ?
        SQL;
        $stmt = $dbh->getConn()->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("si", $name, $id);
        echo $stmt->execute();
        $stmt->close();
        return true;
    }

}