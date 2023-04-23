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

        $in = join(",", array_fill(0, count($tag_ids), '?'));
        $dbh->openConnection();
        $conn = $dbh->getConn();
        $sql = <<<SQL
            DELETE FROM destination_tag WHERE destination_tag.destination = ? AND destination_tag.tag IN ($in);
        SQL;
        $stmt = $conn->prepare($sql);
        echo $stmt->error;
        $stmt->bind_param(str_repeat('i', count($tag_ids) + 1), $destination_id, ...$tag_ids);
        $stmt->execute();
        $stmt->close();
        return true;
    }
    public static function addTagsToDestination(DBHandler $dbh, array $tag_ids, int $destination_id): bool
    {
        $dbh->openConnection();
        $conn = $dbh->getConn();
        $sql = <<<SQL
            INSERT INTO destination_tag (destination, tag) VALUES (?, ?);
        SQL;
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $destination_id, $tag);
        foreach ($tag_ids as $tag_id)
        {
            $tag = $tag_id;
            $stmt->execute();
        }
        $stmt->close();
        echo $conn->error;
        return true;
    }
    public function getName(): string
    {
        return "destination_tag";
    }

}