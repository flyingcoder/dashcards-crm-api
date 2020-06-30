<?php

namespace App\Traits;

use Illuminate\Support\Facades\URL;
use KirbyCaps\Libraries\LinkPreviewer;

trait HasUrlTrait
{
	/**
     * Check if given url can be embed in iframe or not base on headers
     * @param $url | string
     * return boolean
     */
	public function iframeUnavailable($url) 
    {
        try{
            $url_headers = get_headers($url);
            if(is_array($url_headers)) {
                foreach ($url_headers as $key => $value){
                    $x_frame_options_deny = strpos(strtolower($url_headers[$key]), strtolower('X-Frame-Options: DENY'));
                    $x_frame_options_sameorigin = strpos(strtolower($url_headers[$key]), strtolower('X-Frame-Options: SAMEORIGIN'));
                    $x_frame_options_allow_from = strpos(strtolower($url_headers[$key]), strtolower('X-Frame-Options: ALLOW-FROM'));
                    if ($x_frame_options_deny !== false || $x_frame_options_sameorigin !== false || $x_frame_options_allow_from !== false) {
                        return true;
                    }
                }
            }
            return false;// can be iframe
        } catch (\Exception $ex) { 
	        return true;
        }
    }

    /**
     * Get preview data from url
     * @param $url | string
     * return array
     */
    public function getPreviewArray($url)
    {
		$scraper =	new LinkPreviewer($url);
		try{
			$data = $scraper->getAllData();
			$data['canIframe'] = !$this->iframeUnavailable($url);

			return $data;
		} catch (\Exception $ex) {
			return [
				'canIframe' => false
			];
	 	}
    }
}