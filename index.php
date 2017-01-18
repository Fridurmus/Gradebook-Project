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

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Gradebook</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li>
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

$sql = "INSERT INTO gradebook (assign_name, grade_earned, grade_max)
        VALUES (:assign_name, :grade_earned, :grade_max)";


$total_earned = 0;
$total_possible = 0;
$gradebookRows = pdoSelect('SELECT * FROM gradebook');
?>
<div id="maintable" class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Assignment Name</th>
                    <th>Grade Earned</th>
                    <th>Possible Grade</th>
                    <th>Percentage Grade</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($gradebookRows as $gradebookRow) {
                    extract($gradebookRow);
                    $assign_name = htmlspecialchars($assign_name);
                    $pcnt_assign = round((($grade_earned / $grade_max) * 100), 2);
                    $total_earned = $total_earned + $grade_earned;
                    $total_possible = $total_possible + $grade_max;
                    $pcnt_total = round((($total_earned / $total_possible) * 100), 2);
                    echo <<<BUD
      <tr>
      <td>$assign_name</td>
      <td>$grade_earned</td>
      <td>$grade_max</td>
      <td>$pcnt_assign%</td>
      <td><a href='edit_grade.php?id=$assign_id&name=$assign_name&earned=$grade_earned&max=$grade_max' class='btn btn-sm btn-warning'>Edit</a></td>
      </tr>
BUD;
                }
                echo <<<DUD
      <tr>
      <td id='overalltext' colspan='3';>Overall:</td>
      <td>$pcnt_total%</td>
      <td></td>
      </tr>
DUD;
                ?>


                </tbody>
            </table>
            <div class="centerbutton"><a href="add_grade.php"><input type="button" class="btn btn-success"
                                                                     value="Add New +"></a></div>
        </div>
    </div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>