<?php

namespace App\Utilities\KirbyCaps;

/**
 * Class: Scraper
 * Author Kirby Capangpangan
 */

use Exception;

class UrlScraper
{
    public $html = false;
    public $current_directory;
    public $current_url;
    public $siteBaseUrl;
    public $response_header = false;
    public $urlsList = array();
    public $tagsList = array();
    public $httpCode = 200;
    protected $host;
    protected $domDoc = false;
    protected $agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3";
    public $referrer = 'http://www.google.com';
    public $error = "System cannot get the contents from the provided url, The site may disallow sharing of its contents or the site has anti-bot system which prevent the site from being scrapped.";

    /**
     * Get error message base on code
     * @param integer $code
     * @return string
     */
    public function getError($code = 404)
    {
        if ($code == 0) {

            return "Hmm. We’re having trouble finding that site. We can’t connect to the server at " . $this->baseUrl;
        } elseif ($code >= 200 && $code < 300) {
            //2** are not error so return empty
            return "";
        } elseif ($code >= 300 && $code < 400) {

            return "The url provided return status code {$code}, Too many redirects. When following redirects, curl hit the maximum amount and unable to fetch the contents of the site";
        } elseif ($code >= 400 && $code < 500) {

            if ($code == 403 || $code == 401 || $code == 407 || $code == 456) {
                return "The site may disallow sharing of its contents from non-authenticated users.";
            }
            return "The url provided return status code {$code}, It can't find the page or the page has been removed.";
        } elseif ($code >= 500 && $code < 600) {

            if ($code == 500) {
                return "The url provided return status code {$code}, the site may currently experiencing an internal server error on their end.";
            } elseif ($code == 502 || $code == 503) {
                return "The url provided return status code {$code}, the site return Internal Server Error.";
            }
            return "While accessing the provided url, system \"network connect timeout error\". Please try again later.";
        }

        return $this->error;

    }

    /**
     * Function: init_scrapper
     * Expects: trigger file_get_html then load the DOM
     * Purpose: shortcut for scrapping
     * @param string $url
     * @return bool
     */
    public function init_scrapper($url = '')
    {
        $this->file_get_html($url);
        if ($this->html) {
            return $this->loadDom();
        }

        return false;
    }

    /**
     * Function: file_get_html
     * Expects: $url to get the HTML contents.
     * Purpose: Retrieve the HTML contents from the given.
     * @param string $url
     * @return bool
     */
    public function file_get_html($url)
    {
        $this->current_url = $url;
        $parse = parse_url($url);
        $this->host = $parse['host'];
        $this->baseUrl = $parse['scheme'] . '://' . $parse['host'];
        $html = $this->curl_get_contents($url);
        if ($html === false) {
            $this->error = $this->error . " (Code " . $this->httpCode . ")";
            return false;
        }
        $this->html = $html;
    } // end of file_get_html function

    /**
     * Function to generate a random user agent
     *
     * @return string
     */
    public function newUserAgent()
    {
        $agents = [];
        $netclr = [];
        $sysntv = [];
        $ras1 = mt_rand(0, 9);
        $ras2 = mt_rand(0, 255);
        $date = date("YmdHis") . $ras2;
        $netclr[0] = ".NET CLR 2.0.50727";
        $netclr[1] = ".NET CLR 1.1.4322";
        $netclr[2] = ".NET CLR 4.0.30319";
        $netclr[3] = ".NET CLR 3.5.2644";
        $netclr[4] = ".NET CLR 1.0.10322";
        $netclr[5] = ".NET CLR 3.5.11952";
        $netclr[6] = ".NET CLR 4.0.30319";
        $netclr[7] = ".NET CLR 2.0.65263";
        $netclr[8] = ".NET CLR 1.1.4322; .NET CLR 4.0.30319";
        $netclr[9] = ".NET CLR 4.0.30319; .NET CLR 2.0.50727";
        $sysntv[0] = "Windows NT 6.1; WOW64";
        $sysntv[1] = "Windows NT 5.1; rv:10.1";
        $sysntv[2] = "Windows NT 5.1; U; en";
        $sysntv[3] = "compatible; MSIE 10.0; Windows NT 6.2";
        $sysntv[4] = "Windows NT 6.1; U; en; OneNote.2; ";
        $sysntv[5] = "compatible; Windows NT 6.2; WOW64; en-US";
        $sysntv[6] = "compatible; MSIE 10.0; Windows NT 6.2; Trident/5.0; WOW64";
        $sysntv[7] = "Windows NT 5.1; en; FDM";
        $sysntv[8] = "Windows NT 6.2; WOW64; MediaBox 1.1";
        $sysntv[9] = "compatible; MSIE 11.0; Windows NT 6.2; WOW64";
        // Random user agents that are highly randomized
        $agents[0] = "Opera/9.80 (" . $sysntv[$ras1] . ";" . $netclr[$ras1] . ") Presto/2.10." . mt_rand(0, 999) . " Version/11.62";
        $agents[1] = "Mozilla/5.0 (" . $sysntv[$ras1] . ";" . $netclr[$ras1] . ") Gecko/" . $date . " Firefox/23.0." . $ras1;
        $agents[2] = "Mozilla/5.0 (" . $sysntv[$ras1] . ";" . $netclr[$ras1] . ") AppleWebKit/535.2 (KHTML, like Gecko) Chrome/20.0." . mt_rand(0, 9999) . "." . mt_rand(0, 99) . " Safari/535.2";
        $agents[3] = "Mozilla/5.0 (" . $sysntv[$ras1] . ";" . $netclr[$ras1] . ")";
        $agents[4] = "Mozilla/5.0 (" . $sysntv[$ras1] . ") AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0." . mt_rand(0, 9999) . "." . mt_rand(0, 99) . " Safari/537.36)";
        $agents[5] = "Mozilla/5.0 (" . $sysntv[$ras1] . ";" . $netclr[$ras1] . ")";
        $agents[6] = "Opera/9.80 (" . $sysntv[$ras1] . ") Presto/2.9." . $ras2 . " Version/12.50";
        $agents[7] = "Mozilla/5.0 (" . $sysntv[$ras1] . ";" . $netclr[$ras1] . ")";
        $agents[8] = "Mozilla/5.0 (" . $sysntv[$ras1] . ") Gecko/" . $date . " Firefox/17.0";
        $agents[9] = "Mozilla/5.0 (" . $sysntv[$ras1] . ";" . $netclr[$ras1] . ")";
        // return the random user agent string
        return $agents[$ras1];
    }

