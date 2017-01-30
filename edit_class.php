<?PHP
session_start();
?>

<head>
    <title>Manage Class</title>
    <!-- Latest compiled and minified CSS -->
    <meta name="author" content="Sean Davis">
    <meta name="description" content="Gradebook and grade tracker">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
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
<div class="container">
    <form id="editclassform" action=''>
    <div id="messagebox">

    </div>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
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
                echo "<input type='hidden' name='classidedit' id='classidedit' value=$class_id required><br>";

                $sqlstudent = "SELECT * FROM student";
                $studentRows = pdoSelect($sqlstudent);
                $classStudents = pdoSelect("SELECT student_id 
                                                     FROM student_class
                                                     WHERE class_id = $class_id");
                foreach ($classStudents as $classStudent) {
                    array_push($studentlist, join(',', $classStudent));
                }
                ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-md-offset-1">
            <h4 class="center">Student List</h4>
            <hr>
            <?php
            foreach ($studentRows as $studentRow) {
                extract($studentRow);
                $student_name = htmlspecialchars($student_name);
                if (in_array(($student_id), $studentlist)) {
                    echo <<<STU
                            <div class="col-sm-12">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" class="editclasscheck" id="$student_id" value="$student_id" checked>
                                        <strong>$student_name</strong>
                                    </label>
                                </div>
                            </div>
STU;
                } else {
                    echo <<<STD
                        <div class="col-sm-12">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" class="editclasscheck" id="$student_id" value="$student_id">
                                    <strong>$student_name</strong>
                                </label>
                            </div>
                        </div>
STD;
                }
            }
            ?>
        </div>
        <?PHP
            $total_earned = 0;
            $total_possible = 0;
            $pcnt_total = 0;
            $sql = "SELECT * FROM gradebook WHERE class_id = $class_id";
            $gradebookRows = pdoSelect($sql);

        ?>
        <div class="col-md-7">
            <h4 class="center">Assignment List</h4>
            <hr>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Assignment Name</th>
                    <th></th>
                    <th>Possible Grade</th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($gradebookRows as $gradebookRow) {
                    extract($gradebookRow);
                    $assign_name = htmlspecialchars($assign_name);
                    $assignsql = "SELECT * FROM grade WHERE assign_id = $assign_id";
                    $gradeRows = pdoSelect($assignsql);
                    $total_possible = $total_possible + $grade_max;
                    $grade_earned = 0;
                    echo <<<BUD
      <tr>
      <td colspan='2'>$assign_name</td>
      <td>$grade_max</td>
      <td class='addeditbtn'><a href="view_assignments.php?assign=$assign_id" class="btn btn-sm btn-info">Manage Grades</td>
      <td class='addeditbtn'><button type="button" data-toggle="modal" data-target="#editassignmodal" data-assignid="$assign_id"
                             data-assignname="$assign_name" data-grademax="$grade_max" 
                             class="btn btn-sm btn-warning">Edit</td>
      </tr>
BUD;
                }
                echo <<<DUD
      <tr>
      <td id='overalltext' colspan='3';>Overall Possible:</td>
      <td>$total_possible points</td>
      <td class='addeditbtn'><button type="button" data-toggle="modal" data-target="#addassignmodal" class="btn btn-success btn-sm">Add New +</a></td>
      </tr>
DUD;
                ?>


                </tbody>
            </table>
        </div>
    </div>
    </form>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-2 col-md-offset-6">
            <hr>
            <button class='btn btn-lg btn-primary btn-block' form="editclassform" type="submit">Submit</button>
        </div>
    </div>
</div>

<div class="modal fade" id="addassignmodal" tabindex="-1" role="dialog" aria-labelledby="Add Assignment">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addassignmodallabel">Add New Assignment</h4>
            </div>
            <div class="modal-body">
                <?php
                $assignnameform = textField("Assignment Name:", "assignnameadd", "Assignment");
                $gradeearnform = numField("Grade Earned:", "assigngradeadd", "", "", "0");
                $maxgradeform = numField("Max Grade:", "maxgradeadd", "", "", "0");
                ?>
                <div class="container">
                    <div class="row">
                        <div class="col-md-4" id="addgradeform">
                            <form id="addassignform" action="">
                                <?=$assignnameform?>
                                <?=$maxgradeform?>
                                <?="<input type='hidden' id='classidadd' name='classidadd' value=$class_id required>"?><br>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class='btn btn-primary' form="addassignform" type="submit">Submit</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editassignmodal" tabindex="-1" role="dialog" aria-labelledby="Edit Assignment">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editassignmodallabel">Edit Assignment</h4>
            </div>
            <div class="modal-body">
                <?php
                $assignnameform = textField("Assignment Name:", "assignnameedit", "");
                $maxgradeform = numField("Max Grade:", "maxgradeedit", "", "");
                ?>
                <div class="container">
                    <div class="row">
                        <div class="col-md-4" id="editgradeform">
                            <form id="editassignform" action=''>
                                <?=$assignnameform?>
                                <?=$maxgradeform?>
                                <?="<input type='hidden' id='assignidedit' name='assignidedit' required>"?><br>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class='btn btn-primary' form="editassignform" type="submit">Submit</button>
            </div>
        </div>
    </div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript" src="edit_class_handler.js"></script>
<script type="text/javascript" src="add_grade_handler.js"></script>
<script type="text/javascript" src="edit_grade_handler.js"></script>
<script>
    $('#editassignmodal').on('show.bs.modal', function(event){
        var button = $(event.relatedTarget);
        var assignid = button.data("assignid");
        var assignname = button.data("assignname");
        var maxgrade = button.data("grademax");
        var modal = $(this);
        modal.find("#assignidedit").val(assignid);
        modal.find("#assignnameedit").val(assignname);
        modal.find("#maxgradeedit").val(maxgrade);
    });
</script>
</body>