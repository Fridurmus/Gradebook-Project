<head>
    <title>Edit Grade</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
</head>

<body>


<?php
    require_once "includes/pollform_generator.php";

    $assignid = (int)$_GET['id'];
    $assignname = $_GET['name'];
    $assigngrade = $_GET['earned'];
    $assignmax = $_GET['max'];
    $assignnameform = textField("Assignment Name:", "assignname", $assignname, $assignname);
    $gradeearnform = numField("Grade Earned:", "assigngrade", "", "", $assigngrade);
    $maxgradeform = numField("Max Grade:", "maxgrade", "", "", $assignmax);
?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <form action='edit_grade_processing.php' method='post'>
                <?=$assignnameform?>
                <?=$gradeearnform?>
                <?=$maxgradeform?>
                <?="<input type='hidden' name='assignid' value=$assignid required>"?><br>
                <button class='btn btn-primary' type="submit">Submit</button>
            </form>
        </div>
    </div>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>