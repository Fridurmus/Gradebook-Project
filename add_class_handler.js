/**
 * Created by Sean Davis on 1/20/2017.
 */
$("#addclassform").submit(function () {
    event.preventDefault();
    var classname = "classname=" + $("#classname").val();
    var successmess = $("<div class='alert alert-success alert-dismissable fade'>"+
        "<strong>Class was added successfully.</strong>"+
        "<button type='button' href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</button>"+
        "</div>");
    var errormess = $("<div class='alert alert-danger alert-dismissable fade'>"+
        "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+
        "<strong>Update failed.</strong> Please contact support."+
        "</div>");
    function showAlert(){
        $(".alert").addClass("in");
    }

    $.post("add_class_processing.php", classname, function (data) {
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
                location.replace("index.php");
            }, 2000);
        }
        else{
            $("#messagebox").prepend(errormess);
        }
        $('#addclassmodal').modal('hide');
        window.setTimeout(function () {
            showAlert();
        }, 50);
    });
});