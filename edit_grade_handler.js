/**
 * Created by Sean Davis on 1/20/2017.
 */
/**
 * Created by Sean Davis on 1/20/2017.
 */
$("#editgradeform").submit(function () {
    var assignname = $("#assignnameedit").val();
    var assigngrade = $("#assigngradeedit").val();
    var maxgrade = $("#maxgradeedit").val();
    var assignid = $("#assignidedit").val();
    $.post("edit_grade_processing.php", {
        assignname : assignname,
        assigngrade : assigngrade,
        maxgrade : maxgrade,
        assignid : assignid
    }, function () {
        location.replace("view_grades.php");
    });
    event.preventDefault();
});