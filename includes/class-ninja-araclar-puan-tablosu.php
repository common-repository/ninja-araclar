<?php
use Sunra\PhpSimple\HtmlDomParser;
class PuanTablosu {

    protected $url = 'http://www.hurriyet.com.tr/sporarena/puan-durumu/?sportId=1&uniqueTournamentId=52';
    
    protected $durum;
    
    public function __construct() 
    {
        
     

    }
    
    public function body_yukle()
    {
        $client = new \GuzzleHttp\Client();
        
		$respond = $client->request('GET',$this->url);
		
		return $respond->getBody();

        
    }
    
    public function tablo_yukle()
    {
        
        $dom = HtmlDomParser::str_get_html( $this->body_yukle() );
        
        return $dom->find('table.leauge-rating-table tbody > tr'); 
        
    }
    
    public function tablo_ayristir()
    {
        global $wpdb;
        $table_name = $this->puantablosu_table();
        foreach($this->tablo_yukle() as $tablo) {
            if (!is_null($tablo->find('td.team',0)->plaintext)) {
                
               $takim_adi = $tablo->find('td.team',0)->plaintext;
               $results = $wpdb->get_results( "SELECT takim FROM {$table_name} WHERE takim = $takim_adi");
                if (empty($results->takim)) {
                    $wpdb->insert($this->puantablosu_table(),array(
                    
                        'takim' => $tablo->find('td.team',0)->plaintext,
                        'simdikisira' => $tablo->find('td.sort span.position',0)->plaintext,
                        'oncekisira' => $tablo->find('td.sort .hint span.value small',0)->plaintext,
                        'oynadigi' => $tablo->children(3)->plaintext,
                        'galibiyet' => $tablo->children(4)->plaintext,
                        'beraberlik' => $tablo->children(5)->plaintext,
                        'maglubiyet' => $tablo->children(6)->plaintext,
                        'attigi' => $tablo->children(7)->plaintext,
                        'yedigi' => $tablo->children(8)->plaintext,
                        'avaraj' => $tablo->children(9)->plaintext,
                        'puan' => $tablo->children(10)->plaintext
                    ));
                } else {
                    $wpdb->update(
                        $this->puantablosu_table(), 
                        	[ 
                                'simdikisira' => $tablo->find('td.sort span.position',0)->plaintext,
                                'oncekisira' => $tablo->find('td.sort .hint span.value small',0)->plaintext,
                                'oynadigi' => $tablo->children(3)->plaintext,
                                'galibiyet' => $tablo->children(4)->plaintext,
                                'beraberlik' => $tablo->children(5)->plaintext,
                                'maglubiyet' => $tablo->children(6)->plaintext,
                                'attigi' => $tablo->children(7)->plaintext,
                                'yedigi' => $tablo->children(8)->plaintext,
                                'avaraj' => $tablo->children(9)->plaintext,
                                'puan' => $tablo->children(10)->plaintext 
                        		
                        	],
                        	[
                        		'takim' => $tablo->find('td.team',0)->plaintext,
	
                        	] 
                    );
                }
                
            }
            
		    
		}
        
    }
    
