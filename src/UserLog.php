<?php

namespace Skycoder\UserLog;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;

trait UserLog
{

    public static function boot()
    {
        parent::boot();


        if (!App::runningInConsole()) {

            $data = [];

            if (Schema::hasColumn('users', 'created_by')) {
                $data['created_by'] = auth()->id();
            }



            static::creating(function ($model) use ($data) {

                if (Schema::hasColumn('users', 'company_id')) {
                    $data['company_id'] = auth()->id();
                }

                if (count($data) > 0) {
                    $model->fill($data);
                }
            });



            static::updating(function ($model) {


                if (Schema::hasColumn('users', 'updated_by')) {
                    $model->fill([
                        'updated_by' => auth()->id()
                    ]);
                }

                if (Schema::hasColumn('users', 'approved_by')) {

                    if ($model->isDirty('approved_by')) {
                        $model->fill([
                            'approved_by' => auth()->id()
                        ]);
                    }
                }
            });
        }
    }

    public function scopeUserLog($query)
    {
        return $query->with('created_user', 'updated_user');
    }

    public function created_user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updated_user()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function approved_user()
    {
        if (Schema::hasColumn('users', 'approved_by')) {
            return $this->belongsTo(User::class, 'approved_by', 'id');
        }
    }

    public function company()
    {
        if (Schema::hasColumn('users', 'company_id')) {
            return $this->belongsTo(Company::class, 'company_id', 'id');
        }
    }
}
