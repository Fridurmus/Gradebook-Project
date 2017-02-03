<?PHP
session_start();
require_once 'header.php';
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
        <div id="messagebox">

        </div>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th colspan="7">Class Name</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($classRows as $classRow) {
                        extract($classRow);
                        $class_name = htmlspecialchars($class_name);
                        echo <<<BUD
      <tr>
         <td colspan='6'>$class_name</td>
         <td class='addeditbtn'><a href='processing/set_class_edit_processing.php?id=$class_id' 
            class='btn btn-sm btn-primary'>Manage</a></td>
      </tr>
BUD;
                    }
                    echo <<<DUD
      <tr>
        <td colspan='6'></td>
        <td class='addeditbtn'><button data-toggle="modal" data-target="#addclassmodal" 
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

<div class="modal fade" id="addclassmodal" tabindex="-1" role="dialog" aria-labelledby="Add Class">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addclassmodallabel">Add New Class</h4>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4" id="addclassform">
                            <form id="addclassformhandle" action="">
                                <?php
                                    require_once "includes/pollform_generator.php";
                                    $classnameform = textField("Class Name:", "classname", "Class");
                                    echo $classnameform
                                ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class='btn btn-primary' form="addclassformhandle" value="Submit" type="submit">Submit</button>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'footer.php';
?>

<script type="text/javascript" src="scripts/add_class_handler.js"></script>
