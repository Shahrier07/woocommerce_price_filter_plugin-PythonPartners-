<?php 


add_action('wp_ajax_my_ajax_action','my_ajax_function');
add_action('wp_ajax_nopriv_my_ajax_action','my_ajax_function');

function my_ajax_function(){
   //echo "test";
   $q = new WP_Query(array(
        'posts_per_page'=> 1,
        'p'=>$_POST['get_page_id'],
        'post_type'=> 'page'

   ));

   $html = '<div>';
   while($q->have_posts()) : $q->the_post();
        $html .= '<div>'.get_the_content().'</div>';
   endwhile; wp_reset_query(); 

   $html .= '<div>';
   echo $html;




   die();

}
add_shortcode( 'ajax_btn', 'my_shortCode');
function my_shortCode(){
  $html ='<button type="" data-id="2" class="my-ajax-trigger"> Load content
  </button>
  <div id="info">
    
  </div>
  <script>
    jQuery(document).ready(function($){
      $(".my-ajax-trigger").on("click",function(){
        var page_id = $(this).attr("data-id");
        $.ajax({
          url:"'.admin_url('admin-ajax.php').'",
          type: "POST", 
          data: {
              action: "my_ajax_action",
              get_page_id: page_id
            }, 
          success: function(html){
              $("#info").append(html);
          }  
          });
        });
    });

  </script>
   ';
   return $html;

}
















 ?>