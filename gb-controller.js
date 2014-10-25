api_base = 'https://gb-web.herokuapp.com/api/index.php';
if(localStorage['base'])    api_base = localStorage['base'];

var logged_in = false;
var fb_friends;

var cur_t_id = 0;
var cur_t_ind = 0;
var cur_t_p_url = "";
var cur_t_name = "";
var MAX_CONTENT_LENGTH = 500;

if (window.location.hash && window.location.hash == '#_=_') {
    window.location.hash = '';
}

/* UI - Actions */
function show_gb_success(data) {
    var container = $('#gb_success_container');
    if(data == "") {
        container.html('目前沒有資料');
        return;
    }

    var html = '<div class="pure-u-1-12"></div><div class="pure-u-1 pure-u-md-5-6">';
    var previous_time_head = "";
    data.forEach(function(gb) {
        var time_head = format_time_section(gb['ctime']);
        if(time_head != previous_time_head) {
            html += '<h4>'+time_head+'</h4>';
            previous_time_head = time_head;
        }
        html += '<div class="gb-success-single">';
        html += '<h5><a href="https://www.facebook.com/'+gb['user1']+'" target="_blank">'+gb['gid1_d']['user1_name']+'</a> 跟 <a href="https://www.facebook.com/'+gb['user2']+'" target="_blank">'+gb['gid2_d']['user1_name']+'</a> 在一起了！</h5>';
        html += '<div class="pure-g">';

        html += '<div class="pure-u-1-2"><div class="l-box">';
        html += '<p><b>'+gb['gid1_d']['user1_name']+'</b>: '+gb['gid1_d']['content']+'</p>';
        html += '<p><i><time datetime="'+format_time_comment(gb['gid1_d']['ctime'])+'">'+format_time_comment(gb['gid1_d']['ctime'])+'</time></i></p>';
        html += '</div></div>';

        html += '<div class="pure-u-1-2"><div class="l-box">';
        html += '<p><b>'+gb['gid2_d']['user1_name']+'</b>: '+gb['gid2_d']['content']+'</p>';
        html += '<p><i><time datetime="'+format_time_comment(gb['gid2_d']['ctime'])+'">'+format_time_comment(gb['gid2_d']['ctime'])+'</time></i></p>';
        html += '</div></div>';

        html += '</div></div>';
    });
    html += '</div>';     // pure-u-1-12, 5-6
    container.html(html);
    fillFacebookUsername(data);
}

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
    cur_t_ind = this.selectedIndex;

    if(cur_t_ind == 0) {
        cur_t_id = "";
        cur_t_p_url = "";
        $('#gb-target-img').css('background', 'url(../../img/common/user.png) no-repeat');
    } else {
        cur_t_id    = this.value;
        cur_t_p_url = fb_friends[cur_t_ind-1]['picture']['data']['url'];
        cur_t_name  = fb_friends[cur_t_ind-1]['name'];
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
        data: 'target_user_url='+cur_t_p_url+'&target_user_id='+cur_t_id+'&target_user_name='+cur_t_name+'&content='+content,
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

function api_gb_success() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: api_base + "/gb_success",
        error:
        function(data) {
            network_error(data);
        },
        success:
        function(data) {
            show_gb_success(data['data']);
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

function executeAsync(func) {
    setTimeout(func, 0);
}

function format_time_comment(t) {
    var d = new Date(t + " GMT+0000");
    var hours = d.getHours();
    var minutes = d.getMinutes();
    var ampm = hours >= 12 ? 'pm' : 'am';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0'+minutes : minutes;
    var strTime = hours + ':' + minutes + ' ' + ampm;
    return strTime;
}

function format_time_section(t) {
    var d = new Date(t + " GMT+0000");
    var days = ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"];
    var offset = new Date().getTimezoneOffset();    // timezone
    return (d.getYear()+1900) + "." + (d.getMonth()+1) + "." + d.getDate() + " " + days[d.getDay()];
}

function fillFacebookUsername(data) {
    data.forEach(function(gb) {
        var id1 = gb['user1'], id2 = gb['user2'];
        $.get('https://graph.facebook.com/'+id1, function (r) {
            $('.fb_name_'+id1).text(r.name);
        }, 'jsonp');
        $.get('https://graph.facebook.com/'+id2, function (r) {
            $('.fb_name_'+id2).text(r.name);
        }, 'jsonp');
    });
}

/* Main */
$( document ).ready(function() {
    console.log("Hello Hacker!");
    // to speed up, do they in the same time
    executeAsync(api_gb());
    executeAsync(api_gb_success());
});
