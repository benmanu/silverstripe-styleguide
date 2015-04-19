<% if SubNavigation %>
	<nav id="sg-subnav" class="sg-subnav">
		<ul class="nav">
			<% loop $SubNavigation %>
			    <li><a href="#{$Reference}" title="$Description">$Title</a></li>
			<% end_loop %>
			<li><a href="#" title="Top"><small><strong>Back to top</strong></small></a></li>
		</ul>
	</nav>
<% end_if %>