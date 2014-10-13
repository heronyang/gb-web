api_base = 'http://gb-web.herokuapp.com/';
if(localStorage['base'])    api_base = localStorage['base'];

var logged_in = false;
var fb_friends;

var cur_t_id = 0;
var cur_t_p_url = "";
var MAX_CONTENT_LENGTH = 500;

if (window.location.hash && window.location.hash == '#_=_') {
    window.location.hash = '';
}

/* UI - Actions */
function show_logged_in_layout() {
    loading_start();
    $('#init-head-container').hide();
    $('#submit-gb-container').fadeIn('slow');
}

function show_fb_friends() {
    var sel = $('#gb-select-friend');
    fb_friends.forEach(function(i) {
        sel.append($("<option></option>")
            .attr("value", i['id'])
            .text(i['name']));
    });
}

function load_old_gb(data) {
    console.log(data);
    $('#gb-submit').html('更新');
    $.get('https://graph.facebook.com/'+data['user2'], function (r) {
        $('#extra-info').show();
        $('#last-gb-name').html(r.name);
    }, 'jsonp');
}

function loading_start() {
    $('.spinner').show();
}

function loading_end() {
    $('.spinner').hide();
}

/* UI - Event Handlers */
$('#gb-start').click(function() {
    api_login();
});

$('#gb-cancel').click(function() {
    $('#submit-gb-container').hide();
    $('#init-head-container').fadeIn('slow');
    api_logout();
});

$('#gb-submit').click(function() {
    var content = $('#content').val();
    if( content.length>MAX_CONTENT_LENGTH ) {
        alert('字數請在'+MAX_CONTENT_LENGTH+'以內！');
        return;
    } else if( content.length<=0 ) {
        alert('請填寫告白內容');
        return;
    } else if( $('#gb-select-friend').selectedIndex==0 ) {
        alert('請選擇告白對象');
        return;
    } else if( !$('#agree-policy').is(":checked") ) {
        alert('請同意條款後繼續');
        return;
    }
    loading_start();
    api_gb_post(content);
});

$('#gb-select-friend').on('change', function() {
    var cur_t_ind = this.selectedIndex;

    if(cur_t_ind == 0) {
        cur_t_id = "";
        cur_t_p_url = "";
        $('#gb-target-img').css('background', 'url(../../img/common/user.png) no-repeat');
    } else {
        cur_t_id = this.value;
        cur_t_p_url = fb_friends[cur_t_ind-1]['picture']['data']['url'];
        $('#gb-target-img').css('background', 'url('+cur_t_p_url+') no-repeat');
    }
});

/* APIs - Login */
function api_login() {
    console.log(api_base + '/login');
    document.location.href = api_base + '/login';
}

function api_logout() {
    $.ajax({
        type: "GET",
        dataType: "text",
        url: api_base + "/logout",
        xhrFields: {
            withCredentials: true
        },
        error:
        function(data) {
            network_error(data);
        },
        success:
        function(data) {
            console.log("[logout] success");
        }
    });
}

/* APIs - GB */
var da;
function api_gb() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: api_base + "/gb",
        xhrFields: {
            withCredentials: true
        },
        error:
        function(data) {
            if( data.status==403 ) {
                logged_in = false;
                // do nothing
                loading_end();
            } else {
                network_error(data);
            }
        },
        success:
        function(data) {
            logged_in = true;
            if(data['data']) load_old_gb(data['data']);
            show_logged_in_layout();
            api_fb_friends();
        }
    });
}

function api_gb_post(content) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: api_base + "/gb",
        data: 'target_user='+cur_t_p_url+'&content='+content,
        xhrFields: {
            withCredentials: true
        },
        error:
        function(data) {
            loading_end();
            if( data.status==403 ) {
                logged_in = false;
                alert('未成功，請再試試');
                api_login();
            } else {
                network_error(data);
                alert('網路連線問題，請稍後試試');
            }
        },
        success:
        function(data) {
            loading_end();
            alert('告白記錄建立成功！請持續關注放榜訊息！');
            $('#submit-gb-container').hide();
            $('#init-head-container').fadeIn('slow');
            api_logout();
        }
    });
}

/* APIs - Other */
function api_fb_friends() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: api_base + "/fb_friends",
        xhrFields: {
            withCredentials: true
        },
        error:
        function(data) {
            network_error(data);
        },
        success:
        function(data) {
            fb_friends = data['data'];
            sort_fb_friends();
            show_fb_friends();
            loading_end();
        }
    });
}

/* Other */
function network_error(data) {
    console.log("network error");
    console.log(data);
}

function sort_fb_friends() {
    fb_friends.sort(function(a, b) {
        var a_name = a['name'];
        var b_name = b['name'];
        return ( (a_name<b_name) ? -1 : ( (a_name>b_name) ? 1 : 0) );
    });
}

/* Main */
$( document ).ready(function() {
    console.log("Hello Hacker!");
    api_gb();
});
