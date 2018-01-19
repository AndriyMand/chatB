<?php
	include 'db/connection.php';
    include 'db/query.php';
	
	session_start();

	//var_dump($_SESSION);
	//$host = empty($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_X_FORWARDED_PROTO'] . "://" . $_SERVER['HTTP_HOST'] : $_SERVER['HTTP_ORIGIN'];
	
	//$host = $_SESSION['DOMAIN'];
	session_start();
	if (isset($_REQUEST['DOMAIN'])) {
		$_SESSION['DOMAIN'] = $_REQUEST['DOMAIN'];
	} else {
		$_SESSION['DOMAIN'] = 'auspex.bitrix24.ua';
	}

	$domain = $_SESSION['DOMAIN'];
	
	if ($domain) {
		$result = $conn->query(selectAll('tags', '*', 'portal="' . $domain . '"'));
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
    <title>Тестовий 24 / Настройки AI</title>
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
                    <li><a href="blocks.php">Блоки</a></li>
                    <li><a class="active" href="AI.php">Настройки AI</a></li>
                    <li><a href="menu.php">Меню</a></li>
                </ul>
            </div>
        </div>
        <div class="settings__content">
		
			<?php
				while($row = $result->fetch_assoc()) { ?>
					<div class="rule">
						<div class="rule__left">
							<div class="rule__left-title">Возможные слова и фразы пользователя <i class="fa fa-times" aria-hidden="true"></i></div>
							<div class="base__group">
							
								<?php
								if (!empty($row['value'])) {
									$tags = explode(",", $row['value']);
									foreach ($tags as $tag) { 
										if ($tag) { ?>
											<a href="javascript:void(0);" class="btn base__group-block"><?php echo $tag; ?> <i class="fa fa-times" aria-hidden="true"></i></a>
										<?php } 
									}
								} ?>
								<a href="#" class="btn base__group-add-block"><i class="fa fa-plus" aria-hidden="true"></i>Добавить</a>
							</div>
						</div>
						
						<div class="rule__right">
							<div id="tabs-<?php echo $row['id']; ?>" class="tabs-num">
								<div class="rule__right-title">Ответить с помощью:</div>
								<div class="tabs-content">
									<div id="b-<?php echo $row['id']; ?>" class="rule__right-field">
										<div class="base__group">
										
											<?php
												$related = [];
												$botmessage = $conn->query(selectAll('blocks', '*', 'portal="' . $domain . '"'));
												if ($botmessage->num_rows > 0) {
													while($ress = $botmessage->fetch_assoc()) { 
														if (!empty($ress['tags_id'])) {
															$tags = explode(",", $ress['tags_id']);
															if (in_array($row['id'], $tags)) {
															   $related[$ress['id']] = $ress['header']; 
															}
														}
													} 
												} 
												
												if ($related) { ?>
													<div class="blocks_hot">
														<?php
															foreach ($related as $id => $name) { ?>
																<a href="javascript:void(0);" class="btn base__group-block <?php  echo $id; ?>"><?php  echo $name; ?><i class="fa fa-times" aria-hidden="true"></i></a>
													<?php	}  ?>
													</div>
										<?php 	} 

											$botmessage = $conn->query(selectAll('blocks', '*', 'portal="' . $domain . '"'));
											if ($botmessage->num_rows > 0) {  ?>

											<select name="add-block" class="add-block" data-placeholder="+ Выбрать блок">

												<option value="0"></option>
											
												<?php while($botBlocks = $botmessage->fetch_assoc()) { 
													if (empty($botBlocks['default_type']) && !array_key_exists($botBlocks['id'], $related)) { ?>
													<option value="<?php echo $botBlocks['id']; ?>"><?php echo $botBlocks['header']; ?></option>
												<?php 
													}
												} ?>
											
											</select>
											
											<?php } ?>
											
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php
				}
			?>
		
            <div class="rule">
                <div class="rule__left">
                    <a href="javascript:void(0);" class="btn base__group-add addrule"><i class="fa fa-plus" aria-hidden="true"></i>Добавить правило</a>
                </div>
            </div>
            <div class="btn__wrap btn__wrap-mod">
                <button type="button" class="btn btn-save">Сохранить</button>
                <button type="button" class="btn btn-cansel">Отменить</button>
            </div>
        </div>
    </section>

</div>
<script type="js/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script defer src="style/js/vendor.js"></script>
<script defer src="style/js/script.js"></script>
<script defer src="style/js/tools.js"></script>
</body>
</html>