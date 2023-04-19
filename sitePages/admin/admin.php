<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Administration Page</title>
    <link rel="stylesheet" type="text/css" href="style.css">
<?php
    require_once "../../../params.php";
    require_once "../../db/component/Table.php";
    require_once "../../db/component/DestinationTable.php";
    require_once "../../db/component/Destination.php";
    require_once "../../db/component/DBHandler.php";
    require_once "../../db/component/DestinationTagTable.php";
    require_once "../../db/component/Tag.php";
    require_once "../../db/component/TagTable.php";

    // Global connection object
    $dbh = new DBHandler(SERVER, USER, PASSWORD, DB_NAME);

    $destinationTable = new DestinationTable();
    $destinationTagTable = new DestinationTagTable();
    $tagTable = new TagTable();


    $feedback = "";
    if (isset($_POST['btnSubmit'])) {
        $submit = $_POST['btnSubmit'];
        if (isset($submit['destination'])) {
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
            switch ($submit['destination']) {
                case "add":
                    $feedback = $destinationTable->add($dbh, $currDestination)
                        ? "<p class='success'>Successfully added new destination: {$_POST['txtName']} </p>"
                        : "<p class='failed'>{$destinationTable->getErrMsg($dbh->getConn(), $currDestination->getName())}</p>";
                    break;
                case "update":
                    $feedback = $destinationTable->update($dbh, $currDestination)
                        ? "<p class='success'>Successfully updated destination: {$_POST['txtName']}</p>"
                        : "<p class='failed'>{$destinationTable->getErrMsg($dbh->getConn(), $currDestination->getName())}</p>";
                    break;
                case "delete":
                    $feedback = $destinationTable->delete($dbh, $currDestination)
                        ? "<p class='success'>Successfully deleted destination: {$_POST['txtName']}"
                        : "<p class='failed'>{$destinationTable->getErrMsg($dbh->getConn(), $currDestination->getName())}</p>";
            }
        } elseif (isset($submit['tag'])) {
            switch ($submit['tag']) {
                case "add":
                    //TODO; implement add destination_tag entry
                    break;
                case "remove":
                    //TODO implement remove destination_tag entry
                    break;
            }
        }


    }
    $currDestination = (isset($_POST['lstDestination']) && !$_POST['lstDestination'] == '0')
        ? $destinationTable->getById($dbh, (int)$_POST['lstDestination'])
        : new Destination();

    $options = $destinationTable->getOptions($dbh);

    $activeTagOptions = $destinationTagTable->getDestinationTagsJoinTagType($dbh, $currDestination);
    $inactiveTagOptions = $destinationTagTable->getNotDestinationTagsJoinTagType($dbh, $currDestination);

    $dbh->closeConnection();
    ?>
</head>

<!--
    admin.php - web interface for database
    Student Name: Dylan Johnson, James West
    Written:  4/10/23
    Revised:  4/11/23
-->

<body>
<main>
    <?= $feedback ?>
    <div id="frame">
        <form action="<?= htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" name="tblEdit" id="tblEdit">
            <label for="lstDestination"><strong>Destination</strong></label>
            <select name="lstDestination" id="lstDestination" onChange="this.form.submit()">
                <option id="destination-0" value="0">Select a name</option>
                <?PHP
                foreach ($options as $option) {
                    $selected = $currDestination->getId() == (int)$option['destination_id'] ? 'selected="true"' : '';
                    echo <<<EOF
                            <option value="{$option['destination_id']}" $selected >
                                {$option['destination_name']}
                            </option>
                        EOF;
                }
                ?>
            </select>
            <a href="<?= htmlentities($_SERVER['PHP_SELF']) ?>"
               onclick="document.getElementById('destination-0').selected = true;">
                New Record
            </a>
            <fieldset>
                <legend>Destination Information</legend>
                <div class="topLabel">
                    <label for="txtName">Name</label>
                    <input type="text" name="txtName" id="txtName" value="<?= $currDestination->getName() ?>"/>
                </div>

                <div class="topLabel">
                    <label for="txtImg">Image URL</label>
                    <input type="text" name="txtImg" id="txtImg" value="<?= $currDestination->getImageUrl() ?>"/>
                </div>

                <div class="topLabel">
                    <label for="txtWebsite">Website</label>
                    <input type="text" name="txtWebsite" id="txtWebsite" value="<?= $currDestination->getWebsite() ?>"/>
                </div>

                <div class="topLabel">
                    <label for="txtLineOne">Address Line 1</label>
                    <input type="text" name="txtLineOne" id="txtLineOne" value="<?= $currDestination->getLine1() ?>"/>
                </div>

                <div class="topLabel">
                    <label for="txtLineTwo">Address Line 2</label>
                    <input type="text" name="txtLineTwo" id="txtLineTwo" value="<?= $currDestination->getLine2() ?>"/>
                </div>

                <div class="topLabel">
                    <label for="txtCity">City</label>
                    <input type="text" name="txtCity" id="txtCity" value="<?= $currDestination->getCity() ?>"/>
                </div>

                <div class="topLabel">
                    <label for="txtZip">Zip Code</label>
                    <input type="text" name="txtZip" id="txtZip" value="<?= $currDestination->getZip() ?>"/>
                </div>

                <div class="topLabel">
                    <label for="txtDescription">Description</label>
                    <input type="text" name="txtDescription" id="txtDescription"
                           value="<?= $currDestination->getDescription() ?>" size="<?= $currDestination->getLen() ?>"
                           maxlength="5000"/>
                </div>
                <input type="hidden" name="txtID" id="txtID" value="<?= $currDestination->getId() ?>">
            </fieldset>
            <button name="btnSubmit[destination]"
                    value="delete"
                    onclick="this.form.submit();">
                Delete Record
            </button>

            <button name="btnSubmit[destination]"
                    value="add"
                    onclick="this.form.submit();">
                Add New Destination
            </button>

            <button name="btnSubmit[destination]"
                    value="update"
                    onclick="this.form.submit();">
                Update
            </button>
            <br/><br/>
            <?PHP if ($currDestination->getId() != 0): ?>
                <fieldset>
                    <legend><?= $currDestination->getName() ?> Tags</legend>
                    <label for="selActiveTag[]"><strong>Active Tags </strong></label>
                    <select name="selActiveTag[]"
                            id="selActiveTag[]"
                            size="<?= array_sum(array_map("count", $activeTagOptions)) + count($activeTagOptions) ?>"
                            multiple="multiple">
                        <?PHP foreach ($activeTagOptions as $tagType => $tags): ?>
                            <optgroup label="<?= $tagType ?>">
                                <?PHP foreach ($tags as $tag): ?>
                                    <option value="<?= $tag['tag_id'] ?>"><?= $tag['tag_name'] ?></option>
                                <?PHP endforeach; ?>
                            </optgroup>
                        <?PHP endforeach; ?>
                    </select>
                    <button name="frmTag[btnSubmit]"
                            value="remove"
                            onclick="this.form.submit();">
                        Remove
                    </button>
                    <label for="selTag[]"><strong>Inactive Tags</strong></label>
                    <select name="selTag[]"
                            id="selTag[]"
                            size="<?= array_sum(array_map("count", $inactiveTagOptions)) + count($inactiveTagOptions) ?>"
                            multiple="multiple">
                        <?PHP foreach ($inactiveTagOptions as $tagType => $tags): ?>
                            <optgroup label="<?= $tagType ?>">
                                <?PHP foreach ($tags as $tag): ?>
                                    <option value="<?= $tag['tag_id'] ?>"><?= $tag['tag_name'] ?></option>
                                <?PHP endforeach; ?>
                            </optgroup>
                        <?PHP endforeach; ?>
                    </select>
                    <button name="btnSubmit[Tag]"
                            value="add"
                            onclick="this.form.submit();">
                        Add
                    </button>
                </fieldset>
            <?PHP endif; ?>
        </form>
        <!--        TODO add, update, remove, tag form -->
        <!--        TODO add, update, remove tag_type form -->
        <!--        TODO css -->
    </div>
</main>
</body>
