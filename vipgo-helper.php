<?php
// make sure these function run in VIP Go environment where `plugins_loaded` is already fired when loading the plugin
add_action( 'after_setup_theme', 'instant_articles_load_textdomain' );
add_action( 'after_setup_theme', 'instant_articles_load_compat' );
