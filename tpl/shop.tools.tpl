<!-- BEGIN: MAIN -->

		<div class="block">
			<h2><a href="<!-- IF {PHP.usr.groups.5} -->admin.php?m=tools&p=shop<!-- ELSE -->plug.php?e=shop&m=tools<!-- ENDIF -->">{PHP.L.shop_eshop}</a></h2>

<!-- BEGIN: ORDERS -->
			<!-- IF {PHP.usr.groups.5} -->
			<form action="{SHOP_FORM_URL}" method="post" name="updatecart">
				<p>{PHP.L.Delete}: {SHOP_FORM_SELECT} {PHP.L.shop_orders_before} {SHOP_FORM_DATE} <button name="rpublish" type="submit">{PHP.L.Delete}</button></p>
			</form>
			<!-- ENDIF -->
			<p class="marginbottom10">{PHP.L.Filter}: 
			<!-- IF {PHP.usr.groups.5} -->
				<a href="admin.php?m=tools&amp;p=shop&amp;filter=unaccepted">{PHP.L.shop_filter_pending}</a><span class="spaced">{PHP.cfg.separator}</span><a href="admin.php?m=tools&amp;p=shop&amp;filter=unpayed">{PHP.L.shop_filter_unpaid}</a><span class="spaced">{PHP.cfg.separator}</span><a href="admin.php?m=tools&amp;p=shop" class="lower">{PHP.L.All}</a>
			<!-- ELSE -->
				<a href="plug.php?m=tools&amp;e=shop&amp;filter=unaccepted">{PHP.L.shop_filter_pending}</a><span class="spaced">{PHP.cfg.separator}</span><a href="plug.php?m=tools&amp;e=shop&amp;filter=unpayed">{PHP.L.shop_filter_unpaid}</a><span class="spaced">{PHP.cfg.separator}</span><a href="plug.php?m=tools&amp;e=shop" class="lower">{PHP.L.All}</a>
			<!-- ENDIF -->
			</p>
			<table class="cells">
				<tr>
					<td class="coltop width5">ID</td>
					<td class="coltop width10">{PHP.L.Date}</td>
					<td class="coltop width15">{PHP.L.shop_name}</td>
					<td class="coltop width15">{PHP.L.shop_phone}</td>
					<td class="coltop width15">{PHP.L.shop_address}</td>
					<td class="coltop width15">{PHP.L.Email}</td>
					<!-- IF !{PHP.cfg.plugin.shop.noprice} --><td class="coltop width10">{PHP.L.shop_total}</td><!-- ENDIF -->
					<td class="coltop width15">{PHP.L.Action}</td>
				</tr>
<!-- BEGIN: ROW -->
				<tr>
					<td class="centerall {SHOP_ROW_ODDEVEN}"><img src="{PHP.cfg.plugins_dir}/shop/images/status{SHOP_ROW_ORDERSTATE}.png" alt="#" /></td>
					<td class="centerall {SHOP_ROW_ODDEVEN}">{SHOP_ROW_DATE}</td>
					<td class="centerall {SHOP_ROW_ODDEVEN}"><a href="users.php?m=details&amp;id={SHOP_ROW_PAYER_ID}">{SHOP_ROW_PAYERNAME}</a></td>
					<td class="centerall {SHOP_ROW_ODDEVEN}">{SHOP_ROW_PAYERPHONE}</td>
					<td class="centerall {SHOP_ROW_ODDEVEN}">{SHOP_ROW_PAYERADDRESS}</td>
					<td class="centerall {SHOP_ROW_ODDEVEN}">{SHOP_ROW_PAYEREMAIL}</td>
					<!-- IF !{PHP.cfg.plugin.shop.noprice} --><td class="centerall {SHOP_ROW_ODDEVEN}">{SHOP_ROW_PAYERTOTAL}</td><!-- ENDIF -->
					<td class="centerall {SHOP_ROW_ODDEVEN}">
						<!-- IF {PHP.usr.maingrp} == 5 --><a href="{SHOP_ROW_DELETEURL}"><img src="{PHP.cfg.plugins_dir}/shop/images/delete.png" title="{PHP.L.Delete}" /></a><!-- ENDIF -->
						<!-- IF {SHOP_ROW_ORDERSTATE} != 1 AND {SHOP_ROW_ORDERSTATE} != 2 --><a href="{SHOP_ROW_ACCEPTURL}"><img src="{PHP.cfg.plugins_dir}/shop/images/confirm.png" title="{PHP.L.shop_confirm}" /></a><!-- ENDIF -->
						<!-- IF {SHOP_ROW_ORDERSTATE} != 0 AND {SHOP_ROW_ORDERSTATE} != 2 --><a href="{SHOP_ROW_BOUGHTURL}"><img src="{PHP.cfg.plugins_dir}/shop/images/paid.png" title="{PHP.L.shop_paid}" /></a><!-- ENDIF -->
						<a href="{SHOP_ROW_DETAILSURL}"><img src="{PHP.cfg.plugins_dir}/shop/images/info.png" title="{PHP.L.shop_order_info}" /></a>
					</td>
				</tr>
