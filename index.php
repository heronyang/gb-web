<?php

$title = '告白神器';
$description = '向一位FB好友告白，只有他也向你告白時才會被知道！';

$local_config_filename = 'api/local_config.php';
if(file_exists($local_config_filename)) {
    // debug environment settings
} else {
    // deployee environment settings
    if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == "http") {
        if(!headers_sent()) {
            header("Status: 301 Moved Permanently");
            header(sprintf(
                'Location: https://%s%s',
                $_SERVER['HTTP_HOST'],
                $_SERVER['REQUEST_URI']
            ));
            exit();
        }
    }
}
?>

<!doctype html>
<html lang="en">
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no">
        <meta name="description" content="<?php echo $description; ?>">
        <title><?php echo $title; ?></title>

        <link rel="stylesheet" href="css/pure-min.css">

        <!--[if lte IE 8]>
        <link rel="stylesheet" href="css/grids-responsive-old-ie-min.css">
        <![endif]-->
        <!--[if gt IE 8]><!-->
        <link rel="stylesheet" href="css/grids-responsive-min.css">
        <!--<![endif]-->

        <!--[if lte IE 8]>
        <link rel="stylesheet" href="css/marketing-old-ie.css">
        <![endif]-->
        <!--[if gt IE 8]><!-->
        <link rel="stylesheet" href="css/marketing.css">
        <!--<![endif]-->

        <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
        <link rel="stylesheet" href="css/custom.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    </head>
    <body>
        
        <div id="fb-root"></div>

        <div class="header">
            <div class="home-menu pure-menu pure-menu-open pure-menu-horizontal pure-menu-fixed">
                <a class="pure-menu-heading" href=""><?php echo $title; ?></a>
            </div>
        </div>
    
        <div class="spinner"></div>

        <div class="splash-container">
            <div id="init-head-container" class="splash">
                <h1 class="splash-head is-center"><i class="fa fa-trophy head-icon"></i>&nbsp;告白神器&nbsp;<i class="fa fa-trophy head-icon"></i></h1>

                <p class="splash-subhead is-center">向一位FB好友告白<br />只有他也向你告白時才會被知道！</p>
                <p class="is-center">
                    <button id="gb-start" class="button-error pure-button button-xlarge">衝一發</button>
                </p>
                <div class="fb-like" data-share="true" data-width="450" data-show-faces="true"></div>
            </div>
            <div id="submit-gb-container" class="splash hidden">
                <div class="pure-g">
                    <div class="pure-u-1-2">
                        <select name="gb-target" id="gb-select-friend">
                            <option value="not-selected">告白對象..</option>
                        </select>
                    </div>
                    <div class="pure-u-1-2">
                        <div id="gb-target-img" class="circular pull-right"></div>
                    </div>

                </div>

                <textarea id="content" rows="10" placeholder="我想說的話..."></textarea>
                <div hidden>
                    <p id="extra-info" class="hidden">上次與<span id="last-gb-name"></span>的告白記錄將會再更新後失效</p>
                </div>
                <div class="checkbox">
                    <label><input id="agree-policy" type="checkbox"/>同意<a style="color:#000"; href="term.html" target="_blank">使用條款</a>與<a style="color:#000"; href="privacy.html" target="_blank">隱私條款</a></label>
                </div>
                <div class="pure-g">
                    <div class="pure-u-1-2">
                        <button id="gb-cancel" class="button-secondary pure-button button-xlarge">取消</button>
                    </div>
                    <div class="pure-u-1-2">
                        <button id="gb-submit" class="button-error pure-button button-xlarge">送出</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-wrapper">
            <div class="content">
                <div class="is-center">
                    <h2 class="content-head">告白成功名單</h2>
                    <h3>每日晚間 09:09 放榜</h3>
                </div>
            </div>
            <div id="gb_success_container" class="content"></div>

            <div class="ribbon l-box-lrg pure-g">
                <div class="l-box-lrg is-center pure-u-1 pure-u-md-1-2 pure-u-lg-2-5">
                    <img class="pure-img-responsive pull-right" alt="File Icons" width="128" src="img/anger.png">
                </div>
                <div class="pure-u-1 pure-u-md-1-2 pure-u-lg-3-5">

                    <h2 class="content-head content-head-ribbon">你想後悔嗎？</h2>
                    <blockquote class="content-quote">五月天：<br/>「有些事現在不做一輩子都不會做了」</blockquote>

                </div>
            </div>

            <div class="footer l-box is-center">
                <p><a href="http://heron.me/" target="_blank">Heron's Production @ NCTU.CS</a></p>
            </div>

        </div>

        <script>
            window.fbAsyncInit = function() {
                FB.init({
                    appId      : '1479681152310531',
                    xfbml      : true,
                    version    : 'v2.1'
                });
            };

            (function(d, s, id){
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {return;}
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
        <script src="gb-controller.js"></script>

    </body>
</html>
