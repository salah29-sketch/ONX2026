@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">{{ trans('cruds.role.title_singular') }}</h1>
        <div class="db-page-subtitle">{{ trans('cruds.role.title_singular') }} {{ trans('global.list') }}</div>
    </div>
    @can('role_create')
        <a class="db-btn-primary" href="{{ route('admin.roles.create') }}">
            <i class="fas fa-plus"></i>
            {{ trans('global.add') }} {{ trans('cruds.role.title_singular') }}
        </a>
    @endcan
</div>

<div class="db-card">
    <div class="db-card-header">
        <i class="fas fa-user-tag me-2"></i>
        {{ trans('cruds.role.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="db-card-body">
        <div class="overflow-x-auto">
            <table class="db-table datatable datatable-Role db-table text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.role.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.role.fields.title') }}
                        </th>
                        <th>
                            {{ trans('cruds.role.fields.permissions') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $key => $role)
                        <tr data-entry-id="{{ $role->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $role->id ?? '' }}
                            </td>
                            <td>
                                {{ $role->title ?? '' }}
                            </td>
                            <td>
                                @foreach($role->permissions as $key => $item)
                                    <span class="db-badge db-badge-info">{{ $item->title }}</span>
                                @endforeach
                            </td>
                            <td>
                                <div class="db-actions">
                                    @can('role_show')
                                        <a class="db-icon-btn db-view-btn" href="{{ route('admin.roles.show', $role->id) }}" title="{{ trans('global.view') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('role_edit')
                                        <a class="db-icon-btn db-edit-btn" href="{{ route('admin.roles.edit', $role->id) }}" title="{{ trans('global.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('role_delete')
                                        <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="inline" onsubmit="return confirm('{{ trans('global.areYouSure') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="db-icon-btn db-delete-btn" title="{{ trans('global.delete') }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('role_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.roles.massDestroy') }}",
    className: 'db-btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  });
  $('.datatable-Role:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    });
})

</script>
@endsection