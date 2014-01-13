<!-- BEGIN: MAIN -->
		<div id="crumbs"><a href="{PHP.cfg.mainurl}" title="{PHP.L.Home}">{PHP.L.Home}</a> {PHP.cfg.separator} {PHP.L.shop_checkout}</div>

		<div class="block">

{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}

<!-- BEGIN: SUCCESS -->
		<div class="help">{PHP.L.shop_thanks}</div>
<!-- END: SUCCESS -->

<!-- BEGIN: FORM -->
			<form action="{SHOP_ORDER_URL}" method="post" name="updatecart">
				<table class="cells">
					<tr>
						<td>{PHP.L.shop_name}:</td>
						<td>{SHOP_ORDER_PAYERNAME}</td>
					</tr>
					<tr>
						<td>{PHP.L.shop_phone}:</td>
						<td>{SHOP_ORDER_PAYERPHONE}</td>
					</tr>
					<tr>
						<td>{PHP.L.shop_address}:</td>
						<td>{SHOP_ORDER_PAYERADDRESS}</td>
					</tr>
					<tr>
						<td>{PHP.L.Email}:</td>
						<td>{SHOP_ORDER_PAYEREMAIL}</td>
					</tr>
					<tr>
						<td>{PHP.L.shop_misc}:</td>
						<td><textarea name="payerother" rows="10" cols="100">{PHP.payerother}</textarea></td>
					</tr>
					<!-- IF !{PHP.cfg.plugin.shop.noprice} --><tr>
						<td>{PHP.L.shop_total}:</td>
						<td>{SHOP_ORDER_PAYERTOTAL} USD</td>
					</tr><!-- ENDIF -->
					<tr>
						<td colspan="2" class="valid"><button name="rpublish" type="submit">{PHP.L.shop_checkout}</button></td>
					</tr>
				</table>
			</form>
<!-- END: FORM -->
		</div>
<!-- END: MAIN -->