$('.hidethis').hide();
$(document).ready(function(){
    var current = $('#classtoggle').val();
    $('#' + current).show();
    $(function() {
        $('#classtoggle').change(function(){
            console.log($('#classtoggle').val());
            $('.hidethis').hide();
            $('#' + $(this).val()).show();
        });
    });
});

