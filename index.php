<?PHP
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
            <span class="navbar-brand">Gradebook</span>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="index.php">View Classes<span class="sr-only">(current)</span></a></li>
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
$classRows = pdoSelect('SELECT * FROM class');
?>
<div id="classtable">
    <div class="container maintable">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Class Name</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($classRows as $classRow) {
                        $total_earned = 0;
                        $total_possible = 0;
                        $pcnt_total = 0;
                        extract($classRow);
                        $class_name = htmlspecialchars($class_name);
                        $gradebookRows = pdoSelect("SELECT * FROM gradebook WHERE class_id = $class_id");
//                        foreach ($gradebookRows as $gradebookRow) {
//                            extract($gradebookRow);
//                            $assignsql = "SELECT * FROM grade WHERE assign_id = $assign_id";
//                            $gradeRows = pdoSelect($assignsql);
//                            if($gradeRows){
//                                extract($gradeRows[0]);
//                            }
//                            else($grade_earned = 0);
//                            $total_earned = $total_earned + $grade_earned;
//                            $total_possible = $total_possible + $grade_max;
//                            $pcnt_total = round((($total_earned / $total_possible) * 100), 2);
//                        }
                        echo <<<BUD
      <tr>
      <td colspan='5'>$class_name</td>
      <td class='addeditbtn'><a href='set_class_processing.php?id=$class_id' class='btn btn-sm btn-primary'>View Assignments</a></td>
      <td class='addeditbtn'><a href='set_class_edit_processing.php?id=$class_id' class='btn btn-sm btn-warning'>Edit</a></td>
      </tr>
BUD;
                    }
                    echo <<<DUD
      <tr>
      <td colspan='6'></td>
      <td class='addeditbtn'><button data-toggle="modal" data-target="#addclassmodal" class="btn btn-success btn-sm">Add New +</button></td>
      </tr>
DUD;
                    ?>


                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addclassmodal" tabindex="-1" role="dialog" aria-labelledby="Add Class">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addclassmodallabel">Add New Class</h4>
            </div>
            <div class="modal-body">
                <?php
                require_once "includes/pollform_generator.php";
                $classnameform = textField("Class Name:", "classname", "Class");
                ?>
                <div class="container">
                    <div class="row">
                        <div class="col-md-4" id="addclassform">
                            <form id="addclassform" action="">
                                <?=$classnameform?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class='btn btn-primary' form="addclassform" type="submit">Submit</button>
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
<script type="text/javascript" src="add_class_handler.js"></script>
<script type="text/javascript" src="edit_class_handler.js"></script>
<!--<script>-->
<!--    $('#editclassmodal').on('show.bs.modal', function(event){-->
<!--        var button = $(event.relatedTarget);-->
<!--        var classid = button.data("classid");-->
<!--        var classname = button.data("classname");-->
<!--        var modal = $(this);-->
<!--        modal.find("#classnameedit").val(classname);-->
<!--        modal.find("#classidedit").val(classid);-->
<!--        var studentlist = [];-->
<!--        console.log(button.data("studentlist"));-->
<!--        if(typeof(button.data("studentlist")) == "number"){-->
<!--            var studentlistnum = button.data("studentlist").toString();-->
<!--            studentlist.push(studentlistnum);-->
<!--        }-->
<!--        else{-->
<!--            studentlist = button.data("studentlist").split(',');-->
<!--        }-->
<!--        console.log("Class Students Array:" + studentlist);-->
<!--        if(studentlist[0] != ""){-->
<!--            for(i in studentlist){-->
<!--                modal.find("#" + studentlist[i]).prop("checked", true);-->
<!--            }-->
<!--        }-->
<!--    });-->
<!--    $('#editclassmodal').on('hide.bs.modal', function(){-->
<!--        var modal = $(this);-->
<!--        modal.find(":checkbox").prop("checked", false);-->
<!--    });-->
<!--</script>-->
</body>