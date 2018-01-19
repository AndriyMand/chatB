<!doctype html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Рейтинг (пример 10)</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	<!-- Include roboto.css to use the Roboto web font, material.css to include the theme and ripples.css to style the ripple effect -->
	<link href="css/roboto.min.css" rel="stylesheet">
	<link href="css/material.min.css" rel="stylesheet">
	<link href="css/ripples.min.css" rel="stylesheet">
	
    <link href="css/application.css" rel="stylesheet">

</head>
<body>
<div id="app" class="container-fluid">

	<div class="modal fade" id="choose-file-dialog" tabindex="-1" role="dialog" aria-labelledby="choose-file-dialog-label">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="choose-file-dialog-label">Выберите файл</h4>
				</div>
				<div class="modal-body files">
					<ul id="file-list" class="list-unstyled"></ul>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-raised" data-dismiss="modal">Отмена</button>
					<button type="button" class="btn btn-primary btn-raised" id="save-btn" onclick="app.loadPlaceImage()">Выбрать</button>
				</div>
			</div>
		</div>
	</div>

	<div class="bs-callout bs-callout-danger">
		<h4>Работа с Диск24 (тип 2)</h4>
		<p>Получение загруженных файлов</p>
	</div>
	<div class="alert alert-dismissable alert-warning hidden" id="error"></div>
	<div class="row">
		<div class="col-md-5 col-sm-6">
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 class="panel-title">Участники рейтинга</h3>
				</div>
				<div class="panel-body">
					<div class="list-group" id="rating-list">
						<i class="fa fa-spinner fa-spin"></i>
					</div>
				</div>
				<div class="panel-footer">
					<div class="checkbox">
						<label onchange="app.toggleAvatars();">
							<input id="avatar-option" type="checkbox" checked> Показывать аватары
						</label>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-5 col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Мой результат</h3>
				</div>
				<div class="panel-body">
					<div class="list-group" id="rating-list-real">
						<i class="fa fa-spinner fa-spin"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row hidden" id="admin-options">
		<div class="col-md-5 col-sm-6">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title">Изображения для рейтинга</h3>
				</div>
				<div class="panel-body">
					<div class="list-group" id="image-list">
						<ul class="list-inline">
							<li><img src="images/empty.gif" class="img-circle rating-image" data-place="1" title="Заменить изображение для 1-го места"></li>
							<li><img src="images/empty.gif" class="img-circle rating-image" data-place="2" title="Заменить изображение для 2-го места"></li>
							<li><img src="images/empty.gif" class="img-circle rating-image" data-place="3" title="Заменить изображение для 3-го места"></li>
							<li><a class="btn btn-default btn-success btn-raised" href="#" onclick="app.setDefaultImages(true);" title="Вернуть стандартные изображения" role="button"><i class="fa fa-undo"></i></a></li>
						</ul>						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/tempusjs.min.js"></script>
<script type="text/javascript" src="js/accounting.min.js"></script>
<script src="js/ripples.min.js"></script>
<script src="js/material.min.js"></script>
<script type="text/javascript" src="js/application.js"></script>
<script src="//api.bitrix24.com/api/v1/"></script>

<script>
	
    $(document).ready(function () {

		BX24.init(function(){
		
			$.material.init();

			app.saveFrameWidth();
			app.loadOptions();
			
			app.displayDeals();

		});
    });

</script>

</body>
</html>