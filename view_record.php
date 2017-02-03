<?php
    session_start();
    require_once "header.php";
/**
 * Created by PhpStorm.
 * User: Sean Davis
 * Date: 1/23/2017
 * Time: 2:28 PM
 */

//Handler for returning to the correct page after setting a grade record.

$prev_id = (empty($_GET)) ? null : $_GET['previd'];
//if (empty($_GET)){
//    $prev_id = null;
//}
//else{
//    $prev_id = $_GET['previd'];
//}

//Session variable set by set_student_processing.php.
$studentid = $_SESSION['studentid'];
require_once 'includes/database_functions.php';
//Defining a single array of variables for multiple PDOSelects.
$studentIdVars = [':studentid' => $studentid];
$sql = "SELECT * FROM student WHERE student_id = :studentid";
$studentRow = pdoSelect($sql, $studentIdVars);


extract($studentRow[0]);
$student_name = htmlspecialchars($student_name);
?>
<h1 class="center"><?= $student_name ?></h1>
<div class="container" id="messagebox">
    <div class="row" id="classoptions">
        <div class="col-md-4 col-md-offset-4">
            <?PHP
            $studentClasses = array();
            $classSql = "SELECT * FROM class";
            $studentClassSql = "SELECT * FROM student_class WHERE student_id = :studentid";
            $classRows = pdoSelect($classSql);
            // studentvars declared above
            $matchedRows = pdoSelect($studentClassSql, $studentIdVars);
            $rowCount = 0;
            foreach($matchedRows as $matchedRow){
                extract($matchedRow);
                array_push($studentClasses, $class_id);
            };

                //Checking to be sure the student is actually enrolled.
                if(count($studentClasses) == 0){
                    echo "<a href='view_students.php' id='noclass'><div class='alert alert-warning'>
                              <strong>This student is not enrolled in any classes! Click here to go back.</strong>
                          </div></a>";
                }
                else{
                    //Select values are passed to record_view_handler.js to control the view.
                    echo "<select class='form-control' id='classtoggle'>";
                    foreach ($classRows as $classRow) {
                        extract($classRow);
                        if(in_array($class_id, $studentClasses)){
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
                $totalPossible = 0;
                extract($classRow);
                if (in_array($class_id, $studentClasses)){
                    $class_name = htmlspecialchars($class_name);
                    echo <<<LUP
                    <div id=$class_id class='hidethis hideable'>
                     <div class="col-md-8 col-md-offset-2">
                         <hr>
                         <table class="table table-hover">
                            <thead>
                            <tr>
                                <th colspan="3">Assignment Name</th>
                                <th>Grade Earned</th>
                                <th colspan="2">Possible Grade</th>
                                <th colspan="2">Percentage</th>
                            </tr>
                            </thead>
                            <tbody>
LUP;

                    $totalEarned = 0;
                    $pcntTotal = 0;
                    $gradebookSql = "SELECT gb.*, g.grade_earned
                                     FROM gradebook gb
                                     LEFT OUTER JOIN grade g ON g.student_id = :student_id AND g.assign_id = gb.assign_id
                                     WHERE gb.class_id = :class_id";
                    $gradebookVars = [':student_id' => $studentid, ':class_id' => $class_id];
                    $gradebookRows = pdoSelect($gradebookSql, $gradebookVars);
                    foreach ($gradebookRows as $gradebookRow) {
                        extract($gradebookRow);
                        $assign_name = htmlspecialchars($assign_name);
                        $totalPossible = $totalPossible + $grade_max;
                        if($grade_earned){
                            $pcntAssign = round((($grade_earned / $grade_max) * 100), 2);
                            $totalEarned = $totalEarned + $grade_earned;
                            $pcntTotal = round((($totalEarned / $totalPossible) * 100), 2);
                        }
                        else{
                            $grade_earned = 0;
                            $pcntAssign = 0;
                        }

                        echo <<<BUD
                          <tr>
                              <td colspan="3">$assign_name</td>
                              <td>$grade_earned</td>
                              <td colspan="2">$grade_max</td>
                              <td>$pcntAssign%</td>
                              <td class='addeditbtn'><button type="button" data-toggle="modal" 
                                                 data-target="#editrecordmodal" data-assignid="$assign_id"
                                                 data-assignname="$assign_name" data-grademax="$grade_max" 
                                                 data-classid = "$class_id" data-gradeearn="$grade_earned" 
                                                 class="btn btn-sm btn-warning">Edit</td>
                          </tr>
BUD;
                    }
                    echo <<<DUD
                          <tr>
                              <td class='addeditbtn' colspan='7'>Overall:</td>
                              <td>$pcntTotal%</td>
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



<?PHP
require_once "footer.php";
?>
<script type="text/javascript" src="scripts/record_view_handler.js"></script>
<script type="text/javascript" src="scripts/edit_record_handler.js"></script>
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