
$(function(){

    $("head").append("<link rel='stylesheet' type='text/css' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css' />");

    $('select').styler({
        selectPlaceholder: '+ Выбрать блок'
    });

    $('.tab__nav li:nth-child(2)').addClass('select');
	$('.tab__panels > div').hide().filter(':nth-child(2)').show();
	$('.tab__nav a').click(function(){
		$('.tab__panels > div').hide().filter(this.hash).show();
		$('.tab__nav li').removeClass('select');
		$(this).parent().addClass('select');
		return (false);
	});

	//items select

    $('.items__nav li:nth-child(2)').addClass('select');
    $('.items__panels > div').hide().filter(':nth-child(2)').show();
    $('.items a').click(function(){
        $('.items__panels > div').hide().filter(this.hash).show();
        $('.items li').removeClass('select');
        $(this).parent().addClass('select');
        return (false);
    });

    //subitems select

    $('.sub-items__nav li:nth-child(2)').addClass('select');
    $('.sub-items__panels > div').hide().filter(':nth-child(2)').show();
    $('.sub-items a').click(function(){
        $('.sub-items__panels > div').hide().filter(this.hash).show();
        $('.sub-items li').removeClass('select');
        $(this).parent().addClass('select');
        return (false);
    });

    $('.sub-items2__nav li:nth-child(2)').addClass('select');
    $('.sub-items2__panels > div').hide().filter(':nth-child(2)').show();
    $('.sub-items2 a').click(function(){
        $('.sub-items2__panels > div').hide().filter(this.hash).show();
        $('.sub-items2 li').removeClass('select');
        $(this).parent().addClass('select');
        return (false);
    });


//tabs
    var tabs = $('#tabs-1');
    $('.tabs-content > div', tabs).each(function(i){
        if ( i != 0 ) $(this).hide(0);
    });
    tabs.on('click', '.tabs a', function(e){
        e.preventDefault();

        var tabId = $(this).attr('href');

        $('.tabs a',tabs).removeClass();
        $(this).addClass('active');

        $('.tabs-content > div', tabs).hide(0);
        $(tabId).show();
    });

    var tabs = $('#tabs-2');
    $('.tabs-content > div', tabs).each(function(i){
        if ( i != 1 ) $(this).hide(1);
    });
    tabs.on('click', '.tabs a', function(e){
        e.preventDefault();

        var tabId = $(this).attr('href');

        $('.tabs a',tabs).removeClass();
        $(this).addClass('active');

        $('.tabs-content > div', tabs).hide(1);
        $(tabId).show();
    });



});

