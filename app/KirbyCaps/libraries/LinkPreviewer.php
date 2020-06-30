<?php
namespace KirbyCaps\Libraries;

use KirbyCaps\Libraries\UrlScraper;

class LinkPreviewer
{
	protected $url;
	protected $scrapper;
	protected $isInit = false;
	protected $isSuccess = false;

	/**
	 *
	 * @return 
	 */
	public function __construct($url)
	{
		$this->url = $url;
		$this->scrapper = new UrlScraper;
		
	}

	/**
	 *
	 * @return  void
	 */
	public function init()
	{
		$this->isInit = true;
		$this->isSuccess = $this->scrapper->init_scrapper($this->url);
	}

	/**
	 *
	 * @return array
	 */
	public function getAllData()
	{
		if (!$this->isInit) {
			$this->init();
		}
		if (!$this->isSuccess) {
			return [];
		}
		$data = [];

		$data['title'] = $this->scrapper->getTitle();
		$data['url'] = $this->url;
		$data['description'] = $this->scrapper->getDescription();
		$data['is_youtube'] = false;
		$data['is_vimeo'] = false;
		$data['image'] = $this->scrapper->getImage();

		if ($this->isVimeoUrL($this->url)) {
			$data['is_vimeo'] = true;
			$data['vimeo_id'] = $this->getVimeoIdFromUrl($this->url);
			$data['image'] = $this->getVimeoImage($data['vimeo_id']);
		} elseif ($this->isYouTubeUrL($this->url)) {
			$data['title'] = $this->scrapper->getYoutubeTitle();
			$data['is_youtube'] = true;
			$data['youtube_id'] = $this->getYoutubeIdFromUrl($this->url);
			$data['image'] = $this->getYouTubeImage($data['youtube_id']);
		}

		return $data;
	}

	/**
	 * check if URL is vimeo url
	 *
	 * @param string $url
	 * @return boolean
	 */
	public function isVimeoUrL($url)
	{
		$vimeo_pattern  = "/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/.*/";
		return preg_match($vimeo_pattern, $url);
	}

	/**
	 * check if URL is youtube url
	 *
	 * @param string $url
	 * @return boolean
	 */
	public function isYouTubeUrL($url)
	{
		$youtube_pattern = "/^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/";
		return preg_match($youtube_pattern, $url);
	}

	/**
	 * Get Youtube video ID from URL
	 *
	 * @param string $url
	 * @return string Youtube video ID or FALSE if not found
	 */
	public function getYoutubeIdFromUrl($url) {
		if (empty(trim($url))) {
			return false;
		}
	    $parts = parse_url($url);
	    if(isset($parts['query'])){
	        parse_str($parts['query'], $qs);
	        if(isset($qs['v'])){
	            return $qs['v'];
	        }elseif(isset($qs['vi'])){
	            return $qs['vi'];
	        }
	    }
	    if(isset($parts['path'])){
	        $path = explode('/', trim($parts['path'], '/'));
	        return $path[count($path)-1];
	    }
	    return false;
	}

	/**
	 * Get Vimeo video ID from URL
	 *
	 * @param string $url
	 * @return string Vimeo video ID or FALSE if not found
	 */
	public function getVimeoIdFromUrl($url) {
		preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/", $url, $url_pieces);
		return @$url_pieces[5];
	}
	/**
	 * Get Youtube image from id
	 *
	 * @param string $id
	 * @return string image url
	 */
	public function getYouTubeImage($id) {
		if (!$id || empty($id) || is_null($id)) {
			return "";
		}
		return "https://i1.ytimg.com/vi/".$id."/hqdefault.jpg";
	}

	/**
	 * Get Vimeo image from id
	 *
	 * @param string $id
	 * @return string
	 */
	public function getVimeoImage($id) {

		if (!$id || empty($id) || is_null($id)) {
			return "";
		}
		$hash = json_decode(@file_get_contents("https://vimeo.com/api/v2/video/".$id.".json"));
		return array_key_exists('thumbnail_large', $hash[0]) ? $hash[0]->thumbnail_large : '';
	}
}