<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 
        'project_id',
        'due_date',
        'items',
        'total_amount',
        'terms',
        'tax',
        'due_date'
    ];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function store(Request $request)
    {
        if(request()->has('client_id')){
            $user = User::findOrFail(request()->client_id);
        }

        $request->validate( [
            'billed_date' => 'required|date',
            'due_date' => 'required|date',
            'name' => 'required',
            'rate' => 'required',
            'quantity' => 'required'
        ]);

        $invoice = self::create([
            'title' =>request()->title,
            'billed_date' =>request()->billed_date,
            'due_date' =>request()->due_date,
            'notes' =>request()->notes,
            'name' =>request()->name,
            'last_name' =>request()->last_name,
            'rate' =>request()->rate,
            'tax' =>request()->tax,
            'quantity' =>request()->quantity,
            'address' =>request()->address,
            'description' =>request()->description,
            'other_info' =>request()->other_info,
            'city' =>request()->city,
            'state' =>request()->state,
            'phone' =>request()->phone,
            'zip_code' =>request()->zip_code,

        ]);
        
        return $invoice;
    }

    public function project()
    {
    	return $this->belongsTo(Project::class);
    }
}
