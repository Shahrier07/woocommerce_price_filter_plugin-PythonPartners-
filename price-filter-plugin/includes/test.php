<?php 

//Database
global $wpdb;
$table_name = $wpdb->prefix . 'postmeta';

//Add custom Button beside add to cart


    
       function custom_pre_get_posts_query( $q ) {

        // Do your cart logic here
        add_action( 'woocommerce_before_shop_loop', 'add_custom_button_2', 10, 0 );

        //custom button 2
        function add_custom_button_2() {
        ?>    
            <form action="" method="post">                                
               <div class="form-group row">               
                    
                   
                    <!------Discount--------->
                    <a href='?page=discount_sort&percent=5'>              
                    <button type="button" name="" value="">          
                        5% discount
                    </button> 
                    </a>     
                    <a href='?page=discount_sort&percent=10'>              
                    <button type="button" name="" value="">          
                        10% discount
                    </button> 
                    </a>     
                    <a href='?page=discount_sort&percent=15'>              
                    <button type="button" name="" value="">          
                        15% discount
                    </button> 
                    </a>
                    <a href='?page=discount_sort&percent=20'>              
                    <button type="button" name="" value="">          
                        20% discount
                    </button> 
                    </a>    
                    <a href='?page=discount_sort&percent=25'>              
                    <button type="button" name="" value="">          
                        25% discount
                    </button> 
                    </a>         
                </div>
            </form>
            

            
        <?php } //<!----------end button_loop------>
        //<?php

        if (isset($_GET['price'])) {
            
            $search_value=$_GET["price"];
        
            echo $search_value;
            //echo "<br><br><br>";
            global $wpdb;
            $table_name = $wpdb->prefix . 'wc_product_meta_lookup';
            $result = $wpdb->get_results ( "SELECT product_id FROM  $table_name WHERE  $search_value = min_price" );
            foreach ( $result as $print ) {
                $product_id = $print->product_id;
                //echo $product_id;
            }
            
            // Get ids of products which you want to hide
            $array_of_product_id = array($product_id);

            $q->set( 'post__in', $array_of_product_id );
       }
       
       // ***Dissount***
       if (isset($_GET['percent'])) {
            
            $search_value=$_GET["percent"];
        
            //echo $search_value;
            //echo "<br><br><br>";
            global $wpdb;
            $table_name = $wpdb->prefix . 'postmeta';
            $result = $wpdb->get_results ( "SELECT * FROM  $table_name WHERE  meta_value = $search_value" );
            
            $total_pro_id= array();
            foreach ( $result as $print ) {
                $product_id = $print->post_id;
                echo $product_id;
                array_push($total_pro_id, $product_id);

            }
            
            // Get ids of products which you want to show
            //$total_pro_id = array(10,17);
            $array_of_product_id = $total_pro_id;
            $q->set( 'post__in', $array_of_product_id );
       }
        

        }
       add_action( 'woocommerce_product_query', 'custom_pre_get_posts_query' );

?>



























