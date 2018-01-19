<?php
error_reporting(0);

include '../interface/db/connection.php';
include '../interface/db/query.php';

//$host = empty($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_X_FORWARDED_PROTO'] . "://" . $_SERVER['HTTP_HOST'] : $_SERVER['HTTP_ORIGIN'];

if (isset($_REQUEST['auth']['domain'])) {
	$host = $_REQUEST['auth']['domain'];
	$portalId = $conn->query(selectRow('portals', 'id', "host_name='" . $host . "'"))->fetch_assoc()['id'];
	//file_put_contents('log_portalId.txt', print_r( $portalId, true), FILE_APPEND);
} else {
	file_put_contents('log_error_request.txt', print_r( $_REQUEST, true), FILE_APPEND);
	die('error. see log');
}


/*
$blocksWithKeyboard = [];
$keyboardBlocksNames = $conn->query(selectAll('keyboard', 'block', 'block IS NOT NULL'));
$blockNames = '';
if ($keyboardBlocksNames->num_rows > 0) { 
	while($row = $keyboardBlocksNames->fetch_assoc()) {
		$blockNames .= $row['name'];
	} 
	$blockNames = rtrim($blockNames,',');
}

$keyboardBlocks = $conn->query(selectAll('botmessage', '*', "header IN ({$blockNames})"));
if ($keyboardBlocks->num_rows > 0) { 
	while($row = $keyboardBlocks->fetch_assoc()) {
		$blocksWithKeyboard[] = $row;
	}
}
*/

function multiexplode($delimiters,$string) {
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  array_filter($launch);
}

function createAttach($attach_id, $conn) {
	//global $conn;
	$array = [];
	if (!empty($attach_id)) {
		$attachIds = explode(",", rtrim($attach_id,","));
		//file_put_contents('log_attachIds.txt', print_r( $attachIds, true), FILE_APPEND);
		
		foreach ($attachIds as $id) {
			//file_put_contents('log_attachIDSSelect.txt', print_r( selectRow('attach', '*', "id='" . $id . "'"), true), FILE_APPEND);
			$attach = $conn->query(selectRow('attach', '*', "id=$id"))->fetch_assoc(); 
			//file_put_contents('log_attachIDSS.txt', print_r( $attach, true), FILE_APPEND);
			switch ($attach['type']) {
				case 0:
					$array[] = Array("LINK" => Array(
						"NAME" => $attach['name'],
						"LINK" => $attach['link']
					));
					break;
				case 1:
					$image = [];
					if (empty($attach['link'])) {
						$image = [
							"LINK" => "https://app.auspex.com.ua/script-test/Study/InterfaceDone/Files/" . $path
						];
					} else {
						$image = [
							"NAME" => $attach['link'],
							"LINK" => $attach['link'],
							"PREVIEW" => "https://app.auspex.com.ua/script-test/Study/InterfaceDone/Files/" . $attach['name']
						];
					}
					$array[] = Array("IMAGE" => $image);
					break;
				case 2:
					$array[] = Array("MESSAGE" => $attach['name']);
					break;
			}
			
		}
	}

	/*
	$links = [];
	eval("\$links = " . $block['links'] . ";");
	
	$images = [];
	eval("\$images = " . $block['images'] . ";");
	
	$array = [];
	if (!empty($links)) {
		foreach ($links as $title => $link) {
			$array[] = Array("LINK" => Array(
							"NAME" => $title,
							"LINK" => $link
						));
		}
	}
	
	if (!empty($images)) {
		foreach ($images as $path => $link) {
			$image = [];
			if (empty($link)) {
				$image = [
					"LINK" => "https://app.auspex.com.ua/script-test/Study/InterfaceDone/Files/" . $path
				];
			} else {
				$image = [
					"NAME" => $link,
					"LINK" => $link,
					"PREVIEW" => "https://app.auspex.com.ua/script-test/Study/InterfaceDone/Files/" . $path
				];
			}
			$array[] = Array("IMAGE" => $image);
		}
	}
	*/
	return $array;	
}

