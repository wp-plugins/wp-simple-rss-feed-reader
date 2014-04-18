<?php
    /**
    * The Widget
    */
    class SimpleRssWidget extends WP_Widget {
        /** constructor */
        function SimpleRssWidget() {
            parent::WP_Widget(false, $name = 'Simple RSS Feed');    
        }

        /** @see WP_Widget::widget */
        function widget($args, $instance) {      
            //get arguments
            extract( $args );

            //set title var
            $title = 'Simple Rss Widget';

            //get title
            if(isset($instance['title']))
                $title = apply_filters('widget_title', $instance['title']);

            //get limit
            $limit = 5;
            if(isset($instance['limit']))
                $limit = $instance['limit'];

            //get amount of words per item to display
            $amount_of_words = 16;
            if(isset($instance['amount_of_words']))
                $amount_of_words = (int)($instance['amount_of_words']+1);


            //get hide_description
            $hide_description = 0;
            if(isset($instance['hide_description']))
                $hide_description = $instance['hide_description'];

            //get show_date
            $show_date = 0;
            if(isset($instance['show_date']))
                $show_date = $instance['show_date'];

            //get url
            $url = '';
            if(isset($instance['url']))
                $url = $instance['url'];

            echo $before_widget; 

            if ( $title )
                echo $before_title . $title . $after_title;

            $i = 0;


            $url = html_entity_decode($url);

            //test feed first
            $content = @file_get_contents($url); 
            try { 
                $xml = new SimpleXmlElement($content); 
            }   catch(
                Exception $e){ 
                /* the data provided is not valid XML */ 
                return 'Unfortunally the feed you provided is not valid...'; 
                exit();
            }

            if(!is_object($xml)){ 
                echo 'Unfortunally the feed you provided is not valid...';  
            } else {
                $return  = SimplRssParse($xml,$limit,$hide_description,$show_date,$amount_of_words);
                echo $return;
                echo $after_widget;
            }

        }

        /** @see WP_Widget::update */
        function update($new_instance, $old_instance) {                
            $instance = $old_instance;
            $instance['url'] = strip_tags($new_instance['url']);
            $instance['limit'] = (int)($new_instance['limit']);
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['amount_of_words'] = (int)($new_instance['amount_of_words']);
            $instance['hide_description'] = (int)($new_instance['hide_description']);
            $instance['show_date'] = (int)($new_instance['show_date']);
            return $instance;
        }

        /** @see WP_Widget::form */
        function form($instance) {                
            if(isset( $instance['title'] ))
                $title = esc_attr($instance['title']);
            else $title = '';

            if(isset( $instance['limit'] ))
                $limit = (int)($instance['limit']);
            else $limit = 5;

            if(isset( $instance['url'] ))
                $url = esc_attr($instance['url']);
            else $url = '';

            if(isset( $instance['amount_of_words'] ))
                $amount_of_words = esc_attr($instance['amount_of_words']);
            else $amount_of_words = '';

            if(isset( $instance['hide_description'] ))
                $hide_description = (int)($instance['hide_description']);
            else $hide_description = 0;
            
            if(isset( $instance['show_date'] ))
                $show_date = (int)($instance['show_date']);
            else $show_date = 0;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label>

            <br />
            <label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Feed url:'); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo $url; ?>" /></label>

            <br />
            <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('No# messages:'); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo $limit; ?>" /></label>

            <br />
            <label for="<?php echo $this->get_field_id('amount_of_words'); ?>"><?php _e('Amount of words from description:'); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('amount_of_words'); ?>" name="<?php echo $this->get_field_name('amount_of_words'); ?>" type="text" value="<?php echo $amount_of_words; ?>" /></label>

            <br />
            <label for="<?php echo $this->get_field_id('hide_description'); ?>"><?php _e('Hide description:'); ?> &nbsp;
                <input type="checkbox" id="<?php echo $this->get_field_id('hide_description'); ?>" value="1" name="<?php echo $this->get_field_name('hide_description'); ?>" <?php if($hide_description == 1) echo 'checked="checked"'; ?> /></label>
            
            <br />
            <label for="<?php echo $this->get_field_id('show_date'); ?>"><?php _e('Show date?'); ?> &nbsp;
                <input type="checkbox" id="<?php echo $this->get_field_id('show_date'); ?>" value="1" name="<?php echo $this->get_field_name('show_date'); ?>" <?php if($show_date == 1) echo 'checked="checked"'; ?> /></label>
        </p>
        <?php 
        }

    }


    add_action('widgets_init', create_function('', 'return register_widget("SimpleRssWidget");'));