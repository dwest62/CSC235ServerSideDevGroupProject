<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Administration Page</title>
    <link rel="stylesheet" type="text/css" href="style.css">

    <?PHP
    // Global connection object
    $dbh = new DBHandler(SERVER, USER, PASSWORD, DB_NAME);
    $conn = $dbh->getConn();

    $destinationTable = new DestinationTable();
    $destinationTagTable = new DestinationTagTable();
    $tagTable = new TagTable();

    $currDestination = (isset($_POST['lstDestination']) && !$_POST['lstDestination'] == '0')
        ? $destinationTable->getById($conn, (int) $_POST['lstDestination'])
        : new Destination();

    $feedback = "";
    if (isset($_POST['frmDestination']['btnSubmit'])) {
        $currDestination = new Destination(
            (int)$_POST['txtID'],
            $_POST['txtName'],
            $_POST['txtDescription'],
            $_POST['txtZip'],
            $_POST['txtLineOne'],
            $_POST['txtLineTwo'],
            $_POST['txtCity'],
            $_POST['txtImg'],
            $_POST['txtWebsite']
        );
        switch ($_POST['frmDestination']['btnSubmit']) {
            case "add":
                $feedback = $destinationTable->add($conn, $currDestination)
                    ? "<p class='success'>Successfully added new destination: {$_POST['txtName']} </p>"
                    : "<p class='failed'>{$destinationTable->getErrMsg($conn, $currDestination->getName())}</p>";
                break;
            case "update":
                $feedback = $destinationTable->update($conn, $currDestination)
                    ? "<p class='success'>Successfully updated destination: {$_POST['txtName']}</p>"
                    : "<p class='failed'>{$destinationTable->getErrMsg($conn, $currDestination->getName())}</p>";
                break;
            case "delete":
                $feedback = $destinationTable->delete($conn, $currDestination)
                    ? "<p class='success'>Successfully deleted destination: {$_POST['txtName']}"
                    : "<p class='failed'>{$destinationTable->getErrMsg($conn, $currDestination->getName())}</p>";
        }
    }


    $currTag = (isset($_POST['lstTag']) && !$_POST['lstTag'] == '0')
        ? $tagTable->getByID($conn, (int) $_POST['lstTag'])
        : new Tag();

    $options = $destinationTable->getOptions($conn);

    $tagOptions = $destinationTagTable->getDestinationTags($conn, $currDestination);


    print_r($conn->error_list);
    $conn->close();
    ?>
</head>
<body>
<main>
    <?=$feedback?>
    <div id="frame">
        <form action="<?= htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" name="tblEdit" id="tblEdit">
            <label for="lstDestination"><strong>Select Destination</strong></label>
            <select name="lstDestination" id="lstDestination" onChange="this.form.submit()">
                <option id="destination-0" value="0" >Select a name</option>
                <?PHP
                foreach ($options as $option)
                {
                    $selected = $currDestination->getId() == (int) $option['destination_id'] ? 'selected="true"' : '';
                    echo <<<EOF
                            <option value="{$option['destination_id']}" $selected >
                                {$option['destination_name']}
                            </option>
                        EOF;
                }
                ?>
            </select>
            <a href="<?=htmlentities($_SERVER['PHP_SELF'])?>" onclick="document.getElementById('destination-0').selected = true;">
                New Record
            </a>
            <fieldset>
                <legend>Destination Information</legend>
                <div class="topLabel">
                    <label for="txtName">Name</label>
                    <input type="text" name="txtName" id="txtName" value="<?= $currDestination->getName() ?>" />
                </div>

                <div class="topLabel">
                    <label for="txtImg">Image URL</label>
                    <input type="text" name="txtImg"   id="txtImg" value="<?= $currDestination->getImageUrl() ?>" />
                </div>

                <div class="topLabel">
                    <label for="txtWebsite">Website</label>
                    <input type="text" name="txtWebsite"   id="txtWebsite" value="<?= $currDestination->getWebsite() ?>" />
                </div>

                <div class="topLabel">
                    <label for="txtLineOne">Address Line 1</label>
                    <input type="text" name="txtLineOne"   id="txtLineOne" value="<?= $currDestination->getLine1() ?>" />
                </div>

                <div class="topLabel">
                    <label for="txtLineTwo">Address Line 2</label>
                    <input type="text" name="txtLineTwo"   id="txtLineTwo" value="<?= $currDestination->getLine2() ?>" />
                </div>

                <div class="topLabel">
                    <label for="txtCity">City</label>
                    <input type="text" name="txtCity"   id="txtCity" value="<?= $currDestination->getCity() ?>" />
                </div>

                <div class="topLabel">
                    <label for="txtZip">Zip Code</label>
                    <input type="text" name="txtZip"   id="txtZip" value="<?= $currDestination->getZip() ?>" />
                </div>

                <div class="topLabel">
                    <label for="txtDescription">Description</label>
                    <input type="text" name="txtDescription" id="txtDescription"
                           value="<?= $currDestination->getDescription()?>" size="<?= $currDestination->getLen()?>"
                           maxlength="5000" />
                </div>
                <input type="hidden" name="txtID" id="txtID" value="<?= $currDestination->getId()?>">
            </fieldset>
            <button name="frmDestination[btnSubmit]"
                    value="delete"
                    style="float:left;"
                    onclick="this.form.submit();">
                Delete Record
            </button>

            <button name="frmDestination[btnSubmit]"
                    value="add"
                    style="float:right;"
                    onclick="this.form.submit();">
                Add New Destination
            </button>

            <button name="frmDestination[btnSubmit]"
                    value="update"
                    style="float:right;"
                    onclick="this.form.submit();">
                Update
            </button>
            <select name="lstTag" id="lstTag" onChange="this.form.submit()">
                <option id="tag-0" value="0" >Select a name</option>
                <?PHP
                foreach ($tagOptions as $option)
                {
                    $selected = $currTag->getId() == (int) $option['tag_id'] ? 'selected="true"' : '';
                    echo <<<EOF
                            <option value="{$option['tag_id']}" $selected>
                                {$option['tag_name']}
                            </option>
                        EOF;
                }
                ?>
            </select>
            <fieldset>
                <legend>Destination Tags</legend>
                <div class="topLabel">
                    <label for="txtName">Name</label>
                    <input type="text" name="txtName" id="txtName" value="<?= $currDestination->getName() ?>" />
                </div>

            </fieldset>
        </form>
    </div>
</main>
</body>
</html>