<?
session_start();
if (!$_SESSION['_SITE_']['is_adm'])
{
    header('Location:/admin/');
    exit;
}
header('Content-type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>elFinder 2.0</title>

		<link rel="stylesheet" type="text/css" media="screen" href="/admin/utils/elfinder/css/jquery-ui.css">
		<script type="text/javascript" src="/admin/utils/elfinder/js/jquery.min.js"></script>
		<script type="text/javascript" src="/admin/utils/elfinder/js/jquery-ui.min.js"></script>

		<link rel="stylesheet" type="text/css" media="screen" href="/admin/utils/elfinder/css/elfinder.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/admin/utils/elfinder/css/theme.css">

		<script type="text/javascript" src="/admin/utils/elfinder/js/elfinder.min.js"></script>

		<script type="text/javascript" src="/admin/utils/elfinder/js/i18n/elfinder.ru.js"></script>

		<script type="text/javascript" charset="utf-8">
			$().ready(function() {
				var elf = $('#elfinder').elfinder({
					url : '/admin/utils/elfinder/php/connector.php',
					height: 530,
					lang: 'ru'
				}).elfinder('instance');
			});
		</script>
	</head>
	<body>

		<div id="elfinder"></div>

	</body>
</html>
