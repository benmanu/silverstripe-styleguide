<% if SubNavigation %>
	<nav class="sg-subnav" data-spy="affix" data-offset-top="71">
		<ul class="nav">
			<% loop $SubNavigation %>
			    <li><a href="#{$Reference}" title="$Description">$Title</a></li>
			<% end_loop %>
			<li><a href="#" title="Top"><small><strong>Back to top</strong></small></a></li>
		</ul>
	</nav>
<% end_if %>