function getButtons($btnId, $conn) {
	global $conn;
	$result = [];

	if (!empty($btnId)) {
		$buttonIds = explode(",", rtrim($btnId,","));

		foreach ($buttonIds as $id) { 
			//file_put_contents('log_btnId1.txt', print_r( selectRow('buttons', '*', "id='" . $id . "'"), true), FILE_APPEND);
			$button = $conn->query(selectRow('buttons', '*', "id='" . $id . "'"))->fetch_assoc(); 
			//file_put_contents('log_btnId2.txt', print_r( $button, true), FILE_APPEND);
			
			switch ($button['type']) {
				case 0:
					$result['buttons'][] = [
						"TEXT" => $button['name'],
						"LINK" => $button['data'],
						"BG_COLOR" => $button['bg_color'],
						"TEXT_COLOR" => $button['text_color'],
						"DISPLAY" => "LINE",
					];
					break;
				case 1:
					$result['buttons'][] = [
						"TEXT" => $button['name'],
						"COMMAND" => "btn_block",
						"COMMAND_PARAMS" => $button['data'],
						"BG_COLOR" => $button['bg_color'],
						"TEXT_COLOR" => $button['text_color'],
						"DISPLAY" => "LINE",
					];
					break;
				case 2:
					$result['numbers'][] = [
						"TEXT" => $button['name'],
						"LINK" => $button['data']
					];
					break;
			}
		}
	}
	//file_put_contents('log_getButtons.txt', print_r( $result, true), FILE_APPEND);
	return $result;
}

function getPhoneNumbers($array, $conn) {
	global $conn;
	$result = '';
	foreach ($array as $number) {
		$result .= '[URL=tel:' . $number['LINK'] . ']' . $number['TEXT'] . '[/URL] [BR]';
	}
	return $result;
}

$tags_collection = [];
$tags_set = $conn->query(selectAll('tags', '*', 'portal_id=' . $portalId));
if ($tags_set->num_rows > 0) {
	while($collection = $tags_set->fetch_assoc()) { 
		if (!empty($collection['value'])) {
			$tags = explode(",", $collection['value']);
			$tags_collection[$collection['id']] = $tags;
		}
	}
}

//file_put_contents('log__1_tags_collection.txt', print_r( $tags_collection, true), FILE_APPEND);

### CONFIG OF BOT ###
define('DEBUG_FILE_NAME', ''); // if you need read debug log, you should write unique log name
define('CLIENT_ID', ''); // like 'app.67efrrt2990977.85678329' or 'local.57062d3061fc71.97850406' - This code should take in a partner's site, needed only if you want to write a message from Bot at any time without initialization by the user
define('CLIENT_SECRET', ''); // like '8bb00435c88aaa3028a0d44320d60339' - TThis code should take in a partner's site, needed only if you want to write a message from Bot at any time without initialization by the user

writeToLog($_REQUEST, 'ImBot Event Query');

$appsConfig = Array();
if (file_exists(__DIR__.'/config.php'))
	include(__DIR__.'/config.php');

