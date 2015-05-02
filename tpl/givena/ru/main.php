<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
<title><?$APP->loadMetaTitle()?></title>
<meta name="keywords" content="<?=$APP->loadMetaKeywords();?>" >
<meta name="description" content="<?=$APP->loadMetaDescription();?>" >
<meta name='yandex-verification' content='60ba9053b7531a6e' />
<link rel="shortcut icon" href="/favicon.ico" >
<script src="/tpl/givena/js/jquery-1.8.1.min.js" type="text/javascript"></script>

<script type="text/javascript" src="/tpl/givena/js/jquery.ad-gallery.js"></script>
<link rel="stylesheet" type="text/css" href="/tpl/givena/css/jquery.ad-gallery.css">

<script type="text/javascript" src="/tpl/givena/js/pop-up/pop-up.js"></script>
<script type="text/javascript" src="/tpl/givena/js/myselect.js"></script>

<script type="text/javascript" src="/tpl/givena/js/jquery.maskedinput-1.2.2.min.js"></script>

<meta name='wmail-verification' content='8e5bd6f675915bca' />

<link type="text/css" rel="stylesheet" href="/tpl/givena/css/style.css">
<?$APP->loadAdminStyle();?>
</head>

<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.3";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div id="opaco" class="hidden"></div><!-- для pop-up -->
<div id="popup" class="hidden"></div><!-- для pop-up -->
<?$APP->loadAdminPanel();?>
<div id="wrapper">
<div class="head">
		<div class="inn">
			<a href="/"><img src="/tpl/givena/images/logo.png" class="logo" alt="" ></a>
			<div class="head-img">
				<?$APP->loadModule('gallery', 30, 'gallery_short_list.php', 'f_short_catalog(5)')?>
				<div class="clear"></div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="inn">
			<div class="menu-left">
				<div class="phone"><p>8 (499) 504-16-22</p></div>
				<div class="services">
					<a href="/"><img src="/tpl/givena/images/000.gif" class="home" alt="" ></a>
					<a href="/contacts/"><img src="/tpl/givena/images/000.gif" class="letter" alt="" ></a>
					<a href="/sitemap/"><img src="/tpl/givena/images/000.gif" class="mapsite" alt="" ></a>
				</div>

        <?$APP->loadModule('profile', 'form', 'profile/auth_form.php', 'profile/auth_reg.php')?>

				<div class="menu-l">
					<?$APP->loadModule('menu', 'top', 'menu_left.php', 'f_menu_catalog(3)')?>					
				</div>
				
				<?if($_REQUEST['dev']=="seeall"):?>
					<div class="review-l">				
						<?$APP->loadModule('reviews', 513, 'reviews_short_list.php', 'f_short_news(3)');?>						
					</div>
				<?endif?>
				
				<!--<? $url = $_SERVER["REQUEST_URI"]; if ($url == "/") {?>
				<div class="banner">
					<a href="/news/?id=11"><img src="/tpl/givena/images/3.jpg" class="" alt=""></a>
				</div>
				<? }?>-->				
			</div>
			<div class="cont-block">
				<div class="menu-top">
					<?$APP->loadModule('menu', 'top', 'menu_top.php')?>
					<div class="clear"></div>
					<img src="/tpl/givena/images/twirl.gif" class="twirl" alt="" >
				</div>
				<div class="search-basket-top">
					<div class="search poisk">
						<?$APP->loadModule('search', 'simple', 'search/search_form.php', 'search/search_results.php')?>
					</div>
					<div class="basket">
						<div id="basket-block01">
							  <?$APP->loadModule('basket', 'catalog', 'basket/small.php', 'basket/list.php')?>
							</div>

					</div>
				</div>
				<div class="cont-text">
					<?if($_SERVER['REQUEST_URI']=='/'):?>
					<?$APP->loadModule('news', 8, 'news_short_list.php', 'f_short_news(2)');?>
					<?$APP->loadModule('catalog', 6, 'catalog_short_list.php', 'f_recomend_item(6)');?>
					<?else:?>
					<?endif?>
					<?= $CONTENT ?>
					<?$APP->loadAdminBlockAdd();?>
				</div>
				<div class="clear"></div>

				<?
					$url = $_SERVER["REQUEST_URI"];
          if ($url != "/")
            $APP->loadModule('catalog', 6, 'catalog_seen_list_auth.php', 'f_last_seen_item(3)');

					if ($url == "/" || preg_match('~\/profile\/~i',$url))
							//$APP->loadModule('news', 8, 'news_short_list.php', 'f_short_news(2)');

				?>
				<br />
				<br />
				<?if($_SERVER['REQUEST_URI']=='/'):?>
				
				<?else:?>
				<?$APP->loadModule('catalog', 6, 'catalog_short_list.php', 'f_recomend_item(3)');?>
				<?endif?>
				
				<div class="vkontakte">
					<script type="text/javascript" src="//vk.com/js/api/openapi.js?116"></script>
					<!-- VK Widget -->
					<div id="vk_groups"></div>
					<script type="text/javascript">
					VK.Widgets.Group("vk_groups", {mode: 0, width: "350", height: "225", color1: 'FFFFFF', color2: '0a5100', color3: 'c7e28b'}, 51405379);
					</script>
				</div>
				<div class="facebook">
					<div class="fb-page" data-href="https://www.facebook.com/pages/Givena/1417370548564092" data-width="350" data-height="300" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"></div>
				</div>

			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="vcard">
	<div class="footer">
		<div class="inn">
			<div class="menu-left">

				<p class="adress"><strong class="adr"><span class="locality">Москва</span>,<br>
				<span class="street-address">Открытое ш. д.14д, стр.7, оф. 5</span></strong><br>
				<span class="tel">8 (499) 504-16-22</span><br>
				<!--LiveInternet counter--><script type="text/javascript"><!--
