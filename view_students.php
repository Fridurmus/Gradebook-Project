<head>
    <title>Student List</title>
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
            <span class="navbar-brand">Gradebook</span>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="index.php">View Classes<span class="sr-only">(current)</span></a></li>
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
$studentRows = pdoSelect('SELECT * FROM student');
?>
<div id="classtable">
    <div class="container maintable">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Student Name</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($studentRows as $studentRow) {
                        extract($studentRow);
                        $student_name = htmlspecialchars($student_name);
                        echo <<<BUD
      <tr>
      <td colspan='3'>$student_name</td>
      <td class='addeditbtn'><button data-toggle="modal" data-target="#editstudentmodal" class='btn btn-sm btn-warning'
        data-studentname='$student_name' data-studentid='$student_id'>Edit</a></td>
      </tr>
BUD;
                    }
                    echo <<<DUD
      <tr>
      <td colspan='3'></td>
      <td class='addeditbtn'><button data-toggle="modal" data-target="#addstudentmodal" class="btn btn-success btn-sm">Add New +</button></td>
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
                <?php
                require_once "includes/pollform_generator.php";
                $studentnameform = textField("Student Name:", "studentname", "Student");
                ?>
                <div class="container">
                    <div class="row">
                        <div class="col-md-4" id="addstudentform">
                            <form id="studentaddform" action="">
                                <?=$studentnameform?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class='btn btn-primary' type="submit">Submit</button>
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
                <?php
                require_once "includes/pollform_generator.php";
                $studentnameform = textField("Student Name:", "studentnameedit", "");
                ?>
                    <div class="row">
                        <div id="editstudentform">
                            <form id='studenteditform' action=''>
                                <div class="col-md-6 col-md-offset-3">
                                    <?=$studentnameform?>
                                </div>
                                <br>
                                <?="<input type='hidden' name='studentidedit' id='studentidedit' required>"?><br>
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
                                            <input type="checkbox" value="$class_id">
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

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<script type="text/javascript" src="add_student_handler.js"></script>
<script type="text/javascript" src="edit_student_handler.js"></script>
<script>
    $('#editstudentmodal').on('show.bs.modal', function(event){
        var button = $(event.relatedTarget);
        var studentid = button.data("studentid");
        var studentname = button.data("studentname");
        var modal = $(this);
        modal.find("#studentnameedit").val(studentname);
        modal.find("#studentidedit").val(studentid);
    });
</script>
</body>