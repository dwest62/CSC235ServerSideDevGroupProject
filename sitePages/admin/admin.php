<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Administration Page</title>
    <link rel="stylesheet" type="text/css" href="style.css">

    <!--
        admin.php - Web application consisting of forms used to add, delete, update, and view dbtravelminnesota tables.
        Student Name: James West, Dylan Johnson
        Written: 4/10/23
        Revised: 4/25/23
    -->
    <?PHP
    require_once "../../../params.php";
    require_once "../../db/component/Table.php";
    require_once "../../db/component/DestinationTable.php";
    require_once "../../db/component/Destination.php";
    require_once "../../db/component/DBHandler.php";
    require_once "../../db/component/DestinationTagTable.php";
    require_once "../../db/component/Tag.php";
    require_once "../../db/component/TagTable.php";
    require_once "../../db/component/TagTypeTable.php";
    require_once "../../db/component/TagType.php";

    // Global connection object
    $dbh = new DBHandler(SERVER, USER, PASSWORD, "dbtravelminnesota");

    // Initialize table objects
    $destinationTable = new DestinationTable();
    $destinationTagTable = new DestinationTagTable();
    $tagTable = new TagTable();

    // Update to provide feedback on form operations
    $feedback = "<p></p>";

    // Handle the various form button submissions
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
                    $feedback = $currDestination->getName() == ""
                        ? "<p class='failed'>Failed to add destination. Please enter a destination name.</p>"
                        : ($destinationTable->add($dbh, $currDestination)
                            ? "<p class='success'>Added new destination (or already exists): {$_POST['txtName']} </p>"
                            : "<p class='failed'>Failed to add new destination: {$_POST['txtName']}</p>"
                        );
                    break;
                case "update":
                    $feedback = $destinationTable->update($dbh, $currDestination)
                        ? "<p class='success'>Updated destination: {$_POST['txtName']}</p>"
                        : "<p class='failed'>Failed to update destination.</p>";
                    break;
                case "delete":
                    $feedback = $destinationTable->delete($dbh, $currDestination)
                        ? "<p class='success'>Deleted destination: {$_POST['txtName']}"
                        : "<p class='failed'>Failed to delete destination: {$_POST['txtName']}</p>";
            }
        } elseif (isset($_POST['btnSubmit']['destinationTag'])) {
            switch ($_POST['btnSubmit']['destinationTag']) {
                case "add":
                    if (isset($_POST['inactiveTags']) && count($_POST['inactiveTags']) > 0) {
                        $feedback = DestinationTagTable::addTagsToDestination($dbh, $_POST["inactiveTags"], $_POST['lstDestination'])
                            ? "<p class='success'>Added tags to destination"
                            : "<p class='failed'>Failed to add tags to destination: {$dbh->getConn()->error}</p>";
                    }
                    break;
                case "remove":
                    if (isset($_POST['activeTags']) && count($_POST['activeTags']) > 0) {
                        $feedback = DestinationTagTable::removeTagsFromDestination($dbh, $_POST["activeTags"], $_POST['lstDestination'])
                            ? "<p class='success'>Removed tags destination"
                            : "<p class='failed'>Failed to remove tags from destination: {$dbh->getConn()->error}</p>";
                    }
                    break;
            }
        } elseif (isset($_POST['btnSubmit']['tag'])) {
            switch ($_POST['btnSubmit']['tag']) {
                case "add":
                    $feedback = $_POST['lstTagTagTypeId'] == 0
                        ? "<p class='failed'>Failed to add tag. Please select a tag type.</p>"
                        : ($_POST['txtTagName'] == ""
                            ? "<p class='failed'>Failed to add tag. Please enter a tag name.</p>"
                            : (TagTable::addTag($dbh, $_POST['txtTagName'], (int)$_POST['lstTagTagTypeId'])
                                ? "<p class='success'>Added tag (or already exists)"
                                : "<p class='failed'>Failed to add tag: {$dbh->getConn()->error}</p>"));
                    break;
                case "update":
                    $feedback = TagTable::updateTag($dbh, (int)$_POST['lstTag'], $_POST['txtTagName'], (int)$_POST['lstTagTagTypeId'],)
                        ? "<p class='success'>Updated tag"
                        : "<p class='failed'>Failed to update tag: {$dbh->getConn()->error}</p>";
                    break;
                case "delete":
                    $feedback = TagTable::deleteTag($dbh, $_POST['lstTag'])
                        ? "<p class='success'>Tag deleted"
                        : "<p class='failed'>Failed to delete tag: {$dbh->getConn()->error}</p>";
                    break;
            }
        } elseif (isset($_POST['btnSubmit']['tagType'])) {
            switch ($_POST['btnSubmit']['tagType']) {
                case "add":
                    $feedback = $_POST['txtTagTypeName'] == ""
                        ? "<p class='failed'>Failed to add tag type. Please enter a tag type name.</p>"
                        : (TagTypeTable::addTagType($dbh, $_POST['txtTagTypeName'])
                            ? "<p class='success'>Tag Type added (or already exists)"
                            : "<p class='failed'>Failed to add tag type: {$dbh->getConn()->error}</p>");
                    break;
                case "update":
                    $feedback = TagTypeTable::updateTagType($dbh, (int)$_POST['lstTagType'], $_POST['txtTagTypeName'])
                        ? "<p class='success'>Tag Type added (or already exists)"
                        : "<p class='failed'>Failed to add tag type: {$dbh->getConn()->error}</p>";
                    break;
                case "delete":
                    $feedback = TagTypeTable::deleteTagType($dbh, (int)$_POST['lstTagType'])
                        ? "<p class='success'>Tag Type deleted"
                        : "<p class='failed'>Failed to delete tag type: {$dbh->getConn()->error}</p>";
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
    $tagOptions = TagTable::sortTagsByActiveAndType($tags, $currDestination->getId());

    // Set current tag to last selected or to set to default if none selected
    $currTag = (isset($_POST['lstTag']) && !$_POST['lstTag'] == '0')
        ? TagTable::getTagById($tags, $_POST['lstTag'])
        : new Tag();

    // Get all destination options
    $destinationOptions = $destinationTable->getOptions($dbh);

    // Get all tag types
    $tagTypes = TagTypeTable::getTagTypes($dbh);

    $currTagType = (isset($_POST['lstTagType']) && !$_POST['lstTagType'] == '0' && !isset($_POST['btnSubmit']['tagType']))
        ? TagTypeTable::getTagTypeById($tagTypes, $_POST['lstTagType'])
        : new TagType();

    $dbh->closeConnection();
    ?>
    <script>
        function clearTagForm() {
            document.getElementById("tag-0").selected = true;
            document.getElementById("txtTagName").value = "";
            document.getElementById("tagTagType-0").selected = true;
        }
    </script>
</head>
<body>
<main>
    <!-- Feedback for form operations -->
    <hr/>
    <?= $feedback ?>
    <hr/>
    <div id="frame">
        <div class="destAllWrapper">
            <div class="frmGroup">

                <!-- Destination form -->

                <h2>Destination Form</h2>
                <form action="<?= htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" name="destinationEdit"
                      id="destinationEdit">
                    <div class="frmSelectWrapper">
                        <div class="frmSelectLabel">
                            <!-- Allow user to select a destination from options -->
                            <label for="lstDestination">Select a Destination</label>
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
                        </div>
                        <a href="<?= htmlentities($_SERVER['PHP_SELF']) ?>">Reset</a>
                    </div>
                    <!-- Display current destination info inputs -->
                    <fieldset>
                        <legend>Selected Destination Details</legend>
                        <div class="fieldWrapper">
                            <div class="topLabel">
                                <label for="txtName">Name</label>
                                <input type="text" name="txtName" id="txtName"
                                       value="<?= $currDestination->getName() ?>"/>
                            </div>

                            <div class="topLabel">
                                <label for="txtImg">Image URL</label>
                                <input type="text" name="txtImg" id="txtImg"
                                       value="<?= $currDestination->getImageUrl() ?>"/>
                            </div>

                            <div class="topLabel">
                                <label for="txtWebsite">Website</label>
                                <input type="text" name="txtWebsite" id="txtWebsite"
                                       value="<?= $currDestination->getWebsite() ?>"/>
                            </div>

                            <div class="topLabel">
                                <label for="txtLineOne">Address Line 1</label>
                                <input type="text" name="txtLineOne" id="txtLineOne"
                                       value="<?= $currDestination->getLine1() ?>"/>
                            </div>

                            <div class="topLabel">
                                <label for="txtLineTwo">Address Line 2</label>
                                <input type="text" name="txtLineTwo" id="txtLineTwo"
                                       value="<?= $currDestination->getLine2() ?>"/>
                            </div>

                            <div class="topLabel">
                                <label for="txtCity">City</label>
                                <input type="text" name="txtCity" id="txtCity"
                                       value="<?= $currDestination->getCity() ?>"/>
                            </div>

                            <div class="topLabel">
                                <label for="txtZip">Zip Code</label>
                                <input type="text" name="txtZip" id="txtZip" value="<?= $currDestination->getZip() ?>"/>
                            </div>

                            <div class="topLabel">
                                <label for="txtDescription">Description</label>
                                <textarea name="txtDescription"
                                          id="txtDescription"
                                          onfocusin='this.style.height = this.scrollHeight + "px"'
                                          onfocusout='this.style.height = "50px"'
                                          oninput='this.style.height = ""; this.style.height = this.scrollHeight + "px"'
                                ><?= $currDestination->getDescription() ?></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="txtID" id="txtID" value="<?= $currDestination->getId() ?>">
                        <div class="btnWrapper">
                            <!-- Allow user to add a destination -->
                            <button name="btnSubmit[destination]"
                                    value="add"
                                    onclick="this.form.submit();">
                                Add new
                            </button>
                            <!-- Conditionally render update and delete buttons based on if id is selected -->
                            <?PHP if ($currDestination->getId() != 0): ?>
                                <!-- Allow user to update a destination -->
                                <button name="btnSubmit[destination]"
                                        value="update"
                                        onclick="this.form.submit();">
                                    Update
                                </button>
                                <!-- Allow user to delete a destination -->
                                <button name="btnSubmit[destination]"
                                        value="delete"
                                        onclick="this.form.submit();"
                                        class="flxShiftR">
                                    Delete
                                </button>
                            <?PHP endif; ?>
                        </div>
                    </fieldset>
                    <input type="hidden" name="lstTagType" value="<?= $currTagType->getId() ?>">
                    <input type="hidden" name="lstTag" value="<?= $currTag->getId() ?>">
                </form>
            </div>
            <div class="frmGroup">

                <!-- DestinationTag form -->

                <!-- Conditionally render DestinationTag form based on id -->
                <?PHP if ($currDestination->getId() != 0): ?>
                    <form action="<?= htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" name="destinationTagEdit"
                          id="destinationTagEdit">
                        <fieldset>
                            <legend>Selected Destination Tags</legend>
                            <!-- Display tags active for destination -->
                            <div class="destTagWrapper">
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
                                <button name="btnSubmit[destinationTag]" value="remove" onclick="this.form.submit()">
                                    Remove
                                    tag
                                </button>
                            </div>
                            <div class="destTagWrapper">
                                <!-- Display tags inactive for destination -->
                                <label for="inactiveTags[]">Inactive Tags</label>
                                <?PHP $size = array_sum(array_map("count", $tagOptions["inactive"])) + count($tagOptions["inactive"]) ?>
                                <select name="inactiveTags[]" id="inactiveTags[]" multiple="multiple"
                                        size="<?= $size ?>">
                                    <?PHP foreach ($tagOptions["inactive"] as $type => $tags): ?>
                                        <optgroup label="<?= $type ?>">
                                            <?PHP foreach ($tags as $tag): ?>
                                                <option value="<?= $tag['tag_id'] ?>"><?= $tag['tag_name'] ?></option>
                                            <?PHP endforeach; ?>
                                        </optgroup>
                                    <?PHP endforeach; ?>
                                </select>

                                <!-- Add tag button -->
                                <button name="btnSubmit[destinationTag]" value="add" onclick="this.form.submit()">Add
                                    tag
                                </button>
                            </div>
                        </fieldset>
                        <input type="hidden" name="lstDestination" id="lstDestination"
                               value="<?= $currDestination->getId() ?>">
                        <input type="hidden" name="lstTagType" value="<?= $currTagType->getId() ?>">
                    </form>
                <?PHP endif; ?>
            </div>
        </div>
        <div class="frmGroup">

            <!-- Tag Form -->

            <h2>Tag Form</h2>
            <form action="<?= htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" name="tagEdit" id="tagEdit">
                <div class="frmSelectWrapper">
                    <div class="frmSelectLabel">
                        <label for="lstTag">Select a Tag</label>
                        <select name="lstTag" id="lstTag" onChange="this.form.submit()">
                            <option id="tag-0" value="0">Select a name</option>
                            <?PHP foreach ($tagOptions['all'] as $type => $tags): ?>
                                <optgroup label="<?= $type ?>">
                                    <?PHP foreach ($tags as $tag): ?>
                                        <option value="<?= $tag['tag_id'] ?>" <?= $currTag->getId() == (int)$tag['tag_id'] ? "selected" : ''; ?>>
                                            <?= $tag['tag_name'] ?>
                                        </option>
                                    <?PHP endforeach; ?>
                                </optgroup>
                            <?PHP endforeach; ?>
                        </select>
                    </div>
                </div>
                <fieldset>
                    <legend>Selected Tag Details</legend>
                    <div class="fieldWrapper">
                        <div class="topLabel">
                            <label for="txtTagName">Name</label>
                            <input type="text" name="txtTagName" id="txtTagName"
                                   value="<?= $currTag->getName() ?>">
                        </div>

                        <!-- Tag types -->
                        <div class=frmSelectLabel>
                            <label for="lstTagTagTypeId">Select Type</label>
                            <select name="lstTagTagTypeId" id="lstTagTagTypeId">
                                <option id="tagTagType-0" value="0">Select a tag type</option>
                                <?PHP foreach ($tagTypes as $type): ?>
                                    <option value="<?= $type['tag_type_id'] ?>"
                                        <?= $currTag->getType() == $type['tag_type_id'] ? "selected" : "" ?>>
                                        <?= $type['tag_type_name'] ?>
                                    </option>
                                <?PHP endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="btnWrapper">
                        <button name="btnSubmit[tag]"
                                value="add"
                                onclick="this.form.submit();">
                            Add new
                        </button>
                        <?PHP if ($currTag->getId() != 0): ?>
                        <button name="btnSubmit[tag]"
                                value="update"
                                onclick="this.form.submit();">
                            Update
                        </button>
                        <button name="btnSubmit[tag]"
                                value="delete"
                                onclick="this.form.submit();"
                                class="flxShiftR">
                            Delete
                        </button>
                    </div>
                    <?PHP endif; ?>
                </fieldset>
                <input type="hidden" name="lstDestination" id="lstDestination" value="<?= $currDestination->getId() ?>">
            </form>
        </div>
        <div class="frmGroup">
            <h2 id="headerTagType">Tag Type Form</h2>

            <!-- Tag Type Form -->

            <form action="<?= htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" name="tagTypeEdit" id="tagTypeEdit">
                <div class="frmSelectLabel">
                    <label for="lstTagType">Selected Tag Type Details</label>
                    <select name="lstTagType" id="lstTagType" onchange="this.form.submit()">
                        <option id="tagType-0" value="0">Select a tag type</option>
                        <?PHP foreach ($tagTypes as $type): ?>
                            <option value="<?= $type['tag_type_id'] ?>"
                                <?= $currTagType->getId() == $type['tag_type_id'] ? "selected" : "" ?>>
                                <?= $type['tag_type_name'] ?>
                            </option>
                        <?PHP endforeach; ?>
                    </select>
                </div>
                <fieldset>
                    <legend>Selected Tag Type</legend>
                    <div class="topLabel">
                        <label for="txtTagTypeName">Name</label>
                        <input type="text" name="txtTagTypeName" id="txtTagTypeName"
                               value="<?= $currTagType->getName() ?>">
                    </div>
                    <div class="btnWrapper">
                        <button name="btnSubmit[tagType]"
                                value="add"
                                onclick="this.form.submit();">
                            Add new
                        </button>
                        <?PHP if ($currTagType->getId() != 0): ?>
                            <button name="btnSubmit[tagType]"
                                    value="update"
                                    onclick="this.form.submit();">
                                Update
                            </button>
                            <button name="btnSubmit[tagType]"
                                    value="delete"
                                    onclick="this.form.submit();"
                                    class="flxShiftR">
                                Delete
                            </button>
                        <?PHP endif; ?>
                    </div>
                    <input type="hidden" name="lstDestination" value="<?= $currDestination->getId() ?>">
                    <input type="hidden" name="lstTag" value="<?= $currTag->getId() ?>">
                </fieldset>
            </form>
        </div>
    </div>
</main>
</body>
</html>
