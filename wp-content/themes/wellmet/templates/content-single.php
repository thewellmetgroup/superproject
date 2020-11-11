<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
      <h1 class="entry-title"><?php the_title(); ?></h1>
    <div class="entry-content">
      <?php the_content(); ?>
      
      <?php  //load the grantee template
		include( locate_template( 'templates/content-grantee.php' ) );
		?>
    </div>
  </article>
<?php endwhile; ?>
