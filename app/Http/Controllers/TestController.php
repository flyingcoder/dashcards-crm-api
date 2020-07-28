<?php

namespace App\Http\Controllers;

use App\Repositories\CalendarEventRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\MembersRepository;
use App\Repositories\TemplateRepository;
use App\Repositories\TimerRepository;
use App\Traits\HasUrlTrait;
use App\Traits\StripeTrait;
use App\Traits\TemplateTrait;
use App\Traits\TimezoneTrait;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TestController extends Controller
{
    use HasUrlTrait, StripeTrait, TemplateTrait, TimezoneTrait;
    protected $trepo;
    protected $mrepo;
    protected $crepo;
    protected $irepo;
    protected $temprepo;
    protected $user;

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

    /**
     * @param Request $req
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function apiStatus(Request $req)
    {
        $date = now(); //utc
        $utc = $date->copy();
        $toronto = $date->copy()->setTimezone('America/Toronto');
        $manila = $date->copy()->setTimezone('Asia/Manila');
        return view('dashboards.status', compact('utc', 'toronto', 'manila'));
    }

    /**
     * @param Request $req
     * @throws \Throwable
     */
    public function invoiceTest(Request $req)
    {
        $fields = $this->temprepo->getFields();
        $html = view('invoices.template-1')->render();
        foreach ($fields as $key => $field) $html = str_replace('{' . $key . '}', $field['mock_data'], $html);
        echo $html;
    }

    /**
     * @return mixed|void
     */
    public function index()
    {
        $s = Carbon::now('UTC');
        dump($s->copy(),$s->addHour());
    }

}
