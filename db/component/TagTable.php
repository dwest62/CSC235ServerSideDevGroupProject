<?php
include_once "Table.php";

/**
 * TagTable.php - Class assists in handling Tag Table related operations
 * Written by: James West - westj4@csp.edu - April, 2023
 */
class TagTable extends Table
{
    /**
     * @param mysqli $conn
     * @return bool
     */
    public static function addTable(mysqli $conn): bool
    {
        $sql = <<<SQL
            CREATE TABLE tag(
                tag_id int NOT NULL AUTO_INCREMENT,
                tag_type int,
                tag_name varchar(30) NOT NULL,
                PRIMARY KEY (tag_id),
                FOREIGN KEY (tag_type) REFERENCES tag_type(tag_type_id) ON DELETE CASCADE,
                UNIQUE KEY `unique_type_name` (tag_type, tag_name)
            )
        SQL;
        return $conn->query($sql);
    }

    /**
     * @param DBHandler $dbh
     * @param string $name
     * @param int $type
     * @return bool
     */
    public static function addTag(DBHandler $dbh, string $name, int $type): bool
    {
        $sql = <<<SQL
            INSERT INTO tag(tag.tag_name, tag.tag_type) VALUES (?,?)
        SQL;
        $conn = $dbh->getConn();
        $stmt = $dbh->getConn()->prepare($sql);
        if (!$stmt) {
            echo $conn->error;
            return false;
        }
        $stmt->bind_param("si", $name, $type);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    /**
     * @param DBHandler $dbh
     * @param int $id
     * @return bool
     */
    public static function deleteTag(DBHandler $dbh, int $id): bool
    {
        $sql = <<<SQL
            DELETE FROM tag WHERE tag.tag_id = ?
        SQL;
        $stmt = $dbh->getConn()->prepare($sql);
        if ($stmt->error) {
            return false;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    /**
     * @param DBHandler $dbh
     * @return array
     */
    public static function getAllTagsJoinTagTypeName(DBHandler $dbh): array
    {
        $conn = $dbh->getConn();
        $sql = <<<SQL
            SELECT destination, tag_id, tag_name, tag_type_id, tag_type_name FROM destination_tag
            RIGHT JOIN tag ON tag.tag_id = destination_tag.tag
            JOIN tag_type ON tag_type.tag_type_id = tag.tag_type
            ORDER BY tag_type.tag_type_name;
        SQL;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(1);
        $stmt->free_result();
        $stmt->close();
        if (!$result) {
            return [];
        }
        return $result;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return "tag";
    }

    /**
     * @param array $tags
     * @param int $id
     * @return Tag
     */
    public static function getTagById(array $tags, int $id): Tag
    {
        foreach ($tags as $tag) {
            if ($tag['tag_id'] == $id) {
                return new Tag($id, $tag['tag_type_id'], $tag['tag_name'], $tag['tag_type_name']);
            }
        }
        return new Tag();
    }

    /**
     * @param array $tags
     * @param int $destinationId
     * @return array|array[]
     */
    public static function sortTagsByActiveAndType(array $tags, int $destinationId): array
    {
        // Map containing visited tags
        $visited = [];
        $result = ["active" => [], "inactive" => [], "all" => []];
        foreach ($tags as $tag) {
            if ($tag['destination'] == $destinationId) {
                $result["all"][$tag["tag_type_name"]][] = ["tag_name" => $tag['tag_name'], "tag_id" => $tag['tag_id']];
                $result['active'][$tag['tag_type_name']][] = ["tag_name" => $tag['tag_name'], "tag_id" => $tag['tag_id']];
                // Mark tag as visited
                $visited[$tag['tag_id']] = true;
            }
        }
        foreach ($tags as $tag) {
            // If tag is not visited here, it is not active for destination AND has not yet been added to inactive tags
            if (!isset($visited[$tag['tag_id']])) {
                $result["all"][$tag["tag_type_name"]][] = ["tag_name" => $tag['tag_name'], "tag_id" => $tag['tag_id']];
                $result['inactive'][$tag['tag_type_name']][] = ["tag_name" => $tag['tag_name'], "tag_id" => $tag['tag_id']];
                $visited[$tag['tag_id']] = true;
            }
        }
        return $result;
    }

    /**
     * @param DBHandler $dbh
     * @param int $id
     * @param string $name
     * @param int $type
     * @return bool
     */
    public static function updateTag(DBHandler $dbh, int $id, string $name, int $type): bool
    {
        $sql = <<<SQL
            UPDATE tag SET tag.tag_name = ?, tag.tag_type = ? WHERE tag.tag_id = ?
        SQL;
        $conn = $dbh->getConn();
        $stmt = $dbh->getConn()->prepare($sql);
        if (!$stmt) {
            echo $conn->error;
            return false;
        }
        $stmt->bind_param("sii", $name, $type, $id);
        $stmt->execute();
        $stmt->close();
        return true;
    }

}