@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">الخدمات</h1>
        <div class="db-page-subtitle">إدارة الخدمات المعروضة في الموقع والحجز.</div>
    </div>
    <a class="db-btn-primary" href="{{ route('admin.services.create') }}">
        <i class="fas fa-plus"></i>
        إضافة خدمة
    </a>
</div>

@if(session('success'))
    <div class="db-alert db-alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="db-alert db-alert-danger">{{ session('error') }}</div>
@endif

<div class="db-card">
    <div class="db-card-body">
        <div class="overflow-x-auto">
            <table class="db-table text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>الاسم</th>
                        <th>التصنيف</th>
                        <th>Slug</th>
                        <th>عروض</th>
                        <th>نشط</th>
                        <th>ترتيب</th>
                        <th width="300"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $s)
                    <tr>
                        <td>{{ $s->id }}</td>
                        <td><strong>{{ $s->name }}</strong></td>
                        <td>{{ $s->category->name ?? '—' }}</td>
                        <td><code>{{ $s->slug }}</code></td>
                        <td>{{ $s->packages()->count() }}</td>
                        <td>
                            @if($s->is_active)
                                <span class="db-badge db-badge-completed">نعم</span>
                            @else
                                <span class="db-badge">لا</span>
                            @endif
                        </td>
                        <td>{{ $s->sort_order }}</td>
                        <td>
                            {{-- عروض --}}
                            <a class="db-btn-primary" 
                               href="{{ route('admin.packages.index', ['service_id' => $s->id]) }}"
                               title="الباقات">
                               <i class="fas fa-boxes"></i>
                            </a>

                            {{-- تعديل --}}
                            <a class="db-icon-btn db-edit-btn"
                               href="{{ route('admin.services.edit', $s) }}"
                               title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>

                            {{-- حذف --}}
                            <form action="{{ route('admin.services.destroy', $s) }}"
                                  method="POST" class="inline"
                                  onsubmit="return confirm('حذف الخدمة؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="db-icon-btn db-delete-btn" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-[var(--tx-muted)] text-center py-4">لا توجد خدمات.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $services->links() }}</div>
    </div>
</div>
@endsection