    public function takim_kontrol($takim_adi) {
        global $wpdb;
        $table_name = $this->puantablosu_table();
        
        $check = $wpdb->get_row( "SELECT * FROM {$table_name} WHERE takim = $takim_adi" );
        if(is_null($check)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function puantablosu_table() {
        
        global $wpdb;
        return  $wpdb->prefix .'superlig_puan';
    } 
    
    public function veritabani_kontrol ($table_name) 
    {
        global $wpdb;
        $table_name = $wpdb->prefix . $table_name; 
        
        $islem = $this->veritabani_icerik_kontrolu($table_name);
        
        if ($islem) {
            $wpdb->insert(
                $table_name, 
                	[ 
                		'takim' => $data['takim'],
                        'simdikisira' => $data['simdikisira'],
                        'oncekisira' => $data['oncekisira'],
                        'oynadigi' => $data['oynadigi'],
                        'galibiyet' => $data['galibiyet'],
                        'beraberlik' => $data['beraberlik'],
                        'maglubiyet' => $data['maglubiyet'],
                        'attigi' => $data['attigi'],
                        'yedigi' => $data['yedigi'],
                        'yedigi' => $data['yedigi'],
                        'puan' => $data['puan']
                		
                	]
                );
        } else {
            
        }
    }
    
    public function veritabani_icerik_kontrolu($table_name,$data = array())
    {
        global $wpdb;
        $table_name = $wpdb->prefix . $table_name; 
        $tablo = $wpdb->get_row( "SELECT * FROM $table_name" );
        
        if (is_null($tablo)) {
            $wpdb->insert($table_name, 
                	[ 
                		'takim' => $data['takim'],
                        'simdikisira' => $data['simdikisira'],
                        'oncekisira' => $data['oncekisira'],
                        'oynadigi' => $data['oynadigi'],
                        'galibiyet' => $data['galibiyet'],
                        'beraberlik' => $data['beraberlik'],
                        'maglubiyet' => $data['maglubiyet'],
                        'attigi' => $data['attigi'],
                        'yedigi' => $data['yedigi'],
                        'yedigi' => $data['yedigi'],
                        'puan' => $data['puan']
                		
                	]
                );
        } else {
            $wpdb->update(
                $table_name, 
                	[ 
                		'' => $data, 
                		
                	], 
                	[
                		'%s', 
                		'%d' 
                	] 
                );
        }
        
    }
    
    public function veritabanina_yaz($table_name) {
        global $wpdb;
        $wpdb->insert(
                $table_name, 
                	[ 
                		'' => $data, 
                		
                	], 
                	[
                		'%s', 
                		'%d' 
                	] 
                );
    }
    
    public function veritabanini_guncelle($table_name) {
        global $wpdb;
        $wpdb->update(
                $table_name, 
                	[ 
                		'' => $data, 
                		
                	], 
                	[
                		'%s', 
                		'%d' 
                	] 
                );
    }
    
    public function tablo_vt_yukle($lig) {
		global $wpdb;
		$table = $wpdb->prefix .''.$lig."_puan"; 
		
		return $wpdb->get_results("SELECT * FROM $table ORDER BY simdikisira ASC", OBJECT_K);

	}
	
	public function tablo_dizi($lig) {
		

			$tablo = '';
		foreach($this->tablo_vt_yukle($lig) as $takim) {
			$class = $this->tablo_statu($takim->simdikisira);
			$tablo .= '<tr'.$class.'>';
			$tablo .= '<td class="na_tablo_sira">'.$takim->simdikisira.'</td>';
			$tablo .= '<td class="na_tablo_durum">'.$this->tablo_yukselen($takim->oncekisira,$takim->simdikisira).'</td>';
			$tablo .= '<td class="na_tablo_takim">'.$takim->takim.'</td>';
			$tablo .= '<td>'.$takim->oynadigi.'</td>';
			$tablo .= '<td>'.$takim->galibiyet.'</td>';
			$tablo .= '<td>'.$takim->maglubiyet.'</td>';
			$tablo .= '<td>'.$takim->beraberlik.'</td>';
			$tablo .= '<td>'.$takim->attigi.'</td>';
			$tablo .= '<td>'.$takim->yedigi.'</td>';
			$tablo .= '<td class="na_tablo_puan">'.$takim->puan.'</td>';
			$tablo .= '</tr>';
			
		}
		
		return $tablo;
		
	}
	
	public function tablo_statu($sira) {
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
	
	public function tablo_yukselen($onceki,$yeni) {
	    
	    if ($onceki < $yeni) {
	        $sira = '<i class="fa fa-caret-down" aria-hidden="true" style="color:red"></i>';
	    }elseif($onceki > $yeni) {
	        $sira = '<i class="fa fa-caret-up" aria-hidden="true" style="color:green"></i>';
	    }elseif($onceki == $yeni) {
	        $sira = '<i class="fa fa-minus" aria-hidden="true" style="color:orange"></i>';
	    }
	    
	    return $sira;
		
	}

}