// receive event "new message for bot"
if ($_REQUEST['event'] == 'ONIMBOTMESSAGEADD')
{	
	
	$sql = update(
			'bots', 
			"incoming_messages_count=incoming_messages_count+1",
			"portal_id={$portalId}"
			);
			
	if ($conn->query($sql) !== true) {
		file_put_contents('../log_query.txt', 'Bots:', FILE_APPEND);
		file_put_contents('../log_query.txt', $conn->error, FILE_APPEND);
	}
	

	// check the event - authorize this event or not
	if (!isset($appsConfig[$_REQUEST['auth']['application_token']]))
		return false;

	//file_put_contents('log__REQUEST.txt', print_r( $_REQUEST, true), FILE_APPEND);
	$bot = $conn->query(selectRow('bots', '*', 'portal_id=' . $portalId))->fetch_assoc();
	
	file_put_contents('log__2_bot.txt', print_r( $bot, true), FILE_APPEND);
	file_put_contents('log_application_token.txt', print_r( $appsConfig[$_REQUEST['auth']['application_token']], true), FILE_APPEND);
	
	$path = $bot['image'];

	
	$result = restCommand('imbot.update', Array(
		'BOT_ID' => $appsConfig[$_REQUEST['auth']['application_token']]['BOT_ID'], // Идентификатор чат-бота, которого нужно изменить (обяз.)
		'FIELDS' => Array( // Данные для обновления (обяз.)
			'PROPERTIES' => Array( // Обязательное при обновлении данных бота
				'NAME' => $bot['name'], // Имя чат-бота 
				'COLOR' => $bot['color'], 
				'EMAIL' => $bot['email'], // E-mail для связи
				'PERSONAL_BIRTHDAY' => empty($bot['b_day']) ? '2017-07-13' : $bot['b_day'], // День рождения в формате YYYY-mm-dd
				'PERSONAL_WWW' => $bot['www'], // Ссылка на сайт
				'PERSONAL_PHOTO' => base64_encode(file_get_contents(__DIR__.'/'.$bot['image'])),
			)
		)
	), $_REQUEST["auth"]);

	file_put_contents('log__2_bot123.txt', print_r( $result, true), FILE_APPEND);

	// response time
	$latency = (time()-$_REQUEST['ts']);
	$latency = $latency > 60? (round($latency/60)).'m': $latency."s";
	//$latency = "2s";

	
	list($message) = explode(" ", $_REQUEST['data']['PARAMS']['MESSAGE']);
	$messageWords = multiexplode(array(","," ",".","|",":","!","?"), strtolower($_REQUEST['data']['PARAMS']['MESSAGE']));
	$matches = [];
	
	//file_put_contents('log__2_messageWords.txt', print_r( $messageWords, true), FILE_APPEND);
	
	foreach ($tags_collection as $tagsId => $collection) {
		if (array_intersect($collection, $messageWords)) {
			$matches[] = $tagsId;
		}
	}
	
	//file_put_contents('log__2_matches.txt', print_r( $matches, true), FILE_APPEND);
	
	if (!empty($matches)) {
		$tagSetId = $matches[array_rand($matches, 1)];
		//file_put_contents('log__2_tagSetId.txt', print_r( $tagSetId, true), FILE_APPEND);
		$messageBlocks = [];
		$botmessage = $conn->query(selectAll('blocks', '*', 'portal_id=' . $portalId));
		if ($botmessage->num_rows > 0) {
			while($ress = $botmessage->fetch_assoc()) { 
				if (!empty($ress['tags_id'])) {
					$tags = explode(",", $ress['tags_id']);
					if (in_array($tagSetId, $tags)) {
						$messageBlocks[] = $ress;
					}
				}
			} 
		}
		
					//file_put_contents('log__2_tags.txt', print_r( $tags, true), FILE_APPEND);
		file_put_contents('log__3_messageBlocks.txt', print_r( $messageBlocks, true), FILE_APPEND);
		
		$block = $messageBlocks[array_rand($messageBlocks, 1)];
		
		//die;
		file_put_contents('log__3_block.txt', print_r( $block, true), FILE_APPEND);
		
		$attach = createAttach($block['attach_id'], $conn);		
		$buttons = getButtons($block['button_id'], $conn);
		$numbers = getPhoneNumbers($buttons['numbers'], $conn);
		
		file_put_contents('log__3_attach.txt', print_r( $attach, true), FILE_APPEND);
		file_put_contents('log__3_buttons.txt', print_r( $buttons, true), FILE_APPEND);
		file_put_contents('log__3_numbers.txt', print_r( $numbers, true), FILE_APPEND);

		//"[B]" . $block['header'] . "[/B][BR]" . $block['text']
		//print_r($appsConfig[$_REQUEST['auth']['application_token']], true)

		if ($block) {
			$answer_result = restCommand('imbot.message.add', Array(
				"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
				"MESSAGE" => "[B]" . $block['header'] . "[/B][BR]" . $block['text'],
				"ATTACH" => (empty($attach)) ? '' : $attach,
				"KEYBOARD" => (empty($buttons['buttons'])) ? '' : $buttons['buttons']
			), $_REQUEST["auth"]);
			
			if ($numbers) {
				$answer_result = restCommand('imbot.message.add', Array(
					"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
					"MESSAGE" => $numbers
				), $_REQUEST["auth"]);
			}
		} else {
			$answer_result = restCommand('imbot.message.add', Array(
				"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
				"MESSAGE" => "Thanks for your message!",
			), $_REQUEST["auth"]);
		}

		//file_put_contents('log_buttons_status.txt', print_r( $answer_result, true), FILE_APPEND);
	} else {

		$defaultBlock = $conn->query(selectRow('blocks', '*', "default_type='ONIMBOTMESSAGEADD' and portal_id=" . $portalId))->fetch_assoc(); 

		if ($defaultBlock) {

			$attach = createAttach($defaultBlock['attach_id'], $conn);		
			$buttons = getButtons($defaultBlock['button_id'], $conn);
			$numbers = getPhoneNumbers($defaultBlock['numbers'], $conn);
			
			$answer_result = restCommand('imbot.message.add', Array(
				"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
				"MESSAGE" => "[B]" . $defaultBlock['header'] . "[/B][BR]" . $defaultBlock['text'],
				"ATTACH" => (empty($attach)) ? '' : $attach,
				"KEYBOARD" => (empty($buttons['buttons'])) ? '' : $buttons['buttons']
			), $_REQUEST["auth"]);
			
			if ($numbers) {
				$answer_result = restCommand('imbot.message.add', Array(
					"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
					"MESSAGE" => $numbers
				), $_REQUEST["auth"]);
			}
		} else {
			$answer_result = restCommand('imbot.message.add', Array(
				"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
				"MESSAGE" => 'Unexpected error!',
			), $_REQUEST["auth"]);
		}
	}
	
	if($answer_result) {
		$sql = update(
			'bots', 
			"outgoing_messages_count=outgoing_messages_count+1",
			"portal_id={$portalId}"
		);
				
		if ($conn->query($sql) !== true) {
			file_put_contents('../log_query.txt', 'Bots:', FILE_APPEND);
			file_put_contents('../log_query.txt', $conn->error, FILE_APPEND);
		}
	}
	/*
	$menuArray = [];
	$menu = $conn->query(selectAll('menu', '*', 'portal_id=' . $portalId . ' and level=0'));
	if ($menu->num_rows > 0) {
		while($ress = $menu->fetch_assoc()) { 
			
			switch ($ress['attach_type']) {
				case 0:
					
					$menuArray[] = [
						"TEXT" => $ress['name'],
						"COMMAND" => "submenu",
						"COMMAND_PARAMS" => '', //select level 1
						//"BG_COLOR" => $button['bg_color'],
						//"TEXT_COLOR" => $button['text_color'],
						"DISPLAY" => "LINE",
					];
					
					break;
				case 1:
					
					$menuArray[] = [
						"TEXT" => $ress['name'],
						"COMMAND" => "btn_block",
						"COMMAND_PARAMS" => $ress['attach'],
						//"BG_COLOR" => $button['bg_color'],
						//"TEXT_COLOR" => $button['text_color'],
						"DISPLAY" => "LINE",
					];
					
					break;
				case 2:
					
					$menuArray[] = [
						"TEXT" => $ress['name'],
						"LINK" => $ress['attach'],
						//"BG_COLOR" => $button['bg_color'],
						//"TEXT_COLOR" => $button['text_color'],
						"DISPLAY" => "LINE",
					];
					
					break;
			}
		} 
		
		$result = restCommand('imbot.message.add', Array(
				"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
				"MESSAGE" => "Menu",
				"ATTACH" => ''
				"KEYBOARD" => $menuArray
			), $_REQUEST["auth"]);
	}
	*/
	// write debug log
	writeToLog($answer_result, 'ImBot Event message add');
}
// receive event "new command for bot"
if ($_REQUEST['event'] == 'ONIMCOMMANDADD')
{
	/*
	// check the event - authorize this event or not
	if (!isset($appsConfig[$_REQUEST['auth']['application_token']]))
		return false;
	
	// response time
	$latency = (time()-$_REQUEST['ts']);
	$latency = $latency > 60? (round($latency/60)).'m': $latency."s";
	
	$blocksWithKeyboard = [];
	$keyboardBlocksNames = $conn->query(selectAll('keyboard', '*', 'block IS NOT NULL'));
	$blockRelate = [];
	if ($keyboardBlocksNames->num_rows > 0) { 
		while($row = $keyboardBlocksNames->fetch_assoc()) {
			$blockRelate[$row['name']] = $row['block'];
		} 
	}
	
	$blocksIds = implode(",", $blockRelate);

	$keyboardBlocks = $conn->query(selectAll('blocks', '*', "id IN ({$blocksIds}) and portal_id=" . $portalId));
	if ($keyboardBlocks->num_rows > 0) { 
		while($row = $keyboardBlocks->fetch_assoc()) {
			$blocksWithKeyboard[$row['id']] = $row;
		}
	}
	*/
	$result = false;
	foreach ($_REQUEST['data']['COMMAND'] as $command) {
		if ($command['COMMAND'] == 'btn_block') {
			
			$block = $conn->query(selectRow('blocks', '*', "id='" . $command['COMMAND_PARAMS'] . "' and portal_id=" . $portalId))->fetch_assoc();

			$attach = createAttach($block['attach_id'], $conn);		
			$buttons = getButtons($block['button_id'], $conn);
			$numbers = getPhoneNumbers($buttons['numbers'], $conn);

			if ($block) {
				$result = restCommand('imbot.command.answer', Array(
					"COMMAND_ID" => $command['COMMAND_ID'],
					"MESSAGE_ID" => $command['MESSAGE_ID'],
					"MESSAGE" => "[B]" . $block['header'] . "[/B][BR]" . $block['text'],
					"ATTACH" => (empty($attach)) ? '' : $attach,
					"KEYBOARD" => (empty($buttons['buttons'])) ? '' : $buttons['buttons']
				), $_REQUEST["auth"]);
				
				if ($numbers) {
					$result = restCommand('imbot.command.answer', Array(
						"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
						"MESSAGE" => $numbers
					), $_REQUEST["auth"]);
				}
				
			} else {
				$result = restCommand('imbot.command.answer', Array(
					"COMMAND_ID" => $command['COMMAND_ID'],
					"MESSAGE_ID" => $command['MESSAGE_ID'],
					"MESSAGE" => "COMMAND is choosed! = " . $command['COMMAND_PARAMS']
				), $_REQUEST["auth"]);
			}
		} else {
			foreach ($blockRelate as $name => $blockId) {
				if ($name === $command['COMMAND']) {
					$attach = createAttach($blocksWithKeyboard[$blockId]);
					$result = restCommand('imbot.message.add', Array(
						"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
						"MESSAGE" => "[B]" . $blocksWithKeyboard[$blockId]['header'] . "[/B][BR]" . $blocksWithKeyboard[$blockId]['text'],
						"ATTACH" => (empty($attach)) ? '' : $attach,
						"KEYBOARD" => (empty($blocksWithKeyboard[$blockId]['buttons'])) ? [] : getButtons($blocksWithKeyboard[$blockId]['buttons'])
					), $_REQUEST["auth"]);
				}
			}
		}
	}
	
	// write debug log
	writeToLog($result, 'ImBot Event message add');
}
// receive event "open private dialog with bot" or "join bot to group chat"
elseif ($_REQUEST['event'] == 'ONIMBOTJOINCHAT')
{
	// check the event - authorize this event or not
	if (!isset($appsConfig[$_REQUEST['auth']['application_token']]))
		return false;
	
	$sql = update(
		'bots', 
		"dialogs_count=dialogs_count+1",
		"portal_id={$portalId}"
	);
			
	if ($conn->query($sql) !== true) {
		file_put_contents('../log_query.txt', 'Bots:', FILE_APPEND);
		file_put_contents('../log_query.txt', $conn->error, FILE_APPEND);
	}
	
	//defaultMessageGenerate('ONIMBOTJOINCHAT', "ONIMBOTJOINCHAT");
	$block = $conn->query(selectRow('blocks', '*', "default_type='ONIMBOTJOINCHAT' and portal_id=" . $portalId))->fetch_assoc(); 
	
	$attach = createAttach($block['attach_id'], $conn);		
	$buttons = getButtons($block['button_id'], $conn);
	$numbers = getPhoneNumbers($buttons['numbers'], $conn);
	
	file_put_contents('log_ONIMBOTJOINCHAT.txt', print_r( $block, true), FILE_APPEND);
	
	if ($block) {
		$result = restCommand('imbot.message.add', Array(
			"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
			"MESSAGE" => "[B]" . $block['header'] . "[/B][BR]" . $block['text'],
			"ATTACH" => (empty($attach)) ? '' : $attach,
			"KEYBOARD" => (empty($buttons['buttons'])) ? '' : $buttons['buttons']
		), $_REQUEST["auth"]);
		
		if ($numbers) {
			$result = restCommand('imbot.message.add', Array(
				"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
				"MESSAGE" => $numbers
			), $_REQUEST["auth"]);
		}
	} else {
		$result = restCommand('imbot.message.add', Array(
			"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
			"MESSAGE" => 'I`m here:)',
		), $_REQUEST["auth"]);
	}

	// write debug log
	writeToLog($result, 'ImBot Event join chat');
}
// receive event "delete chat-bot"
elseif ($_REQUEST['event'] == 'ONIMBOTDELETE')
{
	// check the event - authorize this event or not
	if (!isset($appsConfig[$_REQUEST['auth']['application_token']]))
		return false;

	// unset application variables
	unset($appsConfig[$_REQUEST['auth']['application_token']]);

	// save params
	saveParams($appsConfig);

	// write debug log
	writeToLog($_REQUEST['event'], 'ImBot unregister');
}
// execute custom action
elseif ($_REQUEST['event'] == 'PUBLISH')
{
	// This event is a CUSTOM event and is not sent from platform Bitrix24
	// check the event - authorize this event or not
	if (!isset($appsConfig[$_REQUEST['application_token']]))
		return false;

	// send answer message
	$result = restCommand('imbot.message.add', $_REQUEST['PARAMS'], $appsConfig[$_REQUEST['application_token']]['AUTH']);

	// write debug log
	writeToLog($result, 'ImBot Event message add');
}
// receive event "Application install"
elseif ($_REQUEST['event'] == 'ONAPPINSTALL')
{
	// handler for events
	$handlerBackUrl = ($_SERVER['SERVER_PORT']==443||$_SERVER["HTTPS"]=="on"? 'https': 'http')."://".$_SERVER['SERVER_NAME'].(in_array($_SERVER['SERVER_PORT'], Array(80, 443))?'':':'.$_SERVER['SERVER_PORT']).$_SERVER['SCRIPT_NAME'];

	// If your application supports different localizations use $_REQUEST['data']['LANGUAGE_ID'] to load correct localization
	// register new bot
	$result = restCommand('imbot.register', Array(
		'CODE' => 'echobot',
		'TYPE' => 'B',
		'EVENT_MESSAGE_ADD' => $handlerBackUrl,
		'EVENT_WELCOME_MESSAGE' => $handlerBackUrl,
		'EVENT_BOT_DELETE' => $handlerBackUrl,
		'OPENLINE' => 'Y', // this flag only for Open Channel mode http://bitrix24.ru/~bot-itr
		'PROPERTIES' => Array(
			'NAME' => 'Chat-Bot V.'.(count($appsConfig)+1),
			'COLOR' => 'GREEN',
			'EMAIL' => 'test@test.ru',
			'PERSONAL_BIRTHDAY' => '2017-05-04',
			'WORK_POSITION' => 'Test bot (WORK_POSITION)',
			'PERSONAL_WWW' => 'http://bitrix24.com',
			'PERSONAL_GENDER' => 'M',
			'PERSONAL_PHOTO' => base64_encode(file_get_contents(__DIR__.'/avatar.png')),
		)
	), $_REQUEST["auth"]);
	$botId = $result['result'];

	// save params
	$appsConfig[$_REQUEST['auth']['application_token']] = Array(
		'BOT_ID' => $botId,
		'LANGUAGE_ID' => $_REQUEST['data']['LANGUAGE_ID'],
		'AUTH' => $_REQUEST['auth'],
	);
	
	$result = restCommand('imbot.command.register', Array(
		'BOT_ID' => $botId,
		'COMMAND' => 'btn_block',
		'COMMON' => 'N',
		'HIDDEN' => 'Y',
		'EXTRANET_SUPPORT' => 'N',
		'LANG' => Array(
			Array('LANGUAGE_ID' => 'en', 'TITLE' => 'block onclick btn', 'PARAMS' => ''),
		),
		'EVENT_COMMAND_ADD' => $handlerBackUrl,
	), $_REQUEST["auth"]);
	$appsConfig[$_REQUEST['auth']['application_token']]['COMMAND_BTNBLOCK'] = $result['result'];
	/*
	$result = restCommand('imbot.command.register', Array(
		'BOT_ID' => $botId,
		'COMMAND' => 'submenu',
		'COMMON' => 'N',
		'HIDDEN' => 'Y',
		'EXTRANET_SUPPORT' => 'N',
		'LANG' => Array(
			Array('LANGUAGE_ID' => 'en', 'TITLE' => 'submenu', 'PARAMS' => ''),
		),
		'EVENT_COMMAND_ADD' => $handlerBackUrl,
	), $_REQUEST["auth"]);
	$appsConfig[$_REQUEST['auth']['application_token']]['COMMAND_SUBMENU'] = $result['result'];
	*/
	// Register IFRAME .. just for test !!
		
	$result = restCommand('imbot.app.register', Array(
	   'BOT_ID' => $botId, // идентификатор бота владельца приложения для чата
	   'CODE' => 'echobot', // код приложения для чата
	   'IFRAME' => 'https://app.auspex.com.ua/script-test/Study(menu)/bot/iframe.php', 
		'IFRAME_WIDTH' => '350', // рекомендованная ширина фрейма
		'IFRAME_HEIGHT' => '50', // рекомендованная высота фрейма 
	   'HASH' => 'd1ab17949a572b0979d8db0d5b349cd2', // токен для доступа к вашему фрейму для проверки подписи, 32 символа. 
		'ICON_FILE' => base64_encode(file_get_contents(__DIR__.'/app-icon.png')),  
		'CONTEXT' => 'BOT', // контекст приложения
	   'HIDDEN' => 'N', // скрытое приложение или нет
	   'EXTRANET_SUPPORT' => 'N', // доступна ли команда пользователям экстранет, по умолчанию N
	   'LANG' => Array( // массив переводов, желательно указывать как минимум для RU и EN
		  Array('LANGUAGE_ID' => 'en', 'TITLE' => 'Menu', 'DESCRIPTION' => 'Menu window', 'COPYRIGHT' => 'Mand'), 
	   )
	), $_REQUEST["auth"]);

	/*
	$keyboardBlocksNames = $conn->query(selectAll('keyboard', '*', 'block IS NOT NULL'));
	if ($keyboardBlocksNames->num_rows > 0) { 
		while($row = $keyboardBlocksNames->fetch_assoc()) {
			
			var_dump($row);
			
			$result = restCommand('imbot.command.register', Array(
				'BOT_ID' => $botId,
				'COMMAND' => trim($row['name']),
				'COMMON' => 'Y',
				'HIDDEN' => 'N',
				'EXTRANET_SUPPORT' => 'N',
				'LANG' => Array(
					Array('LANGUAGE_ID' => 'en', 'TITLE' => 'test title', 'PARAMS' => 'object'),
				),
				'EVENT_COMMAND_ADD' => $handlerBackUrl,
			), $_REQUEST["auth"]);
			$appsConfig[$_REQUEST['auth']['application_token']]['COMMAND_' . $row['name']] = $result['result'];
		} 
	}
	*/
	saveParams($appsConfig);

	// write debug log
	writeToLog(Array($botId, $commandEcho, $commandHelp, $commandList), 'ImBot register');
}
// receive event "Application install"
elseif ($_REQUEST['event'] == 'ONAPPUPDATE')
{
	// check the event - authorize this event or not
	if (!isset($appsConfig[$_REQUEST['auth']['application_token']]))
		return false;

	defaultMessageGenerate($_REQUEST['event'], "ONAPPUPDATE");

	if ($_REQUEST['data']['VERSION'] == 2)
	{
		/*
		Some logic in update event for VERSION 2 You can execute any method RestAPI, BotAPI or ChatAPI, for example delete or add a new command to the bot
		$result = restCommand('...', Array('...' => '...',), $_REQUEST["auth"]);

		For example delete "Echo" command:

		$result = restCommand('imbot.command.unregister', Array(
			'COMMAND_ID' => $appsConfig[$_REQUEST['auth']['application_token']]['COMMAND_ECHO'],
		), $_REQUEST["auth"]);
		*/
	}
	else // send answer message
		$result = restCommand('app.info', array(), $_REQUEST["auth"]);

	// write debug log
	writeToLog($result, 'ImBot update event');
}

