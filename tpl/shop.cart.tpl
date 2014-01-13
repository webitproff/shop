<!-- BEGIN: MAIN -->
		<div id="crumbs"><a href="{PHP.cfg.mainurl}" title="{PHP.L.Home}">{PHP.L.Home}</a> {PHP.cfg.separator} {PHP.L.shop_your_cart}</div>

		<div class="block">
<!-- BEGIN: SHOP -->
			<form action="{SHOP_UPDATE_URL}" method="post" name="updatecart">
				<table class="cells">
					<tr>
						<td class="coltop width60">{PHP.L.shop_item}</td>
						<td class="coltop width10">{PHP.L.shop_price}</td>
						<td class="coltop width10">{PHP.L.Delete}</td>
						<td class="coltop width10">{PHP.L.shop_qty}</td>
						<td class="coltop width10">{PHP.L.shop_total}</td>
					</tr>
<!-- BEGIN: ROW -->
					<tr>
						<td><a href="{SHOP_ROW_URL}">{SHOP_ROW_TITLE}</a></td>
						<td class="centerall">{SHOP_ROW_PRICE}</td>
						<td class="centerall"><a href="{SHOP_ROW_DELETE_URL}" title="{PHP.L.Delete}: {SHOP_ROW_TITLE}"><img src="{PHP.cfg.plugins_dir}/shop/images/delete.png" alt="{PHP.L.Delete}" /></a></td>
						<td class="centerall">{SHOP_ROW_COUNT_INPUT}</td>
						<td class="centerall">{SHOP_ROW_TOTAL}</td>
					</tr>
<!-- END: ROW -->
				<tr>
					<td colspan="4">{PHP.L.shop_total}:</td>
					<td class="centerall">{SHOP_TOTAL}</td>
				</tr>
				<tr>
					<td colspan="5" class="valid"><button name="rpublish" type="submit">{PHP.L.Update}</button></td>
				</tr>
			</table>
			</form>
			<ul class="choices textcenter">
				<li><a href="list.php?c=shop" title="{PHP.L.shop_back}">{PHP.L.shop_back}</a></li>
				<li><a href="{SHOP_CLEARCART_URL}" title="{PHP.L.shop_clear_cart}">{PHP.L.shop_clear_cart}</a></li>
				<li><a href="{SHOP_CHECKOUT_URL}" title="{PHP.L.shop_checkout}">{PHP.L.shop_checkout}</a></li>
			</ul>
<!-- END: SHOP -->

<!-- BEGIN: EMPTYCART -->
			<div class="error">{PHP.L.shop_cart_empty}</div>
<!-- END: EMPTYCART -->

		</div>
<!-- END: MAIN -->