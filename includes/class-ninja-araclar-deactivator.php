<?php

/**
 * Fired during plugin deactivation
 *
 * @link       www.tema.ninja
 * @since      1.0.0
 *
 * @package    Ninja_Araclar
 * @subpackage Ninja_Araclar/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Ninja_Araclar
 * @subpackage Ninja_Araclar/includes
 * @author     TemaNinja <destek@tema.ninja>
 */
class Ninja_Araclar_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		global $wpdb;
		
		// Puan Durumunu kaldıralım
		
		
		$puan_durumu_table = $wpdb->prefix . 'superlig_puan';  	$wpdb->query( "DROP TABLE IF EXISTS {$puan_durumu_table}" );
		
		
		// Puan Tablosu Cronu kaldıralım
		
		wp_clear_scheduled_hook('puantablosu_cron');
		
		
		// Burçlar tablosunu kaldıralım
		
		$burclar_table =  $wpdb->prefix . 'burclar';  $wpdb->query( "DROP TABLE IF EXISTS {$burclar_table}" );
		
		// Puan Tablosu Cronu kaldıralım
		
		wp_clear_scheduled_hook('burclar_cron');
		
		$doviz_table =  $wpdb->prefix . 'doviz_kurlari';  $wpdb->query( "DROP TABLE IF EXISTS {$doviz_table}" );
		
		// Puan Tablosu Cronu kaldıralım
		
		wp_clear_scheduled_hook('doviz_cron');
		
	}

}
