<?php
session_start();
?>
<head>
    <title>My Gradebook</title>
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

<!-- navigation -->
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
 * Date: 1/12/2017
 * Time: 4:24 PM
 */
require_once 'includes/database_functions.php';

$totalEarned = 0;
$totalPossible = 0;
//Session variable set by set_class_edit_processing.php.
$classid = $_SESSION['classid'];
$assignid = $_GET['assign'];

$gradedStudentsSql = "SELECT student.student_name, student.student_id, 
                          grade.grade_earned, gradebook.grade_max, gradebook.assign_name
                       FROM student
                       JOIN gradebook ON gradebook.assign_id = :assignid
                       LEFT OUTER JOIN grade ON grade.student_id = student.student_id AND grade.assign_id = :assignid
                       WHERE student.student_id IN 
                         (SELECT student_id
                            FROM student_class
                            WHERE class_id = :classid)";
$gradedStudentsVars = [':assignid' => $assignid, ':classid' => $classid];
$gradedStudents = pdoSelect($gradedStudentsSql, $gradedStudentsVars);

extract($gradedStudents[0]);

?>
<div id="classgrades">
    <div class="container maintable" id="messagebox">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h2 class="center"><?=$assign_name?></h2><hr>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Student Name</th>
                        <th></th>
                        <th>Grade Earned</th>
                        <th></th>
                        <th>Possible Grade</th>
                        <th></th>
                        <th>Percentage</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $avgGradeSql = "SELECT AVG(grade_earned)
                                        FROM grade
                                        WHERE assign_id = :assignid";
                    $avgGradeVars = [':assignid' => $assignid];
                    $avg_grade = pdoSelect($avgGradeSql, $avgGradeVars);
                    $avg_grade = number_format($avg_grade[0]['AVG(grade_earned)'], 2, '.', '');
                    foreach ($gradedStudents as $testStudent) {
                        extract($testStudent);
                        $student_name = htmlspecialchars($student_name);
                        if($grade_earned){
                            $pcntAssign = round((($grade_earned / $grade_max) * 100), 2);
                        }
                        else{
                            $grade_earned = 0;
                            $pcntAssign = 0;
                        }
                        echo <<<BUD
      <tr>
      <td colspan='2'>$student_name</td>
      <td colspan='2'>$grade_earned</td>
      <td colspan='2'>$grade_max</td>
      <td>$pcntAssign%</td>
      <td class='addeditbtn'><button data-toggle='modal' data-target='#classeditgrademodal' data-assignid='$assignid'
                             data-student='$student_id' data-assigngrade='$grade_earned'
                             class='btn btn-sm btn-warning'>Edit</a></td>
      </tr>
BUD;
                    }
                    echo<<<TOT
                    <tr>
                          <td class='addeditbtn' colspan='7'>Class Average Grade:</td>
                          <td>$avg_grade</td>
                    </tr>
TOT;
                    ?>


                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
<div class="modal fade" id='classeditgrademodal' tabindex="-1" role="dialog" aria-labelledby="Edit Grade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editgrademodallabel">Edit Grade</h4>
            </div>
            <div class="modal-body">
                <?php
                require_once "includes/pollform_generator.php";
                $gradeEarnForm = numField("Grade Earned:", "assigngradeedit", "", "", "0");
                ?>
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <form id="classgradeearnform" action="">
                                <?=$gradeEarnForm?>
                                <?="<input type='hidden' id='assignidgrade' name='assignidgrade' required>"?>
                                <?="<input type='hidden' id='studentidgrade' name='studentidgrade' required>"?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class='btn btn-primary' form="classgradeearnform" type="submit">Submit</button>
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
<script type="text/javascript" src="edit_class_grade_handler.js"></script>
<script>
    $('#classeditgrademodal').on('show.bs.modal', function(event){
        var button = $(event.relatedTarget);
        var assignid = button.data("assignid");
        var studentid = button.data("student");
        var assigngrade = button.data("assigngrade");
        var modal = $(this);
        modal.find("#assignidgrade").val(assignid);
        modal.find("#assigngradeedit").val(assigngrade);
        modal.find("#studentidgrade").val(studentid);
    });
</script>
</body>
