@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">{{ trans('global.show') }} {{ trans('cruds.user.title') }}</h1>
        <div class="db-page-subtitle">{{ trans('cruds.user.title_singular') }}</div>
    </div>
    <a href="{{ route('admin.users.index') }}" class="db-btn-secondary">
        <i class="fas fa-arrow-right"></i>
        {{ trans('global.back_to_list') }}
    </a>
</div>

<div class="db-card">
    <div class="db-card-header">
        <i class="fas fa-user me-2"></i>
        {{ trans('global.show') }} {{ trans('cruds.user.title') }}
    </div>

    <div class="db-card-body">
        <div class="overflow-x-auto">
            <table class="db-table db-table">
                <tbody>
                    <tr>
                        <th>{{ trans('cruds.user.fields.id') }}</th>
                        <td>{{ $user->id }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.user.fields.name') }}</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.user.fields.email') }}</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.user.fields.email_verified_at') }}</th>
                        <td>{{ $user->email_verified_at }}</td>
                    </tr>
                    <tr>
                        <th>Roles</th>
                        <td>
                            @foreach($user->roles as $id => $roles)
                                <span class="db-badge db-badge-confirmed">{{ $roles->title }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="db-form-actions mt-3">
            <a href="{{ route('admin.users.index') }}" class="db-btn-secondary">
                <i class="fas fa-arrow-right"></i>
                {{ trans('global.back_to_list') }}
            </a>
        </div>
    </div>
</div>
@endsection
