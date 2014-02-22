<?php
header("Content-type: application/json; charset=UTF-8");
 $cats = get_categories($args); 
 $index = 0;
 foreach($cats as $key => $category) {
  $cats[$key]->feedlink = get_category_feed_link($category->cat_ID) . "?socialreader=1";
  $cats[$key]->name = html_entity_decode($cats[$key]->name);
 }
 print json_encode($cats);

?>
