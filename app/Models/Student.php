<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'tbl_student';

    protected $primaryKey = 'StudentID';

    public $timestamps = false; // Since the table uses DateCreated, not created_at/updated_at

    protected $fillable = [
        'LRN',
        'Username',
        'RFIDTag',
        'FirstName',
        'MiddleInitial',
        'LastName',
        'GradeLevelID',
        'StrandID',
        'SectionID',
        'PasswordHash',
        'ImagePath',
        'Balance',
        'Role',
        'DateCreated',
        'Status',
        'gmail',
        'IsBlocked',
        'Otp',
        'OtpExpiration',
    ];
        /**
     * Attempt to find a student by LRN or Username.
     *
     * @param string $logcredential
     * @return Student|null
     */
    public static function student_credentials($logcredential)
    {
        return self::where('LRN', $logcredential)
            ->orWhere('Username', $logcredential)
            ->first();
    }
}
