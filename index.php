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



//$res = pdoUpdate($sql, $vars);

//echo "<pre>";
//print_r($res);
$gradebookRows = pdoSelect('SELECT * FROM gradebook');
?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Assignment Name</th>
                <th>Grade Earned</th>
                <th>Possible Grade</th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($gradebookRows as $gradebookRow){
    extract($gradebookRow);
    $assign_name = htmlspecialchars($assign_name);
    echo "<tr>
      <td>$assign_id</td>
      <td>$assign_name</td>
      <td>$grade_earned</td>
      <td>$grade_max</td>
      <td><a href='edit_grade.php?id=$assign_id&name=$assign_name&earned=$grade_earned&max=$grade_max'>Edit</a></td>
      </tr>
    ";
}
?>
        </tbody>
    </table>
</body>