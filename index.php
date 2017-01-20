<?PHP
session_start();
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();
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
                <!--<li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">Dropdown <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="index.php">Change Class</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li>-->
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
                        <th>Grade</th>
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
                        foreach ($gradebookRows as $gradebookRow) {
                            extract($gradebookRow);
                            $total_earned = $total_earned + $grade_earned;
                            $total_possible = $total_possible + $grade_max;
                            $pcnt_total = round((($total_earned / $total_possible) * 100), 2);
                        }
                        echo <<<BUD
      <tr>
      <td colspan='3'>$class_name</td>
      <td colspan='2'>$pcnt_total%</td>
      <td class='addeditbtn'><a href='set_class_processing.php?id=$class_id' class='btn btn-sm btn-primary'>View Grades</a></td>
      <td class='addeditbtn'><button data-toggle="modal" data-target="#editclassmodal" class='btn btn-sm btn-warning'
        data-classname='$class_name' data-classid='$class_id'>Edit</a></td>
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
                            <form action="">
                                <?=$classnameform?>
                                <button class='btn btn-primary' type="submit">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editclassmodal" tabindex="-1" role="dialog" aria-labelledby="Edit Class">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editclassmodallabel">Edit Class</h4>
            </div>
            <div class="modal-body">
                <?php
                    require_once "includes/pollform_generator.php";
                    $classnameform = textField("Class Name:", "classnameedit", "");
                ?>
                <div class="container">
                    <div class="row">
                        <div class="col-md-4" id="editclassform">
                            <form action=''>
                                <?=$classnameform?>
                                <?="<input type='hidden' name='classidedit' id='classidedit' required>"?><br>
                                <button class='btn btn-primary' type="submit">Submit</button>
                            </form>
                        </div>
                    </div>
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
<script type="text/javascript" src="add_class_handler.js"></script>
<script type="text/javascript" src="edit_class_handler.js"></script>
<script>
    $('#editclassmodal').on('show.bs.modal', function(event){
        var button = $(event.relatedTarget);
        var classid = button.data("classid");
        var classname = button.data("classname");
        var modal = $(this);
        modal.find("#classnameedit").val(classname);
        modal.find("#classidedit").val(classid);
    });
</script>
</body>