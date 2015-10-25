<!-- BEGIN: MAIN -->
{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}
<div class="block">
<h3>{PHP.L.Edit}</h3>
	<form action="{FORM_EDIT_URL}" method="POST">
        <table class="cells">
			<tr>
                <td class="width20">{PHP.L.shop_delivery}</td>
                <td class="width30">{PHP.L.shop_min_price}</td>
				<td class="width30">{PHP.L.shop_delivery_sum}</td>
				<td class="width30">{PHP.L.shop_percent}</td>
				<td class="width10">{PHP.L.Delete}</td>
            </tr>			
			<!-- BEGIN: ROW -->
            <tr>
				<td>{FORM_EDIT_DELIVERY}</td>
				<td>{FORM_EDIT_MIN}</td>
				<td>{FORM_EDIT_PRICE}</td>
				<td>{FORM_EDIT_PERCENT}</td>
                <td><a class="negative button trash icon" href="{FORM_EDIT_DELETE_URL}">{PHP.L.Delete}</a></td>
            </tr>
			<!-- END: ROW -->
			<!-- BEGIN: NOROW -->
            <tr>
                <td colspan="5">
					{PHP.L.None}
				</td>
            </tr>
			<!-- END: NOROW -->
        </table>
        <div class="action_bar valid">
			<input type="submit" class="button confirm" value="{PHP.L.Update}" />
        </div>
    </form>
</div>
		
<div class="block">
<h3>{PHP.L.Add}</h3>
	<form action="{FORM_ADD_URL}" method="POST">
        <table class="cells">
			<tr>
                <td class="width20">{PHP.L.shop_delivery}</td>
                <td class="width30">{PHP.L.shop_min_price}</td>
				<td class="width30">{PHP.L.shop_delivery_sum}</td>
				<td class="width30">{PHP.L.shop_percent}</td>
				<td class="width10"></td>
            </tr>			

            <tr>
				<td>{FORM_ADD_DELIVERY}</td>
				<td>{FORM_ADD_MIN}</td>
				<td>{FORM_ADD_PRICE}</td>
				<td>{FORM_ADD_PERCENT}</td>
                <td></td>
            </tr>
        </table>
        <div class="action_bar valid">
			<input type="submit" class="button confirm" value="{PHP.L.Add}" />
        </div>
    </form>
</div>		
<!-- END: MAIN -->