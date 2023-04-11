<?php
    function addDataToTables(): bool
    {
        // change
        global $conn;
        $starterData = json_decode(file_get_contents("starterData.json"), true);

        // Destination data
        echo "&emsp;Destination Table:<br />";
        foreach ($starterData['destination'] as $entry) {
            $sql =
                "INSERT INTO destination (destination_id, destination_name, destination_desc, website, image_url, zip, line_1, line_2, city)
                    VALUES (
                            '{$entry["destination_id"]}',
                            '{$entry["destination_name"]}',
                            '{$entry["destination_desc"]}',
                            '{$entry["website"]}',
                            '{$entry["image_url"]}',
                            '{$entry["zip"]}',
                            '{$entry["line_1"]}',
                            '{$entry["line_2"]}',
                            '{$entry["city"]}'
                    )";
            $result = $conn->query($sql);
            if (!$result) {
                echo $conn->error . "</br>";
                return false;
            }
            echo "&emsp;&emsp;" . $entry["destination_name"] . " added!" . "<br />";
        }

        // Tag type data
        echo "&emsp;Tag Type Table:<br />";
        foreach ($starterData['tag_type'] as $entry) {
            $sql =
                "INSERT INTO tag_type
                    VALUES (
                            '{$entry["tag_type_id"]}',
                            '{$entry["tag_type_name"]}'
                    )";
            $result = $conn->query($sql);
            if (!$result) {
                echo $conn->error . "</br>";
                return false;
            }
            echo "&emsp;&emsp;" . $entry["tag_type_name"] . " added!" . "<br />";
        }

        // Tag data
        echo "&emsp;Tag Table:<br />";
        foreach ($starterData['tag'] as $entry) {
            $sql =
                "INSERT INTO tag (tag_id, tag_type, tag_name)
                    VALUES (
                            {$entry['tag_id']},
                            {$entry['tag_type']},
                            '{$entry['tag_name']}'
                    )";
            $result = $conn->query($sql);
            if (!$result) {
                echo $conn->error . "</br>";
                return false;
            }
            echo "&emsp;&emsp;" . $entry["tag_name"] . " added!" . "<br />";
        }

    // Tag Destination data
    echo "&emsp;Destination Tag Table:<br />";
    foreach ($starterData['destination_tag'] as $entry) {
        $sql =
            "INSERT INTO destination_tag
                        VALUES (
                                {$entry['destination_tag_id']},
                                {$entry["destination"]},
                                {$entry["tag"]}
                        )";
        $result = $conn->query($sql);
        if (!$result) {
            echo $conn->error . "</br>";
            return false;
        }
        echo "&emsp;&emsp;" . $entry["destination_tag_id"] . " added!" . "<br />";
    }
    return true;
}
?>
