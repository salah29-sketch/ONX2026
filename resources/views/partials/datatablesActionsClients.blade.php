{{-- إجراءات العملاء: عرض، تعديل، تعطيل الدخول، إعادة تعيين كلمة السر، حذف --}}
<a class="db-icon-btn db-view-btn me-1 mb-1" href="{{ route('admin.clients.show', $row->id) }}" title="عرض التفاصيل">
    <i class="fas fa-eye"></i>
</a>
<a class="db-icon-btn db-edit-btn me-1 mb-1" href="{{ route('admin.clients.edit', $row->id) }}" title="تعديل">
    <i class="fas fa-edit"></i>
</a>

<form action="{{ route('admin.clients.toggle-login', $row->id) }}" method="POST" class="inline-block me-1 mb-1" onsubmit="return confirm('{{ $row->login_disabled ? 'تفعيل دخول هذا العميل؟' : 'تعطيل دخول هذا العميل؟' }}');">
    @csrf
    @if($row->login_disabled)
        <button type="submit" class="db-btn-success" title="تفعيل الدخول"><i class="fas fa-unlock"></i></button>
    @else
        <button type="submit" class="db-btn-primary" title="تعطيل الدخول"><i class="fas fa-lock"></i></button>
    @endif
</form>

<form action="{{ route('admin.clients.reset-password', $row->id) }}" method="POST" class="inline-block me-1 mb-1" onsubmit="return confirm('سيتم إنشاء كلمة مرور جديدة وعرضها في صفحة التفاصيل مرة واحدة. متابعة؟');">
    @csrf
    <button type="submit" class="db-icon-btn db-edit-btn" title="إعادة تعيين كلمة المرور"><i class="fas fa-key"></i></button>
</form>

<form action="{{ route('admin.clients.destroy', $row->id) }}" method="POST" class="inline-block mb-1" onsubmit="return confirm('{{ trans('global.areYouSure') }}');">
    @csrf
    @method('DELETE')
    <button type="submit" class="db-icon-btn db-delete-btn" title="حذف"><i class="fas fa-trash-alt"></i></button>
</form>
