@extends('layouts.admin')
@section('content')

<div class="db-card">
    <div class="db-card-header">
        {{ trans('global.show') }} {{ trans('cruds.permission.title') }}
    </div>

    <div class="db-card-body">
        <div class="mb-2">
            <table class="db-table">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.permission.fields.id') }}
                        </th>
                        <td>
                            {{ $permission->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.permission.fields.title') }}
                        </th>
                        <td>
                            {{ $permission->title }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <a style="margin-top:20px;" class="db-btn-secondary" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>

    </div>
</div>
@endsection