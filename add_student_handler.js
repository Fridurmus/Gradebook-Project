/**
 * Created by Sean Davis on 1/23/2017.
 */
$("#addstudentform").submit(function () {
    var studentname = "student=" + $("#studentname").val();
    $.post("add_student_processing.php", studentname, function () {
        location.replace("view_students.php");
    });
    event.preventDefault();
});