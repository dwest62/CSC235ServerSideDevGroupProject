<?php
/* getJSON.php - Extract data from database and present as JSON data
*   Written by: James West and Dylan Johnson
*   Written:  4/30/23
*   Revised:  5/2/23
*   Ref: https://www.developphp.com/video/JavaScript/JSON-Timed-Ajax-PHP-MySQL-Data-Request-Web-Application
*/
// The JSON standard MIME header. Output as JSON, not HTML
header('Content-type: application/json');
//$limit = 5;

//Check for the limit value
if (isset($_POST['id'])) {
    $id = preg_replace('#[^0-9]#', '', $_POST['id']);
} else {
    $id = 2;
}

/** params.php provides server specific constants: SERVER, USER, PASSWORD */
require_once "../../params.php";
/*
// Using default username and password for AMPPS
define("SERVER",   "localhost");
define("USER", "root");
define("PASSWORD",  "mysql");
define("DATABASE_NAME", "touristwebapplicationdb");
*/
// Global connection object
$conn = NULL;

// Connect to database
createConnection();

// Get the runner and sponsor data from the tables
// Use the RANDOM function and the $limit variable
// so only four records at random are extracted.
$sql = "SELECT * FROM destination
            WHERE destination_id = " . $id;
$result = $conn->query($sql);
//displayResult($result, $sql);

// Loop through the $result to create JSON formatted data
$runnerArray = array();
while ($thisRow = $result->fetch_assoc()) {
    $runnerArray[] = $thisRow;
}
echo json_encode($runnerArray);


/*************** FUNCTIONS (Alphabetical) *************************/
/* -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  - 
 * createConnection( ) - Create a database connection
 -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  - */
function createConnection()
{
    global $conn;
    // Create connection object
    $conn = new mysqli(SERVER, USER, PASSWORD);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Select the database
    $conn->select_db(DATABASE_NAME);
} // end of createConnection( )


/* -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  - 
 * displayResult( ) - Execute a query and json the result
 *    Parameters:  $rs -  result set to json as 2D array
 *                 $sql - SQL string used to json an error msg
 -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  */
function displayResult($result, $sql)
{
    if ($result->num_rows > 0) {
        echo "<table border='1'>\n";
        // print headings (field names)
        $heading = $result->fetch_assoc();
        echo "<tr>\n";
        // print field names
        foreach ($heading as $key => $value) {
            echo "<th>" . $key . "</th>\n";
        }
        echo "</tr>\n";

        // Print values for the first row
        echo "<tr>\n";
        foreach ($heading as $key => $value) {
            echo "<td>" . $value . "</td>\n";
        }

        // output rest of the records
        while ($row = $result->fetch_assoc()) {
            //print_r($row);
            //echo "<br />";
            echo "<tr>\n";
            // print data
            foreach ($row as $key => $value) {
                echo "<td>" . $value . "</td>\n";
            }
            echo "</tr>\n";
        }
        echo "</table>\n";
    } else {
        echo "<strong>zero results using SQL: </strong>" . $sql;
    }
} // end of displayResult( )
?>