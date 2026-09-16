<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class NavigationsController extends BaseController
{
    private function availableRoutingsQuery($school_id)
    {
        return DB::table('routings')
        ->leftJoin('pages', function ($join) {
            $join->on('pages.id', '=', 'routings.entity_id')
                 ->where('routings.entity', '=', 'pages');
        })
        ->leftJoin('posts', function ($join) {
            $join->on('posts.id', '=', 'routings.entity_id')
                 ->where('routings.entity', '=', 'posts');
        })
        ->where('routings.school_id', $school_id)
        ->whereIn('routings.entity', ['pages','posts'])
        ->where(function ($query) {
            $query->where(function ($q) {
                $q->where('routings.entity', 'pages')
                  ->whereNotNull('pages.id')
                  ->whereNull('pages.deleted_at');
            })->orWhere(function ($q) {
                $q->where('routings.entity', 'posts')
                  ->whereNotNull('posts.id')
                  ->where('posts.is_published', 1)
                  ->whereNull('posts.deleted_at');
            });
        })
        ->orderBy('routings.entity')
        ->orderBy('routings.title')
        ->select('routings.*');
    }

    public function index()
    {
        $school_id = $this->app['school']->id;

        $parents = DB::table('navigations')
        ->whereNull('parent_id')
        ->whereNull('deleted_at')
        ->where('school_id', $school_id)
        ->orderBy('sort')
        ->orderBy('created_at','desc')
        ->get();

        $children = DB::table('navigations')
        ->whereNotNull('parent_id')
        ->whereNull('deleted_at')
        ->where('school_id', $school_id)
        ->orderBy('parent_id')
        ->orderBy('sort')
        ->get()
        ->groupBy('parent_id');

         $navigations = $parents->map(function ($parent) use ($children) {
            return [
                'id' => $parent->id,
                'name' => $parent->name,
                'routing_id' => $parent->routing_id,
                'children' => $children->get($parent->id, collect())->values(),
            ];
        });

        $data['navigations']=$navigations;
        $data['parents']=$parents;
        $data['routings']=$this->availableRoutingsQuery($school_id)->get();
        return view('admin.navigations.index',$data);
    }
    public function show($id)
    {
         $teacher = DB::table('users')
        ->where('school_id', $this->app['school']->id)
        ->where('id', $id)
        ->first();
        $data['teacher']=$teacher;
        return view('admin.navigations.show',$data);
    }
    public function edit($id){

         $navigation = DB::table('navigations')
        ->where('school_id', $this->app['school']->id)
        ->where('id', $id)
        ->first();

        abort_if(!$navigation, 404);

        return response()->json([
            'id' => $navigation->id,
            'name' => $navigation->name,
            'parent_id' => $navigation->parent_id,
            'routing_id' => $navigation->routing_id,
        ]);
    }

    public function update($id,Request $request){
         $validator = Validator::make($request->all(), [
        'name' => 'required|min:5',
        'routing_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }


        DB::table('navigations')
        ->where('id', $id)
        ->update([
                'name' => $request->get('name'),
                'parent_id' => $request->get('parent_id'),
                'routing_id'=> $request->get('routing_id'),
                'updated_at' => now(),
        ]);

        return response()->json(['status' => 'ok']);
    }
    public function create()
    {
        $school_id = $this->app['school']->id;

        $parents = DB::table('navigations')
        ->whereNull('parent_id')
        ->whereNull('deleted_at')
        ->where('school_id', $school_id)
        ->orderBy('sort')
        ->get();

        $data['parents']=$parents;
        $data['routings']=$this->availableRoutingsQuery($school_id)->get();

        return view('admin.navigations.create',$data);
    }
    public function store(Request $request){

        $validator = Validator::make($request->all(), [
        'name' => 'required|min:5',
        'routing_id' => 'required',
        ]);



        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }


        DB::table('navigations')->insertGetId([
        'name' => $request->get('name'),
        'parent_id' => $request->get('parent_id'),
        'routing_id' => $request->get('routing_id'),
        'school_id'=>$this->app['school']->id,
        'created_at' => now(),
        'updated_at' => now(),
        ]);

        return response()->json(['status' => 'ok']);
    }
 
    public function destroy($id){ 
        DB::table('navigations')
        ->where('id', $id)
        ->where('school_id', $this->app['school']->id)
        ->update(['deleted_at' => now()]); 

            return redirect()->route('navigations.index')
                            ->with('success', 'Level deleted successfully.');
    }
    public function sort(Request $request){

       foreach ($request->get('sort') as $sort){
            DB::table('navigations')
            ->where('id', $sort['id'])
            ->where('school_id', $this->app['school']->id)
            ->update(['sort' => $sort['sort']]);
       }
    }
}


