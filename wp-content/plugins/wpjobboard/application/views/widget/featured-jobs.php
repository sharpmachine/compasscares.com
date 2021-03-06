<p>
    <label for="<?php echo $widget->get_field_id("title") ?>">
    <?php _e("Title", WPJB_DOMAIN) ?>
    <?php $this->_html->input(array(
        "id" => $widget->get_field_id("title"),
        "name" => $widget->get_field_name("title"),
        "value" => $instance["title"],
        "type" => "text",
        "class"=> "widefat",
        "maxlength" => 100
    )); 
    ?>
   </label>
</p>

<p>
    <label for="<?php echo $widget->get_field_id("count") ?>">
    <?php _e("Number of jobs", WPJB_DOMAIN) ?>
    <?php $this->_html->input(array(
        "id" => $widget->get_field_id("count"),
        "name" => $widget->get_field_name("count"),
        "value" => $instance["count"],
        "type" => "text",
        "class"=> "widefat",
        "maxlength" => 100
    )); 
    ?>
   </label>
</p>

<p>
   <label for="<?php echo $widget->get_field_id("hide") ?>">
   <?php _e("Show on job board only", WPJB_DOMAIN) ?>
   <?php $this->_html->input(array(
       "id" => $widget->get_field_id("hide"),
       "name" => $widget->get_field_name("hide"),
       "checked" => (int)$instance["hide"],
       "value" => 1,
       "type" => "checkbox",
       "class"=> "widefat"
   )); 
   ?>
   </label>
</p>
