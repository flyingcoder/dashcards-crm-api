<?php

namespace App\Http\Controllers;

use App\Mail\NewInvoiceEmail;
use App\Repositories\CalendarEventRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\MembersRepository;
use App\Repositories\TimerRepository;
use App\User;
use Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Konekt\PdfInvoice\InvoicePrinter;

class TestController extends Controller
{
	protected $trepo ;
	protected $mrepo ;
	protected $crepo ;
    protected $irepo ;
	public function __construct(TimerRepository $trepo, MembersRepository $mrepo, CalendarEventRepository $crepo, InvoiceRepository $irepo)
	{
		$this->trepo = $trepo;
		$this->mrepo = $mrepo;
		$this->crepo = $crepo;
        $this->irepo = $irepo;
	}

	public function test(Request $req)
	{
		$date = now(); //utc
		dump($date->copy());
		dump($date->copy()->setTimezone('America/Toronto'));
		dump($date->copy()->setTimezone('Asia/Manila'));
	}
	
	public function index()
	{	
		$date = now(); //utc
		dump($date->copy());
		dump($date->copy()->setTimezone('America/Toronto'));
		dump($date->copy()->setTimezone('Asia/Manila'));
	}
}
