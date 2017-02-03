/**
 * Created by Sean Davis on 1/27/2017.
 */
$("#editrecordform").submit(function (event) {
    event.preventDefault();
    var gradeearned = $("#editrecordearn").val();
    var assignid = $("#recordassignedit").val();
    var classid = $("#recordclassedit").val();

    var successmess = $("<div class='alert alert-success alert-dismissable fade'>"+
        "<strong>Grade record was updated successfully.</strong>"+
        "<button type='button' href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</button>"+
        "</div>");
    var errormess = $("<div class='alert alert-danger alert-dismissable fade'>"+
        "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+
        "<strong>Update failed.</strong> Please contact support."+
        "</div>");
    function showAlert(){
        $(".alert").addClass("in");
    }

    $.post("./processing/edit_record_processing.php", {gradeearned : gradeearned, assignid : assignid, classid : classid}, function (data) {
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
                    location.replace("view_record.php?previd=" + classid);
                }, 2000);
            }
            else{
                $("#messagebox").prepend(errormess);
            }
            $('#editrecordmodal').modal('hide');
            window.setTimeout(function () {
                showAlert();
            }, 50);
    });
});