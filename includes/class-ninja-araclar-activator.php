<?php

/**
 * Fired during plugin activation
 *
 * @link       www.tema.ninja
 * @since      1.0.0
 *
 * @package    Ninja_Araclar
 * @subpackage Ninja_Araclar/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ninja_Araclar
 * @subpackage Ninja_Araclar/includes
 * @author     TemaNinja <destek@tema.ninja>
 */
class Ninja_Araclar_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		
		global $wpdb;
		$puan_tablosu_table_name = $wpdb->prefix . "superlig_puan"; 
		$burclar_table_name = $wpdb->prefix . "burclar"; 
		$doviz_table_name = $wpdb->prefix . "doviz_kurlari"; 
		$charset_collate = $wpdb->get_charset_collate();

		$puan_tablosu = "CREATE TABLE IF NOT EXISTS $puan_tablosu_table_name (
				  id mediumint(9) NOT NULL AUTO_INCREMENT,
				  takim varchar(55) NOT NULL UNIQUE,
				  simdikisira mediumint(2) NOT NULL,
				  oncekisira mediumint(2) NOT NULL,
				  oynadigi mediumint(2) NOT NULL,
				  galibiyet mediumint(2) NOT NULL,
				  beraberlik mediumint(2) NOT NULL,
				  maglubiyet mediumint(2) NOT NULL,
				  attigi mediumint(3) NOT NULL,
				  yedigi mediumint(3) NOT NULL,
				  avaraj int(3) NOT NULL,
				  puan mediumint(3) NOT NULL,
				  PRIMARY KEY  (id)
				) $charset_collate;";
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $puan_tablosu );
		
		$burclar_tablosu = "CREATE TABLE IF NOT EXISTS $burclar_table_name (
				  id mediumint(9) NOT NULL AUTO_INCREMENT,
				  burc_id varchar(55) NOT NULL UNIQUE,
				  burc_adi varchar(55) NOT NULL UNIQUE,
				  aciklama text(1000) NOT NULL,
				  PRIMARY KEY  (id)
				) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );		
		dbDelta( $burclar_tablosu );
		
		
		$doviz_tablosu = "CREATE TABLE IF NOT EXISTS $doviz_table_name (
				  id mediumint(9) NOT NULL AUTO_INCREMENT,
				  kod varchar(3) NOT NULL UNIQUE,
				  dunalis float(11),
				  dunsatis float(11),
				  bugunalis float(11) NOT NULL,
				  bugunsatis float(11) NOT NULL,
				  son_guncelleme int(11) NOT NULL,
				  bugun int(11) NOT NULL,
				  dun int(11) NOT NULL,
				  PRIMARY KEY  (id)
				) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );		
		dbDelta( $doviz_tablosu );
		
		if ( ! wp_next_scheduled( 'puantablosu_cron' ) ) {
			wp_schedule_event( time(), 'daily', 'puantablosu_cron' );
		}
		
		if ( ! wp_next_scheduled( 'burclar_cron' ) ) {
			wp_schedule_event( time(), 'daily', 'burclar_cron' );
		}
		
		if ( ! wp_next_scheduled( 'doviz_cron' ) ) {
			wp_schedule_event( time(), 'hourly', 'doviz_cron' );
		}

	}
	
	public static function burclari_yukle() {
		$burc = new Ninja_Araclar_Burclar();
		$burc->installBurclar();
		
	}
	public static function puantablosu_yukle() {
		$puan = new PuanTablosu();
		$puan->tablo_ayristir();
		
	}
	public static function doviz_yukle() {
		$doviz = new Ninja_Araclar_Doviz();
		$doviz->doviz_yukle();
		
	}
	


}
