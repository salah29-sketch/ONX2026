<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRoleRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Admin\Permission;
use App\Models\Admin\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class RolesController extends Controller
{
    public function index()
    {
        abort_unless(Gate::allows('role_access'), 403);

        $roles = Role::all();

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        abort_unless(Gate::allows('role_create'), 403);

        $permissions = Permission::pluck('title', 'id');

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request)
    {
        abort_unless(Gate::allows('role_create'), 403);

        $role = Role::create($request->all());
        $role->permissions()->sync($request->input('permissions', []));
        Cache::forget('auth_gates_permissions');

        return redirect()->route('admin.roles.index');
    }

    public function edit(Role $role)
    {
        abort_unless(Gate::allows('role_edit'), 403);

        $permissions = Permission::pluck('title', 'id');

        $role->load('permissions');

        return view('admin.roles.edit', compact('permissions', 'role'));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        abort_unless(Gate::allows('role_edit'), 403);

        $role->update($request->all());
        $role->permissions()->sync($request->input('permissions', []));
        Cache::forget('auth_gates_permissions');

        return redirect()->route('admin.roles.index');
    }

    public function show(Role $role)
    {
        abort_unless(Gate::allows('role_show'), 403);

        $role->load('permissions');

        return view('admin.roles.show', compact('role'));
    }

    public function destroy(Role $role)
    {
        abort_unless(Gate::allows('role_delete'), 403);

        $role->delete();
        Cache::forget('auth_gates_permissions');

        return back();
    }

    public function massDestroy(MassDestroyRoleRequest $request)
    {
        abort_unless(Gate::allows('role_delete'), 403);

        Role::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
