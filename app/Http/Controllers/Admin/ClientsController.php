<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyClientRequest;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ClientsController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(Gate::allows('client_access'), 403);

        if ($request->ajax()) {
            $query = Client::query()
                ->select(sprintf('%s.*', (new Client)->getTable()))
                ->orderBy('id', 'desc');
            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->editColumn('id', fn($row) => $row->id ?? '');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                return view('partials.datatablesActionsClients', ['row' => $row])->render();
            });

            $table->editColumn('id', fn($row) => $row->id ?? '');
            $table->editColumn('name', fn($row) => $row->name ?? '');
            $table->editColumn('phone', fn($row) => $row->phone ?? '');
            $table->editColumn('email', fn($row) => $row->email ?? '');
            $table->addColumn('has_password', function ($row) {
                return $row->hasPassword() ? '<span class="badge badge-success">معرّفة</span>' : '<span class="badge badge-secondary">غير معرّفة</span>';
            });
            $table->addColumn('login_disabled', function ($row) {
                return $row->login_disabled ? '<span class="badge badge-danger">معطّل</span>' : '<span class="badge badge-success">مفعّل</span>';
            });

            $table->rawColumns(['actions', 'placeholder', 'has_password', 'login_disabled']);

            return $table->make(true);
        }

        return view('admin.clients.index');
    }

    public function create()
    {
        abort_unless(Gate::allows('client_create'), 403);

        return view('admin.clients.create');
    }

    public function store(StoreClientRequest $request)
    {
        abort_unless(Gate::allows('client_create'), 403);

        Client::create($request->validated());

        return redirect()
            ->route('admin.clients.index')
            ->with('message', 'تم إنشاء العميل بنجاح.');
    }

    

    public function edit(Client $client)
    {
        abort_unless(Gate::allows('client_edit'), 403);

        return view('admin.clients.edit', compact('client'));
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        abort_unless(Gate::allows('client_edit'), 403);

        $client->update($request->validated());

        return redirect()
            ->route('admin.clients.index')
            ->with('message', 'تم تحديث بيانات العميل بنجاح.');
    }

    public function show(Client $client)
    {
        abort_unless(Gate::allows('client_show'), 403);

        $client->load([
            'bookings.service',
            'bookings.package',
            'bookings.eventBooking.venue',
        ]);

        return view('admin.clients.show', compact('client'));
    }

    public function destroy(Client $client)
    {
        abort_unless(Gate::allows('client_delete'), 403);

        $client->delete();
        return back()->with('message', 'تم حذف العميل بنجاح.');
    }

    /** تعطيل أو تفعيل دخول العميل */
    public function toggleLogin(Client $client)
    {
        abort_unless(Gate::allows('client_edit'), 403);

        $client->update(['login_disabled' => !$client->login_disabled]);
        $label = $client->login_disabled ? 'تم تعطيل دخول العميل.' : 'تم تفعيل دخول العميل.';
        return back()->with('message', $label);
    }

    /** إعادة تعيين كلمة المرور (يُعرض مرة واحدة فقط) */
    public function resetPassword(Client $client)
    {
        abort_unless(Gate::allows('client_edit'), 403);

        $newPassword = Str::random(10);
        $client->password = $newPassword;
        $client->save();
        return redirect()
            ->route('admin.clients.show', $client)
            ->with('message', 'تم تعيين كلمة مرور جديدة.')
            ->with('new_password_once', $newPassword)
            ->with('client_login_identifier', $client->email ?: $client->phone);
    }

    public function massDestroy(MassDestroyClientRequest $request)
    {
        abort_unless(Gate::allows('client_delete'), 403);

        Client::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
