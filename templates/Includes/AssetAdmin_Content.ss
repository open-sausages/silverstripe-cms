<div id="assetadmin-cms-content" class="cms-content center cms-tabset ss-uploadfield-dropzone $BaseCSSClasses" data-layout-type="border" data-pjax-fragment="Content">

	<% with $EditForm %>
	<div class="cms-content-header north">
		<div class="cms-content-header-info">
			<% with $Controller %>
				<% include CMSBreadcrumbs %>
			<% end_with %>
		</div>

		<% if $Fields.hasTabset %>
		<% with $Fields.fieldByName('Root') %>
		<div class="cms-content-header-tabs">

			<div class="search">
				<!-- Open search trigger -->
				<button for="Form_Search_Example" class="btn no-text font-icon-search search__trigger" type="button" title="search"></button>

				<!-- Actual search fields -->
				<div id="collapseExample" class="search__group">
					<input type="text" name="q[Term]" placeholder="Search" class="form-control search__content-field" id="Form_Search_Example">
					<button id="filters-button" class="search__filter-trigger" title="<% _t('CMSPagesController_Tools_ss.FILTER', 'Filter') %>"></button>
					<button class="btn btn-primary search__submit" title="<% _t('CMSPagesController_Tools_ss.FILTER', 'Filter') %>">Go</button>

					<!-- Filter panel -->
					$Top.Tools
				</div>
			</div>

			<div class="icon-button-group">
				<ul class="cms-tabset-nav-primary ss-tabset">
				<% loop $Tabs %>
					<li<% if $extraClass %> class="$extraClass"<% end_if %>><a class="cms-panel-link icon-button <% if $Title == 'List View' %>font-icon-list<% else_if $Title == 'Tree View' %>font-icon-icon-tree<% else %>font-icon-pencil<% end_if %>" href="#$id" title="$Title"></a></li>
				<% end_loop %>
				</ul>
			</div>
		</div>
		<% end_with %>
		<% end_if %>
	</div>

	<div class="cms-content-fields center ui-widget-content cms-panel-padded" data-layout-type="border">
		<div class="cms-content-view">
			$forTemplate
		</div>
	</div>
	<% end_with %>

</div>

<!-- TEMP Javasctript for search panel -->
<script type="text/javascript">
(function($){
	$(document).click(function(e) {
		if (!$(e.target).is('.search *')) {
			$('.search').removeClass('search--active');
		}
	});

	$('.search__trigger').on('click', function() {
		$('.search').addClass('search--active');
		$('.search__content-field').focus();
	});
})(jQuery);
</script>
