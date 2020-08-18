<?php

namespace App;

use App\Traits\ConversableTrait;
use App\Traits\HasTimers;
use App\Traits\TaskTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kodeine\Acl\Traits\HasRole;
use Laravel\Cashier\Billable;
use Laravel\Passport\HasApiTokens;
use Laravel\Scout\Searchable;
use Plank\Metable\Metable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

/**
 * Class User
 * @package App
 */
class User extends Authenticatable implements HasMedia
{
    use Notifiable,
        HasRole,
        Metable,
        SoftDeletes,
        HasApiTokens,
        HasMediaTrait,
        Billable,
        LogsActivity,
        HasTimers,
        Searchable,
        TaskTrait,
        ConversableTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'username', 'first_name', 'last_name', 'email', 'is_online', 'telephone', 'job_title', 'password', 'image_url', 'created_by', 'props'
    ];

    /**
     * @var string
     */
    protected static $logName = 'system.user';

    /**
     * @var array
     */
    protected $appends = ['fullname', 'location', 'rate', 'user_roles', 'is_admin', 'is_client', 'is_manager', 'is_company_owner', 'is_buzzooka_super_admin'];

    /**
     * @var bool
     */
    protected static $logOnlyDirty = true;

    /**
     * @var array
     */
    protected static $logAttributes = [
        'username', 'first_name', 'last_name', 'email', 'telephone', 'job_title', 'password', 'image_url'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'telephone' => 'array',
        'props' => 'array'
    ];
    /**
     * @var array
     */
    protected $default_columns = [
        'email', 'first_name', 'id', 'image_url', 'job_title', 'last_name', 'telephone', 'trial_ends', 'username'
    ];

    /**
     * @var int
     */
    protected $paginate = 10;

    /**
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @var array
     */
    protected $dates = ['deleted_at', 'trial_ends_at', 'subscription_ends_at'];

    /**
     * @return array
     */
    public function basics()
    {
        $company = $this->company() ?? null;
        $basic_company = $company ? ['id' => $company->id, 'name' => $company->name] : [];
        return [
            'id' => $this->id,
            'company' => $basic_company,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'fullname' => $this->fullname,
            'job_title' => $this->job_title,
            'email' => $this->email,
            'image_url' => $this->image_url,
            'is_admin' => $this->hasRoleLike('admin'),
            'is_client' => $this->hasRoleLike('client'),
            'is_manager' => $this->hasRoleLike('manager'),
            'is_company_owner' => $this->is_company_owner ?? false,
            'is_buzzooka_super_admin' => in_array($this->email, config('telescope.allowed_emails'))
        ];
    }

    /**
     * @return array
     */
    public function getParticipantDetailsAttribute()
    {
        return $this->basics();
    }

    /**
     * @return bool
     */
    public function getIsAdminAttribute()
    {
        return $this->hasRoleLike('admin') ?? false;
    }

    /**
     * @return bool
     */
    public function getIsClientAttribute()
    {
        return $this->hasRoleLike('client') ?? false;
    }

    /**
     * @return bool
     */
    public function getIsManagerAttribute()
    {
        return $this->hasRoleLike('manager') ?? false;
    }

    /**
     * @return bool
     */
    public function getIsBuzzookaSuperAdminAttribute()
    {
        return in_array($this->email, config('telescope.allowed_emails')) ?? false;
    }

    /**
     * @return mixed
     */
    public function getPasswordResetToken()
    {
        return app('auth.password.broker')->createToken($this);
    }

    /**
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token, $this->email));
    }

    /**
     * @param $status
     * @return int
     */
    public function taskStatusCounter($status)
    {
        return $this->tasks()->where('status', $status)->count();
    }

    /**
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return "A user has been {$eventName}";
    }

    /**
     * Get the user's full name.
     * @return string
     */
    public function getFullnameAttribute()
    {
        return ucwords($this->first_name) . ' ' . ucwords($this->last_name);
    }

    /**
     * Get the user's profile link
     * @return string
     */
    public function getProfileLinkAttribute()
    {
        if ($this->hasRoleLikeIn(['admin', 'manager'])) {
            return config('app.frontend_url') . '/dashboard/team/profile/' . $this->id;
        } elseif ($this->hasRoleLike('client')) {
            return config('app.frontend_url') . '/dashboard/clients/profile/' . $this->id;
        }
        return config('app.frontend_url') . '/dashboard/team/profile/' . $this->id;
    }

    /**
     * Get the user's  reset_password_link
     * @return string
     */
    public function getResetPasswordLinkAttribute()
    {
        return config('app.frontend_url') . '/set-password/' . $this->email . '/' . $this->getPasswordResetToken();
    }

    /**
     * Get the user's rate
     * @return string
     */
    public function getRateAttribute()
    {
        return $this->getMeta('rate') ?? '';
    }

    /**
     * @return array
     */
    public function getUserRolesAttribute()
    {
        return $this->getRoles() ?? [];
    }

    /**
     * is user the owner of company
     * @return boolean
     */
    public function getIsCompanyOwnerAttribute()
    {
        $company = $this->company();
        if ($company) {
            $owner = $company->company_members()->orderBy('id', 'asc')->first();
            return (int)$this->id == (int)$owner->id;
        }
        return false;
    }

    /**
     * Get the user's location
     * @return string
     */
    public function getLocationAttribute()
    {
        return $this->getMeta('location') ?? 'Unknown';
    }

    /**
     * @return mixed
     */
    public function userRole()
    {
        return collect($this->getRoles())->first();
    }

    /**
     * @param Media|null $media
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(368)
            ->height(232)
            ->sharpen(10);
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function clientStaffs()
    {
        list($sortName, $sortValue) = parseSearchParam(request());

        $model = $this->children();

        if (request()->has('sort') && !empty(request()->sort))
            $model->orderBy($sortName, $sortValue);
        else
            $model->orderBy('users.created_at', 'DESC');

        if (request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        $data = $model->paginate($this->paginate);

        $data->map(function ($user) {
            unset($user['tasks']);
            unset($user['projects']);
            $user['tasks'] = $user->tasks()->count();
            $user['projects'] = $user->projects()->count();
            $roles = $user->roles()->first();
            if (!is_null($roles))
                $user['group_name'] = $roles->id;
        });

        return $data;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function notes()
    {
        return $this->belongsToMany(Note::class)->withPivot('is_pinned');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function addNotes()
    {
        request()->validate([
            'content' => 'required'
        ]);

        $data = [
            'title' => request()->title,
            'content' => request()->get('content'),
            'remind_date' => request()->remind_date
        ];

        $note = $this->notes()->create($data);

        $note->load('users');

        $note->pivot = array(
            'user_id' => auth()->user()->id,
            'note_id' => $note->id,
            'is_pinned' => 0
        );
        return $note;
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function getNotes()
    {
        list($sortName, $sortValue) = parseSearchParam(request());

        $model = $this->notes()->with('users');

        if (request()->has('sort') && !is_null($sortValue)) {
            $query = "note_user.is_pinned DESC, CASE WHEN note_user.is_pinned = 1 THEN notes.id ELSE 0 END, {$sortName} {$sortValue}";

            $model->orderByRaw($query);
        } else {
            $query = "note_user.is_pinned DESC, CASE WHEN note_user.is_pinned = 1 THEN notes.id ELSE 0 END, created_at DESC";

            $model->orderByRaw($query);
        }

        if (request()->has('search') && !empty(request()->search)) {
            $keyword = request()->search;

            $model->where(function ($query) use ($keyword) {
                $query->where('notes.title', 'like', '%' . $keyword . '%');
                $query->orWhere('notes.content', 'like', '%' . $keyword . '%');
                $query->orWhere('notes.created_at', 'like', '%' . $keyword . '%');
            });
        }

        if (request()->has('per_page'))
            $this->paginate = request()->per_page;

        $data = $model->paginate($this->paginate);

        if (request()->has('all') && request()->all)
            $data = $model->get();

        return $data;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function unReadMessages()
    {
        $data = $this->messageNotification()
            ->where('is_seen', 0)
            ->latest()
            ->get();

        $data = $data->unique('conversation_id');

        $data->map(function ($model) {
            $model->msg = $model->message()->latest()->first();
            $model->sender = $model->msg
                ->sender()
                ->select('id', 'first_name', 'last_name', 'image_url')
                ->first();
            $model->body = $model->msg->body;
            unset($model->msg);
        });

        return $data;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messageNotification()
    {
        return $this->hasMany(MessageNotification::class);
    }

    /**
     * @param $query
     * @param array $roles
     * @return mixed
     */
    public function scopeHasRoleIn($query, $roles = [])
    {
        return $query->whereHas('roles', function ($query) use ($roles) {
            foreach ($roles as $key => $role) {
                if ($key == 0) {
                    $query->where('roles.slug', 'like', "%{$role}%");
                } else {
                    $query->orWhere('roles.slug', 'like', "%{$role}%");
                }
            }
        });
    }

    /**
     * @return mixed
     */
    public static function admins()
    {
        return self::whereHas('roles', function ($query) {
            $query->where('roles.slug', 'admin');
        });
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeHasAdminRole($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('roles.slug', 'like', '%admin%');
        });
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeHasManagerRole($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('roles.slug', 'like', '%manager%');
        });
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeHasClientRole($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('roles.slug', 'like', '%client%');
        });
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeHasMemberRole($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('roles.slug', 'like', '%member%');
        });
    }

    /**
     * @param $find
     * @return bool
     */
    public function hasRoleLike($find)
    {
        $roles = $this->getRoles() ?? [];
        foreach ($roles as $key => $role) {
            if (stripos($role, $find) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $find_array
     * @return bool
     */
    public function hasRoleLikeIn($find_array)
    {
        if (empty($find_array)) {
            return false;
        }
        $roles = $this->getRoles() ?? [];
        foreach ($roles as $key => $role) {
            foreach ($find_array as $key2 => $find) {
                if (stripos($role, trim($find)) !== false) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function scopeDefaultColumn()
    {
        return $this->select('id', 'email', 'first_name', 'image_url', 'job_title', 'last_name', 'telephone', 'trial_ends_at', 'username')
            ->where('id', $this->id)
            ->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function oAuthAccessToken()
    {
        return $this->hasMany(OauthAccessToken::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function billedToInvoices()
    {
        return $this->hasMany(Invoice::class, 'billed_to', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function billedFromInvoices()
    {
        return $this->hasMany(Invoice::class, 'billed_from', 'id');
    }

    /**
     * @return mixed
     */
    public function allInvoices()
    {

        return $this->billedToInvoices->merge($this->billedFromInvoices);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function acts()
    {
        return $this->belongsToMany('App\Activity', 'activity_user', 'user_id', 'activity_id')
            ->withPivot('read_at');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function unreadActivity()
    {
        return $this->acts()
            ->whereNull('read_at')
            ->with('causer')
            ->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(User::class, 'created_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activity()
    {
        return $this->morphMany('Spatie\Activitylog\Models\Activity', 'causer');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function timers()
    {
        return $this->morphMany(Timer::class, 'causer')
            ->where('subject_type', 'App\\Company');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function forms()
    {
        return $this->hasMany(Form::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tasks()
    {
        return $this->belongsToMany(Task::class)->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|\Illuminate\Database\Query\Builder
     */
    public function projectsCount()
    {
        return $this->belongsToMany(Project::class)
            ->selectRaw('count(projects.id) as projects')
            ->groupBy('project_id', 'user_id');
    }

    /**
     * @return int
     */
    public function getProjectsCountAttribute()
    {
        if (!array_key_exists('projectsCount', $this->relations)) $this->load('projectsCount');

        $related = $this->getRelation('projectsCount')->first();

        return ($related) ? $related->aggregate : 0;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user')->withPivot('role');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function services()
    {
        return $this->hasMany(ServicesList::class);
    }

    /**
     * @return mixed
     */
    public function company()
    {
        return $this->teams()->first()->company;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_user')
            ->withPivot('type')
            ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function clientCompanies()
    {
        return $this->belongsToMany(Company::class, 'company_user')
            ->wherePivot('type', '=', 'client')
            ->withTimestamps();
    }

    /**
     * @return mixed
     */
    public function getMainCompanyAttribute()
    {
        return $this->belongsToMany(Company::class, 'company_user')
            ->wherePivot('type', '=', 'main')
            ->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function milestoneTemplate()
    {
        return $this->hasMany(MilestoneTemplate::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function calendar()
    {
        return $this->hasOne(CalendarModel::class, 'id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function event_participations()
    {
        return $this->hasMany(EventParticipant::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function events()
    {
        return $this->belongsToMany(EventModel::class, 'event_participants', 'user_id', 'event_id')
            ->withPivot('added_by')
            ->withTimestamps();
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->only('first_name', 'last_name', 'email');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scheduleTasks()
    {
        return $this->hasMany(ScheduleTask::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'mc_conversation_user', 'user_id', 'conversation_id')->withTimestamps();
    }
}
