<?php
/*
Plugin Name: Dbpedia-details
Version: 0.1-alpha
Description: DBPedia日本語版から説明文を取得してポップアップ表示させるプラグインです。
Author: Hideokamoto
Author URI: http://wp-kyoto.net/
Plugin URI: PLUGIN SITE HERE
Text Domain: dbpedia-details
Domain Path: /languages
*/

add_shortcode ( 'db-detail' , 'dbdetails_details');

function dbdetails_details( $atts, $content = '' ){
  if ( !$content )
    return;

  $quote = dbdetails_get_quote($content);
  return "<span data-dbdetails='{$content}' class='dbdetails'>{$content}{$quote}</span>";
}

function dbdetails_get_quote($content){

  $keyword = urlencode($content);
  $url = "http://ja.dbpedia.org/sparql?default-graph-uri=http%3A%2F%2Fja.dbpedia.org&query=select+distinct+*+where+%7B+%3Chttp%3A%2F%2Fja.dbpedia.org%2Fresource%2F{$keyword}%3E+%3Chttp%3A%2F%2Fdbpedia.org%2Fontology%2Fabstract%3E+%3Fo+.+%7D&format=application%2Fsparql-results%2Bjson&timeout=0&debug=on";
  $quote = wp_remote_get($url);
  $quote = json_decode($quote["body"])->results->bindings[0]->o->value;
  $html = "<span class='dbdetails-quote'>{$quote}</span>";
  return $html;
}

function dbdetail_scripts() {
	wp_enqueue_style( 'dbdetail-style', plugin_dir_url( __FILE__ ) . 'inc/dbdetail-style.css' );
	wp_enqueue_script( 'dbdetail-script', plugin_dir_url( __FILE__ ) . '/inc/dbdetail.js', array(), '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'dbdetail_scripts' );
