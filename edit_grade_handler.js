/**
 * Created by Sean Davis on 1/20/2017.
 */
/**
 * Created by Sean Davis on 1/20/2017.
 */
$("#editgradeform").submit(function (event) {
    event.preventDefault();
    var assignname = $("#assignnameedit").val();
    var maxgrade = $("#maxgradeedit").val();
    var assignid = $("#assignidedit").val();
    $.post("edit_grade_processing.php", {
        assignname : assignname,
        maxgrade : maxgrade,
        assignid : assignid
    }, function (data) {
        console.log(data);
        location.replace("edit_class.php");
    });
});