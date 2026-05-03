@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">{{ trans('global.edit') }} {{ trans('cruds.role.title_singular') }}</h1>
        <div class="db-page-subtitle">{{ trans('cruds.role.title_singular') }}</div>
    </div>
    <div class="db-page-head-actions">
        <a href="{{ route('admin.roles.show', $role) }}" class="db-btn-secondary">
            <i class="fas fa-eye"></i>
            {{ trans('global.view') }}
        </a>
        <a href="{{ route('admin.roles.index') }}" class="db-btn-secondary">
            <i class="fas fa-arrow-right"></i>
            {{ trans('global.back_to_list') }}
        </a>
    </div>
</div>

<div class="db-card">
    <div class="db-card-header">
        <i class="fas fa-edit me-2"></i>
        {{ trans('global.edit') }} {{ trans('cruds.role.title_singular') }}
    </div>

    <div class="db-card-body">
        <form action="{{ route("admin.roles.update", [$role->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="title" class="db-label">{{ trans('cruds.role.fields.title') }}*</label>
                <input type="text" id="title" name="title" class="db-input" value="{{ old('title', isset($role) ? $role->title : '') }}" required>
                @if($errors->has('title'))
                    <em class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.role.fields.title_helper') }}
                </p>
            </div>
            <div class="mb-4">
                <label for="permissions" class="db-label">{{ trans('cruds.role.fields.permissions') }}*
                    <span class="db-btn-primary  select-all">{{ trans('global.select_all') }}</span>
                    <span class="db-btn-primary  deselect-all">{{ trans('global.deselect_all') }}</span></label>
                <select name="permissions[]" id="permissions" class=" select2 db-input" multiple="multiple" required>
                    @foreach($permissions as $id => $permissions)
                        <option value="{{ $id }}" {{ (in_array($id, old('permissions', [])) || isset($role) && $role->permissions->contains($id)) ? 'selected' : '' }}>{{ $permissions }}</option>
                    @endforeach
                </select>
                @if($errors->has('permissions'))
                    <em class="invalid-feedback">
                        {{ $errors->first('permissions') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.role.fields.permissions_helper') }}
                </p>
            </div>
            <div class="db-form-actions db-form-actions-lg">
                <button type="submit" class="db-btn-success">
                    <i class="fas fa-save"></i>
                    {{ trans('global.save') }}
                </button>
                <a href="{{ route('admin.roles.index') }}" class="db-btn-secondary">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection