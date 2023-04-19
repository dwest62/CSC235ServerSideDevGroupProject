<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Administration Page</title>
    <link rel="stylesheet" type="text/css" href="style.css">

    <?PHP
    print_r($_POST);
    // Global connection object
    $dbh = new DBHandler(SERVER, USER, PASSWORD, DB_NAME);

    // Initialize table objects
    $destinationTable = new DestinationTable();
    $destinationTagTable = new DestinationTagTable();
    $tagTable = new TagTable();

    // Update to provide feedback on form operations
    $feedback = "";

    // Handle form button submissions
    if (isset($_POST['btnSubmit'])) {
        if (isset($_POST['btnSubmit']['destination'])) {
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
            switch ($_POST['btnSubmit']['destination']) {
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
        } elseif (isset($_POST['btnSubmit']['destinationTag'])) {
            switch ($_POST['btnSubmit']['destinationTag']) {
                case "add":
                    if (isset($_POST['inactiveTags']) && count($_POST['inactiveTags']) > 0) {
                        $feedback = DestinationTagTable::addTagsToDestination($dbh, $_POST["inactiveTags"], $_POST['lstDestination'])
                            ? "<p class='success'>Successfully added tags"
                            : "<p class='failed'>Failed to add tags: {$dbh->getConn()->error}</p>";;
                    }
                    break;
                case "remove":
                    if (isset($_POST['activeTags']) && count($_POST['activeTags']) > 0) {
                        $feedback = DestinationTagTable::removeTagsFromDestination($dbh, $_POST["activeTags"], $_POST['lstDestination'])
                            ? "<p class='success'>Successfully added tags"
                            : "<p class='failed'>Failed to add tags: {$dbh->getConn()->error}</p>";
                    }
                    break;
            }
        } elseif (isset($_POST['btnSubmit']['tag']))
        {
            switch($_POST['btnSubmit']['tag']) {
                case "add":
                    TagTable::addTag($dbh, $_POST['txtTagName'], (int) $_POST['txtTagId'], (int) $_POST['lstTagType']);
                    break;
                case "delete":
                    break;
                case "update":
                    break;
            }
        }

    }

    // Set current destination to last selected or set to default if none selected
    $currDestination = (isset($_POST['lstDestination']) && !$_POST['lstDestination'] == '0')
        ? $destinationTable->getById($dbh, (int)$_POST['lstDestination'])
        : new Destination();

    // Get all tags
    $tags = TagTable::getAllTagsJoinTagTypeName($dbh);

    // Get all tags grouped by tag type and by whether active on current destination
    $tagOptions = TagTable::getTagsGroupByActiveGroupByTagType($tags, $currDestination->getId());

    // Set current tag to last selected or to set to default if none selected
    $currTag = (isset($_POST['lstTag']) && !$_POST['lstTag'] == '0')
        ? TagTable::getTagById($tags, $_POST['lstTag'])
        : new Tag();

    // Get all destination options
    $destinationOptions = $destinationTable->getOptions($dbh);

    // Get all tag types
    $tagTypes = TagTypeTable::getTagTypes($dbh);

    $currTagType = (isset($_POST['lstTagType']))
        ? TagTypeTable::getTagTypeById($tagTypes, $_POST['lstTagType'])
        : new TagType();

    $dbh->closeConnection();
    ?>
</head>
<body>
<main>
    <?= $feedback ?>

    <div id="frame">
        <!-- Destination form -->
        <form action="<?= htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" name="destinationEdit"
              id="destinationEdit">
            <!-- Allow user to select a destination from options -->
            <label for="lstDestination"><strong>Destination</strong></label>
            <select name="lstDestination" id="lstDestination" onChange="this.form.submit()">
                <!-- Default option -->
                <option id="destination-0" value="0">Select a name</option>
                <!-- Loop through destination options and populate select options -->
                <?PHP foreach ($destinationOptions as $option): ?>
                    <!-- Set option value to its destination id and select option if matches current destination -->
                    <option value="<?= $option['destination_id'] ?>"
                        <?= $currDestination->getId() == (int)$option['destination_id'] ? "selected" : ''; ?>>
                        <?= $option['destination_name'] ?>
                    </option>
                <?PHP endforeach; ?>
            </select>
            <!-- Clear current destination -->
            <a href="<?= htmlentities($_SERVER['PHP_SELF']) ?>"
               onclick="document.getElementById('destination-0').selected = true;">
                New Record
            </a>
            <!-- Display current destination info inputs -->
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

                <!-- Allow user to delete a destination -->
                <button name="btnSubmit[destination]"
                        value="delete"
                        onclick="this.form.submit();">
                    Delete Record
                </button>

                <!-- Allow user to add a destination -->
                <button name="btnSubmit[destination]"
                        value="add"
                        onclick="this.form.submit();">
                    Add New Destination
                </button>

                <!-- Allow user to update a destination -->
                <button name="btnSubmit[destination]"
                        value="update"
                        onclick="this.form.submit();">
                    Update
                </button>
            </fieldset>
        </form>
        <?PHP if ($currDestination->getId() != 0): ?>


            <!-- DestinationTag form -->
            <form action="<?= htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" name="destinationTagEdit"
                  id="destinationTagEdit">
                <fieldset>
                    <legend>DestinationTag form</legend>
                    <!-- Display tags active for destination -->
                    <label for="activeTags[]">Active Tags</label>
                    <?PHP $size = array_sum(array_map("count", $tagOptions["active"])) + count($tagOptions["active"]) ?>
                    <select name="activeTags[]" id="activeTags[]" multiple="multiple" size="<?= $size ?>">
                        <?PHP foreach ($tagOptions["active"] as $type => $tags): ?>
                            <optgroup label="<?= $type ?>">
                                <?PHP foreach ($tags as $tag): ?>
                                    <option value="<?= $tag['tag_id'] ?>"><?= $tag['tag_name'] ?></option>
                                <?PHP endforeach; ?>
                            </optgroup>
                        <?PHP endforeach; ?>
                    </select>

                    <!-- Remove tag button -->
                    <button name="btnSubmit[destinationTag]" value="remove" onclick="this.form.submit()">Remove tag
                    </button>

                    <!-- Display tags inactive for destination -->
                    <label for="inactiveTags[]">Inactive Tags</label>
                    <?PHP $size = array_sum(array_map("count", $tagOptions["inactive"])) + count($tagOptions["inactive"]) ?>
                    <select name="inactiveTags[]" id="inactiveTags[]" multiple="multiple" size="<?= $size ?>">
                        <?PHP foreach ($tagOptions["inactive"] as $type => $tags): ?>
                            <optgroup label="<?= $type ?>">
                                <?PHP foreach ($tags as $tag): ?>
                                    <option value="<?= $tag['tag_id'] ?>"><?= $tag['tag_name'] ?></option>
                                <?PHP endforeach; ?>
                            </optgroup>
                        <?PHP endforeach; ?>
                    </select>

                    <!-- Add tag button -->
                    <button name="btnSubmit[destinationTag]" value="add" onclick="this.form.submit()">Add tag</button>
                </fieldset>
                <input type="hidden" name="lstDestination" id="lstDestination" value="<?= $currDestination->getId() ?>">

            </form>
        <?PHP endif; ?>

        <!-- Tag Form -->
        <form action="<?= htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" name="tagEdit" id="tagEdit">
            <label for="lstTag"><strong>Tag</strong></label>
            <select name="lstTag" id="lstTag" onChange="this.form.submit()">
                <option id="tag-0" value="0">Select a tag</option>
                <?PHP foreach($tagOptions['all'] as $type => $tags): ?>
                <optgroup label="<?=$type?>">
                    <?PHP foreach($tags as $tag): ?>
                    <option value="<?=$tag['tag_id']?>" <?= $currTag->getId() == (int) $tag['tag_id'] ? "selected" : ''; ?>>
                        <?=$tag['tag_name']?>
                    </option>
                    <?PHP endforeach;?>
                </optgroup>
                <?PHP endforeach; ?>
            </select>
            <a href="<?= htmlentities($_SERVER['PHP_SELF']) ?>"
               onclick="document.getElementById('tag-0').selected = true;">
                New Record
            </a>
            <fieldset>
                <legend>Tag information</legend>
                <div class="topLabel">
                    <label for="txtTagName">Name</label>
                    <input type="text" name="txtTagName" id="txtTagName"
                           value="<?= $currTag->getName() ?>" ?>
                </div>

                <!-- Tag types -->
                <label for="lstTagType">Select Type</label>
                <select name="lstTagType" id="lstTagType" onChange="this.form.submit()">
                    <?PHP foreach($tagTypes as $type): ?>
                        <option value="<?=$type['tag_type_id']?>"
                                <?=$currTagType->getId() == $type['tag_type_id'] ? "selected" : ""?>>
                            <?=$type['tag_type_name']?>
                        </option>
                    <?PHP endforeach; ?>
                </select>
                <input type="hidden" name="txtTagId" id="txtTagId" value="<?= $currTag->getId() ?>">
                <button name="btnSubmit[tag]"
                        value="delete"
                        onclick="this.form.submit();">
                    Delete Tag
                </button>
                <button name="btnSubmit[tag]"
                        value="delete"
                        onclick="this.form.submit();">
                    Add new Tag
                </button>
                <button name="btnSubmit[tag]"
                        value="delete"
                        onclick="this.form.submit();">
                    Update Tag
                </button>
            </fieldset>
            <input type="hidden" name="lstDestination" id="lstDestination" value="<?= $currDestination->getId() ?>">
            <input type="hidden" name="lstTag" id="lstTag" value="<?=$currTag->getId()?>">
        </form>
    </div>
</main>
</body>
</html>