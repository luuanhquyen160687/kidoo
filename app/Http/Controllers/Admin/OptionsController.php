<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OptionsController extends BaseController
{
    public function index()    
    {  
        $options_list[]=array("key"=>"school_name","type"=>"text","name"=>"Tên trường");
        $options_list[]=array("key"=>"school_slogan","type"=>"text","name"=>"Khẩu hiệu nhà trường");
        $options_list[]=array("key"=>"school_address","type"=>"text","name"=>"Địa chỉ");
        $options_list[]=array("key"=>"school_principal","type"=>"text","name"=>"Hiệu trưởng");
        $options_list[]=array("key"=>"school_phone","type"=>"text","name"=>"Điện thoại");
        $options_list[]=array("key"=>"school_email","type"=>"text","name"=>"Email");
       

     $school = DB::table('schools') 
        ->join('themes','themes.id','schools.theme_id')
        ->where('schools.id', $this->app['school']->id)
        ->select("schools.*","themes.name as theme_name")
        ->first();     
        $data['school']=$school; 
        $themes = DB::table(table: 'themes')
        ->get();      
        $data['themes']=$themes; 
        $data['campuses'] = DB::table('campuses')
        ->where('school_id', $this->app['school']->id)
        ->whereNull('deleted_at')
        ->orderBy('name')
        ->get();
        $options = DB::table('options')
        ->where('options.school_id',$this->app['school']->id)
        ->get();
        $data['options']= $options;

         $data['options_list']= $options_list;

        return view('admin.options.index',$data); 
    }  
  

    public function create(Request $request)
    {
       

  
$data=[];
        return view('admin.options.create',$data);
    } 
    public function store(Request $request){
        $options = DB::table('options')->get();

        foreach ($options as $option) {
            if ($request->has($option->key)) {
                DB::table('options')
                    ->where('id', $option->id)
                    ->update([
                        'data' => $request->input($option->key),
                        'updated_at' => now(),
                    ]);
            }
        }

        return response()->json(['status' => 'success']);
    }
 
    
}


