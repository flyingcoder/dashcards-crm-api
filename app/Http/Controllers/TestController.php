<?php

namespace App\Http\Controllers;

use App\Events\ChatNotification;
use App\Events\GroupChatSent;
use App\Media;
use App\Message;
use App\Notifications\CompanyNotification;
use App\Project;
use App\Repositories\CalendarEventRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\MembersRepository;
use App\Repositories\TemplateRepository;
use App\Repositories\TimerRepository;
use App\Traits\ConversableTrait;
use App\Traits\HasConfigTrait;
use App\Traits\HasUrlTrait;
use App\Traits\StripeTrait;
use App\Traits\TemplateTrait;
use App\Traits\TimezoneTrait;
use App\User;
use Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Musonza\Chat\Eventing\MessageWasSent;

class TestController extends Controller
{
    use HasUrlTrait, StripeTrait, TemplateTrait, TimezoneTrait, HasConfigTrait, ConversableTrait;
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
        $this->user = User::find(5);
    }

    /**
     *
     */
    public function phpinfo()
    {
        echo phpinfo();
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
     * @throws \Exception
     */
    public function index()
    {
        //broadcast(new GroupChatSent(Message::find(17)));
        /*$user = User::find(3);
        $chat = Chat::conversations()->getById(23);
        $message = Chat::message('Group wannaber ')
            ->type('text')
            ->from($user)
            ->to($chat)
            ->send();
        ChatNotification::dispatch($message);
        dump($message);*/
        /*$convos = $this->user->conversations()
                ->whereHas('last_message')
                ->with('last_message')
                ->paginate(15);

        $items = $convos->getCollection();
        $data = collect([]);
        foreach ($items as $key => $convo) {
            $notification = $convo->last_message->getNotification($this->user);
            $data->push(array_merge($convo->toArray(), ['notification' => $notification ]));
        }
        $convos->setCollection($data);

        dump($convos);*/
        /*$convos = $this->user->conversations()
            ->whereHas('last_message')
            ->with('last_message')
            ->paginate(15);

        $items = $convos->getCollection();
        $data = collect([]);
        foreach ($items as $key => $convo) {
            $message = $convo->last_message;
            unset($convo['last_message']);
            $message->setRelation('conversation', $convo);
            $message->setRelation('notification', $message-c>getNotification($this->user));
            $data->push($message);
        }
        $convos->setCollection($data);

        dd($convos);*/
        /*$user = $this->user;
        $company = $user->company();
        $members = $company->company_members()->where('id', '<>', $user->id)->get();
        $user_list = collect([]);
        foreach ($members as $member) {
            $member->conversation = $user->privateRoom($member);
            $user_list->push($member);
        }

        $group_list = $user->conversations()->where('type', 'group')->get();

        return response()->json(['user_list' => $user_list, 'group_list' => $group_list], 200);*/
        $media = Media::find(23);
        dump(url($media->getUrl('thumb')));
    }

    /**
     * @return mixed|void
     */
    public function sample()
    {
        dd(request()->user());
        $user = User::find(3);
        $company = $user->company();

        $data = array(
            'company' => $company->id,
            'targets' => [],
            'title' => 'Flying Kunai',
            'image_url' => 'https://www.itsolutionstuff.com/frontTheme/images/logo.png', //company image, attachments etc
            'message' => " Avigan is a compassionate drug which meant that it was subject to trial. Do not really know the efficacy of the medicine,” she added.",
            'type' => 'task:update',
            'url' => 'https://crm.buzzookalocal.net:8080/dashboard/clients',
        );

        Notification::send($user, new CompanyNotification($data));

        $data = array(
            'company' => $company->id,
            'targets' => [],
            'data' => [
                'title' => 'Flying Kunai',
                'image_url' => 'https://www.itsolutionstuff.com/frontTheme/images/logo.png', //company image, attachments etc
                'message' => " Avigan is a compassionate drug which meant that it was subject to trial. Do not really know the efficacy of the medicine,” she added.",
                'type' => 'task:update',
                'url' => null,
                'sender' => $user
            ],
            'read_at' => null,
        );
        //Notification::send($user, new ChatNotification($data));
    }

}
