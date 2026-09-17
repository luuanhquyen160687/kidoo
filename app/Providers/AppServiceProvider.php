<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Request $request): void
    {
        $domain = $request->getHost();   
        $school = DB::table('schools')
        ->where('domain', $domain)
        ->first();
        if(!$school){
            return;
            }
        View::composer('admin.partials.nav', function ($view) {

            $user = DB::table('users')
            ->select('users.*')
            ->where('users.id', Auth::user()->id)
            ->whereNull('users.deleted_at')
            ->orderBy('users.created_at', 'desc')
            ->first(); 
            
        
            $view->with('currentUser', $user);
        });
        
        // get navigation

         $parents = DB::table('navigations')
        ->whereNull('parent_id')
        ->whereNull('deleted_at')
        ->where('school_id', $school->id)
        ->orderBy('sort')
        ->orderBy('created_at','desc')
        ->get();

        $children = DB::table('navigations')
        ->whereNotNull('parent_id')
        ->whereNull('deleted_at')
        ->where('school_id', $school->id)
        ->orderBy('parent_id')
        ->orderBy('sort')
        ->get()
        ->groupBy('parent_id');

         $navigations = $parents->map(function ($parent) use ($children) {
            return [
                'id' => $parent->id,
                'name' => $parent->name,
                'parent_id' => $parent->parent_id,
                'routing_id' => $parent->routing_id,
                'children' => $children->get($parent->id, collect())->values(),
            ];
        });

        
        $options = DB::table('options')
        ->where('school_id', $school->id)
        ->get()
        ->keyBy('key');

        $logo = $options->get('logo')->data ?? null;

        $data['school']=$school;
        $data['navigations']=$navigations;
        $data['options'] = $options;
        $data['logo_url'] = $logo ? getPhotoUrl($logo) : '/assets/admin/img/icons/logo.png';
        View::share('app', $data);



    }
     

    
}
