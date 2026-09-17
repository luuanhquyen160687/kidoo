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
    protected function optionsList(): array
    {
        return [
            ['key' => 'school_name', 'type' => 'text', 'name' => 'Tên trường'],
            ['key' => 'school_slogan', 'type' => 'text', 'name' => 'Khẩu hiệu nhà trường'],
            ['key' => 'logo', 'type' => 'photo', 'name' => 'Logo'],
            ['key' => 'school_address', 'type' => 'text', 'name' => 'Địa chỉ'],
            ['key' => 'school_principal', 'type' => 'text', 'name' => 'Hiệu trưởng'],
            ['key' => 'school_phone', 'type' => 'text', 'name' => 'Điện thoại'],
            ['key' => 'school_email', 'type' => 'text', 'name' => 'Email'],
        ];
    }

    public function index()
    {
        $options_list = $this->optionsList();

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
        $school_id = $this->app['school']->id;

        foreach ($this->optionsList() as $item) {
            if (! $request->has($item['key'])) {
                continue;
            }

            $existing = DB::table('options')
                ->where('school_id', $school_id)
                ->where('key', $item['key'])
                ->first();

            if ($existing) {
                DB::table('options')
                    ->where('id', $existing->id)
                    ->update([
                        'name' => $item['name'],
                        'data' => $request->input($item['key']),
                        'updated_at' => now(),
                    ]);
            } else {
                DB::table('options')->insert([
                    'school_id' => $school_id,
                    'key' => $item['key'],
                    'name' => $item['name'],
                    'data' => $request->input($item['key']),
                    'type' => $item['type'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return response()->json(['status' => 'success']);
    }
 
    
}


