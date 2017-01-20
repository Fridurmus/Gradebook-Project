/**
 * Created by Sean Davis on 1/20/2017.
 */
$("#addgradeform").submit(function () {
    var assignname = $("#assignname").val();
    var assigngrade = $("#assigngrade").val();
    var maxgrade = $("#maxgrade").val();
    var classid = $("#classid").val();
    $.post("add_grade_processing.php", {
        assignname : assignname,
        assigngrade : assigngrade,
        maxgrade : maxgrade,
        classid : classid
    }, function () {
        location.replace("view_grades.php");
    });
    event.preventDefault();
});