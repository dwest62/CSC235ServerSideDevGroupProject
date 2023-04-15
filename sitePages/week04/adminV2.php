<!--
    admin.php - web interface for database
    Student Name: Dylan Johnson, James West
    Written:  4/10/23
    Revised:  4/11/23
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Administration Page</title>

    <?PHP
        // Set up connection constants
        // Using default username and password for AMPPS
        require_once "../../dbf/dbfLib.php";
        require_once "../../../params.php";

        // Global connection object
        $conn = createConnection(SERVER, USER, PASSWORD);
        $conn->select_db(DATABASE_NAME);

        // TODO Get Destination data

        // TODO Populate Form Options with data

        // TODO Populate Form Fields based on option chosen

        // TODO Implement Add item

        // TODO Implement Delete item

        // TODO Implement Update Destination



    ?>
</head>
</html>