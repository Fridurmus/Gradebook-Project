/**
 * Created by Sean Davis on 1/20/2017.
 */
$("#addgradeform").submit(function () {
    var assignname = $("#assignnameadd").val();
    var assigngrade = $("#assigngradeadd").val();
    var maxgrade = $("#maxgradeadd").val();
    var classid = $("#classidadd").val();
    $.post("add_grade_processing.php", {
        assignname : assignname,
        assigngrade : assigngrade,
        maxgrade : maxgrade,
        classid : classid
    }, function (data) {
        console.log(data);
        location.replace("edit_class.php");
    });
    event.preventDefault();
});