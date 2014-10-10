/* Event Handlers */
$('#gb-start').click(function() {
    $('#init-head-container').hide();
    $('#submit-gb-container').fadeIn('slow');
});

$('#gb-cancel').click(function() {
    $('#submit-gb-container').hide();
    $('#init-head-container').fadeIn('slow');
});

$('#gb-select-friend').on('change', function() {
    var target_id = this.value;
    console.log(target_id);
    if(target_id == "not-selected") {
        $('#gb-target-img').css('background', 'url(../../img/common/user.png) no-repeat');
    } else {
        $('#gb-target-img').css('background', 'url(http://graph.facebook.com/'+target_id+'/picture?type=normal) no-repeat');
    }
});

/* Main */
$( document ).ready(function() {
    console.log("Hello Hacker!");
});
