<?php
/**
 * Plugin Name: WP-Property
 * Plugin URI: http://usabilitydynamics.com/products/wp-property/
 * Description: Property and Real Estate Management Plugin for WordPress.  Create a directory of real estate / rental properties and integrate them into you WordPress CMS.
 * Author: Usability Dynamics, Inc.
 * Version: 2.0.0
 * Text Domain: wpp
 * Author URI: http://usabilitydynamics.com
 *
 * Copyright 2012 - 2014 Usability Dynamics, Inc.  ( email : info@usabilitydynamics.com )
 *
 */

/** This Version  */
if( !defined( 'WPP_Version' ) ) {
  define( 'WPP_Version', '2.0.0' );
}

/** Get Directory - not always wp-property */
if( !defined( 'WPP_Directory' ) ) {
  define( 'WPP_Directory', dirname( plugin_basename( __FILE__ ) ) );
}

/** Path for Includes */
if( !defined( 'WPP_Path' ) ) {
  define( 'WPP_Path', plugin_dir_path( __FILE__ ) );
}

/** Path for front-end links */
if( !defined( 'WPP_URL' ) ) {
  define( 'WPP_URL', plugin_dir_url( __FILE__ ) . 'static/' );
}

/** Directory path for includes of template files  */
if( !defined( 'WPP_Templates' ) ) {
  define( 'WPP_Templates', WPP_Path . 'static/views' );
} 
 
if( !function_exists( 'ud_get_wp_property' ) ) {

  /**
   * Returns  Instance
   *
   * @author Usability Dynamics, Inc.
   * @since 2.0.0
   */
  function ud_get_wp_property( $key = false, $default = null ) {
    $instance = \UsabilityDynamics\WPP\Bootstrap::get_instance();
    return $key ? $instance->get( $key, $default ) : $instance;
  }

}

if( !function_exists( 'ud_check_wp_property' ) ) {
  /**
   * Determines if plugin can be initialized.
   *
   * @author Usability Dynamics, Inc.
   * @since 2.0.0
   */
  function ud_check_wp_property() {
    global $_ud_wp_property_error;
    try {
      //** Be sure composer.json exists */
      $file = dirname( __FILE__ ) . '/composer.json';
      if( !file_exists( $file ) ) {
        throw new Exception( __( 'Distributive is broken. composer.json is missed. Try to remove and upload plugin again.', 'wpp' ) );
      }
      $data = json_decode( file_get_contents( $file ), true );
      //** Be sure PHP version is correct. */
      if( !empty( $data[ 'require' ][ 'php' ] ) ) {
        preg_match( '/^([><=]*)([0-9\.]*)$/', $data[ 'require' ][ 'php' ], $matches );
        if( !empty( $matches[1] ) && !empty( $matches[2] ) ) {
          if( !version_compare( PHP_VERSION, $matches[2], $matches[1] ) ) {
            throw new Exception( sprintf( __( 'Plugin requires PHP %s or higher. Your current PHP version is %s', 'wpp' ), $matches[2], PHP_VERSION ) );
          }
        }
      }
      //** Be sure vendor autoloader exists */
      if ( file_exists( dirname( __FILE__ ) . '/vendor/libraries/autoload.php' ) ) {
        require_once ( dirname( __FILE__ ) . '/vendor/libraries/autoload.php' );
      } else {
        throw new Exception( sprintf( __( 'Distributive is broken. %s file is missed. Try to remove and upload plugin again.', 'wpp' ), dirname( __FILE__ ) . '/vendor/libraries/autoload.php' ) );
      }
      //** Be sure our Bootstrap class exists */
      if( !class_exists( '\UsabilityDynamics\WPP\Bootstrap' ) ) {
        throw new Exception( __( 'Distributive is broken. Plugin loader is not available. Try to remove and upload plugin again.', 'wpp' ) );
      }
    } catch( Exception $e ) {
      $_ud_wp_property_error = $e->getMessage();
      return false;
    }
    return true;
  }

}

if( !function_exists( 'ud_my_wp_plugin_message' ) ) {
  /**
   * Renders admin notes in case there are errors on plugin init
   *
   * @author Usability Dynamics, Inc.
   * @since 1.0.0
   */
  function ud_wp_property_message() {
    global $_ud_wp_property_error;
    if( !empty( $_ud_wp_property_error ) ) {
      $message = sprintf( __( '<p><b>%s</b> can not be initialized. %s</p>', 'wpp' ), 'WP-Property', $_ud_wp_property_error );
      echo '<div class="error fade" style="padding:11px;">' . $message . '</div>';
    }
  }
  add_action( 'admin_notices', 'ud_wp_property_message' );
}

if( ud_check_wp_property() ) {
  //** Initialize. */
  ud_get_wp_property();
}