$(document).ready(function(){
    var current = $('#classtoggle').val();
    $('#' + current).removeClass('hidethis');
    $(function() {
        $('#classtoggle').change(function(){
            console.log($('#classtoggle').val());
            $('.hideable').addClass('hidethis');
            $('#' + $(this).val()).removeClass('hidethis');
        });
    });
});

