<% if $Sections %>
	<div class="row" id="spy-sections">
		<div class="col-xs-9">
			<% loop $Sections %>
				<% include StyleGuideSection %>
			<% end_loop %>
		</div>
		<div class="col-xs-3">
			<% include StyleGuideSubNavigation %>
		</div>
	</div>
<% else %>
	<% include StyleGuideHome %>
<% end_if %>