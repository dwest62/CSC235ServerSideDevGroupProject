<!-- 
    admin.php - web interface for database 
    Student Name: Dylan Johnson
    Written:  4/10/23
    Revised:  
-->


<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <title>Administration Page</title>
    <link rel="stylesheet" type="text/css" href="../admin/style.css">

    <?PHP
        // Set up connection constants
        // Using default username and password for AMPPS  
        require_once "../../db/dbfLib.php";
        require_once "../../../params.php";

            

        // Global connection object
        $conn = createConnection(SERVER, USER, PASSWORD);
        $conn->select_db(DB_NAME);


        // Did the user select a runner from the list?
        // 'new' is the value of the first item on the runner list box 
        
        if(isset($_POST['lstDestination']) && !($_POST['lstDestination'] == 'new')){
            
            $sql = "SELECT *, LENGTH(destination_desc) as 'len' 
                    FROM destination
                    WHERE destination_id =" . $_POST['lstDestination'];
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            $destination = [
                "id" => $row['destination_id'],
                "name" => $row['destination_name'],
                "description" => $row['destination_desc'],
                "img" => $row['image_url'],
                "website" => $row['website'],
                "line1" => $row['line_1'],
                "line2" => $row['line_2'],
                "city" => $row['city'],
                "zip" => $row['zip'],
                "len" => $row['len']
            ];

        } else {
            $destination = clearFields();
        } // end if lstDestination
        

        // Determine which button may have been clicked
        switch($_POST['btnSubmit']) {

            // = = = = = = = = = = = = = = = = = = = 
            // DELETE Selected Destination
            // = = = = = = = = = = = = = = = = = = = 
            case 'delete':

                if($_POST["txtName"] == "") {
                    displayMessage("Please select a destination." , "red");
                }else {
                    //Verify the DELETE using a SELECT first
                    $sql = "DELETE FROM destination WHERE destination_id = " . $destination['id'];
                    $result = $conn->query($sql);
                    if($result) {
                        displayMessage($destination['name'] . " deleted.", "green");
                        clearFields();
                    }
                }
                break;


            // = = = = = = = = = = = = = = = = = = = 
            // ADD NEW Destination
            // = = = = = = = = = = = = = = = = = = = 
            case 'new':
            
                break;

            // = = = = = = = = = = = = = = = = = = = 
            // UPDATE Existing Destination
            // = = = = = = = = = = = = = = = = = = = 
            case 'update':
                
                if ($_POST['txtName']=="" || $_POST['txtDescription']=="" || $_POST['txtLineOne']=="") {
                    displayMessage("Please fill out all required fields", "red");
                }
                // First name and last name are selected
                else {
                    $isSuccessful = false;
                    
                    $descriptionText = mysqli_real_escape_string($conn, $_POST['txtDescription']);
                    
                    $sql = "UPDATE destination SET destination_name = '" . $_POST['txtName'] . "', "
                                    . " destination_desc  = '$descriptionText', "
                                    . " image_url  = '" . $_POST['txtImg'] . "', "
                                    . " zip  = '" . $_POST['txtZip'] . "', "
                                    . " line_1  = '" . $_POST['txtLineOne'] . "', "
                                    . " line_2  = '" . $_POST['txtLineTwo'] . "', "
                                    . " city  = '" . $_POST['txtCity'] . "', "
                                    . " website = '" . $_POST['txtWebsite'] . "' 
                                    WHERE destination_id = " . $_POST['destID'];
                    $result = $conn->query($sql);
                    
                    /*
                    $name = mysqli_real_escape_string($conn, $_POST['txtName']);
                    $description = mysqli_real_escape_string($conn, $_POST['txtDescription']);
                    $img = mysqli_real_escape_string($conn, $_POST['txtImg']);
                    $zip = mysqli_real_escape_string($conn, $_POST['txtZip']);
                    $line1 = mysqli_real_escape_string($conn, $_POST['txtLineOne']);
                    $line2 = mysqli_real_escape_string($conn, $_POST['txtLineTwo']);
                    $city = mysqli_real_escape_string($conn, $_POST['txtCity']);
                    $website = mysqli_real_escape_string($conn, $_POST['txtWebsite']);
                    $destID = mysqli_real_escape_string($conn, $_POST['destID']);

                    // Build the SQL query using the escaped input
                    $sql = "UPDATE destination SET 
                            destination_name = '$name', 
                            destination_desc  = '$description', 
                            image_url  = '$img', 
                            zip  = '$zip', 
                            line_1  = '$line1', 
                            line_2  = '$line2', 
                            city  = '$city', 
                            website = '$website' 
                            WHERE destination_id = '$destID'";

                    // Execute the SQL query
                    echo $sql;
                    mysqli_query($conn, $sql);
                    */
                    if (!$result) {
                        displayMessage("Error: " . $conn->error, "red");
                    } else {
                        displayMessage($destination['name'] . " updated!", "green");
                    }
                
                }

                break;
                //End


                            
        } // end of switch( )
        

    ?>

    </head>
    <body>
        <div id="frame">
            <h1>Minnesota Destination CRUD</h1>

            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" name="tableEdit" id="tableEdit">
                <label for="lstDestination"><strong>Select Destination</strong></label>

                <select name="lstDestination" id="lstDestination" onChange="this.form.submit();">
                    <option value="new">Select a name</option>
                    <?php
                        // Loop through the runner dbTable to build the <option> list
                        $sql = "SELECT destination_id as 'id', destination_name as 'name'
                                FROM destination ORDER BY destination_name";
                        $result = $conn->query($sql);
                        while($row = $result->fetch_assoc()) {   
                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>\n";
                        }
                    ?>
                </select> 

                &nbsp;&nbsp;<a href="<?php echo htmlentities($_SERVER['PHP_SELF']); clearFields();?>">New Record</a>
                <br />
                <br />

                <fieldset>
                    <legend>Destination Information</legend>
                            
                    <div class="topLabel">
                        <label for="txtName">Name</label>
                        <input type="text" name="txtName"   id="txtName" value="<?php echo $destination['name'] ?>" />
                    </div>

                    <div class="topLabel">
                        <label for="txtImg">Image URL</label>
                        <input type="text" name="txtImg"   id="txtImg" value="<?php echo $destination['img'] ?>" />
                    </div>

                    <div class="topLabel">
                        <label for="txtWebsite">Website</label>
                        <input type="text" name="txtWebsite"   id="txtWebsite" value="<?php echo $destination['website'] ?>" />
                    </div>

                    <div class="topLabel">
                        <label for="txtLineOne">Address Line 1</label>
                        <input type="text" name="txtLineOne"   id="txtLineOne" value="<?php echo $destination['line1'] ?>" />
                    </div>

                    <div class="topLabel">
                        <label for="txtLineTwo">Address Line 2</label>
                        <input type="text" name="txtLineTwo"   id="txtLineTwo" value="<?php echo $destination['line2'] ?>" />
                    </div>

                    <div class="topLabel">
                        <label for="txtCity">City</label>
                        <input type="text" name="txtCity"   id="txtCity" value="<?php echo $destination['city'] ?>" />
                    </div>

                    <div class="topLabel">
                        <label for="txtZip">Zip Code</label>
                        <input type="text" name="txtZip"   id="txtZip" value="<?php echo $destination['zip'] ?>" />
                    </div>

                    <div class="topLabel">
                        <label for="txtDescription">Description</label>
                        <input type="text" name="txtDescription"   id="txtDescription" 
                                value="<?php echo $destination['description'] ?>" size="<?php echo $destination['len']?>"
                                maxlength="<?php echo 5000 ?>" />
                    </div>
                    <input type="hidden" name="destID" id="destID" value="<?php echo $destination['id']?>">

                </fieldset>

                <br />
                <button name="btnSubmit" 
                    value="delete"
                    style="float:left;"
                    onclick="this.form.submit();">
                    Delete Record
                </button>
                    
                <button name="btnSubmit"    
                    value="new"  
                    style="float:right;"
                    onclick="this.form.submit();">
                    Add New Destination
                </button>
                    
                <button name="btnSubmit" 
                    value="update" 
                    style="float:right;"
                    onclick="this.form.submit();">
                    Update
                </button>
                <br />     
                <!-- Use a hidden field to tell server if return visitor -->
                <input type="hidden" name="hidIsReturning" value="true" />
            </form>


        </div>
    </body>
</html>