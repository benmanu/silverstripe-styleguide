<% if $Sections %>
	<div class="sg-row">
		<div class="sg-col-sm-9">
			<% loop $Sections %>
				$forTemplate
			<% end_loop %>
		</div>
		<div class="sg-col-sm-3">
			<% include BenManu/StyleGuide/SGSubNavigation %>
		</div>
	</div>
<% end_if %>
