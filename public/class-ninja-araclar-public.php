<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       www.tema.ninja
 * @since      1.0.0
 *
 * @package    Ninja_Araclar
 * @subpackage Ninja_Araclar/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ninja_Araclar
 * @subpackage Ninja_Araclar/public
 * @author     TemaNinja <destek@tema.ninja>
 */
class Ninja_Araclar_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ninja_Araclar_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ninja_Araclar_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ninja-araclar-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'_awesome', plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ninja_Araclar_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ninja_Araclar_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ninja-araclar-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-tabs');


	}
	
	public function ninja_araclar_widgets() {
		register_widget( 'NinjaAraclarPuanTablosu_Widget' );
		register_widget( 'NinjaAraclarBurclar_Widget' );
		register_widget( 'NinjaAraclarDoviz_Widget' );
	}
	
	public function puan_tablosu_yukle($lig) {
		global $wpdb;
		$table = $wpdb->prefix .''.$lig."_puan"; 
		
		return $wpdb->get_results("SELECT * FROM $table", OBJECT_K);

	}
	
	public function puan_tablosu_array($lig) {
		

			$tablo = '';
		foreach($this->puan_tablosu_yukle($lig) as $takim) {
			$class = $this->tablo_statu($takim->simdikisira);
			$tablo .= '<tr'.$class.'>';
			$tablo .= '<td>'.$takim->simdikisira.'</td>';
			$tablo .= '<td>'.$takim->oncekisira.'</td>';
			$tablo .= '<td>'.$takim->takim.'</td>';
			$tablo .= '<td>'.$takim->oynadigi.'</td>';
			$tablo .= '<td>'.$takim->galibiyet.'</td>';
			$tablo .= '<td>'.$takim->maglubiyet.'</td>';
			$tablo .= '<td>'.$takim->beraberlik.'</td>';
			$tablo .= '<td>'.$takim->attigi.'</td>';
			$tablo .= '<td>'.$takim->yedigi.'</td>';
			$tablo .= '<td>'.$takim->puan.'</td>';
			$tablo .= '</tr>';
			
		}
		
		return $tablo;
		
	}
	
	public function puan_tablosu_statu($sira) {
		if ($sira <= 2) {
			$class=' class="na_chmp"';
		}elseif($sira <= 4 && $sira > 2) {
			$class=' class="na_uefa"';
		}elseif($sira >= 16) {
			$class=' class="na_gulegule"';
		}else{
			$class='';
		}
		
		return $class;
	}
	
	public function puan_tablosu_yukselen() {
		
	}

}
