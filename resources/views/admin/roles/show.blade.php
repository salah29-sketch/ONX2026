@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">{{ trans('global.show') }} {{ trans('cruds.role.title') }}</h1>
        <div class="db-page-subtitle">{{ trans('cruds.role.title_singular') }}</div>
    </div>
    <a href="{{ route('admin.roles.index') }}" class="db-btn-secondary">
        <i class="fas fa-arrow-right"></i>
        {{ trans('global.back_to_list') }}
    </a>
</div>

<div class="db-card">
    <div class="db-card-header">
        <i class="fas fa-user-tag me-2"></i>
        {{ trans('global.show') }} {{ trans('cruds.role.title') }}
    </div>

    <div class="db-card-body">
        <div class="overflow-x-auto">
            <table class="db-table db-table">
                <tbody>
                    <tr>
                        <th>{{ trans('cruds.role.fields.id') }}</th>
                        <td>{{ $role->id }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.role.fields.title') }}</th>
                        <td>{{ $role->title }}</td>
                    </tr>
                    <tr>
                        <th>Permissions</th>
                        <td>
                            @foreach($role->permissions as $id => $permissions)
                                <span class="db-badge db-badge-confirmed">{{ $permissions->title }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="db-form-actions mt-3">
            <a href="{{ route('admin.roles.index') }}" class="db-btn-secondary">
                <i class="fas fa-arrow-right"></i>
                {{ trans('global.back_to_list') }}
            </a>
        </div>
    </div>
</div>
@endsection
