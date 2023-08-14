<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\staff;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\StaffRequest;
class StaffController extends Controller
{
    public function index(Request $request){
            $title = "list staff";
            $staffList = staff::all();
            if ($request->isMethod("POST")){
                $staffList = DB::table('staff')->where('name','like','%'.$request->search.'%')->get();
            }
            return view('staff.list',compact('title','staffList'));
    }
    public function add(StaffRequest $request){
        $title = "add";
        if ($request->isMethod('post')){
            $params = $request->except('_token');
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

            $staff = staff::where('id', $id)->update($params);

            if ($staff){

                Session::flash('success','Sua thanh cong');
                return redirect()->route('list');
            }
        }
        return view('staff.edit',compact('title','staff'));

    }
}
