<?php
use Sunra\PhpSimple\HtmlDomParser;
class Ninja_Veriler {
    
    protected $puantablosu;
    
    protected $burclar;
    
    protected $dovizler;
 
    function __construct() {
        $this->puantablosu = 'http://www.hurriyet.com.tr/sporarena/puan-durumu/?sportId=1&uniqueTournamentId=52';
        $this->burclar = 'http://mahmure.hurriyet.com.tr/astroloji/burclar/';
        $this->dovizler = 'http://www.tcmb.gov.tr/kurlar/today.xml';
    }
    
    public function getBody($tip)
    {
        $client = new \GuzzleHttp\Client();
        
		$respond = $client->request('GET',$tip);
		
		return $respond->getBody();

    }
    
    public function getPuanContent($html)
    {
        
        $dom = HtmlDomParser::str_get_html( $this->getBody($this->puantablosu) );
        
        return $dom->find($html); 
        
    }
    public function getBurcContent($burc)
    {
        
        $dom = HtmlDomParser::str_get_html( $this->getBody($this->burclar.''.$burc) );
        
        return $dom->find('.burcDetail p',0)->plaintext; 
        
    }
    
    public function getDovizContent()
    {
        return new SimpleXMLElement($this->getBody($this->dovizler)->getContents());
        
    }
    
 
    
}