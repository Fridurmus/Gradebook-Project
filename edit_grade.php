<body>

<?php
    $assignid = (int)$_GET['id'];
    $assignname = $_GET['name'];
    $assigngrade = $_GET['earned'];
    $assignmax = $_GET['max'];
    echo "<form action='edit_grade_processing.php' method='post'>
            <label>Assignment Name:</label>
            <input name='assignname' type='text' value='$assignname' required><br>
            <label>Grade Earned:</label>
            <input name='assigngrade' type='number' value=$assigngrade required><br>
            <label>Max Grade:</label>
            <input name='maxgrade' type='number' value=$assignmax required><br>
            <input type='hidden' name='assignid' value=$assignid required><br>
            <input type='submit'>
          </form>";
?>



</body>