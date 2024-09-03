<?php

use App\Models\Semester;
use Illuminate\Http\Request;

// Get academic year
if (!function_exists('getAcademicYearId')) {
    function getAcademicYearId(Request $request)
    {
        $auth_user = $request->user();

        if($auth_user->user_semester){
            return $auth_user->user_semester->semester->academic_year_id;
        }else{
            return Semester::active_semester()->academic_year_id;
        }


    }
}

// Get Semster
if (!function_exists('getSemesterId')) {
    function getSemesterId(Request $request)
    {
        $auth_user = $request->user();

        if($auth_user->user_semester){
            return $auth_user->user_semester->semester_id;
        }else{
            return Semester::active_semester()->id;
        }


    }
}
