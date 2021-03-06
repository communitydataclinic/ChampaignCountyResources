<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;

class OrganizationAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        $routeName = $request->route()->getName();

        $list = [
            'contacts.index',
            'contacts.create',
            'contacts.edit',
            'contacts.update',
            'contacts.store',
            'contacts.destroy',
            'facilities.index',
            'facilities.create',
            'facilities.edit',
            'facilities.update',
            'facilities.store',
            'facilities.destroy',
            'organizations.create',
            'organizations.edit',
            'organizations.update',
            'organizations.store',
            'organizations.destroy',
            'services.create',
            'services.edit',
            'services.update',
            'services.store',
            'services.destroy',
            'events.create',
            'events.edit',
            'events.update',
            'events.store',
            'events.destroy',
        ];
        if(in_array($routeName, $list) && (empty($user) || empty($user->roles) || ($user->roles->name != 'Organization Admin' && $user->roles->name != 'System Admin'))) {
            if (!empty($api)) {
                return response()->json(['message' => 'you_dont_have_permission_to_use_this_route'], 403);
            } else {

                Session::flash('message', 'Not enough permissions. Please contact Us for more');
                Session::flash('status', 'warning');
                //return redirect()->back();
                return redirect()->route('login');
            }
        } else {
            return $next($request);
        }

        /* if ($routeName != 'contacts.index' && $routeName != 'facilities.index' && $routeName != 'organizations.create' || $user && $user->roles->name != 'Organization Admin') {
            return $next($request);
        } else {
            if (!empty($api)) {
                return response()->json(['message' => 'you_dont_have_permission_to_use_this_route'], 403);
            } else {

                Session::flash('message', 'Warning! Not enough permissions. Please contact Us for more');
                Session::flash('status', 'warning');
                return redirect()->back();
            }
        } */
    }
}
