/**
 * Created by Sean Davis on 1/20/2017.
 */
$("#editclassform").submit(function () {
    var classname = $("#classname").val();
    var classid = $("#classid").val();
    $.post("edit_class_processing.php", {classname : classname, classid : classid}, function () {
        location.replace("index.php");
    });
    event.preventDefault();
});