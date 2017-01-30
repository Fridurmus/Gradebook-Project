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

//Handler for returning to the correct page after setting a grade record.
if (empty($_GET)){
    $prev_id = null;
}
else{
    $prev_id = $_GET['previd'];
}

//Session variable set by set_student_processing.php.
$studentid = $_SESSION['studentid'];
require_once 'includes/database_functions.php';
$sql = "SELECT * FROM student WHERE student_id = $studentid";
$studentRow = pdoSelect($sql);


extract($studentRow[0]);
$student_name = htmlspecialchars($student_name);
?>
<h1 class="center"><?= $student_name ?></h1>
<div class="container" id="messagebox">
    <div class="row" id="classoptions">
        <div class="col-md-4 col-md-offset-4">
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
            ?>
                <?PHP
                //Checking to be sure the student is actually enrolled.
                if(count($student_classes) == 0){
                    echo "<a href='view_students.php' id='noclass'><div class='alert alert-warning'>
                              <strong>This student is not enrolled in any classes! Click here to go back.</strong>
                          </div></a>";
                }
                else{
                    //Select values are passed to record_view_handler.js to control the view.
                    echo "<select class='form-control' id='classtoggle'>";
                    foreach ($classRows as $classRow) {
                        extract($classRow);
                        if(in_array($class_id, $student_classes)){
                            if($prev_id == $class_id) {
                                $class_name = htmlspecialchars($class_name);
                                echo "<option value='$class_id' selected='selected'>$class_name</option>";
                            }
                            else{
                                $class_name = htmlspecialchars($class_name);
                                echo "<option value='$class_id'>$class_name</option>";
                            }
                        };
                    }
                }
                ?>
            </select>
        </div>
    </div>
</div>
<div class="container">
    <div class="row" id="classrecords">
            <?PHP
            foreach ($classRows as $classRow) {
                extract($classRow);
                if (in_array($class_id, $student_classes)){
                    $class_name = htmlspecialchars($class_name);
                    echo <<<LUP
                    <div id=$class_id class='hidethis hideable'>
                     <div class="col-md-8 col-md-offset-2">
                         <hr>
                         <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Assignment Name</th>
                                <th></th>
                                <th></th>
                                <th>Grade Earned</th>
                                <th>Possible Grade</th>
                                <th></th>
                                <th>Percentage</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
LUP;
                    $total_possible = 0;
                    $total_earned = 0;
                    $pcnt_total = 0;
                    $sqlgradebook = "SELECT * FROM gradebook 
                                     WHERE class_id = :class_id";
                    $gradebookvar = array(":class_id" => $class_id);
                    $gradebookRows = pdoSelect($sqlgradebook, $gradebookvar);
                    foreach ($gradebookRows as $gradebookRow) {
                        extract($gradebookRow);
                        $assign_name = htmlspecialchars($assign_name);
                        $assignsql = "SELECT * FROM grade WHERE assign_id = $assign_id 
                                      AND student_id = $student_id";
                        $gradeRows = pdoSelect($assignsql);
                        $total_possible = $total_possible + $grade_max;
                        $grade_earned = 0;
                        /**Similarly to the if statement in view_assignments.php, this helps to prevent some ugly and
                        problematic division by zero issues. It might be doable better with a joined table. We'll see.*/
                        if($gradeRows){
                            extract($gradeRows[0]);
                            $pcnt_assign = round((($grade_earned / $grade_max) * 100), 2);
                            $total_earned = $total_earned + $grade_earned;
                            $pcnt_total = round((($total_earned / $total_possible) * 100), 2);
                        }
                        else{
                            $pcnt_assign = 0;
                        }

                        echo <<<BUD
                          <tr>
                          <td>$assign_name</td>
                          <td></td>
                          <td></td>
                          <td>$grade_earned</td>
                          <td>$grade_max</td>
                          <td></td>
                          <td>$pcnt_assign%</td>
                          <td class='addeditbtn'><button type="button" data-toggle="modal" data-target="#editrecordmodal" data-assignid="$assign_id"
                                                 data-assignname="$assign_name" data-grademax="$grade_max" 
                                                 data-classid = "$class_id" data-gradeearn="$grade_earned" 
                                                 class="btn btn-sm btn-warning">Edit</td>
                          </tr>
BUD;
                    }
                    echo <<<DUD
                          <tr>
                          <td id='overalltext' colspan='7'>Overall:</td>
                          <td>$pcnt_total%</td>
                          </tr>
                          </tbody>
                        </table>
                    </div>
                </div>
DUD;
                        }
                    }
                ?>
    </div>
</div>
<div class="modal fade" id="editrecordmodal" tabindex="-1" role="dialog" aria-labelledby="Edit Record">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editrecordmodallabel">Edit Grade</h4>
            </div>
            <div class="modal-body">
                <?php
                require_once "includes/pollform_generator.php";
                $editrecordform = numField("New grade:", "editrecordearn", "", "", "0");
                ?>
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <form id="editrecordform" action="">
                                <?=$editrecordform?>
                            </form>
                            <?="<input type='hidden' id='recordclassedit' name='recordclassedit' required>"?><br>
                            <?="<input type='hidden' id='recordassignedit' name='recordassignedit' required>"?><br>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class='btn btn-primary' form="editrecordform" type="submit">Submit</button>
            </div>
        </div>
    </div>
</div>



<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<script type="text/javascript" src="record_view_handler.js"></script>
<script type="text/javascript" src="edit_record_handler.js"></script>
<script>
    $('#editrecordmodal').on('show.bs.modal', function(event){
        var button = $(event.relatedTarget);
        var gradeearn = button.data("gradeearn");
        var assignid = button.data("assignid");
        var classid = button.data("classid");
        var modal = $(this);
        modal.find("#editrecordearn").val(gradeearn);
        modal.find("#recordclassedit").val(classid);
        modal.find("#recordassignedit").val(assignid);
    });
</script>
</body>