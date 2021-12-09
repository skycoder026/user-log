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



            static::creating(function ($model) {


                $data = [];

                if (Schema::hasColumn($model->getTable(), 'created_by')) {
                    $data['created_by'] = auth()->id();
                }


                if (Schema::hasColumn($model->getTable(), 'company_id') && Schema::hasColumn('users', 'company_id')) {
                    $data['company_id'] = auth()->user()->company_id;
                }

                if (count($data) > 0) {
                    $model->fill($data);
                }
            });



            static::updating(function ($model) {


                if (Schema::hasColumn($model->getTable(), 'updated_by')) {
                    $model->fill([
                        'updated_by' => auth()->id()
                    ]);
                }

                if (Schema::hasColumn($model->getTable(), 'approved_by')) {

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

        $class_name = self::class;
        $table_name = (new $class_name())->getTable();

        if (Schema::hasColumn($table_name, 'created_by')) {
            return $this->belongsTo(User::class, 'created_by', 'id');
        }
    }

    public function updated_user()
    {

        $class_name = self::class;
        $table_name = (new $class_name())->getTable();

        if (Schema::hasColumn($table_name, 'updated_by')) {
            return $this->belongsTo(User::class, 'updated_by', 'id');
        }
    }

    public function approved_user()
    {

        $class_name = self::class;
        $table_name = (new $class_name())->getTable();

        if (Schema::hasColumn($table_name, 'approved_by')) {
            return $this->belongsTo(User::class, 'approved_by', 'id');
        }
    }

    public function company()
    {

        $class_name = self::class;
        $table_name = (new $class_name())->getTable();

        if (Schema::hasColumn($table_name, 'company_id')) {
            return $this->belongsTo(Company::class, 'company_id', 'id');
        }
    }
}
