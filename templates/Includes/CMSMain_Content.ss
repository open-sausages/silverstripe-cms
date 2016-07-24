<div id="pages-controller-cms-content" class="has-panel cms-content center cms-tabset $BaseCSSClasses" data-layout-type="border" data-pjax-fragment="Content" data-ignore-tab-state="true">

	<div class="cms-content-header north">

		<div class="cms-content-header-nav">
			<a href="admin/pages" class="btn btn-secondary panel-back font-icon-left-open-big btn--icon-md btn--no-text hidden-lg-up"><span class="sr-only">Back to Pages</span></a>

			<% include CMSBreadcrumbs %>

			<div class="cms-content-header-tabs">
				<ul class="cms-tabset-nav-primary">
					<li class="content-treeview<% if class == 'CMSPageEditController' %> ui-tabs-active<% end_if %>">
						<a href="$LinkPageEdit" class="cms-panel-link font-icon-edit-write btn--icon-md" title="Form_EditForm" data-href="$LinkPageEdit">
							<span class="tab-text"><% _t('CMSMain.TabContent', 'Content') %></span>
						</a>
					</li>
					<li class="content-listview<% if class == 'CMSPageSettingsController' %> ui-tabs-active<% end_if %>">
						<a href="$LinkPageSettings" class="cms-panel-link font-icon-cog btn--icon-md" title="Form_EditForm" data-href="$LinkPageSettings">
							<span class="tab-text"><% _t('CMSMain.TabSettings', 'Settings') %></span>
						</a>
					</li>
					<li class="content-listview<% if class == 'CMSPageHistoryController' %> ui-tabs-active<% end_if %>">
						<a href="$LinkPageHistory" class="cms-panel-link font-icon-back-in-time btn--icon-md" title="Form_EditForm" data-href="$LinkPageHistory">
							<span class="tab-text"><% _t('CMSMain.TabHistory', 'History') %></span>
						</a>
					</li>
				</ul>
			</div>
		</div>

		<div class="cms-content-header-info">
			<div class="section-heading">
				<% include CMSSectionIcon %>
				<span class="section-label"><a href="$LinkPages">{$MenuCurrentItem.Title}</a></span>
			</div>

			<div class="view-controls">
				<button id="filters-button" class="icon-button font-icon-search no-text" title="<% _t('CMSPagesController_Tools_ss.FILTER', 'Filter') %>"></button>
				<div class="icon-button-group">
					<a href="$LinkPages#cms-content-treeview" class="icon-button font-icon-tree active" title="<% _t('CMSPagesController.TreeView', 'Tree View') %>"></a><a href="$LinkPages#cms-content-listview" class="icon-button font-icon-list" title="<% _t('CMSPagesController.ListView', 'List View') %>"></a>
				</div>
			</div>
		</div>
	</div>

	$Tools

	$EditForm

</div>
