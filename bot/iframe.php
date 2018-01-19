<?php
	$message      = 'test message';
	$number       = 380939967891;
	$support_code = '6a4cdbcf753addac1a573ea64be826ca';
?>

<html>
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="utf-8" />
		<link href="style.css" rel='stylesheet' type='text/css' />
	</head>

	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	
	<body style="height: 100%;margin: 0;padding: 0; background: #fff">
		<div id="menu">
			<ul>
				<li>
					<a href="#">Повідомлення</a> 
					<span>
						<a href="#" onclick="frameCommunicationSend({'action': 'send', 'message': '<?php echo $message; ?>'})">Надіслати</a> 
						<a href="#" onclick="frameCommunicationSend({'action': 'put', 'message': '<?php echo $message; ?>'})">Вставити</a> 
					</span>
				</li>
				<li>
					<a href="#">Дія</a> 
					<span>
						<a href="#" onclick="frameCommunicationSend({'action': 'call', 'number': '<?php echo $number; ?>'})">Дзвінок оператору</a> 
						<a href="#" onclick="frameCommunicationSend({'action': 'support', 'code': '<?php echo $support_code; ?>'})">Відкрити бот-підтримку</a> 
					</span>
				</li>
				<li>
					<a href="#" onclick="frameCommunicationSend({'action': 'close'})">Закрити</a>
				</li>
			</ul>
		</div>
		<script type="text/javascript">
			function frameCommunicationInit()
			{
				if (!window.frameCommunication)
				{
					window.frameCommunication = {timeout: {}};
				}
				if(typeof window.postMessage === 'function')
				{
					window.addEventListener('message', function(event){
						var data = {};
						try { data = JSON.parse(event.data); } catch (err){}
			
						if (data.action == 'init')
						{
							frameCommunication.uniqueLoadId = data.uniqueLoadId;
							frameCommunication.postMessageSource = event.source;
							frameCommunication.postMessageOrigin = event.origin;
						}
					});
				}
			}
			function frameCommunicationSend(data)
			{
				data['uniqueLoadId'] = frameCommunication.uniqueLoadId;
				var encodedData = JSON.stringify(data);
				if (!frameCommunication.postMessageOrigin)
				{
					clearTimeout(frameCommunication.timeout[encodedData]);
					frameCommunication.timeout[encodedData] = setTimeout(function(){
						frameCommunicationSend(data);
					}, 10);
					return true;
				}
				
				if(typeof window.postMessage === 'function')
				{
					if(frameCommunication.postMessageSource)
					{
						frameCommunication.postMessageSource.postMessage(
							encodedData,
							frameCommunication.postMessageOrigin
						);
					}
				}
			}
			frameCommunicationInit();
		</script>
	</body>
</html>