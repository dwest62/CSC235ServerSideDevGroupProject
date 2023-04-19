<?php

include_once "Table.php";
class DestinationTagTable extends Table
{

    public static function addTable(mysqli $conn): bool
    {
        $sql = <<<SQL
            CREATE TABLE destination_tag(
                destination_tag_id int NOT NULL AUTO_INCREMENT,
                destination int NOT NULL,
                tag int NOT NULL,
                PRIMARY KEY (destination_tag_id),
                FOREIGN KEY (destination) REFERENCES destination(destination_id) ON DELETE CASCADE,
                FOREIGN KEY (tag) REFERENCES tag(tag_id) ON DELETE CASCADE ON UPDATE CASCADE,
                UNIQUE KEY `unique` (destination, tag)
            )
        SQL;
        return $conn->query($sql);
    }

    public static function removeTagsFromDestination(DBHandler $dbh, array $tag_ids, int $destination_id): bool
    {
        return $this->getTagsQuery(
            $dbh,
            $destination,
            <<<SQL
                CALL getDestinationTagsJoinTagType(?)
            SQL
        );
    }
    public function getNotDestinationTagsJoinTagType(DBHandler $dbh, Destination $destination): array
    {
        return $this->getTagsQuery(
            $dbh,
            $destination,
            <<<SQL
                CALL getNotDestinationTagsJoinTagType(?)
            SQL
        );
    }

    /**
     * @param DBHandler $dbh
     * @param Destination $destination
     * @return array
     */
    public function getTagsQuery(DBHandler $dbh, Destination $destination, $sql): array
    {
        $conn = $dbh->getNewConn();
        $id = $destination->getId();
        $stmt = $conn->prepare($sql);
        echo $stmt->error;
        $stmt->bind_param(str_repeat('i', count($tag_ids) + 1), $destination_id, ...$tag_ids);
        $stmt->execute();
        $stmt->close();
        if (!$result) {
            return [];
        }
        $dbh->getNewConn();
        print_r($result);
        $arr = [];
        foreach ($result as $tag) {
            $arr[$tag['tag_type_name']][] = $tag;
        }
        return $arr;
    }
    public function getName(): string
    {
        return "destination_tag";
    }

}