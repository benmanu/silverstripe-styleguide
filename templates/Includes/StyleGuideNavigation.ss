<% if $Navigation %>
	<nav class="navbar navbar-static-top navbar-inverse">
		<div class="container-fluid">
			<ul class="nav navbar-nav">
				<% loop $Navigation %>
		    		<li<% if $Active %> class="active"<% end_if %>>
		    			<a href="$Link" title="$Description">$Title</a>
		    		</li>
				<% end_loop %>
			</ul>
		</div>
	</nav>
<% end_if %>