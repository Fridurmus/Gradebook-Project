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
        "<strong>Class was updated successfully.</strong> " +
        "<span><a href='./index.php' class='alert-link'>Click here to return to the class list.</a></span>"+
        "<button type='button' href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</button>"+
    "</div>");
    var errormess = $("<div class='alert alert-danger alert-dismissable fade'>"+
        "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+
        "<strong>Update failed.</strong> Please contact support."+
        "</div>");
    function showAlert(){
        $(".alert").addClass("in");
    }

    $.post("./processing/edit_class_processing.php", {
        classname : classname,
        classid : classid,
        studentids :
        studentids},
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
            }
            else{
                $("#messagebox").prepend(errormess);
            }
            window.setTimeout(function () {
                showAlert();
            }, 50);
       });
});