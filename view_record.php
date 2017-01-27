<?php
    session_start();
?>

<head>
    <title>Student Record</title>
    <meta name="author" content="Sean Davis">
    <meta name="description" content="Gradebook and grade tracker">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
          integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="gradebook_theme.css">
</head>

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="index.php"><span class="navbar-brand">Gradebook</span></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="index.php">View Classes</a></li>
                <li><a href="view_students.php">View Students</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<body>
<?php
/**
 * Created by PhpStorm.
 * User: Sean Davis
 * Date: 1/23/2017
 * Time: 2:28 PM
 */
$studentid = $_SESSION['studentid'];
require_once 'includes/database_functions.php';
$sql = "SELECT * FROM student WHERE student_id = $studentid";
$studentRow = pdoSelect($sql);


extract($studentRow[0]);
$student_name = htmlspecialchars($student_name);
?>
<h1 id="studentrecname"><?= $student_name ?></h1>
<div class="row" id="classoptions">
    <div class="col-md-6 col-md-offset-3">
        <?PHP
        $student_classes = array();
        $sqlclass = "SELECT * FROM class";
        $sqlstudentclass = "SELECT * FROM student_class WHERE student_id = $studentid";
        $classRows = pdoSelect($sqlclass);
        $matchedRows = pdoSelect($sqlstudentclass);
        $rowCount = 0;
        foreach($matchedRows as $matchedRow){
            extract($matchedRow);
            array_push($student_classes, $class_id);
        };
        print_r($student_classes);
        ?>
        <select class='form-control'>
            <?PHP
            foreach ($classRows as $classRow) {
                extract($classRow);
                if(in_array($class_id, $student_classes)){
                    $class_name = htmlspecialchars($class_name);
                    echo "<option value='$class_id'>$class_name</option>";
                };
            }
            ?>
        </select>
    </div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
</body>