document.write("<a href='http://www.liveinternet.ru/click' "+
"target=_blank><img src='//counter.yadro.ru/hit?t26.15;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random()+
"' alt='' title='LiveInternet: показано число посетителей за"+
" сегодня' "+
"border='0' width='88' height='15' style='margin-top: 3px;'><\/a>")
//--></script><!--/LiveInternet--></p>

			</div>
			<div class="cont-block">
				<img src="/tpl/givena/images/twirl.gif" class="twirl" alt="" />
				<div class="menu-footer">
					<?$APP->loadModule('menu', 'bottom', 'menu_bottom.php')?>
				</div>
				<p class="copy">© 2012 <span class="fn org">Живена</span></p>
				<div class="social">
					<a href="http://vk.com/givena" class="vk" target="_blank"></a>
					<a href="https://www.facebook.com/pages/Givena/1417370548564092" class="fb" target="_blank"></a>
					<a href="http://www.odnoklassniki.ru/group/52025149489230" class="od" target="_blank"></a>
				</div>
				<div class="creater">
					<?if($_SERVER['REQUEST_URI']=='/'):?>
					<a href="http://www.creater.ru/"><img title="creater.ru" alt="Creater - создание и продвижение сайтов" src="/tpl/givena/images/logo-creater-new.png" /></a>

					<?else:?>
					<noindex><a href="http://www.creater.ru/" rel="nofollow"><img title="creater.ru" alt="Creater - создание и продвижение сайтов" src="/tpl/givena/images/logo-creater-new.png" /></a></noindex>
					<?endif?>
				</div>
			</div>
		</div>
	</div>
	</div>
</div>
<!-- для pop-up -->
<div id="popup_reference1" class="hidden">
    <div class="reference" style="width: 920px;">
		<a class="close" onclick="closePopupFrontEnd(); return false;"><img src="/tpl/givena/images/basket_close_but.png" alt="Закрыть" ></a>
		<div class="inner_reference"></div>
    </div>
</div>
<!-- для pop-up -->
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter20442349 = new Ya.Metrika({id:20442349,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/20442349" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

</body>
</html>