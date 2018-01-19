<?php

include 'db/connection.php';
include 'db/query.php';

session_start();
if (isset($_REQUEST['DOMAIN'])) {
	$_SESSION['DOMAIN'] = $_REQUEST['DOMAIN'];
} else {
	$_SESSION['DOMAIN'] = 'auspex.bitrix24.ua';
}

$domain = $_SESSION['DOMAIN'];


if ($domain) {
	$bot = $conn->query(selectRow('bots', '*', 'portal="' . $domain . '"'))->fetch_assoc(); 
	//die(selectRow('users', 'last_activity, count(*) as count', 'portal="' . $domain . '"', 'last_activity DESC'));
	$time = $conn->query(selectRow('users', 'max(max_time) as max_time, min(min_time) as min_time, avg(avg_time) as avg_time', 'portal="' . $domain . '"'))->fetch_assoc(); 
	$users = $conn->query(selectRow('users', 'count(*) as users_count, sum(send_count) as messages_count, sum(dialog_count) as dialogs_count', 'portal="' . $domain . '"'))->fetch_assoc(); 
	file_put_contents('log_time.txt', print_r($time, true), FILE_APPEND);
} 

if (empty($bot)) {
	die('You need to install / reinstall chat-bot app');
}

if (isset($_POST['bot'])) {
	$image = isset($_FILES['avatar']['name']) ? $_FILES['avatar']['name'] : '';

	if (!$_FILES['avatar']['error']) {
		if (is_uploaded_file($_FILES['avatar']['tmp_name'])) {
			if (move_uploaded_file($_FILES['avatar']['tmp_name'], "../bot/".$image)) {

			} 
		}
	}

	$sql = update(
		'bots', 
		  "name='". sprintf($conn->real_escape_string($_POST['bot']['name'])) ."', "
		. "color='". sprintf($conn->real_escape_string($_POST['bot']['color'])) ."', "
		. "email='" . sprintf($conn->real_escape_string($_POST['bot']['email'])) . "', "
		. "b_day='" . sprintf($conn->real_escape_string($_POST['bot']['b_day'])) . "',"
		. "image='" . sprintf($conn->real_escape_string($image)) . "',"
		. "www='" . sprintf($conn->real_escape_string($_POST['bot']['www'])) . "'",
		'portal=' . $domain
	);
	if ($conn->query($sql) !== true) {
		file_put_contents('log_query.txt', 'Bots:', FILE_APPEND);
		file_put_contents('log_query.txt', $conn->error, FILE_APPEND);
	}
}

?>

<!DOCTYPE html>
<!--[if IE 7 ]><html class="ie7"> <![endif]-->
<!--[if IE 8 ]><html class="ie8"> <![endif]-->
<!--[if IE 9 ]><html class="ie9"> <![endif]-->
<!--[if (gte IE 10)|!(IE)]><!--><html lang="en"> <!--<![endif]-->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Тестовий 24</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=1365">
    <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />

    <link rel="stylesheet" href="style/css/main.css">
    <link rel="stylesheet" href="style/css/vendor.css">

    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <![endif]-->
</head>
<body>
<div class="wrapper">
    <header class="header"></header>
    <aside class="left__menu"></aside>
    <section class="settings">
        <nav class="top__nav">
            <div class="company__info">
                <a href="#" class="phone">+38 032 242 00 12</a>
                <a href="#" class="logo">
                    <img src="style/img/logo.svg" alt="logo">
                </a>
            </div>
        </nav>
        <div class="settings__header">
            <div class="user__info">
                <div class="user__info-photo">
                    <img src="style/img/user-pic.png" alt="pic">
                </div>
                <input class="user__info-name" maxlength="40" type="text" placeholder="Имя Фамилия">
            </div>
            <div class="settings__menu">
                <ul>
                    <li><a href="blocks.php"><?php echo _("Блоки"); ?></a></li>
                    <li><a href="AI.php"><?php echo _("Настройки AI"); ?></a></li>
                    <li><a href="menu.php"><?php echo _("Меню"); ?></a></li>
                </ul>
            </div>
        </div>
        <div class="settings__header-change">
            <div class="user__info">
                <div class="user__info-photo">
                    <img src="style/img/user-pic.png" alt="pic">
                    <a href="#" class="download__img"><?php echo _("Загрузить изображение"); ?></a>
                </div>
                <input class="user__info-name" maxlength="40" type="text" placeholder="Имя Фамилия">
            </div>
            <div class="btn__wrap btn__wrap-mod">
                <button type="button" class="btn btn-save"><?php echo _("Сохранить"); ?></button>
                <button type="button" class="btn btn-cansel"><?php echo _("Отменить"); ?></button>
            </div>
        </div>
        <div class="settings__content">
            <div class="tab__box">
                <ul class="tab__nav">
                    <li><a href="#t-block"><?php echo _("Блоки"); ?></a></li>
                    <li><a href="#t-set"><?php echo _("Настройки"); ?></a></li>
                    <li><a href="#t-menu"><?php echo _("Меню"); ?></a></li>
                    <li><a href="#t-support"><?php echo _("Техподдержка"); ?></a></li>
                </ul>
                <div class="tab__panels">
                    <div id="t-block" class="t-block"><?php echo _("первая вкладка"); ?></div>
                    <div id="t-set" class="t-set">
                        <div class="feature">
                            <div class="feature__heading"><?php echo _("Колличество"); ?></div>
							<div class="feature__field">
                                <span class="item__left"><?php echo _("Пользователи"); ?></span>
                                <span class="item__right"><?php echo $users['users_count']; ?></span>
                            </div>
                            <div class="feature__field">
                                <span class="item__left"><?php echo _("Диалоги"); ?></span>
                                <span class="item__right"><?php echo $users['dialogs_count']; ?></span>
                            </div>
                            <div class="feature__field">
                                <span class="item__left"><?php echo _("Cообщения"); ?></span>
                                <span class="item__right"><?php echo $users['messages_count']; ?></span>
                            </div>
                        </div>
						<div class="feature">
                            <div class="feature__heading"><?php echo _("Время"); ?></div>
                            <div class="feature__field">
                                <span class="item__left"><?php echo _("Среднее"); ?></span>
                                <span class="item__right"><?php echo gmdate("H:i:s", (int)$time['avg_time']); ?></span>
                            </div>
							<div class="feature__field">
                                <span class="item__left"><?php echo _("Максимальное"); ?></span>
                                <span class="item__right"><?php echo gmdate("H:i:s", $time['max_time']); ?></span>
                            </div>
							<div class="feature__field">
                                <span class="item__left"><?php echo _("Минимальное"); ?></span>
                                <span class="item__right"><?php echo gmdate("H:i:s", $time['min_time']); ?></span>
                            </div>
                        </div>
                    </div>
                    <div id="t-menu"><?php echo _("третья вкладка"); ?></div>
                    <div id="t-support"><?php echo _("четвертая вкладка"); ?></div>
                </div>
            </div>
        </div>
    </section>

</div>
    <script defer src="style/js/vendor.js"></script>
    <script defer src="style/js/script.js"></script>
</body>
</html>