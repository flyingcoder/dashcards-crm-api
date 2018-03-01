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
        'name', 
        'last_name', 
        'address', 
        'city', 
        'state', 
        'phone', 
        'zip_code', 
        'description', 
        'rate', 
        'tax', 
        'quantity', 
        'billed_date', 
        'due_date', 
        'notes',
        'other_info'
    ];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function store(Request $request)
    {
        $request->validate( [
            'billed_date' => 'required|date',
            'due_date' => 'required|date'
        ]);

        $invoice = self::create([
            'billed_date' =>request()->billed_date,
            'due_date' =>request()->due_date,
            'notes' =>request()->notes,

        ]);
        return $invoice;
    }

    public function project()
    {
    	return $this->belongsTo(Project::class);
    }
}
