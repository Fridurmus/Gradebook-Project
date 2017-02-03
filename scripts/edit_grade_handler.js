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
    var successmess = $("<div class='alert alert-success alert-dismissable fade'>"+
        "<strong>Assignment was updated successfully.</strong>"+
        "<button type='button' href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</button>"+
        "</div>");
    var errormess = $("<div class='alert alert-danger alert-dismissable fade'>"+
        "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+
        "<strong>Update failed.</strong> Please contact support."+
        "</div>");
    function showAlert(){
        $(".alert").addClass("in");
    }

    $.post("./processing/edit_grade_processing.php", {
        assignname : assignname,
        maxgrade : maxgrade,
        assignid : assignid
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
                location.replace("edit_class.php");
            }, 2000);
        }
        else{
            $("#messagebox").prepend(errormess);
        }
        $('#editassignmodal').modal('hide');
        window.setTimeout(function () {
            showAlert();
        }, 50);

    });
});