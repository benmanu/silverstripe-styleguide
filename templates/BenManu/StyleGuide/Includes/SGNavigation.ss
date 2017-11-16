<% if $Navigation %>
<nav class="sg-navbar sg-navbar--main sg-navbar--static-top sg-navbar--inverse">
	<div class="sg-container--fluid">
		<ul class="sg-nav sg-navbar__nav sg-navbar--left">
			<li>
				<a href="$BaseURL" title="$SiteConfig.Title home">$SiteConfig.Title</a>
			</li>
		</ul>
		<ul class="sg-nav sg-navbar__nav sg-navbar--right">
			<% loop $Navigation %>
			<li<% if $Active %> class="active"<% end_if %>>
				<a href="$Link">$Title</a>
			</li>
			<% end_loop %>
		</ul>
	</div>
</nav>
<% loop $Navigation %>
	<% if $Active && $Children %>
		<% include BenManu/StyleGuide/SGSecondaryNavigation %>
	<% end_if %>
<% end_loop %>
<% end_if %>
