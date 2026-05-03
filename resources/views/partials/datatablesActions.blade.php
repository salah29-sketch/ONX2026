@can($viewGate)
    <a class="db-icon-btn db-view-btn" href="{{ route('admin.' . $crudRoutePart . '.show', $row->id) }}" title="عرض">
        <i class="fas fa-eye"></i>
    </a>
@endcan

@can($editGate)
    <a class="db-icon-btn db-edit-btn" href="{{ route('admin.' . $crudRoutePart . '.edit', $row->id) }}" title="تعديل">
        <i class="fas fa-edit"></i>
    </a>
@endcan

@can($deleteGate)
    <form action="{{ route('admin.' . $crudRoutePart . '.destroy', $row->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" class="inline-block">
        @csrf
        @method('DELETE')
        <button type="submit" class="db-icon-btn db-delete-btn" title="��ذف">
            <i class="fas fa-trash-alt"></i>
        </button>
    </form>
@endcan

