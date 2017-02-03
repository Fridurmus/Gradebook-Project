<?PHP
session_start();
require_once 'header.php';
?>


<div class="container">
    <form id="editclassform" action=''>
    <div id="messagebox">

    </div>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
                <?PHP
                require_once 'includes/database_functions.php';
                require_once "includes/pollform_generator.php";
                //Session variable set by set_class_edit_processing.php.
                $classId = $_SESSION['classid'];
                //Defining a single variable array for multiple pdoSelects.
                $classIdVars = [':classId' => $classId];
                $studentList = [];
                $classStudentSql = "SELECT s.student_id, s.student_name, c.class_name
                                                FROM student s
                                                JOIN class c ON c.class_id = :classId";
                $classStudents = pdoSelect($classStudentSql, $classIdVars);
                extract($classStudents[0]);
                $classNameForm = textField("Class Name:", "classnameedit", $class_name, $class_name);
                echo $classNameForm;
                echo "<input type='hidden' name='classidedit' id='classidedit' value=$classId required><br>";

                $enrollStudentsSql = "SELECT student_id 
                                          FROM student_class
                                          WHERE class_id = :classId";
                $enrollStudents = pdoSelect($enrollStudentsSql, $classIdVars);
                foreach ($enrollStudents as $enrollStudent) {
                    array_push($studentList, join(',', $enrollStudent));
                }
                ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-md-offset-1">
            <h4 class="center">Student List</h4>
            <hr>
            <?php
            foreach ($classStudents as $studentRow) {
                extract($studentRow);
                $student_name = htmlspecialchars($student_name);
                if (in_array(($student_id), $studentList)) {
                    $checked = (in_array(($student_id), $studentList)) ? 'checked' : '';
                }
                    echo <<<STU
                            <div class="col-sm-12">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" class="editclasscheck" id="$student_id" value="$student_id" $checked>
                                        <strong>$student_name</strong>
                                    </label>
                                </div>
                            </div>
STU;

            }
            ?>
        </div>
        <?PHP
            $totalPossible = 0;
            $gradeSql = "SELECT * FROM gradebook WHERE class_id = :classId";
            $gradebookRows = pdoSelect($gradeSql, $classIdVars);
        ?>
        <div class="col-md-7">
            <h4 class="center">Assignment List</h4>
            <hr>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th colspan="2">Assignment Name</th>
                    <th colspan="3">Possible Grade</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($gradebookRows as $gradebookRow) {
                    extract($gradebookRow);
                    $assign_name = htmlspecialchars($assign_name);
                    $totalPossible = $totalPossible + $grade_max;
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
         <td>$totalPossible points</td>
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
                <div class="container">
                    <div class="row">
                        <div class="col-md-4" id="addgradeform">
                            <form id="addassignform" action="">
                                <?php
                                $assignnameform = textField("Assignment Name:", "assignnameadd", "Assignment");
                                $gradeearnform = numField("Grade Earned:", "assigngradeadd", "", "", "0");
                                $maxgradeform = numField("Max Grade:", "maxgradeadd", "", "", "0");
                                echo $assignnameform;
                                echo $maxgradeform;
                                ?>
                                <?="<input type='hidden' id='classidadd' name='classidadd' value=$classId required>"?><br>
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
                <div class="container">
                    <div class="row">
                        <div class="col-md-4" id="editgradeform">
                            <form id="editassignform" action=''>
                                <?php
                                $assignnameform = textField("Assignment Name:", "assignnameedit", "");
                                $maxgradeform = numField("Max Grade:", "maxgradeedit", "", "");
                                echo $assignnameform;
                                echo $maxgradeform;
                                ?>
                                <input type='hidden' id='assignidedit' name='assignidedit' required><br>
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

<?php
require_once 'footer.php';
?>
<script type="text/javascript" src="scripts/edit_class_handler.js"></script>
<script type="text/javascript" src="scripts/add_grade_handler.js"></script>
<script type="text/javascript" src="scripts/edit_grade_handler.js"></script>
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