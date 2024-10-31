<?php
use Sunra\PhpSimple\HtmlDomParser;
class Ninja_Araclar_Doviz {
    
    protected $doviz;
    
    protected $doviz_html;
    
    protected $doviz_yon;
    
    function __construct() {
        
    }
    
    function setDovizCinsleri() {
        
        return [
            //'TRY'  => 'Türk Lirası',
            'USD'  => 'Dolar',
            'EUR'  => 'Euro',
            'GBP'  => 'Sterlin',
            'RUB'  => 'Ruble',
            'JPY'  => 'Yen',
            'SAR'  => 'AMK Suud Riyali'
            ];  
    }
    
    public function setDovizYon($kod) {
        global $wpdb;
        $table_name = $this->Doviz_Table();
        $kur = $wpdb->get_row( $wpdb->prepare("SELECT * FROM {$table_name} WHERE kod = %s", $kod) );
        if ($kur->dunalis > $kur->bugunalis) {
            $yon = '<i class="fa fa-caret-down" aria-hidden="true" style="color:red"></i>';
        }elseif($kur->dunalis == $kur->bugunalis) {
            $yon = '<i class="fa fa-minus" aria-hidden="true" style="color:orange"></i>';
        } else {
            $yon = '<i class="fa fa-caret-up" aria-hidden="true" style="color:green"></i>';
        }
        
        return $yon;
        
    }
    
    public function getDovizYon($kod) {
        return $this->setDovizYon($kod);
    }
    
    function getDovizCinsleri() {
        
        return $this->setDovizCinsleri();
        
    }
    
    public function setDovizKuru($code) {
        
        $dovizler = new Ninja_Veriler();
       
        // KUR Kodu $dovizler->getDovizContent()->Currency->attributes()->CurrencyCode;
        // ALIŞ $dovizler->getDovizContent()->Currency->ForexBuying;
        // Satış $dovizler->getDovizContent()->Currency->ForexSelling;
        //print_r($dovizler->getDovizContent()->Currency);
        //print_r($dovizler->getDovizContent());
        
        foreach($dovizler->getDovizContent() as $doviz) {
            
            
            if($code == $doviz->attributes()->CurrencyCode) {
                return $doviz;
                
            }
        }
    }
    
    public function setDovizKuruDun($code) {
        
        $dovizler = new Ninja_Veriler();
       
        // KUR Kodu $dovizler->getDovizContent()->Currency->attributes()->CurrencyCode;
        // ALIŞ $dovizler->getDovizContent()->Currency->ForexBuying;
        // Satış $dovizler->getDovizContent()->Currency->ForexSelling;
        //print_r($dovizler->getDovizContent()->Currency);
        //print_r($dovizler->getDovizContent());
        
        print_r($dovizler->getDovizContent() );
    }
    
    
    
    public function getDovizKuru($code) {
        return $this->setDovizKuru($code);
    }
    
    public function setDovizVt($code) {
        global $wpdb;
        $table_name = $this->Doviz_Table();
        $wpdb->show_errors( true );
        $kur = $wpdb->get_row( $wpdb->prepare("SELECT * FROM {$table_name} WHERE kod = %s", $code) );

        if (is_null($kur)) {
                    $wpdb->insert($table_name,array(
                    
                        'kod' => $code,
                        'dunalis' => $this->getDovizKuru($code)->ForexBuying,
                        'dunsatis' => $this->getDovizKuru($code)->ForexSelling,
                        'bugunalis' => $this->getDovizKuru($code)->ForexBuying,
                        'bugunsatis' => $this->getDovizKuru($code)->ForexSelling,
                        'son_guncelleme' => strtotime('today'),
                        //'bugun' => strtotime('today'),
                        'dun' => strtotime('-1 day')
                        

                    ));
                } else {
                    // unixtimestamp ile 1 gün ve öncesi diye kontrol yaptırmak lazım
                    
                    if ($kur->son_guncelleme < strtotime('today')) {
                        
                        $wpdb->update(
                        $table_name, 
                        	[ 
                                'dunalis' => $kur->bugunalis,
                                'dunsatis' => $kur->bugunsatis,
                                'bugunalis' => $this->getDovizKuru($code)->ForexBuying,
                                'bugunsatis' => $this->getDovizKuru($code)->ForexSelling,
                                'son_guncelleme' => strtotime('today'),
                        		
                        	],
                        	[
                        		'kod' => $code,
	
                        	] 
                    );
                        
                    } 
                    $wpdb->update(
                        $table_name, 
                        	[ 
                                
                                'bugunalis' => $this->getDovizKuru($code)->ForexBuying,
                                'bugunsatis' => $this->getDovizKuru($code)->ForexSelling,
                                'son_guncelleme' => strtotime('today'),
                        		
                        	],
                        	[
                        		'kod' => $code,
	
                        	] 
                    );
                }
    }
    public function setDovizHtml() {
        global $wpdb;
        $table_name = $this->Doviz_Table();
        $wpdb->show_errors( true );
        
        $icerik = '';
        foreach ($this->getDovizCinsleri() as $dovizkodu => $doviz) {
            $dun_timestamp = new DateTime('yesterday');
            $kur = $wpdb->get_row( $wpdb->prepare("SELECT * FROM {$table_name} WHERE kod = %s", $dovizkodu) );
            //print_r($dovizkodu);
            $icerik .='<li>';
			$icerik .='<div class="doviz-bg doviz-bayrak-'.strtolower($dovizkodu).'"></div>';
			$icerik .='<div class="doviz_durum">'.$this->getDovizYon($dovizkodu).'</div>';
			$icerik .='<div class="doviz_alis">'.$kur->bugunalis.'</div>';
			$icerik .='<div class="doviz_satis">'.$kur->bugunsatis.'</div>';
    		$icerik .='</li>';
        }
        
        return $icerik;
    }
    
    public function getDovizHtml() {
         return $this->setDovizHtml();
    }
    
    public function Doviz_Table() {
        
        global $wpdb;
        return  $wpdb->prefix .'doviz_kurlari';
        
    }
    
    public function doviz_yukle() {
        foreach ($this->getDovizCinsleri() as $dovizkodu => $doviz) { 
            $this->setDovizVt($dovizkodu);
        }
    }
    
    
    
}