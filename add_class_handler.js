/**
 * Created by Sean Davis on 1/20/2017.
 */
$("#addclassform").submit(function () {
    var classname = "classname=" + $("#classname").val();
    $.post("add_class_processing.php", classname, function () {
        location.replace("index.php");
    });
    event.preventDefault();
});

//add_class_processing.php