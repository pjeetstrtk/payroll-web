<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/** Maps the original TBL_USER table (EMP_ID / USER_NAME / USER_PASS / USER_TYPE / IS_ACTIVE). */
class User extends Authenticatable
{
    protected $table = 'tbl_user';
    protected $primaryKey = 'emp_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $guarded = [];
    protected $hidden = ['user_pass'];

    public function getAuthPassword(): string
    {
        return $this->user_pass;
    }

    public function getRememberTokenName(): string
    {
        return '';
    }

    public function isAdmin(): bool
    {
        return in_array($this->user_type, config('payroll.admin_roles'), true);
    }
}
