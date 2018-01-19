//var baseURL = "https://app.auspex.com.ua/script-test/Study(menu)/interface/action.php";
//var operatorID = 99;

$(document).ready(function () {

	$(document).on('click', '.fa-times', function (e) {
        e.preventDefault();
		var status = 0;
        status = $(this).closest('.base__group-block').remove();
		if (status.length === 0) {
			$(this).closest('.rule').remove();
		}
    });
	
	$(document).on('click', '.addgroup', function (e) {
        e.preventDefault();
		$(this).before('\
			<div class="left__set-heading">New group <i class="fa fa-times" aria-hidden="true"></i></div>\n\
			<div class="base__group">\n\
				<a href="javascript:void(0);" class="btn base__group-add-block"><i class="fa fa-plus" aria-hidden="true"></i>Добавить блок</a>\n\
			</div>\n\
			<div class="left__set-line"></div>\n\
		');
    });
	
	$(document).on('click', '.addrule', function (e) {
        e.preventDefault();
		
		$(this).closest(".rule").before('\
			<div class="rule">\n\
				<div class="rule__left">\n\
					<div class="rule__left-title">Возможные слова и фразы пользователя <i class="fa fa-times" aria-hidden="true"></i></div>\n\
					<div class="base__group">\n\
						<a href="#" class="btn base__group-add-block"><i class="fa fa-plus" aria-hidden="true"></i>Добавить</a>\n\
					</div>\n\
				</div>\n\
				<div class="rule__right">\n\
					<div id="tabs-12" class="tabs-num">\n\
						<div class="rule__right-title">Ответить с помощью:</div>\n\
						<div class="tabs-content">\n\
							<div id="b-12" class="rule__right-field">\n\
								<div class="base__group">\n\
								</div>\n\
							</div>\n\
						</div>\n\
					</div>\n\
				</div>\n\
			</div>\n\
		');
    });

});
