/**
 * Created by Sean Davis on 1/20/2017.
 */
$("#addgradeform").submit(function () {
    event.preventDefault();
    var assignname = $("#assignnameadd").val();
    var assigngrade = $("#assigngradeadd").val();
    var maxgrade = $("#maxgradeadd").val();
    var classid = $("#classidadd").val();
    var successmess = $("<div class='alert alert-success alert-dismissable fade'>"+
        "<strong>Assignment was added successfully.</strong>"+
        "<button type='button' href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</button>"+
        "</div>");
    var errormess = $("<div class='alert alert-danger alert-dismissable fade'>"+
        "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+
        "<strong>Update failed.</strong> Please contact support."+
        "</div>");
    function showAlert(){
        $(".alert").addClass("in");
    }


    $.post("add_grade_processing.php", {
        assignname : assignname,
        assigngrade : assigngrade,
        maxgrade : maxgrade,
        classid : classid
    },
        function (data) {
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
        $('#addassignmodal').modal('hide');
        window.setTimeout(function () {
            showAlert();
        }, 50);
    });
});