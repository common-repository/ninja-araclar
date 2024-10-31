<?php

class NinjaAraclarDoviz_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'DÖviz_widget', // Base ID
			esc_html__( 'Döviz Kurları', 'text_domain' ), // Name
			array( 'description' => esc_html__( 'Döviz Kurları', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		global $wpdb;
		$doviz = new Ninja_Araclar_Doviz();
		$dovizler = $instance['dovizler'];
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		} 
		$tablo = new Ninja_Araclar_Doviz();
	
		?>
		<div id="doviz_kurlari">

    		<ul>
    			<li>
    				<div class="doviz">Döviz</div>
    				<div class="doviz_durum"></div>
    				<div class="doviz_alis">Alış</div>
    				<div class="doviz_satis">Satış</div>
    			</li>
    			<?php 
    			
    			global $wpdb;
		        $table_name = $doviz->Doviz_Table();
		        
		        
		        $icerik = '';
		        foreach ($dovizler as $dovizkodu) {
		        	
		        		$kur = $wpdb->get_row( $wpdb->prepare("SELECT * FROM {$table_name} WHERE kod = %s", $dovizkodu) );
			            $icerik .='<li>';
						$icerik .='<div class="doviz-bg doviz-bayrak-'.strtolower($dovizkodu).'"></div>';
						$icerik .='<div class="doviz_durum">'.$doviz->getDovizYon($dovizkodu).'</div>';
						$icerik .='<div class="doviz_alis">'.$kur->bugunalis.'</div>';
						$icerik .='<div class="doviz_satis">'.$kur->bugunsatis.'</div>';
			    		$icerik .='</li>';
		        	
		            
		        }
		        echo $icerik;
    			?>
    		</ul>
			
    	</div>

		<?php
		
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Döviz Kurları', 'text_domain' );
		$dovizler = !empty($instance["dovizler"]) ? $instance["dovizler"] : ['USD','EUR','GBP'];
		$doviz = new Ninja_Araclar_Doviz();
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
		foreach ($doviz->getDovizCinsleri() as $dovizkodu => $dovizcinsi) { ?>
			<p>
			<input class="checkbox" id="<?php echo $this->get_field_id("dovizler") . $dovizkodu; ?>" name="<?php echo $this->get_field_name("dovizler"); ?>[]" type="checkbox" value="<?php echo $dovizkodu; ?>" <?php checked(in_array($dovizkodu, $dovizler)); ?>
			<label for="<?php echo $this->get_field_id('dovizler').$dovizkodu; ?>"><?php echo $dovizkodu; ?> <?php _e( ' Listelensin' ); ?></label><br />
			</p>	
		<?php }
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['dovizler'] = !empty($new_instance['dovizler']) ? $new_instance['dovizler'] : ['USD','EUR'];
		return $instance;
	}

}