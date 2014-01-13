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
			'<div class="mypopup"><p>Товар &laquo;'+tovar+'&raquo; добавлен в корзину</p><p><a href="plug.php?e=shop&m=cart">Оформить заказ</a><span class="spaced">//</span> <a href="#" onclick="$.fancybox.close();return false;">Вернуться к покупкам</a></p></div>',
			{  
				'autoDimensions' : false,  
				'width' : 350,  
				'height' : 'auto',  
				'transitionIn' : 'none',  
				'transitionOut' : 'none'  
			});
			return false;		
		}
	});
});