/**
 * Created by Sean Davis on 1/20/2017.
 */
$("#editclassform").submit(function (event) {
    event.preventDefault();
    var classname = $("#classnameedit").val();
    var classid = $("#classidedit").val();
    var studentids = $(".editclasscheck:checked").map(function () {
        return this.value;
    }).get().join();
    var successmess = $("<div class='alert alert-success alert-dismissable fade'>"+
        "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+
    "<strong>Success!</strong> Indicates a successful or positive action."+
    "</div>");
    function showAlert(){
        $(".alert").addClass("in");
    }

    $.post("edit_class_processing.php", {classname : classname, classid : classid, studentids : studentids}, function (data) {
        console.log("Errors:" + data);
        $("#messagebox").prepend(successmess);
        window.setTimeout(function () {
            showAlert();
        }, 50);
    });
});