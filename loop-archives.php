<ul id="archives-page" class="xoxo">
	<li id="category-archives">
		<h3><?php _e( 'Archives by Category', 'sandbox' ) ?></h3>
		<ul>
			<?php wp_list_categories('optioncount=1&title_li=&show_count=1') ?> 
		</ul>
	</li>
	<li id="monthly-archives">
		<h3><?php _e( 'Archives by Month', 'sandbox' ) ?></h3>
		<ul>
			<?php wp_get_archives('type=monthly&show_post_count=1') ?>
		</ul>
	</li>
</ul>