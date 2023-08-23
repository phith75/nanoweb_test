<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\staff;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\StaffRequest;
use Illuminate\Validation\ValidationException;
class StaffController extends Controller
{
    public function index(Request $request){
            $title = "list staff";
            $itemPerPage = 3;
            $currentPage = $request->input('page',1); // trang hien tai, mac dinh la 1;
            $start = ($currentPage - 1) * $itemPerPage;
            $end = $start + $itemPerPage;
            $staffList = DB::table('staff')->skip($start)->take($itemPerPage)->get();
            $totalItems = DB::table('staff')->count();
            $totalPages = ceil($totalItems / $itemPerPage);
            if ($request->isMethod("POST")){
                $staffList = DB::table('staff')->where('name','like','%'.$request->search.'%')->get();
            }
            return view('staff.list',compact('title','staffList', 'currentPage', 'totalPages'));
    }
    public function add(StaffRequest $request){
        $title = "add";
        if ($request->isMethod('post')){

            $params = $request->except('_token');
            $request->validate([
                'tel' => [
                    'max:14',
                    function($attribute, $value, $fail){
                    if (!preg_match('/^[0-9]{1,4}-[0-9]{1,4}-[0-9]{1,4}$/', $value)){
                        $fail('Tel Khong dung format');
                    }
                    }
                ]
            ]);
            $params['name'] = strip_tags($params['name']);
            $params['name'] = htmlspecialchars($params['name'], ENT_QUOTES, 'UTF-8');

            $staff = staff::create($params);
            if ($staff){
                Session::flash('success','Them thanh cong');
            }
        }
        return view('staff.add',compact('title'));
    }
    public function edit(StaffRequest $request,$id){
        $title = "edit";
        $staff = DB::table('staff')->where('id',$id)->first();
        if ($request->isMethod('POST')){

            $params = $request->except('_token');
            $request->validate([
                'tel' => [
                    'max:14',
                    function($attribute, $value, $fail){
                        if (!preg_match('/^[0-9]{1,4}-[0-9]{1,4}-[0-9]{1,4}$/', $value)){
                            $fail('Tel Khong dung format');
                        }
                    }
                ]
            ]);
            $params['name'] = strip_tags($params['name']);
            $params['name'] = htmlspecialchars($params['name'], ENT_QUOTES, 'UTF-8');
            $staff = staff::where('id', $id)->update($params);

            if ($staff){

                Session::flash('success','Sua thanh cong');
                return redirect()->route('list');
            }
        }
        return view('staff.edit',compact('title','staff'));

    }
}
