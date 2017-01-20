/**
 * Created by Sean Davis on 1/20/2017.
 */
$("#editclassform").submit(function () {
    var classname = $("#classnameedit").val();
    var classid = $("#classidedit").val();
    $.post("edit_class_processing.php", {classname : classname, classid : classid}, function () {
        console.log("Class name: " + classname);
        location.replace("index.php");
    });
    event.preventDefault();
});