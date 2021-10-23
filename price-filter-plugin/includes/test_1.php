<form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="filter">
   <?php
      if( $terms = get_terms( array( 'taxonomy' => 'category', 'orderby' => 'name' ) ) ) : 
   
         echo '<select name="categoryfilter"><option value="">Select category...</option>';
         foreach ( $terms as $term ) :
            echo '<option value="' . $term->term_id . '">' . $term->name . '</option>'; // ID of the category as the value of an option
         endforeach;
         echo '</select>';
      endif;
   ?>
   <input type="text" name="price_min" placeholder="Min price" />
   <input type="text" name="price_max" placeholder="Max price" />
   <label>
      <input type="radio" name="date" value="ASC" /> Date: Ascending
   </label>
   <label>
      <input type="radio" name="date" value="DESC" selected="selected" /> Date: Descending
   </label>
   <label>
      <input type="checkbox" name="featured_image" /> Only posts with featured images
   </label>
   <button>Apply filter</button>
   <input type="hidden" name="action" value="myfilter">
</form>
<div id="response"></div>

<!----------------------------->
<?php 
add_action('wp_ajax_myfilter', 'misha_filter_function'); // wp_ajax_{ACTION HERE} 
add_action('wp_ajax_nopriv_myfilter', 'misha_filter_function');

function misha_filter_function(){
   $args = array(
      'orderby' => 'date', // we will sort posts by date
      'order'  => $_POST['date'] // ASC or DESC
   );
 
   // for taxonomies / categories
   if( isset( $_POST['categoryfilter'] ) )
      $args['tax_query'] = array(
         array(
            'taxonomy' => 'category',
            'field' => 'id',
            'terms' => $_POST['categoryfilter']
         )
      );
 
   // create $args['meta_query'] array if one of the following fields is filled
   if( isset( $_POST['price_min'] ) && $_POST['price_min'] || isset( $_POST['price_max'] ) && $_POST['price_max'] || isset( $_POST['featured_image'] ) && $_POST['featured_image'] == 'on' )
      $args['meta_query'] = array( 'relation'=>'AND' ); // AND means that all conditions of meta_query should be true
 
   // if both minimum price and maximum price are specified we will use BETWEEN comparison
   if( isset( $_POST['price_min'] ) && $_POST['price_min'] && isset( $_POST['price_max'] ) && $_POST['price_max'] ) {
      $args['meta_query'][] = array(
         'key' => '_price',
         'value' => array( $_POST['price_min'], $_POST['price_max'] ),
         'type' => 'numeric',
         'compare' => 'between'
      );
   } else {
      // if only min price is set
      if( isset( $_POST['price_min'] ) && $_POST['price_min'] )
         $args['meta_query'][] = array(
            'key' => '_price',
            'value' => $_POST['price_min'],
            'type' => 'numeric',
            'compare' => '>'
         );
 
      // if only max price is set
      if( isset( $_POST['price_max'] ) && $_POST['price_max'] )
         $args['meta_query'][] = array(
            'key' => '_price',
            'value' => $_POST['price_max'],
            'type' => 'numeric',
            'compare' => '<'
         );
   }

 
   // if post thumbnail is set
   if( isset( $_POST['featured_image'] ) && $_POST['featured_image'] == 'on' )
      $args['meta_query'][] = array(
         'key' => '_thumbnail_id',
         'compare' => 'EXISTS'
      );
   // if you want to use multiple checkboxed, just duplicate the above 5 lines for each checkbox
 
   $query = new WP_Query( $args );
   
   if( $query->have_posts() ) :
      while( $query->have_posts() ): $query->the_post();
         echo '<h2>' . $query->post->post_title . '</h2>';
      endwhile;
      wp_reset_postdata();
   else :
      echo 'No posts found';
   endif;
   
   die();
}





 ?>








<script>
   jQuery(function($){
   $('#filter').submit(function(){
      var filter = $('#filter');
      $.ajax({
         url:filter.attr('action'),
         data:filter.serialize(), // form data
         type:filter.attr('method'), // POST
         beforeSend:function(xhr){
            filter.find('button').text('Processing...'); // changing the button label
         },
         success:function(data){
            filter.find('button').text('Apply filter'); // changing the button label back
            $('#response').html(data); // insert data
         }
      });
      return false;
   });
});
</script>