<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Administration Page</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<!--
    admin.php - web interface for database
    Student Name: Dylan Johnson, James West
    Written:  4/10/23
    Revised:  4/11/23
-->
<body>
<?php
require_once "../../../params.php";
require_once "../../db/component/Table.php";
require_once "../../db/component/DestinationTable.php";
require_once "../../db/component/Destination.php";
require_once "../../db/component/DBHandler.php";
require_once "../../db/component/DestinationTagTable.php";
require_once "../../db/component/Tag.php";
require_once "../../db/component/TagTable.php";
include "component/destinationForm.php";
?>
</body>
</html>
