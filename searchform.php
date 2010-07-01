<form role="search" method="get" id="searchform" action="<?php echo home_url(); ?>">
	<label class="screen-reader-text" for="s"><?php __('Search for:'); ?></label>
	<div class="left"></div>
		<input type="text" name="s" id="s" value="<?php echo CWM::search(); ?>" />
	<div class="right"></div>
	<input type="submit" id="searchsubmit" value="<?php esc_attr__('Search'); ?>" />
</form>