<?php

$title = '告白神器';
$description = '向一位FB好友告白，只有他也向你告白時才會被知道！';

?>

<!doctype html>
<html lang="en">
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no">
        <meta name="description" content="<?php echo $description; ?>">
        <title><?php echo $title; ?></title>

        <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/pure-min.css">

        <!--[if lte IE 8]>
        <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/grids-responsive-old-ie-min.css">
        <![endif]-->
        <!--[if gt IE 8]><!-->
        <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/grids-responsive-min.css">
        <!--<![endif]-->

        <!--[if lte IE 8]>
        <link rel="stylesheet" href="css/layouts/marketing-old-ie.css">
        <![endif]-->
        <!--[if gt IE 8]><!-->
        <link rel="stylesheet" href="css/layouts/marketing.css">
        <!--<![endif]-->

        <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
        <link rel="stylesheet" href="css/layouts/custom.css">

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

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
                <p id="extra-info" class="hidden">上次與<span id="last-gb-name"></span>的告白記錄將會再更新後失效</p>
                <div class="checkbox">
                    <label><input id="agree-policy" type="checkbox"/>同意使用條款與隱私條款</label>
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
                <div class="pure-g">
                    <div class="pure-u-1-12"></div>
                    <div class="pure-u-1 pure-u-md-5-6">
                        <hr/>
                        <h4>2014.04.21 星期日</h4>
                        <div class="gb-success-single">
                            <h5><a href="http://fb.com/apple" target="_blank">周杰倫</a> 跟 <a href="http://fb.com/banana" target="_blank">昆凌</a> 在一起了！</h5>
                            <div class="pure-g">
                                <div class="pure-u-1-2">
                                    <div class="l-box">
                                        <p><b>周杰倫</b>: 有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!</p>
                                        <p><i><time datetime="2001-05-15 19:00">13:15</time></i></p>
                                    </div>
                                </div>
                                <div class="pure-u-1-2">
                                    <div class="l-box">
                                        <p><b>昆凌</b>: 有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!</p>
                                        <p><i><time datetime="2001-05-15 19:00">13:15</time></i></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="gb-success-single">
                            <h5><a href="http://fb.com/apple" target="_blank">周杰倫</a> 跟 <a href="http://fb.com/banana" target="_blank">昆凌</a> 在一起了！</h5>
                            <div class="pure-g">
                                <div class="pure-u-1-2">
                                    <div class="l-box">
                                        <p><b>周杰倫</b>: 有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!</p>
                                        <p><i><time datetime="2001-05-15 19:00">13:15</time></i></p>
                                    </div>
                                </div>
                                <div class="pure-u-1-2">
                                    <div class="l-box">
                                        <p><b>昆凌</b>: 有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!</p>
                                        <p><i><time datetime="2001-05-15 19:00">13:15</time></i></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="gb-success-single">
                            <h5><a href="http://fb.com/apple" target="_blank">周杰倫</a> 跟 <a href="http://fb.com/banana" target="_blank">昆凌</a> 在一起了！</h5>
                            <div class="pure-g">
                                <div class="pure-u-1-2">
                                    <div class="l-box">
                                        <p><b>周杰倫</b>: 有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!</p>
                                        <p><i><time datetime="2001-05-15 19:00">13:15</time></i></p>
                                    </div>
                                </div>
                                <div class="pure-u-1-2">
                                    <div class="l-box">
                                        <p><b>昆凌</b>: 有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!</p>
                                        <p><i><time datetime="2001-05-15 19:00">13:15</time></i></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pure-u-1-12"></div>
                </div>


                <div class="pure-g">
                    <div class="pure-u-1-12"></div>
                    <div class="pure-u-1 pure-u-md-5-6">
                        <hr/>
                        <h4>2014.04.20 星期六</h4>
                        <div class="gb-success-single">
                            <h5><a href="http://fb.com/apple" target="_blank">周杰倫</a> 跟 <a href="http://fb.com/banana" target="_blank">昆凌</a> 在一起了！</h5>
                            <div class="pure-g">
                                <div class="pure-u-1-2">
                                    <div class="l-box">
                                        <p><b>周杰倫</b>: 有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!</p>
                                        <p><i><time datetime="2001-05-15 19:00">13:15</time></i></p>
                                    </div>
                                </div>
                                <div class="pure-u-1-2">
                                    <div class="l-box">
                                        <p><b>昆凌</b>: 有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!</p>
                                        <p><i><time datetime="2001-05-15 19:00">13:15</time></i></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="gb-success-single">
                            <h5><a href="http://fb.com/apple" target="_blank">周杰倫</a> 跟 <a href="http://fb.com/banana" target="_blank">昆凌</a> 在一起了！</h5>
                            <div class="pure-g">
                                <div class="pure-u-1-2">
                                    <div class="l-box">
                                        <p><b>周杰倫</b>: 有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!</p>
                                        <p><i><time datetime="2001-05-15 19:00">13:15</time></i></p>
                                    </div>
                                </div>
                                <div class="pure-u-1-2">
                                    <div class="l-box">
                                        <p><b>昆凌</b>: 有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!</p>
                                        <p><i><time datetime="2001-05-15 19:00">13:15</time></i></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="gb-success-single">
                            <h5><a href="http://fb.com/apple" target="_blank">周杰倫</a> 跟 <a href="http://fb.com/banana" target="_blank">昆凌</a> 在一起了！</h5>
                            <div class="pure-g">
                                <div class="pure-u-1-2">
                                    <div class="l-box">
                                        <p><b>周杰倫</b>: 有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!</p>
                                        <p><i><time datetime="2001-05-15 19:00">13:15</time></i></p>
                                    </div>
                                </div>
                                <div class="pure-u-1-2">
                                    <div class="l-box">
                                        <p><b>昆凌</b>: 有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!有你真好!</p>
                                        <p><i><time datetime="2001-05-15 19:00">13:15</time></i></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pure-u-1-12"></div>
                </div>

            </div>

            <div class="ribbon l-box-lrg pure-g">
                <div class="l-box-lrg is-center pure-u-1 pure-u-md-1-2 pure-u-lg-2-5">
                    <img class="pure-img-responsive pull-right" alt="File Icons" width="128" src="img/common/anger.png">
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
