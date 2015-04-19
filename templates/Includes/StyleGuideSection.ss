<div class="row">
	<div class="col-sm-12">
		<h2 id="$Reference">$Title</h2>
		<p>$Description</p>
		<% if $Parameters %>
			<ul>
				<% loop $Parameters %>
					<li><em>$Name</em> - $Description</li>
				<% end_loop %>
			</ul>
		<% end_if %>
		<% if $Compatibility %>
			<div class="sg-callout sg-callout--success">
				<p>$Compatibility</p>
			</div>
		<% end_if %>
		<% if $Experimental %>
			<div class="sg-callout sg-callout--warning">
				<p>$Experimental</p>
			</div>
		<% end_if %>
		<% if $Deprecated %>
			<div class="sg-callout sg-callout--danger">
				<p>$Deprecated</p>
			</div>
		<% end_if %>
	</div>
	<% if $Template %>
		<div class="col-sm-12">
			<div class="sg-example">
				<p>$Template</p>
			</div>
			<div class="sg-copy-button">
				<span data-clipboard-text="$Template.XML" title="Click to copy me.">Copy</span>
			</div>
			<div class="sg-code">
		    	<pre class="prettyprint">$Template.XML</pre>
		    </div>
		</div>
	<% end_if %>
	<% if $MarkupNormal %>
		<div class="col-sm-12">
			<div class="sg-example">
				<p>$MarkupNormal</p>
			</div>
			<div class="sg-copy-button">
				<span data-clipboard-text="$MarkupNormal.XML" title="Click to copy me.">Copy</span>
			</div>
			<div class="sg-code">
		    	<pre class="prettyprint">$MarkupNormal.XML</pre>
		    </div>
		</div>
	<% end_if %>
</div>
<% if $Modifiers %>
	<div class="row">
		<div class="col-sm-12">
			<h3>Modifiers</h3>
		</div>
		<% loop $Modifiers %>
			<div class="col-sm-12">
		    	<p><strong>$Name</strong> - $Description</p>
		    </div>
		    <div class="col-sm-12">
		    	<div class="sg-example">
		    		<p>$ExampleHtml</p>
		    	</div>
				<div class="sg-copy-button">
					<span data-clipboard-text="$ExampleHtml.XML" title="Click to copy me.">Copy</span>
				</div>
		    	<div class="sg-code">
		    		<pre class="prettyprint">$ExampleHtml.XML</pre>
		    	</div>
		    </div>
		<% end_loop %>
	</div>
<% end_if %>