/*
 * Save application configuration.
 * WARNING: this method is only created for demonstration, never store config like this
 * @param $params
 * @return bool
 */
function saveParams($params)
{
	$config = "<?php\n";
	$config .= "\$appsConfig = ".var_export($params, true).";\n";
	$config .= "?>";

	file_put_contents(__DIR__."/config.php", $config);
	return true;
}

/*
 * Send rest query to Bitrix24.
 * @param $method - Rest method, ex: methods
 * @param array $params - Method params, ex: Array()
 * @param array $auth - Authorize data, ex: Array('domain' => 'https://test.bitrix24.com', 'access_token' => '7inpwszbuu8vnwr5jmabqa467rqur7u6')
 * @param boolean $authRefresh - If authorize is expired, refresh token
 * @return mixed
 */
function restCommand($method, array $params = Array(), array $auth = Array(), $authRefresh = true)
{
	$queryUrl = "https://".$auth["domain"]."/rest/".$method;
	$queryData = http_build_query(array_merge($params, array("auth" => $auth["access_token"])));

	writeToLog(Array('URL' => $queryUrl, 'PARAMS' => array_merge($params, array("auth" => $auth["access_token"]))), 'ImBot send data');
	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_POST => 1,
		CURLOPT_HEADER => 0,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_SSL_VERIFYPEER => 1,
		CURLOPT_URL => $queryUrl,
		CURLOPT_POSTFIELDS => $queryData,
	));

	$result = curl_exec($curl);
	curl_close($curl);

	$result = json_decode($result, 1);

	if ($authRefresh && isset($result['error']) && in_array($result['error'], array('expired_token', 'invalid_token')))
	{
		$auth = restAuth($auth);
		if ($auth)
			$result = restCommand($method, $params, $auth, false);
	}

	return $result;
}

