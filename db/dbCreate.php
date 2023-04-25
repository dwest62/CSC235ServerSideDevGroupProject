<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Tourism Site</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <!-- dbfCreate.php - Creates new web tourism database and populates with data displaying log of the results.
        Contributors:
            James West - westj4@csp.edu
            Dylan Johnson - johnsond47@csp.edu
        Course: CSC235 Server-Side Development
        Assignment: Group Project
        Date: 4/1/2023
        Last modified: 4/25/2023
    -->

    <?PHP
        include_once 'component/DBHandler.php';
        include_once "component/DestinationTable.php";
        include_once "component/DestinationTagTable.php";
        include_once "component/Table.php";
        include_once "component/TagTable.php";
        include_once "component/TagTypeTable.php";
        require_once "component/dbProcedure.php";

        /** param.php provides server specific constants: SERVER, USER, PASSWORD */
        require_once "../../params.php";

        const DB_NAME = "dbtravelminnesota";

        // Get start data from JSON file
        $startData = json_decode(file_get_contents("data.json", true), true);
        // Connect to DB and initialize new DBHandler
        $dbh = new DBHandler(SERVER, USER, PASSWORD, NULL); // ../../params.php

        // Initialize table objects
        $tables = [new DestinationTable(), new TagTypeTable(), new TagTable(), new DestinationTagTable()];

    ?>
</head>
<body>
    <h1>Travel Minnesota: Create Database</h1>
    <?PHP if($dbh->databaseExists(DB_NAME)): ?>
    <p>
        Dropping database: <?=DB_NAME?> - <?= $dbh->displayQuerySuccess($dbh->dropDatabase(DB_NAME))?>
    </p>
    <?PHP endif;?>
    <p>
        Creating database: <?=DB_NAME?> - <?= $dbh->displayQuerySuccess($dbh->createDatabase(DB_NAME))?>
    </p>
    <?PHP $dbh->getconn()->select_db(DB_NAME);?>
    <p>Creating tables:</p>
    <ul>
        <?PHP foreach($tables as $table): ?>
        <li>
            <?=$table->getName()?> - <?=$dbh->displayQuerySuccess($table->addTable($dbh->getConn()))?>
        </li>
        <?PHP endforeach; ?>
    </ul>
    <p>Adding entries:</p>
    <ul>
        <?PHP foreach($tables as $table): ?>
        <li>
            <?= $table->getName()?> -
            <?= $dbh->displayQuerySuccess($table->addJSONEntries($dbh->getConn(), $startData, true))?>
        </li>
        <?PHP endforeach; ?>
    </ul>
    <p>Creating Stored Procedures</p>
    <ul>
        <?PHP foreach (getProcedures() as $name=>$procedure): ?>
            <li>
                Adding <?=$name?> - <?= $dbh->displayQuerySuccess($dbh->getConn()->query($procedure))?>
            </li>
        <?PHP endforeach; ?>
    </ul>
    <p> Showing Table data: </p>
    <?PHP foreach ($tables as $table): ?>
        <?= $table->getDisplay($dbh->getConn()) ?>
    <?PHP endforeach; ?>


    <?PHP $dbh->getConn()->close(); ?>

</body>
</html>
