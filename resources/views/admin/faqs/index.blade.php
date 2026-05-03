@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">الأسئلة الشائعة (FAQ)</h1>
        <div class="db-page-subtitle">إدارة الأسئلة المعروضة في صفحة FAQ بالموقع.</div>
    </div>

    <a href="{{ route('admin.faqs.create') }}" class="db-btn-primary">
        <i class="fas fa-plus"></i>
        إضافة سؤال
    </a>
</div>

@if(session('message'))
    <div class="alert-success">{{ session('message') }}</div>
@endif

<div class="db-card">
    <div class="db-card-header">قائمة الأسئلة</div>

    <div class="db-card-body">
        <div class="overflow-x-auto">
            <table class="db-table db-table text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>السؤال</th>
                        <th>الترتيب</th>
                        <th>نشط</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faqs as $faq)
                        <tr>
                            <td>{{ $faq->id }}</td>
                            <td class="text-right" style="max-width:320px;">{{ Str::limit($faq->question, 60) }}</td>
                            <td>{{ $faq->sort_order }}</td>
                            <td>{{ $faq->is_active ? 'نعم' : 'لا' }}</td>
                            <td>
                                <div class="db-actions">
                                    <a href="{{ route('admin.faqs.edit', $faq) }}" class="db-icon-btn db-edit-btn" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="db-icon-btn db-delete-btn" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="db-empty">
                                    <i class="fas fa-question-circle"></i>
                                    لا توجد أسئلة. أضف سؤالاً من الزر أعلاه.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $faqs->links() }}</div>
    </div>
</div>
@endsection
