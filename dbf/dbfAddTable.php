<?php
/**
    dbfAddTable.php - Defines and creates all tables for tourism database.
    Contributors:
        James West - westj4@csp.edu
        Dylan Johnson - johnsond47@csp.edu
    Course: CSC235 Server-Side Development
    Assignment: Group Project
    Date: 4/10/2023
 */

/**
 * @return bool true if operation succeeds, otherwise false.
 */
function addDestinationTable(): bool
{
    global $conn;
    $sql =
        "CREATE TABLE destination(
                    destination_id int NOT NULL AUTO_INCREMENT,
                    destination_name varchar(50) NOT NULL,
                    destination_desc varchar(5000) NOT NULL,
                    image_url varchar(50),
                    website varchar(300),
                    zip varchar(35) NOT NULL,
                    line_1 varchar(95) NOT NULL,
                    line_2 varchar(95) NOT NULL,
                    city varchar(35) NOT NULL,
                    PRIMARY KEY (destination_id)
        )";
    return $conn->query($sql);
}

/**
 * @return bool true if operation succeeds, otherwise false.
 */
function addTagTable(): bool
{
    global $conn;
    $sql =
        "CREATE TABLE tag(
                    tag_id int NOT NULL AUTO_INCREMENT,
                    tag_type int,
                    tag_name varchar(30) NOT NULL,
                    PRIMARY KEY (tag_id),
                    FOREIGN KEY (tag_type) REFERENCES tag_type(tag_type_id) ON DELETE CASCADE ON UPDATE CASCADE
        )";
    return $conn->query($sql);
}
/**
 * @return bool true if operation succeeds, otherwise false.
 */
function addTagTypeTable(): bool
{
    global $conn;
    $sql =
        "CREATE TABLE tag_type(
                    tag_type_id int NOT NULL  AUTO_INCREMENT,
                    tag_type_name varchar(30) NOT NULL,
                    PRIMARY KEY (tag_type_id)
        )";
    return $conn->query($sql);
}

/**
 * @return bool true if operation succeeds, otherwise false.
 */
function addDestinationTagTable(): bool
{
    global $conn;
    $sql =
        "CREATE TABLE destination_tag(
                    destination_tag_id int NOT NULL AUTO_INCREMENT,
                    destination int NOT NULL,
                    tag int NOT NULL,
                    PRIMARY KEY (destination_tag_id),
                    FOREIGN KEY (destination) REFERENCES destination (destination_id) ON DELETE CASCADE ON UPDATE CASCADE,
                    FOREIGN KEY (tag) REFERENCES tag (tag_id) ON DELETE CASCADE ON UPDATE CASCADE
        )";
    return $conn->query($sql);
}

/**
 * @return bool true if operation succeeds, otherwise false.
 */
function addUserTable(): bool
{
    global $conn;
    $sql =
        "CREATE TABLE user(
                    u_id int NOT NULL AUTO_INCREMENT,
                    u_lname varchar(30) NOT NULL,
                    u_fname varchar(30) NOT NULL,
                    u_pass_hash varchar(50) NOT NULL,
                    u_login varchar(25) NOT NULL,
                    is_admin bool NOT NULL,                        
                    PRIMARY KEY (u_id)
        )";
    return $conn->query($sql);
}

/**
 * @return bool true if operation succeeds, otherwise false.
 */
function addTables(): bool
{
    foreach (
        [
            ["destination", "addDestinationTable"],
            ["tag_type", "addTagTypeTable"],
            ["user", "addUserTable"],
            ["tag", "addTagTable"],
            ["destination_tag", "addDestinationTagTable"]
        ] as $nameFunc) {
        $success = ($nameFunc[1])();
        echo "<br />&emsp;Adding $nameFunc[0] table > " . getQueryResultMsg($success);
        if (!$success) {
            return false;
        }
    }
    return true;
}
?>
