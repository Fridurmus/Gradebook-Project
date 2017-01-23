/**
 * Created by Sean Davis on 1/23/2017.
 */
$("#editstudentform").submit(function () {
    var studentname = $("#studentnameedit").val();
    var studentid = $("#studentidedit").val();
    $.post("edit_student_processing.php", {studentname : studentname, studentid : studentid}, function () {
        location.replace("view_students.php");
    });
    event.preventDefault();
});