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
        Last modified: 4/16/2023
    -->

    <?PHP
        include_once "component/DBHandler.php";
        include_once "component/DestinationTable.php";
        include_once "component/DestinationTagTable.php";
        include_once "component/Table.php";
        include_once "component/TagTable.php";
        include_once "component/TagTypeTable.php";
        include_once "../../params.php";
        require_once "component/dbProcedure.php";

        const DB_NAME = "dbtravelminnesota";

        $startData = json_decode(file_get_contents("data.json", true), true);
        $dbh = new DBHandler(SERVER, USER, PASSWORD, NULL);
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
                Adding <?=$name?> - <?= $dbh->addProcedure($procedure)?>
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
