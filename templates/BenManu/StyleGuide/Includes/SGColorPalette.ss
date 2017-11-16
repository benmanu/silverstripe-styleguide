<div id="$ReferenceID" class="sg-row sg-section-wrap">
	<div class="sg-col-sm-12 sg-section">
		<div class="sg-row">
			<div class="sg-col-sm-12">
				<h2>$Title</h2>
				<p>$Description</p>
				<% if $Parameters %>
					<div class="sg-row">
						<% loop $Parameters %>
							<div class="sg-col-sm-3 sg-swatch">
								<div class="sg-swatch__color" style="background-color: $Description;">
								</div>
								<div class="sg-swatch__var">
									<p>$Name<br/>$Description</p>
								</div>
							</div>
						<% end_loop %>
					</div>
				<% end_if %>
			</div>
		</div>
	</div>
</div>