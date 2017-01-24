/**
 * Created by Sean Davis on 1/20/2017.
 */
$("#editclassform").submit(function () {
    var classname = $("#classnameedit").val();
    var classid = $("#classidedit").val();
    var studentids = $('#editclassform [type="checkbox"]:checked').map(function () {
        return this.value;
    }).get().join();

    $.post("edit_class_processing.php", {classname : classname, classid : classid, studentids : studentids}, function (data) {
        console.log("Errors:" + data);
        location.replace("index.php");
    });
    event.preventDefault();
});