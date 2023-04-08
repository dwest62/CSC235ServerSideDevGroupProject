<?php
/**
    dbfLib.php - Library of useful php / MYSQL database functions.
    Contributors:
        James West - westj4@csp.edu
        Dylan Johnson - johnsond47@csp.edu
    Course: CSC235 Server-Side Development
    Assignment: Group Project
    Date: 4/1/2023
    test
 */


function createConnection(string $server, string $userName, string $password): mysqli
{
    $conn = new mysqli($server, $userName, $password);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

function displayTable(String $query, mysqli $conn): bool
{
    // Handle case where query fails
    if (!$result = $conn->query($query))
    {
        echo "There was an error running the query[' . $conn->error . ']";
        return false;
    }
    // Handle case where query returns empty set
    else if (!$result->num_rows > 0)
    {
        echo "No rows to display";
        return false;
    }
    else
    {
        $row = $result->fetch_assoc();
        echo "<table>";
        displayTableRow(array_keys($row), true);
        displayTableRow($row, false);
        while ($row = $result->fetch_assoc())
        {
            displayTableRow($row, false);
        }
        echo "</table>";
    }
    return true;
}

function displayTableRow(array $values,  bool $isHeading): void
{
    $openTag = $isHeading ? "<th>" : "<td>";
    $closeTag = substr($openTag, 0, 1) . "/" . substr($openTag, 1);
    echo "<tr>";
    foreach ($values as $value)
    {
        echo $openTag . $value . $closeTag;
    }
    echo "</tr>";
}
