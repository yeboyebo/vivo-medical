<?php

/* controlling view of advance settings*/
if ( !defined( 'ABSPATH' ) ) {
    die;
}

if ( dd_aeh()->is_not_paying() ) {
    ?>
	<div id="test-2" class="col s12">
		<div class="col s12" style="margin-top:15px;vertical-align:bottom">
			<h5 class="left margin-zero" style="margin:0px">Advance Features</h5>
			<a class="waves-effect waves-light btn-small right" href="<?php 
    echo  dd_aeh()->get_trial_url() ;
    ?>"><i class="material-icons left">local_offer</i>Sign-up for 7-day free trial!</a>
		</div>
		<div class="clearfix" style="clear:both"></div>
		<div class="divider" style="margin-top:15px"></div>
		<div id="test-2" class="col s6">
	    <ul class="row">
				<li class="valign-wrapper" style="margin-bottom:15px">
				    <i class="material-icons teal-text">check_circle</i> <!-- Icon-->
				    <span style="margin-left:5px">Adding new/custom file types </span>                  <!-- Text-->
				</li>
				<li class="valign-wrapper" style="margin-bottom:15px">
				    <i class="material-icons teal-text">check_circle</i> <!-- Icon-->
				    <span style="margin-left:5px">Remove version info from files</span>                  <!-- Text-->
				</li>
				<li class="valign-wrapper" style="margin-bottom:15px">
				    <i class="material-icons teal-text">check_circle</i> <!-- Icon-->
				    <span style="margin-left:5px">Enable HTTP (gzip) compression</span>                  <!-- Text-->
				</li>
			</ul>
		</div>
		<div id="test-2" class="col s6">
	    <ul class="row">
				<li class="valign-wrapper" style="margin-bottom:15px">
				    <i class="material-icons teal-text">check_circle</i> <!-- Icon-->
				    <span style="margin-left:5px">Prevent Specific files from browser cache</span>                  <!-- Text-->
				</li>
				<li class="valign-wrapper" style="margin-bottom:15px">
				    <i class="material-icons teal-text">check_circle</i> <!-- Icon-->
				    <span style="margin-left:5px">Clear Browser cache for users(beta)</span>                  <!-- Text-->
				</li>
				<li class="valign-wrapper" style="margin-bottom:15px">
				    <i class="material-icons teal-text">check_circle</i> <!-- Icon-->
				    <span style="margin-left:5px">Refresh Browser cache periodically</span>                  <!-- Text-->
				</li>
			</ul>
		</div>

	</div>
<?php 
}
