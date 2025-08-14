<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_id',
        'user_id',
        'enterprise_type',
        'reporter_name',
        'details',
        'reported_at',
        'priority',
        'status',
    ];

    protected $dates = ['reported_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
