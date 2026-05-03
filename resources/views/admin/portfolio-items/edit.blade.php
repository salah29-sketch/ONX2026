@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">تعديل العمل</h1>
        <div class="db-page-subtitle">تحديث بيانات عنصر Portfolio.</div>
    </div>
</div>

<div class="db-card">
    <div class="db-card-header">بيانات العمل</div>

    <div class="db-card-body">
        <form method="POST" action="{{ route('admin.portfolio-items.update', $portfolioItem->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('admin.portfolio-items.partials.form')

            <div class="mt-3">
                <button class="db-btn-primary">تحديث</button>
            </div>
        </form>
    </div>
</div>
@endsection
