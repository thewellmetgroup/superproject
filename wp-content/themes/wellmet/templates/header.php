<header class="banner<?php if (is_front_page()): echo " home"; endif; ?>">
	<div class="container">
        	<div class="logo">
            	<a class="brand" href="<?= esc_url(home_url('/')); ?>">
                	<img src="<?php echo get_template_directory_uri(); ?>/dist/images/wellmet-logo.png" alt="<?php bloginfo('name'); ?>" width="250" height="250">
            	</a>
        	</div>
        	<nav class="navbar navbar-custom nav-primary" role="navigation">
            	<div class="container-fluid">
              		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#wellmet-nav">
       			 		<span class="sr-only">Toggle navigation</span>
        				<span class="icon-bar"></span>
        				<span class="icon-bar"></span>
        				<span class="icon-bar"></span>
      				</button>
              	<?php
              
                        wp_nav_menu( array(
                            'menu'              => 'primary_navigation',
                			'theme_location'    => 'primary_navigation',
                			'depth'             => 2,
                			'container'         => 'div',
                			'container_class'   => 'collapse navbar-collapse',
        					'container_id'      => 'wellmet-nav',
                			'menu_class'        => 'nav navbar-nav',
                			'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
                			'walker'            => new wp_bootstrap_navwalker())
                        );
                    ?>
        		</div>
        	</nav>
        	<?php if (is_front_page()): ?>
        		<div class="mission">
                	<?php echo get_bloginfo('description'); ?>
            	</div>
            <?php endif; ?>
	</div>
</header>