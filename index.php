<head>
    <title>My Gradebook</title>
    <link rel="stylesheet" type="text/css" href="gradebook_theme.css">
</head>

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

$vars = array(':assign_name'=>'test_assign2', ':grade_earned'=>80.5, ':grade_max'=>100.0);

$total_earned = 0;
$total_possible = 0;

//$res = pdoUpdate($sql, $vars);

//echo "<pre>";
//print_r($res);
$gradebookRows = pdoSelect('SELECT * FROM gradebook');
?>
    <table>
        <thead>
            <tr>
                <th>Assignment Name</th>
                <th>Grade Earned</th>
                <th>Possible Grade</th>
                <th>Percentage Grade</th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($gradebookRows as $gradebookRow){
    extract($gradebookRow);
    $assign_name = htmlspecialchars($assign_name);
    $pcnt_assign = round((($grade_earned / $grade_max) * 100), 2);
    $total_earned = $total_earned + $grade_earned;
    $total_possible = $total_possible + $grade_max;
    echo "<tr>
      <td>$assign_name</td>
      <td>$grade_earned</td>
      <td>$grade_max</td>
      <td>$pcnt_assign%</td>
      <td><a href='edit_grade.php?id=$assign_id&name=$assign_name&earned=$grade_earned&max=$grade_max'>Edit</a></td>
      </tr>
    ";
}
    $pcnt_total = round((($total_earned / $total_possible) * 100), 2);

    echo"<tr>
    <td id='overalltext' colspan='3';>Overall:</td>
    <td>$pcnt_total%</td>
    </tr>
    ";
?>

        </tbody>
    </table>
<div class="centerbutton"><a href="add_grade.php"><input type="button" value="Add New +"></a></div>
</body>