<?php
use Sunra\PhpSimple\HtmlDomParser;
class Ninja_Araclar_Burclar {
    
    protected $burc;
    
    function __construct() {
        
    }
    public function setBurclar() {
        
        return [
                        'koc' => 'Koç',
                        'boga' => 'Boğa',
                        'ikizler' => 'İkizler',
                        'yengec' => 'Yengeç',
                        'aslan' => 'Aslan',
                        'basak' => 'Başak',
                        'terazi' => 'Terazi',
                        'akrep' => 'Akrep',
                        'yay' => 'Yay',
                        'oglak' => 'Oğlak',
                        'kova' => 'Kova',
                        'balik' => 'Balık',
                    ];
            
    }
    public function getBurclar() {
        
        return $this->setBurclar();
        
    }

    
    public function installBurclar() {
       global $wpdb;
       $burc_veri = new Ninja_Veriler();
       
       foreach($this->getBurclar() as $burckey => $burc) {
           
           $wpdb->replace( 
            	$wpdb->prefix.'burclar', 
            	array( 
                    'burc_id' => $burckey,
                    'burc_adi' => $burc,
            		'aciklama' => $burc_veri->getBurcContent($burckey)
            	)
            );
          
       }
            
    }
    
    public function setBurc($burc) {
        global $wpdb;
        
        $icerik = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT aciklama 
                    FROM {$wpdb->prefix}burclar 
                    WHERE burc_id = %s",
                    $burc
                ));
        
        return $icerik;
        
    }
    
   public function getBurc(){
       
       foreach($this->getBurclar() as $burckey => $burc) {
           
           return $this->setBurc($burckey);
          
       }
       
   }
   
   public function setBurcHtml() {
       
       $icerik = '<ul>';
       
       foreach($this->getBurclar() as $burckey => $burc) {
           
           $icerik .= '<li><a href="#'.$burckey.'">'.$burc.'</a></li>';
       }
       
       $icerik .='</ul>';
       
       foreach($this->getBurclar() as $burckey => $burc) {
           
           $icerik .= '<div class="widget_burc" id="'.$burckey.'"><img src="'.plugin_dir_url(__FILE__).'../public/images/'.$burckey.'.png">'.$this->setBurc($burckey).'</div>';
       }
       
       
       $icerik .= '';
       return $icerik;
   }
   
   public function getBurcHtml() {
       
       return $this->setBurcHtml();
       
   }
    
  
}