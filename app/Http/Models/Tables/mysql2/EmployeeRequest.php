<?php

namespace App\Http\Models\Tables\mysql2;

use Illuminate\Database\Eloquent\Model;

class EmployeeRequest extends Model
{
    protected $primaryKey = "txn_no";
    protected $connection = "mysql2";
    protected $table = 't_cashr_rqst';
    public $timestamps = false;

    public function user(){
            return $this->hasOne("App\User", "UserID", "userid")->withDefault([ 'uname' => '-', 'UserID' => '-']);
    }

    public function from_branch(){
    	return $this->hasOne("App\Branch", "Branch", "from_branch")->withDefault([ 'branch' => '-', 'ShortName' => '-' ]);;
    }

    public function to_branch(){
    	return $this->hasOne("App\Branch", "Branch", "to_branch")->withDefault([ 'branch' => '-', 'ShortName' => '-' ]);;
    }
}
