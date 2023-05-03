<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Show Data</title>
    <!--
            showJSONData.php - displays json data
            Student Name: Dylan Johnson, James West
            Written: 4/30/2023
            Revised: 5/1/2023
    -->
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" type="image/x-icon" href="/groupProject/graphic/favicon.png">
    <style>
        #databox {
            padding: 12px;
            background: #F3F3F3;
            border: #CCC 1px solid;
            width: 50%;
            height: 100%;
        }
    </style>

    <script>
        function getData() {
            var counter = 0;
            var databox = document.getElementById("databox");
            var httpReq = new XMLHttpRequest();

            // Add AJAX call
            // Request the API script using POST, calling the PHP script
            httpReq.open("POST", "../json/getJSONData.php", true);
            httpReq.setRequestHeader("content-type", "application/x-www-form-urlencoded");
            httpReq.onreadystatechange = function () {
                if (httpReq.readyState == 4 && httpReq.status == 200) {
                    var dataObject = JSON.parse(httpReq.responseText);
                    //clear the data each time around
                    databox.innerHTML = "";
                    for (var index in dataObject) {
                        if (dataObject[index].destination_name) {
                            //Populate the databox using array data returned from server
                            databox.innerHTML += '<h2>' + dataObject[index].destination_name + '</h2>';
                        }
                        if (dataObject[index].destination_desc) {
                            //Populate the databox using array data returned from server
                            databox.innerHTML += '<p>' + dataObject[index].destination_desc + '</p>';
                        }
                    }//end of for()
                }//end of if readyState
            }// end of onreadystatechange

            //Send the request with a POST variable of 4 for the limit
            httpReq.send("limit=10");

            databox.innerHTML = "requesting...  Counter is: " + counter++;
            // Twiddle the CPU's thumbs for 4 seconds
            // Then, call the function.
            setTimeout('getData()', 10000);
        } // end of getData( )
    </script>
</head>
<body>

<header>
    <img id="logo" src="/groupProject/graphic/logo.png"/>
    <nav>
        <a href="/groupProject/index.php">Home</a>
        <div class="divider"></div>
        <a href="/groupProject/sitePages/readMe.php">Read Me</a>
        <div class="divider"></div>
        <a href="/groupProject/sitePages/admin.php">Admin</a>
        <div class="divider"></div>
        <a href="/groupProject/sitePages/showJSONData.php">Show JSON data</a>
    </nav>
</header>
<h2>Team MN Destinations</h2>
<p>By: James West & Dylan Johnson</p>
<h1>Destinations</h1>
<div id="databox"></div>
<script>
    getData();
</script>


</body>
</html>