<!-- END: ROW -->
			</table>
<!-- END: ORDERS -->

<!-- BEGIN: DETAILS -->
			<h3>{PHP.L.shop_buyer_info}:</h3>
			<table class="cells marginbottom10">
				<tr>
					<td class="width30">ID:</td>
					<td class="width70">{SHOP_ID}</td>
				</tr>
				<tr>
					<td>{PHP.L.Date}:</td>
					<td>{SHOP_DATE}</td>
				</tr>
				<tr>
					<td>{PHP.L.shop_name}:</td>
					<td>{SHOP_PAYER_NAME} ({SHOP_PAYERNAME})</td>
				</tr>
				<tr>
					<td>{PHP.L.shop_phone}:</td>
					<td>{SHOP_PAYERPHONE}</td>
				</tr>
				<tr>
					<td>{PHP.L.shop_address}:</td>
					<td>{SHOP_PAYERADDRESS}</td>
				</tr>
				<tr>
					<td>{PHP.L.Email}:</td>
					<td>{SHOP_PAYEREMAIL}</td>
				</tr>
				<tr>
					<td>{PHP.L.shop_misc}:</td>
					<td>{SHOP_PAYEROTHER}</td>
				</tr>
				<!-- IF !{PHP.cfg.plugin.shop.noprice} -->
				<tr>
					<td>{PHP.L.shop_total}:</td>
					<td>{SHOP_PAYERTOTAL}</td>
				</tr>
				<!-- ENDIF -->
				<tr>
					<td>{PHP.L.Status}:</td>
					<td>
						<!-- IF {SHOP_ORDERSTATE} == 0 --><p>{PHP.L.shop_order_state0}</p><!-- ENDIF -->
						<!-- IF {SHOP_ORDERSTATE} == 1 --><p>{PHP.L.shop_order_state1} ({SHOP_VALIDATOR_NAME})</p><!-- ENDIF -->
						<!-- IF {SHOP_ORDERSTATE} == 2 --><p>{PHP.L.shop_order_state1} ({SHOP_VALIDATOR_NAME})<br />{PHP.L.shop_order_state2} ({SHOP_VALIDATOR2_NAME})</p><!-- ENDIF -->
					</td>
				</tr>
<!-- IF {PHP.usr.maingrp} == 5 -->
				<tr>
					<td>{PHP.L.Administration}:</td>
					<td>
						<ul class="bullets">
							<li><a href="{SHOP_ACCEPTURL}">{PHP.L.shop_confirm}</a><span class="spaced">{PHP.cfg.separator}</span><a href="{SHOP_UNACCEPTURL}">{PHP.L.shop_unconfirm}</a></li>
							<li><a href="{SHOP_BOUGHTURL}">{PHP.L.shop_paid}</a><span class="spaced">{PHP.cfg.separator}</span><a href="{SHOP_UNBOUGHTURL}">{PHP.L.shop_unpaid}</a></li>
							<li><a href="{SHOP_ROW_DELETEURL}">{PHP.L.Delete}</a></li>
						</ul>
					</td>
				</tr>
<!-- ENDIF -->
			</table>
			<h3>{PHP.L.shop_order_info}:</h3>
			<table class="cells">
				<tr>
					<td class="coltop width55">{PHP.L.shop_item}</td>
					<!-- IF !{PHP.cfg.plugin.shop.noprice} --><td class="coltop width15">{PHP.L.shop_price}</td><!-- ENDIF -->
					<td class="coltop width15">{PHP.L.shop_qty}</td>
					<td class="coltop width15"><!-- IF !{PHP.cfg.plugin.shop.noprice} -->{PHP.L.shop_total}<!-- ELSE --> {PHP.L.Desc} <!-- ENDIF --></td>
				</tr>
<!-- BEGIN: ROW -->
				<tr>
					<td><a href="page.php?id={SHOP_ROW_PAGEID}">{SHOP_ROW_TITLE}</a></td>
					<!-- IF !{PHP.cfg.plugin.shop.noprice} --><td class="centerall">{SHOP_ROW_PRICE}</td><!-- ENDIF -->
					<td class="centerall">{SHOP_ROW_COUNT}</td>
					<td class="centerall"><!-- IF !{PHP.cfg.plugin.shop.noprice} -->{SHOP_ROW_TOTAL}<!-- ELSE -->{SHOP_ROW_DESC}<!-- ENDIF --></td>
				</tr>
<!-- END: ROW -->
			</table>

<!-- END: DETAILS -->
		</div>

<!-- END: MAIN -->