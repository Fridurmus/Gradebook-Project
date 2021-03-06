/**
 * Created by Sean Davis on 1/23/2017.
 */
$("#editstudentform").submit(function (event) {
    event.preventDefault();
    var studentname = $("#studentnameedit").val();
    var studentid = $("#studentidedit").val();
    var classids = $('#editstudentform [type="checkbox"]:checked').map(function () {
        return this.value;
    }).get().join();
    var successmess = $("<div class='alert alert-success alert-dismissable fade'>"+
        "<strong>Student was updated successfully.</strong>"+
        "<button type='button' href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</button>"+
        "</div>");
    var errormess = $("<div class='alert alert-danger alert-dismissable fade'>"+
        "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+
        "<strong>Update failed.</strong> Please contact support."+
        "</div>");
    function showAlert(){
        $(".alert").addClass("in");
    }

    $.post("./processing/edit_student_processing.php", {studentname : studentname, studentid : studentid, classids : classids
                                           }, function (data) {
        $(".alert-dismissable").alert('close');
        console.log(data);
        var resultstate = true;
        var resarray = data.split('|');
        for(var i = 1; i < resarray.length; i++){
            console.log(resarray[i]);
            if(resarray[i] != 's'){
                resultstate = false;
            }
        }
        if(resultstate){
            $("#messagebox").prepend(successmess);
            setTimeout(function(){
                location.replace("view_students.php");
            }, 2000);
        }
        else{
            $("#messagebox").prepend(errormess);
        }
        $('#editstudentmodal').modal('hide');
        window.setTimeout(function () {
            showAlert();
        }, 50);
    });
});