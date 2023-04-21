<?php

include_once "Table.php";
class DestinationTable extends Table
{
    private string $name = "destination";
    public static function addTable(mysqli $conn): bool
    {
        $sql = <<<SQL
            CREATE TABLE destination(
                destination_id int NOT NULL AUTO_INCREMENT,
                destination_name varchar(50) NOT NULL UNIQUE,
                destination_desc varchar(5000) NOT NULL,
                image_url varchar(50),
                website varchar(300),
                zip varchar(35) NOT NULL,
                line_1 varchar(95) NOT NULL,
                line_2 varchar(95) NOT NULL,
                city varchar(35) NOT NULL,
                PRIMARY KEY (destination_id)
        )
        SQL;
        return $conn->query($sql);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function add(mysqli $conn, Destination $destination): bool
    {
        $desc = addslashes($destination->getDescription());
        $sql =<<<SQL
        CALL addDestination('{$destination->getName()}', '{$desc}', '{$destination->getImageUrl()}', 
            '{$destination->getWebsite()}', '{$destination->getZip()}', '{$destination->getLine1()}', '{$destination->getLine2()}', 
            '{$destination->getCity()}')
        SQL;
        $destination->clear();
        return $conn->query($sql);
    }

    public function getById(mysqli $conn, int $id): Destination
    {
        $stmt = $conn->prepare(
            <<<SQL
            SELECT *, LENGTH(destination_desc) AS 'len' FROM destination WHERE destination_id = ?
            SQL
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(1);
        $result->free();
        while($conn->next_result()){
            echo "here";
            if($result = $conn->store_result()){$result->free();}
        }
        $stmt->close();
        if($data) {
            return new Destination($data[0]['destination_id'], $data[0]['destination_name'], $data[0]['destination_desc'], $data[0]['zip'],
                $data[0]['line_1'], $data[0]['line_2'], $data[0]['city'], $data[0]['image_url'], $data[0]['website']);
        } else {
            return new Destination();
        }
    }
    public function getOptions(mysqli $conn): array
    {
        $result = $conn->query("SELECT destination_id, destination_name FROM destination ORDER BY destination_name");
        if(!$result)
        {
            echo "<br>Destination table options error: $conn->error";
            return [];
        }
        return $result->fetch_all(1);
    }
    public function update(mysqli $conn, Destination $destination): bool
    {
        $desc = addslashes($destination->getDescription());
        $sql =<<<SQL
        CALL updateDestination(?,?,?,?,?,?,?,?,?)
        SQL;

        $stmt = $conn->prepare($sql);
        $id = $destination->getId();
        $name = $destination->getName();
        $imageUrl = $destination->getImageUrl();
        $website = $destination->getWebsite();
        $zip = $destination->getZip();
        $line1 = $destination->getLine1();
        $line2 = $destination->getLine2();
        $city = $destination->getCity();
        $success = false;

        $stmt->bind_param("sssssssss", $id, $name, $desc, $imageUrl, $website, $zip, $line1, $line2, $city);
        $stmt->execute();
        $stmt->bind_result($success);
        $stmt->free_result();
        $stmt->close();
        return $success;
    }
    public function delete(mysqli $conn, Destination $destination): bool
    {
        $sql =<<<SQL
            CALL deleteDestination({$destination->getId()});
        SQL;
        $destination->clear();
        return $conn->query($sql);
    }

    public function getErrMsg(mysqli $conn, string $name): string
    {
        switch ($conn->errno)
        {
            case 1062:
                return "Error adding $name: a destination with that name already exists.";
            default:
                return "Error: $conn->error";
        }
    }
}