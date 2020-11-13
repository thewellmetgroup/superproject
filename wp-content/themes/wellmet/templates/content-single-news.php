<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
      <h1 class="entry-title"><?php the_title(); ?></h1>
    <div class="entry-content">
    	<div class="row">
    		<div class="col-md-2"></div>
    		<div class="col-md-8">
      			<?php the_content(); ?>
      		</div>
      	</div>
    </div>
  </article>
<?php endwhile; ?>
