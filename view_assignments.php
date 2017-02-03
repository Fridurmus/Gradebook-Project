<?php
session_start();
/**
 * Created by PhpStorm.
 * User: Sean Davis
 * Date: 1/12/2017
 * Time: 4:24 PM
 */
require_once 'header.php';
require_once 'includes/database_functions.php';
$totalEarned = 0;
$totalPossible = 0;
//Session variable set by set_class_edit_processing.php.
$classid = $_SESSION['classid'];
$assignid = $_GET['assign'];

$gradedStudentsSql = "SELECT s.student_name, s.student_id, 
                          g.grade_earned, gb.grade_max, gb.assign_name
                       FROM student s
                       JOIN gradebook gb ON gb.assign_id = :assignid
                       LEFT OUTER JOIN grade g ON g.student_id = s.student_id AND g.assign_id = :assignid
                       WHERE s.student_id IN 
                         (SELECT student_id
                            FROM student_class
                            WHERE class_id = :classid)";
$gradedStudentsVars = [':assignid' => $assignid, ':classid' => $classid];
$gradedStudents = pdoSelect($gradedStudentsSql, $gradedStudentsVars);
if($gradedStudents){
    extract($gradedStudents[0]);
}
?>
<div id="classgrades">
    <div class="container maintable" id="messagebox">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h2 class="center"><?=$assign_name?></h2><hr>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th colspan="2">Student Name</th>
                        <th colspan="2">Grade Earned</th>
                        <th colspan="2">Possible Grade</th>
                        <th colspan="2">Percentage</th>
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
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <form id="classgradeearnform" action="">
                                <?php
                                    require_once "includes/pollform_generator.php";
                                    $gradeEarnForm = numField("Grade Earned:", "assigngradeedit", "", "", "0");
                                    echo $gradeEarnForm;
                                    echo "<input type='hidden' id='assignidgrade' name='assignidgrade' required>";
                                    echo "<input type='hidden' id='studentidgrade' name='studentidgrade' required>"
                                ?>
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


<?PHP
require_once 'footer.php'
?>
<script type="text/javascript" src="scripts/edit_class_grade_handler.js"></script>
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
