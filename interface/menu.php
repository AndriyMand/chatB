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
	$result = $conn->query(selectAll('menu', '*', 'portal="' . $domain . '"'));
	$hello_message = $conn->query(selectRow('menu_text', 'text', 'portal="' . $domain . '"'))->fetch_assoc();
}

$menu = [];
if (isset($result->num_rows) && $result->num_rows > 0) { 
	while($row = $result->fetch_assoc()) { 
		$menu[$row['level']][] = $row;
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
    <title>Тестовий 24 / Меню</title>
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
        </nav><!--end-top-nav-->

        <div class="settings__header">
            <div class="user__info">
                <div class="user__info-photo">
                    <img src="style/img/user-pic.png" alt="pic">
                </div>
                <input class="user__info-name" maxlength="40" type="text" placeholder="Имя Фамилия">
            </div><!--end-user-info-->
            <div class="settings__menu">
                <ul>
                    <li><a href="blocks.php">Блоки</a></li>
                    <li><a href="AI.php">Настройки AI</a></li>
                    <li><a class="active" href="menu.php">Меню</a></li>
                </ul>
            </div><!--end-settings-menu-->
        </div><!--end-settings-header-->

        <div class="settings__content">

            <div class="message">
                <div class="message__title">Приветственное сообщение</div>
                <div class="message__field">
                    <input class="text" value="<?php echo $hello_message['text']; ?>"
                    <a href="#" class="add__pic"><span class="icon icon-pic"></span>Добавить изображение</a>
                </div>
                <button type="button" class="btn btn-save">Сохранить</button>
                <button type="button" class="btn btn-cansel">Отменить</button>
            </div><!--end-message-->

            <div class="menu__items">
                <div class="menu__items-title">Настройка пунктов меню <i class="fa fa-times" aria-hidden="true"></i></div>
                <div class="items">
                    <ul class="items__nav">
                        <li><a href="#items1">Первый пункт <i class="fa fa-times" aria-hidden="true"></i></a></li>
                        <li><a href="#items2">Название второго пункта <i class="fa fa-times" aria-hidden="true"></i></a></li>
                        <li><a href="#items3">Третий пункт <i class="fa fa-times" aria-hidden="true"></i></a></li>
                        <li><a class="add__items" href="#">+ Добавить</a></li>
                    </ul>
                    <div class="items__panels">
                        <div id="items1" class="setting__first-item">
                            <div class="setting__first-item-title">Настройка первого пункта</div>
                            <input type="text" class="setting__first-item-input" placeholder="Название первого пункта">
                            <ul class="link__wrap">
                                <li><a href="#" class="items__link items__link-first"><span class="icon icon-url"></span>Ссылка</a>
                                    <ul class="drop">
                                        <li>
                                            <input type="text" placeholder="https://crmsolutions.bitrix24.ua/">
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="#" class="items__link items__link-second"><i class="fa fa-plus" aria-hidden="true"></i>Блок</a>
                                    <ul class="drop drop__mod">
                                        <li class="sel">Название блока</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                    </ul>
                                </li>
                            </ul>
                            <ul class="link__tags">
                                <li class="active">Пункт</li>
                                <li>Подпункт</li>
                            </ul>
                        </div>
                        <div id="items2" class="setting__second-item">
                            <div class="setting__second-item-title">Настройка второго пункта</div>
                            <input type="text" class="setting__second-item-input" placeholder="Название второго пункта">
                            <ul class="link__wrap">
                                <li><a href="#" class="items__link items__link-first"><span class="icon icon-url"></span>Ссылка</a>
                                    <ul class="drop">
                                        <li>
                                            <input type="text" placeholder="https://crmsolutions.bitrix24.ua/">
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="#" class="items__link items__link-second"><i class="fa fa-plus" aria-hidden="true"></i>Блок</a>
                                    <ul class="drop drop__mod">
                                        <li class="sel">Название блока</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                    </ul>
                                </li>
                            </ul>
                            <ul class="link__tags">
                                <li class="active">Пункт</li>
                                <li>Подпункт</li>
                            </ul>
                        </div>
                        <div id="items3" class="setting__third-item">
                            <div class="setting__third-item-title">Настройка третьего пункта</div>
                            <input type="text" class="setting__third-item-input" placeholder="Название третьего пункта">
                            <ul class="link__wrap">
                                <li><a href="#" class="items__link items__link-first"><span class="icon icon-url"></span>Ссылка</a>
                                    <ul class="drop">
                                        <li>
                                            <input type="text" placeholder="https://crmsolutions.bitrix24.ua/">
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="#" class="items__link items__link-second"><i class="fa fa-plus" aria-hidden="true"></i>Блок</a>
                                    <ul class="drop drop__mod">
                                        <li class="sel">Название блока</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                    </ul>
                                </li>
                            </ul>
                            <ul class="link__tags">
                                <li class="active">Пункт</li>
                                <li>Подпункт</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div><!--end-menu-items-->

            <div class="menu__sub-items">
                <div class="menu__sub-items-title">Подпункт меню 1 <i class="fa fa-times" aria-hidden="true"></i></div>
                <div class="sub-items">
                    <ul class="sub-items__nav">
                        <li><a href="#sub-items1">Первый подпункт <i class="fa fa-times" aria-hidden="true"></i></a></li>
                        <li><a href="#sub-items2">Название второго подпункта <i class="fa fa-times" aria-hidden="true"></i></a></li>
                        <li><a href="#sub-items3">Третий подпункт <i class="fa fa-times" aria-hidden="true"></i></a></li>
                        <li><a class="add__items" href="#">+ Добавить</a></li>
                    </ul>
                    <div class="sub-items__panels">
                        <div id="sub-items1" class="setting__first-sub-item">
                            <div class="setting__first-sub-item-title">Настройка подпункта</div>
                            <input type="text" class="setting__first-sub-item-input" placeholder="Название первого подпункта">
                            <ul class="link__wrap">
                                <li><a href="#" class="items__link items__link-first"><span class="icon icon-url"></span>Ссылка</a>
                                    <ul class="drop">
                                        <li>
                                            <input type="text" placeholder="https://crmsolutions.bitrix24.ua/">
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="#" class="items__link items__link-second"><i class="fa fa-plus" aria-hidden="true"></i>Блок</a>
                                    <ul class="drop drop__mod">
                                        <li class="sel">Название блока</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                    </ul>
                                </li>
                            </ul>
                            <ul class="link__tags">
                                <li>Пункт</li>
                                <li class="active">Подпункт</li>
                            </ul>
                        </div>
                        <div id="sub-items2" class="setting__second-sub-item">
                            <div class="setting__second-sub-item-title">Настройка подпункта</div>
                            <input type="text" class="setting__second-sub-item-input" placeholder="Название второго подпункта">
                            <ul class="link__wrap">
                                <li><a href="#" class="items__link items__link-first"><span class="icon icon-url"></span>Ссылка</a>
                                    <ul class="drop">
                                        <li>
                                            <input type="text" placeholder="https://crmsolutions.bitrix24.ua/">
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="#" class="items__link items__link-second"><i class="fa fa-plus" aria-hidden="true"></i>Блок</a>
                                    <ul class="drop drop__mod">
                                        <li class="sel">Название блока</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                    </ul>
                                </li>
                            </ul>
                            <ul class="link__tags">
                                <li>Пункт</li>
                                <li class="active">Подпункт</li>
                            </ul>
                        </div>
                        <div id="sub-items3" class="setting__third-sub-item">
                            <div class="setting__third-sub-item-title">Настройка подпункта</div>
                            <input type="text" class="setting__third-sub-item-input" placeholder="Название третьего подпункта">
                            <ul class="link__wrap">
                                <li><a href="#" class="items__link items__link-first"><span class="icon icon-url"></span>Ссылка</a>
                                    <ul class="drop">
                                        <li>
                                            <input type="text" placeholder="https://crmsolutions.bitrix24.ua/">
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="#" class="items__link items__link-second"><i class="fa fa-plus" aria-hidden="true"></i>Блок</a>
                                    <ul class="drop drop__mod">
                                        <li class="sel">Название блока</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                    </ul>
                                </li>
                            </ul>
                            <ul class="link__tags">
                                <li class="active">Пункт</li>
                                <li>Подпункт</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div><!--end-menu-sub-items-->

            <div class="menu__sub-items2">
                <div class="menu__sub-items-title">Подпункт меню 2 <i class="fa fa-times" aria-hidden="true"></i></div>
                <div class="sub-items2">
                    <ul class="sub-items2__nav">
                        <li><a href="#sub-items11">Первый подпункт <i class="fa fa-times" aria-hidden="true"></i></a></li>
                        <li><a href="#sub-items22">Название второго подпункта <i class="fa fa-times" aria-hidden="true"></i></a></li>
                        <li><a href="#sub-items33">Третий подпункт <i class="fa fa-times" aria-hidden="true"></i></a></li>
                        <li><a class="add__items" href="#">+ Добавить</a></li>
                    </ul>
                    <div class="sub-items2__panels">
                        <div id="sub-items11" class="setting__first-sub-item">
                            <div class="setting__first-sub-item-title">Настройка подпункта</div>
                            <input type="text" class="setting__first-sub-item-input" placeholder="Название первого подпункта">
                            <ul class="link__wrap">
                                <li><a href="#" class="items__link items__link-first"><span class="icon icon-url"></span>Ссылка</a>
                                    <ul class="drop">
                                        <li>
                                            <input type="text" placeholder="https://crmsolutions.bitrix24.ua/">
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="#" class="items__link items__link-second"><i class="fa fa-plus" aria-hidden="true"></i>Блок</a>
                                    <ul class="drop drop__mod">
                                        <li class="sel">Название блока</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                    </ul>
                                </li>
                            </ul>
                            <ul class="link__tags">
                                <li>Пункт</li>
                                <li class="active">Подпункт</li>
                            </ul>
                        </div>
                        <div id="sub-items22" class="setting__second-sub-item">
                            <div class="setting__second-sub-item-title">Настройка подпункта</div>
                            <input type="text" class="setting__second-sub-item-input" placeholder="Название второго подпункта">
                            <ul class="link__wrap">
                                <li><a href="#" class="items__link items__link-first"><span class="icon icon-url"></span>Ссылка</a>
                                    <ul class="drop">
                                        <li>
                                            <input type="text" placeholder="https://crmsolutions.bitrix24.ua/">
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="#" class="items__link items__link-second"><i class="fa fa-plus" aria-hidden="true"></i>Блок</a>
                                    <ul class="drop drop__mod">
                                        <li class="sel">Название блока</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                    </ul>
                                </li>
                            </ul>
                            <ul class="link__tags">
                                <li>Пункт</li>
                                <li class="active">Подпункт</li>
                            </ul>
                        </div>
                        <div id="sub-items33" class="setting__third-sub-item">
                            <div class="setting__third-sub-item-title">Настройка подпункта</div>
                            <input type="text" class="setting__third-sub-item-input" placeholder="Название третьего подпункта">
                            <ul class="link__wrap">
                                <li><a href="#" class="items__link items__link-first"><span class="icon icon-url"></span>Ссылка</a>
                                    <ul class="drop">
                                        <li>
                                            <input type="text" placeholder="https://crmsolutions.bitrix24.ua/">
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="#" class="items__link items__link-second"><i class="fa fa-plus" aria-hidden="true"></i>Блок</a>
                                    <ul class="drop drop__mod">
                                        <li class="sel">Название блока</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                        <li class="sel">Следующий блок</li>
                                    </ul>
                                </li>
                            </ul>
                            <ul class="link__tags">
                                <li class="active">Пункт</li>
                                <li>Подпункт</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div><!--end-menu-sub-items-->

            <div class="btn__wrap">
                <button type="button" class="btn btn-save">Сохранить</button>
                <button type="button" class="btn btn-cansel">Отменить</button>
            </div>
        </div><!--end-settings-content-->
    </section><!--end-section-settings-->
</div>
<script defer src="style/js/vendor.js"></script>
<script defer src="style/js/script.js"></script>
</body>
</html>