<!-- BEGIN: MAIN -->

		<div class="block">
			<h2><span>{PHP.L.shop_history}</span></h2>
			<table class="cells">
				<tr>
					<td class="coltop width55">{PHP.L.shop_item}</td>
					<!-- IF !{PHP.cfg.plugin.shop.noprice} --><td class="coltop width15">{PHP.L.shop_price}</td><!-- ENDIF -->
					<td class="coltop width15">{PHP.L.shop_qty}</td>
					<td class="coltop width15"><!-- IF !{PHP.cfg.plugin.shop.noprice} -->{PHP.L.shop_total}<!-- ELSE --> {PHP.L.Desc} <!-- ENDIF --></td>
				</tr>
<!-- BEGIN: DETAILS -->
				<tr class="small strong">
					<td colspan="4">Заказ # {SHOP_ID} от {SHOP_DATE} на сумму {SHOP_PAYERTOTAL} {PHP.L.BYR}:</td>
				</tr>
<!-- BEGIN: ROW -->
				<tr>
					<td><a href="page.php?id={SHOP_ROW_PAGEID}" style="marginleft10">{SHOP_ROW_TITLE}</a></td>
					<!-- IF !{PHP.cfg.plugin.shop.noprice} --><td class="centerall">{SHOP_ROW_PRICE}</td><!-- ENDIF -->
					<td class="centerall">{SHOP_ROW_COUNT}</td>
					<td class="centerall"><!-- IF !{PHP.cfg.plugin.shop.noprice} -->{SHOP_ROW_TOTAL}<!-- ELSE -->{SHOP_ROW_DESC}<!-- ENDIF --></td>
				</tr>
<!-- END: ROW -->
<!-- BEGIN: NOROW -->
				<tr>
					<td colspan="4">Нет данных о заказе</td>
				</tr>
<!-- END: NOROW -->
<!-- END: DETAILS -->
<!-- BEGIN: NODETAILS -->
				<tr>
					<td colspan="4" class="centerall">{PHP.L.None}</td>
				</tr>
<!-- END: NODETAILS -->
			</table>
		</div>

<!-- END: MAIN -->