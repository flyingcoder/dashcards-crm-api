<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Invoice extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'billed_date', 'due_date', 'notes','project_id'
    ];

    protected $dates = ['deleted_at'];


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
            'project_id' =>request()->project_id,

        ]);
        return $invoice;
    }

    public function project()
    {
    	return $this->belongsTo(Project::class);
    }
}
