<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Mail\NewInvoiceEmail;
use App\Repositories\CalendarEventRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\MembersRepository;
use App\Repositories\TemplateRepository;
use App\Repositories\TimerRepository;
use App\Service;
use App\Company;
use App\User;
use Carbon\Carbon;
use Chat;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use PDF;

class TestController extends Controller
{
	protected $trepo ;
	protected $mrepo ;
	protected $crepo ;
    protected $irepo ;
    protected $temprepo ;
    protected $user ;
	public function __construct(TimerRepository $trepo, MembersRepository $mrepo, CalendarEventRepository $crepo, InvoiceRepository $irepo, TemplateRepository $temprepo)
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
		
	}
}
