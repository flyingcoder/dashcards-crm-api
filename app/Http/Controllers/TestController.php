<?php

namespace App\Http\Controllers;

use App\Company;
use App\Invoice;
use App\Mail\NewInvoiceEmail;
use App\Repositories\CalendarEventRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\MembersRepository;
use App\Repositories\TemplateRepository;
use App\Repositories\TimerRepository;
use App\ServiceList;
use App\Task;
use App\Traits\HasUrlTrait;
use App\User;
use Carbon\Carbon;
use Chat;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use KirbyCaps\Libraries\LinkPreviewer;
use PDF;

class TestController extends Controller
{
	use HasUrlTrait;
	protected $trepo ;
	protected $mrepo ;
	protected $crepo ;
    protected $irepo ;
    protected $temprepo ;
    protected $user ;
    
	public function __construct(
		TimerRepository $trepo, 
		MembersRepository $mrepo, 
		CalendarEventRepository $crepo, 
		InvoiceRepository $irepo, 
		TemplateRepository $temprepo)
	{
		$this->trepo = $trepo;
		$this->mrepo = $mrepo;
		$this->crepo = $crepo;
        $this->irepo = $irepo;
        $this->temprepo = $temprepo;
	    $this->user = User::find(28);
	}
	public function apiStatus(Request $req)
	{
		$date = now(); //utc
		$utc = $date->copy();
		$toronto = $date->copy()->setTimezone('America/Toronto');
		$manila = $date->copy()->setTimezone('Asia/Manila');
		return view('dashboards.status', compact('utc', 'toronto', 'manila'));
	}
	
	public function invoiceTest(Request $req)
	{
		$fields = $this->temprepo->getFields();
		$html  = view('invoices.template-1')->render();
		foreach ($fields as $key => $field) {
			$html  = str_replace('{'.$key.'}', $field['mock_data'], $html);	
		}
		echo $html;
	}

	public function index()
	{	
		// $url = "https://www.amd.com/en/products/cpu/amd-ryzen-5-3600";
		// $url = 'https://music.youtube.com/watch?v=Vdm4eL3OZqM&list=LM';
		$url = 'https://www.canva.com/design/DAEAQ3jvhzw/t-ZU1IwhawS0ydl8_gQucw/view?utm_content=DAEAQ3jvhzw&utm_campaign=designshare&utm_medium=link&utm_source=publishsharelink';
		// $url = 'https://docs.google.com/document/d/11cYpJ4QhcogyYIWwUKjrggFudWeiJBsBIoBD9m367lI/edit';
		dump($this->getPreviewArray($url));
		// dump('olll');
	}

	public function isIframeDisabled($url) 
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
	    } catch (\Exception $ex) { }
	    return false;
	}
}
