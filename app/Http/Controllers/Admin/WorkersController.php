<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Worker\Worker;
use Illuminate\Http\Request;

class WorkersController extends Controller
{
    public function index()
    {
        $workers = Worker::orderBy('name')->paginate(20);
        return view('admin.workers.index', compact('workers'));
    }

    public function create()
    {
        return view('admin.workers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:workers,email',
            'password' => 'required|string|min:6|confirmed',
            'phone'    => 'nullable|string|max:50',
            'is_active'=> 'nullable|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['password'] = bcrypt($data['password']);
        Worker::create($data);
        return redirect()->route('admin.workers.index')->with('success', 'تم إضافة العامل.');
    }

    public function edit(Worker $worker)
    {
        return view('admin.workers.edit', compact('worker'));
    }

    public function update(Request $request, Worker $worker)
    {
        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:workers,email,' . $worker->id,
            'phone'    => 'nullable|string|max:50',
            'is_active'=> 'nullable|boolean',
        ];
        if ($request->filled('password')) {
            $rules['password'] = 'nullable|string|min:6|confirmed';
        }
        $data = $request->validate($rules);
        $data['is_active'] = $request->boolean('is_active', true);
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        } else {
            unset($data['password']);
        }
        $worker->update($data);
        return redirect()->route('admin.workers.index')->with('success', 'تم التحديث.');
    }

    public function destroy(Worker $worker)
    {
        $worker->delete();
        return redirect()->route('admin.workers.index')->with('success', 'تم الحذف.');
    }
}
