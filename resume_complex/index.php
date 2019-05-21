<?php
include_once 'db.php';

?>

<!DOCTYPE html>
<html>

<head>
    <!-- <link href="css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="css/semantic.min.css" rel="stylesheet">
    <link href="css/filepond.min.css" rel="stylesheet">
    <link href="css/style.css?random=<?php echo uniqid(); ?>" rel="stylesheet">
    <style>
        html,
        body {
            height: 100%;
        }
    </style>
</head>

<body>
    <div class="ui attached top menu">
        <div class="ui container">
            <div class="header item">
                Resume
            </div>
            <a class="item">About Us</a>
        </div>
    </div>
    <div class="ui main container">
        <div class="ui grid centered">
        <div class="six wide column">
        <div class="ui placeholder segment">
            <div class="ui icon header">
                <i class="pdf file outline icon"></i>
                Upload your resume.
            </div>
            <form action="api/submit.php" method="post" enctype="multipart/form-data">
                    <input type="file" id="resume" name="resume[]" multiple>
                    <!-- <div class="custom-file my-2">
                        <input type="file" class="custom-file-input" id="resume" name="resume" accept=".pdf">
                        <label class="custom-file-label" for="resume">Choose file</label>
                    </div> -->
                    <input class="ui primary button" disabled id="submit" type="submit" value="Add Document" name="submit">
            </form>
        </div>
        </div>
        </div>
    </div>
    <div class="ui container">
        <div class="ui grid centered">
            <div class="twelve wide column">
            <table class="ui selectable celled table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Id</th>
                        <th scope="col">Resume</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
$sql = 'SELECT * FROM userFile';
$handle = $db->prepare($sql);
$handle->execute();

$result = $handle->fetchAll(\PDO::FETCH_OBJ);

if (!empty($result)) {
    foreach ($result as $_k => $row) {
        $_k++;
        echo "<tr>";
        echo "<td>$_k</td>";
        echo "<td>$row->id</td>";
        echo "<td><a href='uploads/$row->resume'>$row->name</a></td>";
        echo "</tr>";
    }
}
?>
                </tbody>
            </table>
            </div>
        </div>
    </div>

    <script src="js/filepond-plugin-file-rename.js">
    </script>
    <script src="js/filepond-plugin-file-validate-type.min.js">
    </script>
    <script src="js/filepond-plugin-file-validate-size.min.js"></script>
    <script src="js/filepond.min.js">
    </script>
    <script src="js/main.js?random=<?php echo uniqid(); ?>">
    </script>
</body>

</html>