/*
 * Get new authorize data if you authorize is expire.
 * @param array $auth - Authorize data, ex: Array('domain' => 'https://test.bitrix24.com', 'access_token' => '7inpwszbuu8vnwr5jmabqa467rqur7u6')
 * @return bool|mixed
 */
function restAuth($auth)
{
	if (!CLIENT_ID || !CLIENT_SECRET)
		return false;

	if(!isset($auth['refresh_token']) || !isset($auth['scope']) || !isset($auth['domain']))
		return false;

	$queryUrl = 'https://'.$auth['domain'].'/oauth/token/';
	$queryData = http_build_query($queryParams = array(
		'grant_type' => 'refresh_token',
		'client_id' => CLIENT_ID,
		'client_secret' => CLIENT_SECRET,
		'refresh_token' => $auth['refresh_token'],
		'scope' => $auth['scope'],
	));

	writeToLog(Array('URL' => $queryUrl, 'PARAMS' => $queryParams), 'ImBot request auth data');
	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_HEADER => 0,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $queryUrl.'?'.$queryData,
	));

	$result = curl_exec($curl);
	curl_close($curl);

	$result = json_decode($result, 1);
	if (!isset($result['error']))
	{
		$appsConfig = Array();
		if (file_exists(__DIR__.'/config.php'))
			include(__DIR__.'/config.php');

		$result['application_token'] = $auth['application_token'];
		$appsConfig[$auth['application_token']]['AUTH'] = $result;
		saveParams($appsConfig);
	}
	else
		$result = false;

	return $result;
}

