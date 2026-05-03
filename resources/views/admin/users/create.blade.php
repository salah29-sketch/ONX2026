@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">{{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}</h1>
        <div class="db-page-subtitle">{{ trans('cruds.user.title_singular') }}</div>
    </div>
    <a href="{{ route('admin.users.index') }}" class="db-btn-secondary">
        <i class="fas fa-arrow-right"></i>
        {{ trans('global.back_to_list') }}
    </a>
</div>

<div class="db-card">
    <div class="db-card-header">
        <i class="fas fa-plus me-2"></i>
        {{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}
    </div>

    <div class="db-card-body">
        <form action="{{ route("admin.users.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="name" class="db-label">{{ trans('cruds.user.fields.name') }}*</label>
                <input type="text" id="name" name="name" class="db-input" value="{{ old('name', isset($user) ? $user->name : '') }}" required>
                @if($errors->has('name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.name_helper') }}
                </p>
            </div>
            <div class="mb-4">
                <label for="email" class="db-label">{{ trans('cruds.user.fields.email') }}*</label>
                <input type="email" id="email" name="email" class="db-input" value="{{ old('email', isset($user) ? $user->email : '') }}" required>
                @if($errors->has('email'))
                    <em class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.email_helper') }}
                </p>
            </div>
            <div class="mb-4">
                <label for="password" class="db-label">{{ trans('cruds.user.fields.password') }}</label>
                <input type="password" id="password" name="password" class="db-input" required>
                @if($errors->has('password'))
                    <em class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.password_helper') }}
                </p>
            </div>
            <div class="mb-4">
                <label for="roles" class="db-label">{{ trans('cruds.user.fields.roles') }}*
                    <span class="db-btn-primary  select-all">{{ trans('global.select_all') }}</span>
                    <span class="db-btn-primary  deselect-all">{{ trans('global.deselect_all') }}</span></label>
                <select name="roles[]" id="roles" class=" select2 db-input" multiple="multiple" required>
                    @foreach($roles as $id => $roles)
                        <option value="{{ $id }}" {{ (in_array($id, old('roles', [])) || isset($user) && $user->roles->contains($id)) ? 'selected' : '' }}>{{ $roles }}</option>
                    @endforeach
                </select>
                @if($errors->has('roles'))
                    <em class="invalid-feedback">
                        {{ $errors->first('roles') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.roles_helper') }}
                </p>
            </div>
            <div class="db-form-actions db-form-actions-lg">
                <button type="submit" class="db-btn-success">
                    <i class="fas fa-save"></i>
                    {{ trans('global.save') }}
                </button>
                <a href="{{ route('admin.users.index') }}" class="db-btn-secondary">
                    <i class="fas fa-times"></i>
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
@endsection