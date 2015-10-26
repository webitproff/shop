<!-- BEGIN: MAIN -->
<div class="container-fluid container">
	<h2>{PHP.L.shop_eshop}</h2>
{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}

<!-- BEGIN: ERROR -->
		<div class="alert alert-danger" role="alert">
			{PHP.L.shop_error}
		</div>
<!-- END: ERROR -->
<!-- BEGIN: CANCEL -->
		<div class="alert alert-warning" role="alert">
			{PHP.L.shop_cancel}
		</div>
<!-- END: CANCEL -->
<!-- BEGIN: SUCCESS -->
		<div class="alert alert-info" role="alert">
			{PHP.L.shop_thanks}
		</div>
<!-- END: SUCCESS -->
</div>
<!-- END: MAIN -->