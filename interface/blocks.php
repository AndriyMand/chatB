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
	$result = $conn->query(selectAll('blocks', '*', 'portal="' . $domain . '"'));
}

$groups  = [];
$default = [];
if (isset($result->num_rows) && $result->num_rows > 0) { 
	while($block = $result->fetch_assoc()) { 
		if (!empty($block['default_type'])) {
			$default[$block['default_type']] = $block;
		} else {
			$groups[$block['group']][] = $block;
		}
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
    <title>Тестовий 24 / Блоки</title>
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
                    <li><a class="active" href="blocks.php">Блоки</a></li>
                    <li><a href="AI.php">Настройки AI</a></li>
                    <li><a href="menu.php">Меню</a></li>
                </ul>
            </div>
        </div>
        <div class="settings__content">
            <div class="mandatory__settings">
                <div class="left__set">
                    <div class="left__set-title">Настройка обязательных блоков <i class="fa fa-times" aria-hidden="true"></i></div>
                    <a href="#" class="btn btn-message active">Приветственное сообщение</a>
                    <a href="#" class="btn btn-message">Сообщение по умолчанию</a>
					
					<?php	
						foreach ($groups as $group => $blocks) {
							?>
							<div class="left__set-line"></div>
							<div class="left__set-heading"><?php echo $group;?> <i class="fa fa-times" aria-hidden="true"></i></div>
							<div class="base__group">
								<?php
									foreach ($blocks as $block) {
									?>
										<a href="javascript:void(0);" class="btn base__group-block"><?php echo $block['header'];?> <i class="fa fa-times" aria-hidden="true"></i></a>
									<?php
									}	
								?>
								<a href="javascript:void(0);" class="btn base__group-add-block"><i class="fa fa-plus" aria-hidden="true"></i>Добавить блок</a>
							</div>
							<?php
						}			
					?>
					
                    <div class="left__set-line"></div>
                    <a href="javascript:void(0);" class="btn base__group-add addgroup"><i class="fa fa-plus" aria-hidden="true"></i>Добавить группу</a>
                </div>
                <div class="right__set">
                    <div class="right__set-title">Приветственное сообщение</div>
                    <div class="right__set-field">Оглавление для сообщения</div>
                    <div class="right__set-field high">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Beatae dolor impedit nemo omnis quas. Nihil.</div>
                    <div class="right__set-field"><span class="icon icon-url"></span><a href="#">https://crmsolutions.bitrix24.ua/</a></div>
                    <div class="right__set-field">
                        <img class="field__pic" src="style/img/image.png" alt="pic">

                        <div class="btn__group">
                            <a href="#" class="btn btn__group-block">Название кнопки 1 <i class="fa fa-times" aria-hidden="true"></i></a>
                            <a href="#" class="btn btn__group-block">Кнопка 2 <i class="fa fa-times" aria-hidden="true"></i></a>
                            <a href="#" class="btn btn__group-block">Название кнопки 3 <i class="fa fa-times" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <div class="add__setting">
                        <a href="#" class="add"><span class="icon icon-pic"></span>Добавить изображение</a>
                        <a href="#" class="add"><span class="icon icon-text"></span>Добавить текст</a>
                        <a href="#" class="add"><span class="icon icon-url"></span>Добавить ссылку</a>
                        <a href="#" class="add"><i class="fa fa-plus" aria-hidden="true"></i>Добавить кнопку</a>
                    </div>
                </div>
                <div class="btn__wrap">
                    <button type="button" class="btn btn-save">Сохранить</button>
                    <button type="button" class="btn btn-cansel">Отменить</button>
                </div>
            </div>
        </div>
    </section>

</div>
<script defer src="style/js/vendor.js"></script>
<script defer src="style/js/script.js"></script>
<script defer src="style/js/tools.js"></script>
</body>
</html>