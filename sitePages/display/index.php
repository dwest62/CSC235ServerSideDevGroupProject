<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destinations</title>
    <link rel="shortcut icon" type="image/x-icon" href="graphic/mn.png">
    <link rel="stylesheet" type="text/css" href="style.css">
    <?php 

        require_once "../../../params.php";

        global $conn;

        function createConnection( ) {
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
    ?>
    <script>
            function getData() {
                var counter = 0;
                var name = document.getElementById("destination_name");
                var description = document.getElementById("descr");
                var link = document.getElementById("link");
                var address = document.getElementById("address");
                var image = document.getElementById("image");
                var selector = document.getElementById('destinationDropdown');
                var selected = selector.value;
                var httpReq = new XMLHttpRequest();
                
                // Add AJAX call
                // Request the API script using POST, calling the PHP script
                httpReq.open("POST", "getJSON.php", true);
                httpReq.setRequestHeader("content-type", "application/x-www-form-urlencoded");
                httpReq.onreadystatechange = function() {
                    if(httpReq.readyState == 4 && httpReq.status == 200) {
                        var dataObject = JSON.parse(httpReq.responseText);
                        //clear the data each time around
                        name.innerHTML = "";
                        description.innerHTML = "";
                        link.innerHTML = "";
                        address.innerHTML = "";
                        image.innerHTML = "";
                        for(var index in dataObject) {
                            if(dataObject[index].destination_name) {
                                //Populate the databox using array data returned from server
                                name.innerHTML += '<h2>' + dataObject[index].destination_name + '</h2>';
                            }
                            if(dataObject[index].destination_desc) {
                                //Populate the databox using array data returned from server
                                description.innerHTML += '<p>' + dataObject[index].destination_desc + '</p>';
                            }
                            if(dataObject[index].website) {
                                //Populate the databox using array data returned from server
                                link.innerHTML += '<a href="' + dataObject[index].website + '"> ' + dataObject[index].destination_name + ' Website</a>';
                            }
                            if(dataObject[index].zip) {
                                address.innerHTML += '<h2> Address </h2>'
                                address.innerHTML += '<p>' + dataObject[index].line_1 + ' ' + dataObject[index].line_2 + '</p>' 
                                address.innerHTML += '<p>' + dataObject[index].city + ' Minnesota ' + dataObject[index].zip + '</p>'
                            }
                            if(dataObject[index].image_url == "" || dataObject[index].image_url) {
                                image.innerHTML += '<h2> Image </h2>'
                                image.innerHTML += '<img src="' + dataObject[index].image_url + '" alt="' + dataObject[index].destination_name + ' image">'
                            }
                        }//end of for()
                    }//end of if readyState
                }// end of onreadystatechange

                //Send the request with a POST variable of 4 for the limit
                if(selected == "new") {
                    httpReq.send("id=1");
                } else {
                    httpReq.send("id=" + selected);
                }
                
                //Allow a reset time before calling the function again.
                //setTimeout('getData()',4000);
            } // end of getData( )
        </script>
</head>
<body>
    
    <header>
        <h1>Destination Selector</h1>
        <img src="graphic/minnesota.png" alt="Minnesota Image">
    </header>
    
    <main>
        <div id="dropdown">
            <select name="destinationDropdown" id="destinationDropdown">
                <option value="new">Select a destination</option>
                <?php 
                    createConnection();
                    $sql = "SELECT destination_id, destination_name FROM destination ORDER BY destination_name";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" .$row['destination_id'] . "'>" . $row['destination_name'] . "</option>\n";
                    }
                    $conn->close();
                ?>
            </select>
            <script>
                var dropdown = document.getElementById('destinationDropdown');
                dropdown.addEventListener('change', getData);
            </script>
        </div>
        
        <div id="destination_name">
            
        </div>
        <div id="descr">
            
        </div>

        <div id="link">
            
        </div>

        <div id="address">
            
        </div>

        <div id="image">
            
        </div>

    </main>

    <nav>
        <p>Navigation pane</p>
    </nav>
    
    <script>
        getData();
    </script>
    
</body>
</html>