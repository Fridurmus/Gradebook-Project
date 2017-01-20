/**
 * Created by Sean Davis on 1/20/2017.
 */
/**
 * Created by Sean Davis on 1/20/2017.
 */
$("#editgradeform").submit(function () {
    var assignname = $("#assignname").val();
    var assigngrade = $("#assigngrade").val();
    var maxgrade = $("#maxgrade").val();
    var assignid = $("#assignid").val();
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