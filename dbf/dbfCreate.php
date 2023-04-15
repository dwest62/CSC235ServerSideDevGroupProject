<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Tourism Site</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <!-- dbfCreate.php - Creates new web tourism database and populates with data displaying log of the results.
        Note: Drops existing database if exists.
        Contributors:
            James West - westj4@csp.edu
            Dylan Johnson - johnsond47@csp.edu
        Course: CSC235 Server-Side Development
        Assignment: Group Project
        Date: 4/1/2023
        Last modified: 4/7/2023
    -->

    <?PHP
        // link library
        require_once "dbfLib.php";
        require_once "dbfAddTable.php";
        require_once "dbfAddData.php";
        require_once "../../params.php";

        

        $conn = createConnection(SERVER, USER, PASSWORD);

        /**
         * Main function
         */
        function main(): void
        {
            global $conn;

            // Drop database if exists.
            echo "<p>Database " . (databaseExists()
                    ? (DATABASE_NAME . " exists.<br/>DROPPING database > " . getQueryResultMsg(dropDatabase()))
                    : DATABASE_NAME . " does not exist.");

            // Create new database
            echo "<br />Creating database " . DATABASE_NAME . " from scratch > " . getQueryResultMsg(createNewDatabase());

            // Select database
            $conn->select_db(DATABASE_NAME);

            // Add tables
            echo "<br />Adding Tables > ";
            echo "<br />" . getQueryResultMsg(addTables()) . "<br />";

            // Add data to tables
            echo "<br />Adding data to Tables > <br />";
            echo getQueryResultMsg(addDataToTables());
            echo "</p>";
        }


        /**
         * @return bool true if operation succeeds, otherwise false.
         */
        function createNewDatabase(): bool
        {
            global $conn;
            $sql =
                "CREATE DATABASE " . DATABASE_NAME;
            return $conn->query($sql);
        }

        // Source: https://www.tutorialspoint.com/how-to-check-if-a-mysql-database-exists. Checks if database exists.
        /**
         * @return bool true if database exists, otherwise false.
         */
        function databaseExists(): bool
        {
            global $conn;
            $sql = "SELECT schema_name FROM information_schema.schemata WHERE SCHEMA_NAME LIKE " . "'" . DATABASE_NAME . "'";
            return $conn->query($sql)->num_rows;
        }

        /**
         * @return bool true if operation succeeds, otherwise false
         */
        function dropDatabase(): bool
        {
            global $conn;
            $sql =
                "DROP DATABASE " . DATABASE_NAME;
            return $conn->query($sql);
        }


        /**
         * @param bool $query was the query a success?
         * @return String "'success!' if true, otherwise 'failure!'
         */
        function getQueryResultMsg(bool $query): String
        {
            return $query ? "success!" : "failure!";
        }
        ?>
    </head>
    <body>
    <h1>Creating database Log</h1>
    <!-- Allow user to select which table to view -->
<?PHP
main();
?>
<h1>Tables</h1>
<div>
    <h3>Destination Table</h3>
    <?= displayTable("SELECT * FROM destination", $conn)?>
    <h3>Tag Type Table</h3>
    <?= displayTable("SELECT * FROM tag_type", $conn)?>
    <h3>Destination Tag Table</h3>
    <?=displayTable("SELECT * FROM destination_tag", $conn)?>
    <?php $conn->close() ?>
</div>
</body>
</html>
