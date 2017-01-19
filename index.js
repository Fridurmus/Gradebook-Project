/**
 * Created by Sean Davis on 1/18/2017.
 */
$(document).ready(function(){
    $('#myTabs a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    })
})