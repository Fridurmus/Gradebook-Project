<?php
/**
 * Created by PhpStorm.
 * User: Sean Davis
 * Date: 1/12/2017
 * Time: 4:24 PM
 */
require_once 'header.php';
require_once 'includes/database_functions.php';
$studentRows = pdoSelect('SELECT * FROM student');
?>
<div id="classtable">
    <div class="container maintable">
        <div id="messagebox">

        </div>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th colspan="6">Student Name</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($studentRows as $studentRow) {
                        $student_classes = [];
                        extract($studentRow);
                        $student_name = htmlspecialchars($student_name);
                        $studentClassesSql = "SELECT class_id 
                                                 FROM student_class
                                                 WHERE student_id = :student_id";
                        $studentClassesVars = [':student_id' => $student_id];
                        $studentClasses = pdoSelect($studentClassesSql, $studentClassesVars);
                        foreach($studentClasses as $studentClass){
                            array_push($student_classes, join(',', $studentClass));
                        }
                        $student_classes = join(',', $student_classes);
                        echo <<<BUD
      <tr>
          <td colspan='4'>$student_name</td>
             <td><a href="processing/set_student_processing.php?student=$student_id" class="btn btn-sm btn-info">View Grades</a>
          </td>
          <td class='addeditbtn'><button data-toggle="modal" data-target="#editstudentmodal" class='btn btn-sm btn-warning'
             data-studentname='$student_name' data-studentid='$student_id' data-studentclasses='$student_classes'>Edit</a></td>
      </tr>
BUD;
                    }
                    echo <<<DUD
      <tr>
         <td colspan='5'></td>
         <td class='addeditbtn'><button data-toggle="modal" data-target="#addstudentmodal" 
            class="btn btn-success btn-sm">Add New +</button></td>
      </tr>
DUD;
                    ?>


                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addstudentmodal" tabindex="-1" role="dialog" aria-labelledby="Add Student">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addstudentmodallabel">Add New Student</h4>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4" id="addstudentform">
                            <form id="studentaddform" action="">
                                <?PHP
                                require_once "includes/pollform_generator.php";
                                $studentnameform = textField("Student Name:", "studentname", "Student");
                                echo $studentnameform;
                                ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class='btn btn-primary' form="studentaddform" type="submit">Submit</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editstudentmodal" tabindex="-1" role="dialog" aria-labelledby="Edit Student">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editstudentmodallabel">Edit Student</h4>
            </div>
            <div class="modal-body">
                    <div class="row">
                        <div id="editstudentform">
                            <form id='studenteditform' action=''>
                                <div class="col-md-6 col-md-offset-3">
                                    <?PHP
                                    require_once "includes/pollform_generator.php";
                                    $studentnameform = textField("Student Name:", "studentnameedit", "");
                                    echo $studentnameform;
                                    ?>
                                </div>
                                <br>
                                <input type='hidden' name='studentidedit' id='studentidedit' required><br>
                                <?PHP
                                $sqlclass = "SELECT * FROM class";
                                $classRows = pdoSelect($sqlclass);

                                foreach($classRows as $classRow){
                                    extract($classRow);
                                    $class_name = htmlspecialchars($class_name);
                                    echo <<<STU
                                    <div class="col-sm-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="$class_id" value="$class_id">
                                                <strong>$class_name</strong>
                                            </label>
                                        </div>
                                    </div>
STU;
                                }
                                ?>
                            </form>
                        </div>
                    </div>
                </div>
            <div class="modal-footer">
                <button class='btn btn-primary' form="studenteditform" type="submit">Submit</button>
            </div>
            </div>
        </div>
    </div>
</div>

<?php
    require_once 'footer.php';
?>
<script type="text/javascript" src="scripts/add_student_handler.js"></script>
<script type="text/javascript" src="scripts/edit_student_handler.js"></script>
<script>
    $('#editstudentmodal').on('show.bs.modal',  function(event){

        var button = $(event.relatedTarget);
        var studentid = button.data("studentid");
        var studentname = button.data("studentname");
        var studentclasses = [];
        console.log(button.data("studentclasses"));
        if(typeof(button.data("studentclasses")) == "number"){
            var studentclassnum = button.data("studentclasses").toString();
            studentclasses.push(studentclassnum);
        }
        else{
            studentclasses = button.data("studentclasses").split(',');
        }
        console.log("Student Classes Array:" + studentclasses);
        var modal = $(this);
        modal.find("#studentnameedit").val(studentname);
        modal.find("#studentidedit").val(studentid);
        if(studentclasses[0] != ""){
            for(i in studentclasses){
                modal.find("#" + studentclasses[i]).prop("checked", true);
            }
        }
    });
    $('#editstudentmodal').on('hide.bs.modal', function(){
        var modal = $(this);
        modal.find(":checkbox").prop("checked", false);
    });
</script>