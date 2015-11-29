<nav class="sg-navbar sg-navbar--secondary sg-navbar--static-top">
    <div class="sg-container--fluid">
        <ul class="sg-nav sg-navbar__nav sg-navbar--right">
            <% loop $Children %>
            <li<% if $Active %> class="active"<% end_if %>>
                <a href="$Link">$Title</a>
            </li>
            <% end_loop %>
        </ul>
    </div>
</nav>