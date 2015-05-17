<% if $Sections %>
	<div class="sg-row">
		<div class="sg-col-sm-9">
			<% loop $Sections %>
				<% include SGSection %>
			<% end_loop %>
		</div>
		<div class="sg-col-sm-3">
			<% include SGSubNavigation %>
		</div>
	</div>
<% end_if %>