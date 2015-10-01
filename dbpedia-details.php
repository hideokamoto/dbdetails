<?php
/*
Plugin Name: Dbpedia-details
Version: 0.1-alpha
Description: PLUGIN DESCRIPTION HERE
Author: Hideokamoto
Author URI: http://wp-kyoto.net/
Plugin URI: PLUGIN SITE HERE
Text Domain: dbpedia-details
Domain Path: /languages
*/

add_shortcode ( 'detail' , 'dbdetails_details');
add_action('wp_footer', 'dbdetails_script');

function dbdetails_details( $atts, $content = '' ){
  if ( !$content )
    return;

  return "<div data-dbdetails='{$content}' class='dbdetails'>{$content}</div>";
}

function dbdetails_script() {
  $script  = "<script>";
  $script .= "(function($) {";
  $script .= "var keywords = encodeURIComponent($('.dbdetails').data('dbdetails'));";
  $script .= "console.log(keywords);";
  $script .= "$.ajax({";
  $script .= "  url: 'http://ja.dbpedia.org/sparql?default-graph-uri=http%3A%2F%2Fja.dbpedia.org&query=select+distinct+*+where+%7B+%3Chttp%3A%2F%2Fja.dbpedia.org%2Fresource%2F' + keywords + '%3E+%3Chttp%3A%2F%2Fdbpedia.org%2Fontology%2Fabstract%3E+%3Fo+.+%7D&format=application%2Fsparql-results%2Bjson&timeout=0&debug=on',";
  $script .= "  type:'GET',";
  $script .= "  dataType: 'json',";
  $script .= "  timeout:10000,";
  $script .= "}).done(function(data) {";
  $script .= "  var html = '<blockquote>' + data.results.bindings[0].o.value + '</blockquote>';";
  $script .= "  $('.dbdetails').append(html);";
  $script .= "}).fail(function(data) {";
  $script .= "  var html = '<blockquote>failed</blockquote>';";
  $script .= "  $('.dbdetails').append(html);";
  $script .= "});})(jQuery);</script>';";
  echo $script;
}
