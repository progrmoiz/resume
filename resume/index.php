<?php
include_once 'db.php';
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Resume</a>
    </nav>
    <div class="jumbotron">
        <div class="container">
        
            <h1 class="my-4 display-4">Welcome</h1>
            <h3 class="my-4 lead">Upload your resume.</h3>
            <div class="row">
            
                <div class="col-6">
                
                    <form action="upload.php" method="post" enctype="multipart/form-data">
                        <div class="custom-file my-2">
                            <input type="file" class="custom-file-input" id="resume" name="resume" accept=".pdf">
                            <label class="custom-file-label" for="resume">Choose file</label>
                        </div>
                        <input class="btn btn-dark" type="submit" value="Submit" name="submit">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <table class="table table-striped mt-5">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Id</th>
                    <th scope="col">User Id</th>
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
        echo "<td>$row->user_id</td>";
        echo "<td><a href='user_files/$row->resume'>$row->resume</a></td>";
        echo "</tr>";
    }
}
?>
            </tbody>
        </table>
    </div>

    <script src="js/main.js">
    </script>
</body>

</html>