<div id="$ReferenceID" class="sg-row sg-section-wrap">
	<div class="sg-col-sm-12 sg-section">
		<div class="sg-row">
			<div class="sg-col-sm-12">
				<h2>$Title</h2>
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
		</div>

		<% if $getTemplate %>
			<div class="sg-row">
				<div class="sg-col-sm-12">
					<div class="sg-example--buttons">
						<a data-clipboard-text="$Template.XML" title="Click to copy me." class="sg-example__copy">Copy</a>
						<a title="Click to display the code." class="sg-example__toggle">Code</a>
					</div>
					<div class="sg-example">
						$getTemplate
					</div>
					<div class="sg-code">
				    	<pre class="prettyprint">$getTemplate.XML</pre>
				    </div>
				</div>
		    </div>
		<% end_if %>

		<% if $MarkupNormal %>
			<div class="sg-row">
				<div class="sg-col-sm-12">
					<div class="sg-example--buttons">
						<a data-clipboard-text="$MarkupNormal.XML" title="Click to copy me." class="sg-example__copy">Copy</a>
						<a title="Click to display the code." class="sg-example__toggle">Code</a>
					</div>
					<div class="sg-example">
						$MarkupNormal
					</div>
					<div class="sg-code">
				    	<pre class="prettyprint">$MarkupNormal.XML</pre>
				    </div>
			    </div>
		    </div>
		<% end_if %>

		<% if $Modifiers %>
			<div class="sg-row">
				<div class="sg-col-sm-12">
					<h3>Modifiers</h3>
					<% loop $Modifiers %>
					    <p id="$Reference"><strong>$Name</strong> - $Description</p>
					    <div class="sg-row">
							<div class="sg-col-sm-12">
								<div class="sg-example--buttons">
									<a data-clipboard-text="$ExampleHtml.XML" title="Click to copy me." class="sg-example__copy">Copy</a>
									<a title="Click to display the code." class="sg-example__toggle">Code</a>
								</div>
						    	<div class="sg-example">
						    		$ExampleHtml
						    	</div>
						    	<div class="sg-code">
						    		<pre class="prettyprint">$ExampleHtml.XML</pre>
						    	</div>
						    </div>
						</div>
					<% end_loop %>
				</div>
			</div>
		<% end_if %>
	</div>
</div>
