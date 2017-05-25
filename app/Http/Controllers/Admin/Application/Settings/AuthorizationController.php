<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */

namespace App\Http\Controllers\Admin\Application\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\AuthHistory;
use App\Models\Authorization;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class AuthorizationController extends Controller
{
    /**
     * List off data
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $content['title'] = app_title('Authorization');
        $content['authorizations'] = Authorization::get();
        $content['request'] = $request;

        return admin_view('authorization.index', $content);
    }

    /**
     * Authentication history
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getHistory(Request $request)
    {
        $options = [];

        if ($request->get('user_id')) {
            $options['user_id'] = $request->get('user_id');
        }

        $content['title'] = app_title('Authorization Histories');
        $content['authorization_histories'] = AuthHistory::get($options);
        $content['request'] = $request;

        return admin_view('authorization.history', $content);
    }

    /**
     * Create new data
     *
     * @return mixed
     */
    public function create()
    {
        $content['title'] = app_title('Create Authorization');
        $content['route_names'] = access_routes();

        return admin_view('authorization.create', $content);
    }

    /**
     * Store new data
     *
     * @param Requests\Admin\AuthorizationStore $request
     * @return mixed
     */
    public function store(Requests\Admin\AuthorizationStore $request)
    {
        Authorization::store($request->all());

        return redirect('admin/authorizations');
    }

    /**
     * Edit data
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $content['title'] = app_title('Edit Authorization');
        $content['route_names'] = access_routes();

        $data = Authorization::single($id);
        if (!$data) {
            abort(404);
        }

        $content['authorization'] = $data;
        return admin_view('authorization.edit', $content);
    }

    /**
     * Update data
     *
     * @param Requests\Admin\AuthorizationUpdate $request
     * @return mixed
     */
    public function update(Requests\Admin\AuthorizationUpdate $request)
    {
        Authorization::edit($request->get('id'), $request->all());
        return redirect('admin/authorizations');
    }

    /**
     * Reset all authorization
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function reset()
    {
        DB::table('authorizations')->truncate();
        DB::table('authorization_roles')->truncate();

        // routes
        foreach (Route::getRoutes() as $value) {
            $name = $value->getName();
            if ($name) {
                DB::table('authorizations')->insert([
                    'name' => ucwords(str_replace('_', ' ', str_replace('-', ' ', $name))),
                    'identifier' => $name,
                    'created_at' => sql_date()
                ]);
            }
        }

        // authorization role (administrator)
        $authorization = DB::table('authorizations');
        $roles = ['admin', 'employee', 'cashier', 'supplier', 'tel_agent'];

        foreach ($roles as $role) {
            $find_role = Role::where('slug', $role)->first();

            if ($find_role) {
                foreach ($authorization->get() as $row) {
                    DB::table('authorization_roles')->insert([
                        'role_id' => $find_role->id,
                        'authorization_id' => $row->id,
                        'created_at' => sql_date()
                    ]);
                }
            }
        }

        return redirect('admin/authorizations');
    }

    /**
     * Delete data
     *
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        Authorization::remove($id);

        if (request()->ajax()) {
            return success_json_response('Successfully deleted authorization.');
        }

        return redirect()->back();
    }
}
