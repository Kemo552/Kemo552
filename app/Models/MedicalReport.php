<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalReport extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'medical_reports';
    public $timestamp = false;
    protected $fillable = [
        'report_date',
        'blood_groub',
        'alergies',
        'heart_disease',
        'blood_pressure',
        'previous_surgeries',
        'doctor_name',
        'doctor_phone',
    ];
}
