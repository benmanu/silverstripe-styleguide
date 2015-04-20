<% if $Sections %>
	<div class="sg-row">
		<div class="sg-col-sm-9">
			<% loop $Sections %>
				<% include StyleGuideSection %>
			<% end_loop %>
		</div>
		<div class="sg-col-sm-3">
			<% include StyleGuideSubNavigation %>
		</div>
	</div>
<% else %>
	<% include StyleGuideHome %>
<% end_if %>