    /**
     * Emulate browser request via curl as much as possible,
     * @param string $url
     * @return mixed
     */
    public function curl_get_contents($url)
    {
        $curl = @curl_init();

        $header = [];
        $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
        $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
        $header[] = "Cache-Control: max-age=0";
        $header[] = "Connection: keep-alive";
        $header[] = "Keep-Alive: 300";
        $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $header[] = "Accept-Language: en-us,en;q=0.5";
        $header[] = "Pragma: "; //browsers keep this blank.

        @curl_setopt($curl, CURLOPT_URL, $url);
        @curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);
        @curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        @curl_setopt($curl, CURLOPT_HEADER, 0);
        @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        @curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        @curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        @curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        @curl_setopt($curl, CURLOPT_REFERER, $this->referrer);
        @curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
        @curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        // @curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            @curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        } else {
            @curl_setopt($curl, CURLOPT_USERAGENT, $this->newUserAgent());
        }
        $response = @curl_exec($curl);
        $httpCode = @curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $this->httpCode = $httpCode;
        if ($httpCode != 200) {
            $get_error = $this->getError($httpCode);
            if (empty($get_error)) {
                $this->error = @curl_error($curl);
            } else {
                $this->error = $get_error;
            }
            @curl_close($curl);
            return false;
        }
        @curl_close($curl);

        return $response;
    }

    /**
     * Function: loadDom
     * Purpose: Load the HTML document into the DomDocument class
     * @return boolean
     */
    public function loadDom()
    {
        libxml_use_internal_errors(true);
        $this->domDoc = new \DomDocument;
        if ($this->html !== '') {
            $this->domDoc->loadHTML('<?xml encoding="UTF-8">' . $this->html);
            return true;
        } else {
            return false;
        }
    } // end of loadDom function

    /**
     * Function: str_get_html
     * Expects: String containing HTML code.
     * Purpose: Retrieve the HTML contents from the given
     * @param string $html_str
     * @return void
     */
    public function str_get_html($html_str)
    {
        $this->html = $html_str;
        return;
    }

    /**
     * Function:    setCurrentDirectory
     * Purpose:    Sets the current directory. This is necessary for the rebuildUrl method to work.
     * @param string $current_directory
     */
    public function setCurrentDirectory($current_directory)
    {
        if (substr($current_directory, -1) == "/") {
            $this->current_directory = $current_directory;
        } else {
            $this->current_directory = $current_directory . "/";
        }
        return;
    }

    /**
     * Method:    getRedirectPath
     * Purpose:
     * @param string $url
     * @return array|bool|string
     */
    public function getRedirectPath($url = null)
    {
        if ($url == null) {
            $redirect_url = $this->current_url;
        } else {
            $redirect_url = $url;
        }

        //get the header in key/value format
        $this->response_header = get_headers($redirect_url);
        $responseHeader = $this->parseResponseHeader();

        if ($responseHeader['status'][0] < 300) {
            return false;
        }

        $status_count = 0;
        $redirectPath = array();

        if (isset($responseHeader['location'])) {
            $dest_url =
                $responseHeader['location'][count($responseHeader['location']) - 1];
            foreach ($responseHeader['location'] as $to_url) {
                $redirectPath[] = [
                    'dest_url' => $dest_url,
                    'from_url' => $redirect_url,
                    'to_url' => $to_url,
                    'status' => $responseHeader['status'][$status_count++]
                ];
                $redirect_url = $to_url;
            }
        } else {
            return false;
        }

        return $redirectPath;
    } // end of getRedirectPath method

    /**
     * Function:    parseResponseHeader
     * Expects:    $html var must be filled first.
     * Purpose:    Parse the response header from an HTML document.
     * @return array Plain Text of an HTML document.
     */
    public function parseResponseHeader()
    {
        $responseHeaderArray = array();
        foreach ($this->response_header as $header_item) {
            $space_pos = strpos($header_item, " ");

            if ($space_pos == 0) {
                $space_pos = strlen($header_item);
            }

            $header_key = strtolower(substr($header_item, 0, $space_pos));

            // check for a status header...
            if (substr($header_key, 0, 5) == "http/") {
                $header_key = "status:";
            }

            $header_key = substr($header_key, 0, strlen($header_key) - 1);
            $length = strlen($header_item) - $space_pos;
            $header_value = substr($header_item, $space_pos + 1, $length);

            switch ($header_key) {
                case 'status':
                    $responseHeaderArray[$header_key][] =
                        substr($header_value, 0, strpos($header_value, " "));
                    break;
                case 'content-type':
                case 'location':
                case 'set-cookie':
                    $responseHeaderArray[$header_key][] = $header_value;
                    break;
                case 'server':
                    $responseHeaderArray[$header_key] = $header_value;
                    break;
                /*default:
                    # code...
                    break;*/
            }

            // $responseHeaderArray[$header_key] = $header_value;

        }
        return $responseHeaderArray;
    }

    /**
     * Function: getPlainText
     * Expects: $html var must be filled first.
     * Purpose: Parse the plain text (visible text) from an HTML document.
     * @param null $html_str
     * @return string Plain Text of an HTML document.
     */
    public function getPlainText($html_str = null)
    {
        // remove comments and any content found in the the comment area
        // (strip_tags only removes the actual tags).
        if (!$html_str) {
            $plaintext = preg_replace('#<!--.*?-->#s', '', $this->html);
        } else {
            $plaintext = preg_replace('#<!--.*?-->#s', '', $html_str); // for use of this function within this class
        }

        $plaintext = html_entity_decode($plaintext);

        // put a space between list items (strip_tags just removes the tags).
        $plaintext = preg_replace('#</li>#', ' </li>', $plaintext);

        // remove all script and style tags
        $plaintext = preg_replace('#<(script|style)\b[^>]*>(.*?)</(script|style)>#is', "", $plaintext);

        // remove br tags (missed by strip_tags)
        $plaintext = preg_replace("#<br[^>]*?>#", " ", $plaintext);

        // remove all remaining html
        $plaintext = strip_tags($plaintext);
        $plaintext = preg_replace('#\s+#', ' ', $plaintext);
        $plaintext = htmlspecialchars_decode($plaintext, ENT_QUOTES);
        $plaintext = preg_replace('/&(#\d+|\w+);/', ' ', $plaintext);
        $plaintext = $this->normalize_str($plaintext);

        return $plaintext;
    }

    /**
     * Converting smart qoutes
     * @param string $string
     * @return string
     */
    private function convert_smart_quotes($string)
    {
        $search = array(
            chr(145), chr(146), chr(147), chr(148),
            chr(151), chr(150), chr(133));
        $replace = array(
            "'", "'", '"', '"', '--', '-', '...');
        return str_replace($search, $replace, $string);
    }

    /**
     * Normalising string
     * @param $str
     * @return string
     */
    private function normalize_str($str)
    {
        $invalid = array('Š' => 'S', 'š' => 's', 'Đ' => 'Dj', 'đ' => 'dj', 'Ž' => 'Z', 'ž' => 'z',
            'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A',
            'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E',
            'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
            'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y',
            'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a',
            'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i',
            'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
            'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b',
            'ÿ' => 'y', 'Ŕ' => 'R', 'ŕ' => 'r', "`" => "'", "´" => "'", "„" => ",", "`" => "'",
            "´" => "'", "“" => "\"", "”" => "\"", "´" => "'", "&acirc;€™" => "'", "{" => "",
            "~" => "", "–" => "--", "’" => "'", "—" => "--");

        $str = str_replace(array_keys($invalid), array_values($invalid), $str);

        return $str;
    }

    /**
     * Function:    getTitleTag
     * Purpose:    Gets the title from the source stored in the $html variable.
     * @return array Title as string.
     */
    public function getTitleTag()
    {
        $title_tag_array = array();

        $found = $this->domDoc->getElementsByTagName("title");
        if ($found->length > 0) {
            $title_text = $found->item(0)->nodeValue;
            $tag_code = $this->domDoc->saveHTML($found->item(0));
            // $tag_code = $this->innerHTML($found->item(0));

            $this->tagsList[$tag_code] = null;

            $title_tag_array[] = array(
                'tag_code' => $tag_code,
                'title_tag_text' => trim($title_text),
                'title_tag_text_code' => $this->innerHTML($found->item(0)));
        }

        return $title_tag_array;
    }

    /**
     * Function:    getHeadingTags
     * Purpose:    Gets all heading tags from the source stored in the $html variable.
     * @return array    Associative array containing all found heading tags.
     */
    public function getHeadingTags()
    {

        $headings = array();
        for ($type = 1; $type < 6; $type++) {
            $matches = $this->domDoc->getElementsByTagName("h$type");
            $h_instance = 1;
            foreach ($matches as $heading) {
                $heading_tag = $this->domDoc->saveHTML($heading);
                // $a_tag = $this->getATagFromNode($heading_tag);
                $a_tag = $this->getATagFromNode($heading);
                $meta_attributes = $this->getElementAttributes($heading);

                if ($a_tag !== false) {
                    $this->tagsList[$heading_tag] =
                        $this->tagsList[$a_tag['tag_code']];
                } else {
                    $this->tagsList[$heading_tag] = null;
                }

                $headings[] = array(
                    'level' => $type,
                    'instance' => $h_instance++,
                    'heading_tag_text' => trim($heading->nodeValue),
                    'heading_tag_text_code' => $this->innerHTML($heading),
                    'tag_code' => $heading_tag,
                    'a_tag' => $a_tag['tag_code'],
                    'href' => $a_tag['href_rebuilt'],
                    'meta_attributes' => $meta_attributes);
            }
        }

        return $headings;
    }

    /**
     * Function:    getATags
     * Purpose:    Gets all a tags from the source stored in the $html variable.
     * @return array    Associative array conatining all found a tags.
     */
    public function getATags()
    {
        // $this->loadDom();
        $tags_array = array();

        $matches = $this->domDoc->getElementsByTagName("a");
        foreach ($matches as $tag) {
            $tag_code = $this->domDoc->saveHTML($tag);
            // $img_tag = $this->getImgTagFromNode($tag_code);
            $img_tag = $this->getImgTagFromNode($tag);
            $href = trim($tag->getAttribute('href'));
            if ($href == "" || substr($href, 0, 4) == "http") {
                $href_rebuilt = $href;
            } else {
                // CakeLog::write('smf', "href: $href");
                $href_rebuilt = $this->rebuildUrl($href);
                // CakeLog::write('smf', "href_rebuilt: $href_rebuilt");
                // CakeLog::write('smf', "current_directory: $this->current_directory");
            }

            if ($href_rebuilt !== "") {
                $this->urlsList[] = $href_rebuilt;
            }
            $this->tagsList[$tag_code] = $href_rebuilt;

            // $href_rebuilt = $this->rebuildUrl($tag)
            $meta_attributes = $this->getElementAttributes($tag);
            // $meta_attribs = $this->getMetaTagAttributes("a", $tag, false);

            $tags_array[] = array(
                'tag_code' => $tag_code,
                'href' => $href,
                'href_rebuilt' => $href_rebuilt,
                'a_tag_text' => trim($tag->nodeValue),
                'a_tag_text_code' => $this->innerHTML($tag),
                'img_tag' => $img_tag,
                'meta_attributes' => $meta_attributes);
        }

        return $tags_array;
    }

    /**
     * Function:    getImgTags
     * Purpose:    Gets all img tags from the source stored in the $html variable.
     * @return array *    Associative array conatining all found img tags.
     */
    public function getImgTags()
    {
        $tags_array = array();

        $matches = $this->domDoc->getElementsByTagName("img");
        foreach ($matches as $tag) {
            $tag_code = $this->domDoc->saveHTML($tag);
            $src = $tag->getAttribute('src');
            if ($src == "" || substr($src, 0, 4) == "http") {
                $src_rebuilt = $src;
            } else {
                $src_rebuilt = $this->rebuildUrl($src);
            }

            if ($src_rebuilt !== "") {
                $this->urlsList[] = $src_rebuilt;
            }
            $this->tagsList[$tag_code] = $src_rebuilt;

            $meta_attributes = $this->getElementAttributes($tag);
            $tags_array[] = array(
                'tag_code' => $tag_code,
                'src' => $src,
                'src_rebuilt' => $src_rebuilt,
                'meta_attributes' => $meta_attributes);

        }

        return $tags_array;
    }

    /**
     * Function:    getLinkTags
     * Purpose:    Gets all link tags from the source stored in the $html variable.
     * @return array    Associative array conatining all found link tags.
     */
    public function getLinkTags()
    {
        $tags_array = array();

        $matches = $this->domDoc->getElementsByTagName("link");
        foreach ($matches as $tag) {
            $tag_code = $this->domDoc->saveHTML($tag);
            $href = $tag->getAttribute('href');
            if ($href == "" || substr($href, 0, 4) == "http") {
                $href_rebuilt = $href;
            } else {
                $href_rebuilt = $this->rebuildUrl($href);
            }

            if ($href_rebuilt !== "") {
                $this->urlsList[] = $href_rebuilt;
            }
            $this->tagsList[$tag_code] = $href_rebuilt;

            $meta_attributes = $this->getElementAttributes($tag);
            $tags_array[] = array(
                'tag_code' => $tag_code,
                'href' => $href,
                'href_rebuilt' => $href_rebuilt,
                'meta_attributes' => $meta_attributes);

        }

        return $tags_array;
    }

    /**
     * Function:    getMetaTags
     * Purpose:    Gets all meta tags from the source stored in the $html variable.
     * @return array    Associative array conatining all found meta tags.
     */
    public function getMetaTags()
    {
        $tags_array = array();

        $matches = $this->domDoc->getElementsByTagName("meta");
        foreach ($matches as $tag) {
            $tag_code = $this->domDoc->saveHTML($tag);

            $this->tagsList[$tag_code] = null;

            $meta_attributes = $this->getElementAttributes($tag);

            $tags_array[] = array(
                'tag_code' => $tag_code,
                'meta_attributes' => $meta_attributes
            );
        }

        return $tags_array;
    }

    /**
     * Function:    getScriptTags
     * Purpose:    Gets all script tags from the source stored in the $html variable.
     * @return array    Associative array conatining all found script tags.
     */
    public function getScriptTags()
    {
        $tags_array = array();

        $matches = $this->domDoc->getElementsByTagName("script");
        foreach ($matches as $tag) {
            $tag_code = $this->domDoc->saveHTML($tag);
            $src = $tag->getAttribute('src');
            if ($src == "" || substr($src, 0, 4) == "http") {
                $src_rebuilt = $src;
            } else {
                $src_rebuilt = $this->rebuildUrl($src);
            }

            if ($src_rebuilt !== "") {
                $this->urlsList[] = $src_rebuilt;
            }
            $this->tagsList[$tag_code] = $src_rebuilt;

            $meta_attributes = $this->getElementAttributes($tag);
            $tags_array[] = array(
                'tag_code' => $tag_code,
                'src' => $src,
                'src_rebuilt' => $src_rebuilt,
                'meta_attributes' => $meta_attributes);
        }

        return $tags_array;
    }

    /**
     * Function:    getStyleTags
     * Purpose:    Gets all style tags from the source stored in the $html variable.
     * @return array    Associative array conatining all found style tags.
     */
    public function getStyleTags()
    {
        $tags_array = array();

        $matches = $this->domDoc->getElementsByTagName("style");
        foreach ($matches as $tag) {
            $tag_code = $this->domDoc->saveHTML($tag);

            $this->tagsList[$tag_code] = null;

            $meta_attributes = $this->getElementAttributes($tag);
            $tags_array[] = array(
                'tag_code' => $tag_code,
                'meta_attributes' => $meta_attributes);
        }

        return $tags_array;
    }

    /**
     * Function:    getImgTagFromNode
     * Purpose:    Use this when you want the entire nodeValue with HTML code
     * @param $node
     * @return bool the nodeValue with including HTML (if it has any).
     */
    public function getImgTagFromNode($node)
    {
        $matches = $node->getElementsByTagName("img");
        if ($matches->length > 0) {
            return $this->domDoc->saveHTML($matches->item(0));
        }
        return false;
    }

    /**
     * Function:    getATagFromNode
     * Purpose:    Use this when you want the entire nodeValue with HTML code
     * @param $node
     * @return array|bool the nodeValue with including HTML (if it has any).
     */
    public function getATagFromNode($node)
    {
        $matches = $node->getElementsByTagName("a");
        if ($matches->length > 0) {
            $anchor_text = $matches->item(0)->nodeValue;
            // $tag_code 		= $this->innerHTML($matches->item(0));
            $tag_code = $this->domDoc->saveHTML($matches->item(0));
            $href = $matches->item(0)->getAttribute('href');

            if ($href == "" || substr($href, 0, 4) == "http") {
                $href_rebuilt = $href;
            } else {
                $href_rebuilt = $this->rebuildUrl($href);
            }

            return array(
                'tag_code' => $tag_code,
                'a_tag_text' => trim($anchor_text),
                'href_rebuilt' => $href_rebuilt);
        }
        return false;

    }

    /**
     * Function:    getATagFromString
     * Purpose:    Use this when you want the entire nodeValue with HTML code
     * @param null $node
     * @return bool|false|string
     */
    public function getATagFromString($node = null)
    {
        $begin = strpos($node, "<a ");

        if (!$begin) {
            return false;
        } else {
            $end = strpos($node, "/a>", $begin) + 2;
            $length = $end - $begin + 1;
            $a_tag = substr($node, $begin, $length);
            $href = ""; // $this->getHrefFromATag($a_tag);
            return $a_tag;
        }
    }

    /**
     * Function:    getImgTagFromString
     * Purpose:    Use this when you want the entire nodeValue with HTML code
     * @param null $node
     * @return bool|false|string
     */
    public function getImgTagFromString($node = null)
    {
        $begin = strpos($node, "<img ");

        if (!$begin) {
            return false;
        } else {
            $end = strpos($node, ">", $begin);
            $length = $end - $begin + 1;
            return substr($node, $begin, $length);
        }
    }

    /**
     * Function:    getElementAttributes
     * Purpose:    Get all attributes (keys and values) from a given tag.
     * @param $element
     * @return array Array of attributes.
     */
    public function getElementAttributes($element)
    {
        $attributes = array();

        foreach ($element->attributes as $attribute_name => $attribute_node) {
            $attributes[$attribute_name] = $attribute_node->nodeValue;
        }
        return $attributes;
    }

    /**
     * Function: getElementById
     * Expects: $html var must be filled first.
     * Purpose: Retrieve the HTML contents from the given
     * @param $elementId
     * @return bool|false|string
     */
    public function getElementById($elementId)
    {
        // $this->loadDom();
        return $this->domDoc->getElementById($elementId);
    }

    /**
     * Function: getElementByName
     * Expects: $html var must be filled first.
     * Purpose: Retrieve the HTML contents from the given
     * @param $elementName
     * @return bool|false|string
     */
    public function getElementByName($elementName)
    {
        return $this->domDoc->getElementByName($elementName);
    }

    /**
     * Function:    innerHTMLDom
     * Purpose:    Use this when you want the entire nodeValue with HTML code
     * @return bool|false|string
     */
    public function innerHTMLDom()
    {
        return $this->domDoc->saveXML();
    }

    /**
     * Function:    innerHTML
     * Purpose:    Use this when you want the entire nodeValue with HTML code
     * @param $node
     * @return bool|false|string
     */
    public function innerHTML($node)
    {
        $doc = $node->ownerDocument;
        $frag = $doc->createDocumentFragment();

        foreach ($node->childNodes as $child) {
            $frag->appendChild($child->cloneNode(TRUE));
        }

        $inner_html = $doc->saveHTML($frag);
        $inner_html = substr($inner_html, 2, strlen($inner_html) - 5);
        return $inner_html;
    }

    /**
     * Function:    getMetaData
     * Purpose:    Gets all the meta data from the given html source.
     * @param null $html_str
     *
     * @return array|false
     */
    public function getMetaData($html_str = null)
    {
        if (!$html_str) {

            preg_match_all("#\s(\w*)\s*=\s*(?|\"([^\"]+)\"|'([^']+)'|([^\s><'\"]+))#i",
                $this->html,
                $matches
            );
        } else {
            preg_match_all("#\s(\w*)\s*=\s*(?|\"([^\"]+)\"|'([^']+)'|([^\s><'\"]+))#i",
                $html_str,
                $matches
            );
        }
        return array_combine($matches[1], $matches[2]);
    }

    /**
     * Function:    getMetaRegEx
     * Purpose:    Gets all meta-tag attributes from the source stored in the  $html variable.
     *        The keys are the meta-names, the values the content of the attributes.
     *        (like $tags["robots"] = "nofollow")
     *  Note:        Uses the same regex statement as used in the PHPCrawl class,  written by Uwe Hunfeld
     * @return array
     */
    public function getMetaRegEx()
    {
        preg_match_all("#<\s*meta\s+" .
            "name\s*=\s*(?|\"([^\"]+)\"|'([^']+)'|([^\s><'\"]+))\s+" .
            "content\s*=\s*(?|\"([^\"]+)\"|'([^']+)'|([^\s><'\"]+))" .
            ".*># Uis", $this->html, $matches
        );

        $tags = array();
        for ($x = 0; $x < count($matches[0]); $x++) {
            $meta_name = strtolower(trim($matches[1][$x]));
            $meta_value = strtolower(trim($matches[2][$x]));
            $tags[$meta_name] = $meta_value;
        }
        return $tags;
    }

    /**
     * Function:    findATagNode
     * Purpose:    Gets all the meta data from the given html source.
     * @param null $array
     * @return mixed|null
     */
    public function findATagNode($array = null)
    {
        foreach ($array as $value) {
            if (substr($value, 0, 3) == "<a ") {
                return $value;
            }
        }
        return null;
    }

    /**
     * Function:    getChildNodesArray
     * Purpose:    To get the child nodes of a node
     * @param $node
     * @return array
     */
    public function getChildNodesArray($node)
    {
        $doc = $node->ownerDocument;
        $child_node_array = array();

        foreach ($node->childNodes as $child) {
            $child_node_array[] = $doc->saveXML($child);
        }
        return $child_node_array;
    }

    /**
     * Function:    rebuildUrl
     * Purpose:    To rebuid a relative link based on the page it is found in.
     * @param null $relative_url
     * @return bool|string
     */
    public function rebuildUrl($relative_url = null)
    {
        // If relative URL has a scheme, clean path and return.
        $r = $this->split_url($relative_url);
        if ($r === FALSE)
            return FALSE;
        if (!empty($r['scheme'])) {
            if (!empty($r['path']) && $r['path'][0] == '/')
                $r['path'] = $this->url_remove_dot_segments($r['path']);
            return $this->join_url($r);
        }

        // Make sure the base URL is absolute.
        $b = $this->split_url($this->current_directory);
        if ($b === FALSE || empty($b['scheme']) || empty($b['host']))
            return FALSE;
        $r['scheme'] = $b['scheme'];

        // If relative URL has an authority, clean path and return.
        if (isset($r['host'])) {
            if (!empty($r['path']))
                $r['path'] = $this->url_remove_dot_segments($r['path']);
            return $this->join_url($r);
        }
        unset($r['port']);
        unset($r['user']);
        unset($r['pass']);

        // Copy base authority.
        $r['host'] = $b['host'];
        if (isset($b['port'])) $r['port'] = $b['port'];
        if (isset($b['user'])) $r['user'] = $b['user'];
        if (isset($b['pass'])) $r['pass'] = $b['pass'];

        // If relative URL has no path, use base path
        if (empty($r['path'])) {
            if (!empty($b['path']))
                $r['path'] = $b['path'];
            if (!isset($r['query']) && isset($b['query']))
                $r['query'] = $b['query'];
            return $this->join_url($r);
        }

        // If relative URL path doesn't start with /, merge with base path
        if ($r['path'][0] != '/') {
            $base = mb_strrchr($b['path'], '/', TRUE, 'UTF-8');
            if ($base === FALSE) $base = '';
            $r['path'] = $base . '/' . $r['path'];
        }
        $r['path'] = $this->url_remove_dot_segments($r['path']);
        return $this->join_url($r);
    }

    /**
     *
     * @param $relative_url
     * @return string
     */
    public function url_to_absolute($relative_url)
    {
        // If relative URL has a scheme, clean path and return.
        $r = $this->split_url($relative_url);
        if ($r === FALSE)
            return FALSE;
        if (!empty($r['scheme'])) {
            if (!empty($r['path']) && $r['path'][0] == '/')
                $r['path'] = $this->url_remove_dot_segments($r['path']);
            return $this->join_url($r);
        }

        // Make sure the base URL is absolute.
        $b = $this->split_url($this->current_directory);
        if ($b === FALSE || empty($b['scheme']) || empty($b['host']))
            return FALSE;
        $r['scheme'] = $b['scheme'];

        // If relative URL has an authority, clean path and return.
        if (isset($r['host'])) {
            if (!empty($r['path']))
                $r['path'] = $this->url_remove_dot_segments($r['path']);
            return $this->join_url($r);
        }
        unset($r['port']);
        unset($r['user']);
        unset($r['pass']);

        // Copy base authority.
        $r['host'] = $b['host'];
        if (isset($b['port'])) $r['port'] = $b['port'];
        if (isset($b['user'])) $r['user'] = $b['user'];
        if (isset($b['pass'])) $r['pass'] = $b['pass'];

        // If relative URL has no path, use base path
        if (empty($r['path'])) {
            if (!empty($b['path']))
                $r['path'] = $b['path'];
            if (!isset($r['query']) && isset($b['query']))
                $r['query'] = $b['query'];
            return $this->join_url($r);
        }

        // If relative URL path doesn't start with /, merge with base path
        if ($r['path'][0] != '/') {
            $base = mb_strrchr($b['path'], '/', TRUE, 'UTF-8');
            if ($base === FALSE) $base = '';
            $r['path'] = $base . '/' . $r['path'];
        }
        $r['path'] = $this->url_remove_dot_segments($r['path']);
        return $this->join_url($r);
    }

    /**
     *
     * @param $path
     * @return string
     */
    public function url_remove_dot_segments($path)
    {
        // multi-byte character explode
        $inSegs = preg_split('!/!u', $path);
        $outSegs = array();
        foreach ($inSegs as $seg) {
            if ($seg == '' || $seg == '.')
                continue;
            if ($seg == '..')
                array_pop($outSegs);
            else
                array_push($outSegs, $seg);
        }
        $outPath = implode('/', $outSegs);
        if ($path[0] == '/')
            $outPath = '/' . $outPath;
        // compare last multi-byte character against '/'
        if ($outPath != '/' &&
            (mb_strlen($path) - 1) == mb_strrpos($path, '/', 'UTF-8'))
            $outPath .= '/';
        return $outPath;
    }

    /**
     *
     * @param $parts
     * @param bool $encode
     * @return string
     */
    public function join_url($parts, $encode = TRUE)
    {
        if ($encode) {
            if (isset($parts['user']))
                $parts['user'] = rawurlencode($parts['user']);
            if (isset($parts['pass']))
                $parts['pass'] = rawurlencode($parts['pass']);
            if (isset($parts['host']) &&
                !preg_match('!^(\[[\da-f.:]+\]])|([\da-f.:]+)$!ui', $parts['host']))
                $parts['host'] = rawurlencode($parts['host']);
            if (!empty($parts['path']))
                $parts['path'] = preg_replace('!%2F!ui', '/',
                    rawurlencode($parts['path']));
            if (isset($parts['query']))
                $parts['query'] = rawurlencode($parts['query']);
            if (isset($parts['fragment']))
                $parts['fragment'] = rawurlencode($parts['fragment']);
        }

        $url = '';
        if (!empty($parts['scheme']))
            $url .= $parts['scheme'] . ':';
        if (isset($parts['host'])) {
            $url .= '//';
            if (isset($parts['user'])) {
                $url .= $parts['user'];
                if (isset($parts['pass']))
                    $url .= ':' . $parts['pass'];
                $url .= '@';
            }
            if (preg_match('!^[\da-f]*:[\da-f.:]+$!ui', $parts['host']))
                $url .= '[' . $parts['host'] . ']'; // IPv6
            else
                $url .= $parts['host'];             // IPv4 or name
            if (isset($parts['port']))
                $url .= ':' . $parts['port'];
            if (!empty($parts['path']) && $parts['path'][0] != '/')
                $url .= '/';
        }
        if (!empty($parts['path']))
            $url .= $parts['path'];
        if (isset($parts['query']))
            $url .= '?' . $parts['query'];
        if (isset($parts['fragment']))
            $url .= '#' . $parts['fragment'];
        return $url;
    }

    /**
     *
     * @param $url
     * @param bool $decode
     * @return array
     */
    public function split_url($url, $decode = TRUE)
    {
        $xunressub = 'a-zA-Z\d\-._~\!$&\'()*+,;=';
        $xpchar = $xunressub . ':@%';

        $xscheme = '([a-zA-Z][a-zA-Z\d+-.]*)';

        $xuserinfo = '(([' . $xunressub . '%]*)' .
            '(:([' . $xunressub . ':%]*))?)';

        $xipv4 = '(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})';

        $xipv6 = '(\[([a-fA-F\d.:]+)\])';

        $xhost_name = '([a-zA-Z\d-.%]+)';

        $xhost = '(' . $xhost_name . '|' . $xipv4 . '|' . $xipv6 . ')';
        $xport = '(\d*)';
        $xauthority = '((' . $xuserinfo . '@)?' . $xhost .
            '?(:' . $xport . ')?)';

        $xslash_seg = '(/[' . $xpchar . ']*)';
        $xpath_authabs = '((//' . $xauthority . ')((/[' . $xpchar . ']*)*))';
        $xpath_rel = '([' . $xpchar . ']+' . $xslash_seg . '*)';
        $xpath_abs = '(/(' . $xpath_rel . ')?)';
        $xapath = '(' . $xpath_authabs . '|' . $xpath_abs .
            '|' . $xpath_rel . ')';

        $xqueryfrag = '([' . $xpchar . '/?' . ']*)';

        $xurl = '^(' . $xscheme . ':)?' . $xapath . '?' .
            '(\?' . $xqueryfrag . ')?(#' . $xqueryfrag . ')?$';


        // Split the URL into components.
        $parts = array();

        if (!preg_match('!' . $xurl . '!', $url, $m))
            return FALSE;

        if (!empty($m[2])) $parts['scheme'] = strtolower($m[2]);

        if (!empty($m[7])) {
            if (isset($m[9])) $parts['user'] = $m[9];
            else            $parts['user'] = '';
        }
        if (!empty($m[10])) $parts['pass'] = $m[11];

        if (!empty($m[13])) $h = $parts['host'] = $m[13];
        else if (!empty($m[14])) $parts['host'] = $m[14];
        else if (!empty($m[16])) $parts['host'] = $m[16];
        else if (!empty($m[5])) $parts['host'] = '';
        if (!empty($m[17])) $parts['port'] = $m[18];

        if (!empty($m[19])) $parts['path'] = $m[19];
        else if (!empty($m[21])) $parts['path'] = $m[21];
        else if (!empty($m[25])) $parts['path'] = $m[25];

        if (!empty($m[27])) $parts['query'] = $m[28];
        if (!empty($m[29])) $parts['fragment'] = $m[30];

        if (!$decode)
            return $parts;
        if (!empty($parts['user']))
            $parts['user'] = rawurldecode($parts['user']);
        if (!empty($parts['pass']))
            $parts['pass'] = rawurldecode($parts['pass']);
        if (!empty($parts['path']))
            $parts['path'] = rawurldecode($parts['path']);
        if (isset($h))
            $parts['host'] = rawurldecode($parts['host']);
        if (!empty($parts['query']))
            $parts['query'] = rawurldecode($parts['query']);
        if (!empty($parts['fragment']))
            $parts['fragment'] = rawurldecode($parts['fragment']);
        return $parts;
    }

    /**
     * get the content from meta via its property|name
     * @param $meta_name
     * @param string $index
     * @return string or empty
     */
    public function getMetaByIndex($meta_name, $index = 'property')
    {
        if (empty($meta_name)) {
            return "";
        }
        $metas = @$this->getMetaTags();
        if (!empty($metas)) {
            foreach ($metas as $key => $meta) {
                if (array_key_exists($index, $meta['meta_attributes'])
                    && strtolower($meta['meta_attributes'][$index]) == strtolower($meta_name)) {
                    return trim($meta['meta_attributes']['content']);
                }
            }
        }
        return "";
    }

    /**
     * get the title from the dom
     * @return string or empty
     */
    public function getTitle()
    {
        $title = @$this->getMetaByIndex("og:title");
        if (empty(trim($title))) {
            $title = @$this->getTitleTag()[0]['title_tag_text'];
        }
        return $this->clean($title);
    }

    /**
     * get the title from the dom
     * @return string or empty
     */
    public function getYoutubeTitle()
    {
        $doc = new \DOMDocument();
        $doc->preserveWhiteSpace = FALSE;
        $doc->loadHTMLFile($this->current_url);

        $title_div = $doc->getElementById('eow-title');
        $title = $this->clean((!is_null($title_div) ? $title_div->nodeValue : ""));

        if (empty($title)) {
            $title = "YouTube";
        }
        return $this->clean($title);
    }

    /**
     * get the description from the dom
     * @return string or empty
     */
    public function getDescription()
    {
        $desc = @$this->getMetaByIndex("og:description");
        if (empty($desc)) {
            $desc = @$this->getMetaByIndex("description", 'name');
        }
        return $this->clean($desc);
    }

    /**
     * get the best image from the dom
     * @return string or empty
     */
    public function getImage()
    {
        $image = @$this->getAllImage();
        if (is_array($image)) {
            $image = $image[array_rand($image, 1)];
            // dump($image);
            // $image_set = array_filter($image);
            // $best_index = $this->chooseBestPreviewImage($image_set);
            // $image_set = $this->make_best_first($image_set, $best_index);
            // dump($image_set);
            // $image = ($best_index === false) ? "" : $image_set[0];
        }
        return $image;
    }

    /**
     * get the image from the dom
     * @return string or empty
     */
    public function getAllImage()
    {
        $image = @$this->getMetaByIndex("og:image");
        if (empty($image)) {
            $image_set = @$this->getImages();
            return is_array($image_set) ? $image_set : "";
        }
        $parse_image_url = parse_url($image);
        if (!isset($parse_image_url['host']) && !empty($image)) {
            $image = $this->siteBaseUrl . $image;
        }
        return $image;
    }

    /**
     * get the type from the dom
     * @return string or empty
     */
    public function getType()
    {
        $type = @$this->getMetaByIndex("og:type");
        return !empty($type) ? $type : "";
    }

    /**
     * get the category from the dom
     * @return string or empty
     */
    public function getCategory()
    {
        $category = @$this->getMetaByIndex("og:category");
        return !empty($category) ? $category : "";
    }

    /**
     *    get the first nth images on the dom
     * @param $host string
     * @param $limit integer
     * @return array|bool
     */
    public function getImages($host = "", $limit = 5)
    {
        $images = @$this->getImgTags();
        $image_set = [];
        $host = empty($host) ? $this->host : $host;
        if (!empty($images)) {
            $images = array_slice(array_filter($images), 0, $limit);
            foreach ($images as $key => $img) {

                $el_src = $img['src'];
                $parse = parse_url($el_src);
                if (empty($parse['host'])) {
                    $el_src = 'http://' . $host . '/' . ltrim($parse['path'], '/.');
                }
                $image_set[] = $el_src;
            }
            return $image_set;
        }
        return false;
    }

    /**
     * Clear dom
     *
     * @return void
     */
    public function clear()
    {
        $this->domDoc = false;
        $this->html = false;
        $this->response_header = false;
    }

    /**
     * Clean string
     *
     * @param string
     * @return string
     */
    public function clean($string = "")
    {
        //$string = str_replace(["“","”"],"\"",$string);
        $string = str_replace(["\t", "\n"], " ", $string);
        $string = strip_tags($string);
        return $string;
    }

    /**
     * Get image size and type equivalent to getimagesize but will be using curl to allow scrapping
     * external image behind firewall
     *
     * @param string $image_url
     * @return mixed array or FALSE if not found
     */
    public function getImageSizeViaCurl($image_url = "")
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $image_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_USERAGENT, $this->newUserAgent());
            $data = curl_exec($ch);

            if (FALSE === $data) {
                throw new Exception(curl_error($ch), curl_errno($ch));
            }
            $curl_info = @curl_getinfo($ch);

            @curl_close($ch);

            $width = $height = null;

            $image = @imagecreatefromstring($data);
            if (!$image && isset($curl_info['http_code']) && $curl_info['http_code'] == 200
                && $curl_info['size_download'] >= 1000) {
                $width = 200;
                $height = 200;
                $info = [
                    'width' => $width,
                    'height' => $height,
                    'mime' => $curl_info['content_type'],
                    'via' => 'getImageSizeViaCurl'
                ];
            } else {
                $finfo = new \finfo(FILEINFO_MIME_TYPE);

                $width = @imagesx($image);
                $height = @imagesy($image);
                $info = [
                    'width' => $width,
                    'height' => $height,
                    'mime' => $finfo->buffer($data),
                    'via' => 'getImageSizeViaCurl'
                ];

            }
            @imagedestroy($image);

            return (is_null($width) || is_null($height)) ? false : $info;

        } catch (Exception $e) {
            //trigger_error(sprintf( 'Curl failed with error #%d: %s', $e->getCode(), $e->getMessage()), E_USER_ERROR);
            return false;
        }

    }

    /**
     * Get image size and type equivalent
     *
     * @param $image_url
     * @return mixed array or FALSE if not found
     */
    public function getimgsize($image_url)
    {
        if (empty($image_url) || is_null($image_url)) {
            return false;
        }
        $info = @getimagesize($image_url);
        if (!$info) {
            return $this->getImageSizeViaCurl($image_url);
        }

        return [
            'width' => @$info[0],
            'height' => @$info[1],
            'mime' => @$info['mime'],
            'via' => 'getimagesize'
        ];
    }

    /**
     * get the first image which pass the checking of height and width
     *
     * @param array $set ex. array('http://domain.com/images/image.jpg','http://domain.com/images/image2.jpg')
     * @return int index
     */
    public function chooseBestPreviewImage($set = [])
    {
        if (empty($set)) {
            return false;
        }
        if (!is_array($set)) {
            return false;
        }

        $preferred_dimension = 1200;//means 1200x1200 px

        foreach ($set as $index => $url) {
            if ($image = @$this->getimgsize($url)) {
                $width = isset($image["width"]) ? (int)$image["width"] : 0;
                $height = isset($image["height"]) ? (int)$image["height"] : 0;

                if ($width > 0 && $height > 0) {
                    if (($width >= $preferred_dimension && $height >= $preferred_dimension && $width == $height)
                        || ($width >= $preferred_dimension && $height >= $preferred_dimension)
                        || ($width >= ($preferred_dimension / 2) && $height >= ($preferred_dimension / 2) && $width == $height)
                        || ($width >= ($preferred_dimension / 2) && $height >= ($preferred_dimension / 2))
                    ) {
                        return $index;
                    }
                }
            }
        }//endforeach
        return 0;
    }

    /**
     * get the best preview image then put it on first of array
     * @param $images_array
     * @param int $best_index
     * @return array
     */
    public function make_best_first($images_array, $best_index = 0)
    {
        if ($best_index == 0 || $best_index > count($images_array)) {
            return $images_array;
        }
        $temp = $images_array[0];
        $images_array[0] = $images_array[$best_index];
        $images_array[$best_index] = $temp;
        return $images_array;
    }

}