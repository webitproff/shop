$(document).ready(function(){
	$(".shopbuylink").live('click', function() {
		var ids = $(this).attr('rel');
		var idsl = ids.split(';', 2);
		var id = idsl[0];
		var tovar = idsl.length < 2 ? '' : idsl[1];
		if(parseInt(id) > 0)
		{
			ajaxSend({data: '',  
				url: 'plug.php?r=shop&action=cart&buyid='+id, divId: 'mycart',  
				errMsg: 'AJAX Error' 
				});  
			$.fancybox(  
			'<div class="mypopup"><p>Товар &laquo;'+tovar+'&raquo; добавлен в корзину</p><p><a class="uibutton special" href="plug.php?e=shop&m=cart">Оформить заказ</a><span class="spaced">//</span> <a href="#" class="uibutton" onclick="$.fancybox.close();return false;">Вернуться к покупкам</a></p></div>',
			{  
				'autoDimensions' : false,  
				'width' : 350,  
				'height' : 'auto',  
				'transitionIn' : 'none',  
				'transitionOut' : 'none'  
			});
				var link = $(this).data('unhref');
				$(this).attr('href', link).text('Не покупать').removeClass( "shopbuylink" ).addClass( "shopunbuylink" );
			return false;		
		}
	});
	$(".shopunbuylink").live('click', function() {
		var ids = $(this).attr('rel');
		var idsl = ids.split(';', 2);
		var id = idsl[0];
		var tovar = idsl.length < 2 ? '' : idsl[1];
		if(parseInt(id) > 0)
		{
			ajaxSend({data: '',  
				url: 'plug.php?r=shop&action=cart&unbuyid='+id, divId: 'mycart',  
				errMsg: 'AJAX Error' 
				});  
			$.fancybox(  
			'<div class="mypopup"><p>Товар &laquo;'+tovar+'&raquo; удален из корзины</p><p><a class="uibutton" href="#" onclick="$.fancybox.close();return false;">Вернуться к покупкам</a></p></div>',
			{  
				'autoDimensions' : false,  
				'width' : 350,  
				'height' : 'auto',  
				'transitionIn' : 'none',  
				'transitionOut' : 'none'  
			});
				var link = $(this).data('unhref');
				$(this).attr('href', link).text('В корзину').removeClass( "shopunbuylink" ).addClass( "shopbuylink" );
			return false;		
		}
	});	
});