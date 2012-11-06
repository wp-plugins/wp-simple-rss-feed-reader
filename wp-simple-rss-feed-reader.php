<?php
/*
Plugin Name: WP Simple Rss Feed Reader 
Description: Plugin to view an rss feed on your page or post. [simple-rss feed="http://www.yourfeed.com/myfeed.xml" limit=10]. Simply use the shortcode and the RSS feed will be shown in HTML to your visitor
Version: 0.6.3
Author: Viancen
Author URI: http://viancen.com
License: GPL2
*/

    function SimplRssfirstWords($string,$words=100){
        $wo = explode(" ",$string);
        $c=0;
        $return='';
        foreach ($wo as $piece){
            $c++;
            if($words>$c)
                $return .= " ".$piece;
        }
        return $return;
    }

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

            //get shorten
            $shorten = 0;
            if(isset($instance['shorten']))
                $shorten = $instance['shorten'];

            //get hide_description
            $hide_description = 0;
            if(isset($instance['hide_description']))
                $hide_description = $instance['hide_description'];

            //get url
            $url = '';
            if(isset($instance['url']))
                $url = $instance['url'];

            echo $before_widget; 

            if ( $title )
                echo $before_title . $title . $after_title;

            $i = 0;
            $url = html_entity_decode($url);
            $xml = simplexml_load_file($url); 
            $return = "";
            $return .= "<ul class=\"wp-simple-rss-list\">";
            foreach($xml->channel->item as $item) {
                if($i == $limit) break; 
                $titel = $item->title; 
                $link = $item->link; 
                
                if( $shorten == 1 )
                	
                	$titel = substr($titel,0,30).'...';
                
                    $return .= ' <li><h3><a href="'.$link.'" target="_blank" title="'.($titel).'" target="_blank" class="wp-simple-rss-link">'.($titel).'</a></h3>';
                    
                    if($hide_description == 0)
                   		$return .= '<span>'.(SimplRssfirstWords($item->description,15)).'...</span><br />'; 
                   	
                   	$return .= '</li>';
                   
                $i++;
            } 
            $return .= "</ul><br /><a href=\"".$xml->channel->link."\" class=\"wp-simple-rss-feed-url\">".$xml->channel->link."</a>";
            echo $return;
            echo $after_widget;

        }

        /** @see WP_Widget::update */
        function update($new_instance, $old_instance) {                
            $instance = $old_instance;
            $instance['url'] = strip_tags($new_instance['url']);
            $instance['limit'] = (int)($new_instance['limit']);
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['shorten'] = (int)($new_instance['shorten']);
            $instance['hide_description'] = (int)($new_instance['hide_description']);
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
            
            if(isset( $instance['shorten'] ))
                $shorten = (int)($instance['shorten']);
            else $shorten = 0;
            
            if(isset( $instance['url'] ))
                $url = esc_attr($instance['url']);
            else $url = '';
            
            if(isset( $instance['hide_description'] ))
                $hide_description = (int)($instance['hide_description']);
            else $hide_description = 0;
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
            <label for="<?php echo $this->get_field_id('shorten'); ?>"><?php _e('Shorten link:'); ?> &nbsp;
            <input type="checkbox" id="<?php echo $this->get_field_id('shorten'); ?>" value="1" name="<?php echo $this->get_field_name('shorten'); ?>" <?php if($shorten == 1) echo 'checked="checked"'; ?> /></label>
            
            <br />
            <label for="<?php echo $this->get_field_id('hide_description'); ?>"><?php _e('Hide description:'); ?> &nbsp;
            <input type="checkbox" id="<?php echo $this->get_field_id('hide_description'); ?>" value="1" name="<?php echo $this->get_field_name('hide_description'); ?>" <?php if($hide_description == 1) echo 'checked="checked"'; ?> /></label>
        </p>
        <?php 
    }

}


add_action('widgets_init', create_function('', 'return register_widget("SimpleRssWidget");'));


/**
* Short tag function
* 
* @param mixed $url
*/

function wp_simple_rss_feed_reader($url,$limit=50){
	$url = html_entity_decode($url);
    $xml = simplexml_load_file($url); 
    
    $return = "<a href=\"".$xml->channel->link."\" class=\"wp-simple-rss-feed-url\" target=\"_blank\">".$xml->channel->link."</a><hr />";
    $return .= "<ul  class=\"wp-simple-rss-list\">";
    $i=0;
    foreach($xml->channel->item as $item) 
    { 
    	$i++;
    	
        $titel = $item->title; 
        $link = $item->link; 
            $return .= ' <li><h3><a href="'.$link.'" target="_blank" title="'.($titel).'" class="wp-simple-rss-link">'.($titel).'</a></h3>
           <span>'.($item->description).'</span><br /><br /> </li> '; 
        if($i == $limit) break;
    } 
    $return .= "</ul>";
    return $return;
}


/**
* Include the shortscriptfunctions for rss
* 
* enables:
* [simple-rss feed="http://www.xxx.feed"]
* 
*/
function wp_simple_rss_feed_shorttag($atts) {
    extract(shortcode_atts(array(
    'feed'         => '',
    'limit'         => ''
    ), $atts));

    return wp_simple_rss_feed_reader($feed,$limit);
}
//add shortcodes
add_shortcode( 'simple-rss', 'wp_simple_rss_feed_shorttag');
?>