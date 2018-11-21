
<?php 
$hover_colour = get_field('hover_colour', 'option');

$icon_colour = get_field('icon_colour', 'option');
?>


<?php if($icon_colour || $hover_colour) : ?>
	<style>

		<?php if($icon_colour) : ?>
			.beautiful-gallery-item i{
				color: <?php echo $icon_colour; ?>!important;
			}
		<?php endif; ?>
		<?php if($hover_colour) : ?>
			
				.beautiful-gallery-item .overlay{
					background-color: <?php echo $hover_colour; ?>!important;
				}
			
		<?php endif; ?>
	</style>
<?php endif; ?>


<?php 
$images = get_field('gallery');



if( $images ): ?>
<?php $gutters = get_field('display_gutters'); ?>


	<div class="<?php if($gutters):?>no-gutters <?php endif;?>gallery">
		

<?php foreach( $images as $image ): ?>
	 <div class="beautiful-gallery-item <?php if(!$gutters):?>no-gutters<?php endif;?><?php if(get_field('images_per_row') == '3') : ?> three<?php elseif(get_field('images_per_row') == '4') : ?> four<?php elseif(get_field('images_per_row') == '5') : ?> five<?php elseif(get_field('images_per_row') == '2') : ?> two<?php endif;?>">
    
		<a href="<?php echo $image['url']; ?>">
		 	<img src="<?php echo $image['sizes']['thumbnail']; ?>" alt="<?php echo $image['alt']; ?>" />
			
			<?php $hover_colour_single = get_field('overlay_colour'); ?>
		 	<div class="overlay">
				<i class="fas fa-search"></i>
			</div>
		</a>

		
    </div>
<?php endforeach; ?>

	</div>


<?php endif;?>