/*
 * Write data to log file. (by default disabled)
 * WARNING: never store log file in public folder
 * @param mixed $data
 * @param string $title
 * @return bool
 */
function writeToLog($data, $title = '')
{
	if (!DEBUG_FILE_NAME)
		return false;

	$log = "\n------------------------\n";
	$log .= date("Y.m.d G:i:s")."\n";
	$log .= (strlen($title) > 0 ? $title : 'DEBUG')."\n";
	$log .= print_r($data, 1);
	$log .= "\n------------------------\n";

	file_put_contents(__DIR__."/".DEBUG_FILE_NAME, $log, FILE_APPEND);
	return true;
}

function defaultMessageGenerate($type, $default='Default') 
{
	$block = $conn->query(selectRow('blocks', '*', "default_type='{$type}'"))->fetch_assoc(); 
	
	if ($block) {
		$result = restCommand('imbot.message.add', Array(
			"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
			"MESSAGE" => "[B]" . $block['header'] . "[/B][BR]" . $block['text'],
			"ATTACH" => (empty($attach)) ? '' : $attach,
			"KEYBOARD" => (empty($block['buttons'])) ? [] : getButtons($block['buttons'])
		), $_REQUEST["auth"]);
	} else {
		$result = restCommand('imbot.message.add', Array(
			"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
			"MESSAGE" => $default,
		), $_REQUEST["auth"]);
	}
}