<?php

namespace App\Models;

use App\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ErrorLogs extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * @param $request
     * @param $e
     * @return array
     */
    public static function exceptionLogs($request, $functionName, $e)
    {
        try {
            DB::beginTransaction();
            $errorLogs = ErrorLogs::create([
                'request_id' => $request['request_id'],
                'function_name' => $functionName,
                'line_number' => $e->getLine(),
                'exception' => $e->getMessage(),
            ]);
            DB::commit();
            return $errorLogs;
        } catch (\Exception $e) {
            DB::rollBack();
            Helper::errorResponse($request, __FUNCTION__, $e);
        }
    }
}
