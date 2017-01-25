<?PHP
session_start();
?>

<head>
    <title>Edit Class</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
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
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <form id="editclassform" action=''>
                <?PHP
                require_once 'includes/database_functions.php';
                require_once "includes/pollform_generator.php";
                $class_id = $_SESSION['classid'];
                $studentlist = [];
                $sqlclass = "SELECT * FROM class WHERE class_id = $class_id";
                $classRows = pdoSelect($sqlclass);
                extract($classRows[0]);
                $classnameform = textField("Class Name:", "classnameedit", $class_name, $class_name);
                echo $classnameform;
                echo "<input type='hidden' name='classidedit' id=$class_id required><br>";

                $sqlstudent = "SELECT * FROM student";
                $studentRows = pdoSelect($sqlstudent);
                $classStudents = pdoSelect("SELECT student_id 
                                                     FROM student_class
                                                     WHERE class_id = $class_id");
                foreach($classStudents as $classStudent){
                    array_push($studentlist, join(',', $classStudent));
                }

                foreach($studentRows as $studentRow){
                    extract($studentRow);
                    $student_name = htmlspecialchars($student_name);
                    if (in_array(($student_id), $studentlist)){
                    echo <<<STU
                            <div class="col-sm-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="$student_id" value="$student_id" checked>
                                        <strong>$student_name</strong>
                                    </label>
                                </div>
                            </div>
STU;
                    }
                    else{
                    echo <<<STD
                        <div class="col-sm-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="$student_id" value="$student_id">
                                    <strong>$student_name</strong>
                                </label>
                            </div>
                        </div>
STD;
                    }
                }
                ?>
                <button class='btn btn-primary btn-block' form="editclassform" type="submit">Submit</button>
            </form>
        </div>
    </div>
</div>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript" src="edit_class_handler.js"></script>
</body>