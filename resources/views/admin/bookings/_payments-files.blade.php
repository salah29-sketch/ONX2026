{{--
    ═══════════════════════════════════════════════════════
    PARTIAL: admin/bookings/_payments-files.blade.php
    أضف @include('admin.bookings._payments-files') في show.blade.php
    ═══════════════════════════════════════════════════════
--}}

{{-- ───── PAYMENTS SECTION ─────────────────────────────── --}}
<div class="db-card mb-4" x-data="{ openPayments: true, openFiles: true }">
    <div class="db-card-header db-card-header-toggle flex justify-between items-center cursor-pointer" @click="openPayments = !openPayments" role="button" :aria-expanded="openPayments">
        <span><i class="bi bi-cash-coin me-2"></i> المدفوعات</span>
        <span class="flex items-center gap-2">
            @if($booking->total_price)
                <span class="badge" style="background:rgba(245,166,35,.18);color:#f5a623;font-size:13px;padding:6px 14px;border-radius:999px;">
                    {{ $booking->paymentPercent() }}% مسدّد
                </span>
            @endif
            <i class="fas fa-chevron-down db-collapse-icon"></i>
        </span>
    </div>
    <div x-show="openPayments" x-collapse class="db-card-body">

        {{-- Set total price --}}
        <form action="{{ route('admin.bookings.payments.update-total', $booking->id) }}" method="POST" class="mb-4">
            @csrf @method('PATCH')
            <div class="grid grid-cols-12 gap-4 g-2 items-end">
                <div class="col-span-12 md:col-span-4">
                    <label class="db-label">السعر الإجمالي المتفق عليه (DA)</label>
                    <input type="number" name="total_price" class="db-input"
                           value="{{ $booking->total_price }}" min="0" step="100"
                           placeholder="مثال: 35000">
                </div>
                <div class="col-span-12 md:col-span-2">
                    <button type="submit" class="db-btn-primary w-full">
                        <i class="bi bi-check2"></i> حفظ
                    </button>
                </div>
            </div>
        </form>

        {{-- Payment summary --}}
        @if($booking->total_price)
        <div class="grid grid-cols-12 gap-4 g-3 mb-4">
            <div class="col-4">
                <div class="p-3 rounded-3 text-center" style="background:#f8f9fb;border:1px solid #e5e7eb;">
                    <div style="font-size:11px;color:#94a3b8;font-weight:700;margin-bottom:4px;">الإجمالي</div>
                    <div style="font-size:20px;font-weight:900;">{{ number_format($booking->total_price, 0) }} <small>DA</small></div>
                </div>
            </div>
            <div class="col-4">
                <div class="p-3 rounded-3 text-center" style="background:#f0fdf4;border:1px solid #bbf7d0;">
                    <div style="font-size:11px;color:#16a34a;font-weight:700;margin-bottom:4px;">مدفوع</div>
                    <div style="font-size:20px;font-weight:900;color:#16a34a;">{{ number_format($booking->paidAmount(), 0) }} <small>DA</small></div>
                </div>
            </div>
            <div class="col-4">
                <div class="p-3 rounded-3 text-center" style="background:{{ $booking->remainingAmount() > 0 ? '#fff1f2' : '#f0fdf4' }};border:1px solid {{ $booking->remainingAmount() > 0 ? '#fecdd3' : '#bbf7d0' }};">
                    <div style="font-size:11px;font-weight:700;margin-bottom:4px;color:{{ $booking->remainingAmount() > 0 ? '#dc2626' : '#16a34a' }};">متبقي</div>
                    <div style="font-size:20px;font-weight:900;color:{{ $booking->remainingAmount() > 0 ? '#dc2626' : '#16a34a' }};">
                        {{ number_format($booking->remainingAmount(), 0) }} <small>DA</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Progress bar --}}
        <div class="mb-4">
            <div class="flex justify-between mb-1" style="font-size:12px;font-weight:700;">
                <span>تقدم السداد</span>
                <span>{{ $booking->paymentPercent() }}%</span>
            </div>
            <div class="progress" style="height:10px;border-radius:999px;background:#e5e7eb;">
                <div class="progress-bar"
                     style="width:{{ $booking->paymentPercent() }}%;background:linear-gradient(90deg,#f5a623,#fbbf24);border-radius:999px;"></div>
            </div>
        </div>
        @endif

        {{-- Payments list --}}
        @if($booking->payments->isNotEmpty())
        <table class="table db-table table-sm mb-4">
            <thead>
                <tr>
                    <th>النوع</th>
                    <th>الطريقة</th>
                    <th>المرجع</th>
                    <th>التاريخ</th>
                    <th>المبلغ</th>
                    <th>إجراء</th>
                </tr>
            </thead>
            <tbody>
                @foreach($booking->payments as $pay)
                <tr>
                    <td>
                        <span class="badge" style="background:rgba(245,166,35,.15);color:#b45309;font-size:11px;">
                            {{ $pay->typeLabel() }}
                        </span>
                    </td>
                    <td>{{ $pay->methodLabel() }}</td>
                    <td>{{ $pay->reference ?: '—' }}</td>
                    <td>{{ $pay->paid_at->format('d/m/Y') }}</td>
                    <td><strong style="color:#16a34a;">+ {{ number_format($pay->amount, 0) }} DA</strong></td>
                    <td>
                        <form action="{{ route('admin.bookings.payments.destroy', $pay->id) }}" method="POST"
                              onsubmit="return confirm('حذف هذه الدفعة؟')">
                            @csrf @method('DELETE')
                            <button class="text-sm db-icon-btn db-delete-btn" type="submit">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="text-muted small mb-4">لا توجد دفعات مسجلة بعد.</p>
        @endif

        {{-- Add payment form --}}
        <div style="background:#f8f9fb;border:1px solid #e5e7eb;border-radius:16px;padding:18px;">
            <p class="db-label mb-3"><i class="bi bi-plus-circle me-1"></i> تسجيل دفعة جديدة</p>
            <form action="{{ route('admin.bookings.payments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                <div class="grid grid-cols-12 gap-4 g-2">
                    <div class="col-span-12 md:col-span-3">
                        <label class="db-label">المبلغ (DA)</label>
                        <input type="number" name="amount" class="db-input" min="1" step="1" required>
                    </div>
                    <div class="col-span-12 md:col-span-2">
                        <label class="db-label">النوع</label>
                        <select name="type" class="db-input">
                            <option value="deposit">دفعة أولى</option>
                            <option value="partial" selected>جزئية</option>
                            <option value="final">نهائية</option>
                            <option value="full">كاملة</option>
                        </select>
                    </div>
                    <div class="col-span-12 md:col-span-2">
                        <label class="db-label">الطريقة</label>
                        <select name="method" class="db-input">
                            <option value="cash">نقدًا</option>
                            <option value="bank_transfer">تحويل بنكي</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>
                    <div class="col-span-12 md:col-span-2">
                        <label class="db-label">رقم المرجع</label>
                        <input type="text" name="reference" class="db-input" placeholder="اختياري">
                    </div>
                    <div class="col-span-12 md:col-span-2">
                        <label class="db-label">تاريخ الدفع</label>
                        <input type="date" name="paid_at" class="db-input"
                               value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    <div class="col-span-12 md:col-span-1 flex items-end">
                        <button type="submit" class="db-btn-success w-full">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-4 mt-2">
                    <div class="col-span-12 md:col-span-8">
                        <input type="text" name="notes" class="db-input"
                               placeholder="ملاحظة (اختياري)">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ───── FILES SECTION ─────────────────────────────────── --}}
<div class="db-card mb-4">
    <div class="db-card-header db-card-header-toggle flex justify-between items-center cursor-pointer" @click="openFiles = !openFiles" role="button" :aria-expanded="openFiles">
        <span><i class="bi bi-folder2-open me-2"></i> ملفات المشروع</span>
        <span class="flex items-center gap-2">
            <span class="db-badge db-badge-secondary">{{ $booking->files->count() }} ملف</span>
            <i class="fas fa-chevron-down db-collapse-icon"></i>
        </span>
    </div>
    <div x-show="openFiles" x-collapse class="db-card-body">

        {{-- رابط الفيديو النهائي (يظهر للعميل) ─── --}}
        <div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:16px;padding:18px;margin-bottom:20px;">
            <p class="db-label mb-2"><i class="bi bi-link-45deg me-1"></i> رابط الفيديو النهائي (يظهر للعميل)</p>
            <form action="{{ route('admin.bookings.finalVideo', $booking) }}" method="POST" class="grid grid-cols-12 gap-4 g-2 items-end">
                @csrf
                @method('PATCH')
                <div class="col-span-12 md:col-span-8">
                    <input type="text" name="final_video_path" value="{{ $booking->final_video_path }}"
                           placeholder="رابط فيديو أو مسار الملف" class="db-input">
                </div>
                <div class="col-span-12 md:col-span-2">
                    <button type="submit" class="db-btn-primary w-full">
                        <i class="bi bi-check2"></i> حفظ
                    </button>
                </div>
            </form>
        </div>

        @if($booking->files->isNotEmpty())
        <table class="table db-table table-sm mb-4">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>النوع</th>
                    <th>الحجم</th>
                    <th>مرئي للعميل</th>
                    <th>تاريخ الرفع</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($booking->files as $file)
                <tr>
                    <td>
                        <i class="bi {{ $file->typeIcon() }}" style="color:{{ $file->typeColor() }};"></i>
                        {{ $file->label }}
                    </td>
                    <td>{{ strtoupper($file->type) }}</td>
                    <td>{{ $file->humanSize() ?: '—' }}</td>
                    <td>
                        <form action="{{ route('admin.bookings.files.toggle', $file->id) }}" method="POST" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-sm"
                                    style="border-radius:999px;font-size:11px;font-weight:700;
                                    background:{{ $file->is_visible ? 'rgba(34,197,94,.15)' : 'rgba(148,163,184,.15)' }};
                                    color:{{ $file->is_visible ? '#16a34a' : '#94a3b8' }};border:none;">
                                {{ $file->is_visible ? '✅ مرئي' : '🔒 مخفي' }}
                            </button>
                        </form>
                    </td>
                    <td>{{ $file->created_at->format('d/m/Y') }}</td>
                    <td>
                        <div class="db-actions">
                            <a href="{{ asset($file->path) }}" target="_blank"
                               class="db-icon-btn db-view-btn" title="فتح الملف">
                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                            <form action="{{ route('admin.bookings.files.destroy', $file->id) }}" method="POST"
                                  onsubmit="return confirm('حذف هذا الملف؟')">
                                @csrf @method('DELETE')
                                <button class="db-icon-btn db-delete-btn" type="submit">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="text-muted small mb-4">لا توجد ملفات مرفوعة بعد.</p>
        @endif

        {{-- Upload file form --}}
        <div style="background:#f8f9fb;border:1px solid #e5e7eb;border-radius:16px;padding:18px;">
            <p class="db-label mb-3"><i class="bi bi-upload me-1"></i> رفع ملف جديد</p>
            <form action="{{ route('admin.bookings.files.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                <div class="grid grid-cols-12 gap-4 g-2">
                    <div class="col-span-12 md:col-span-4">
                        <label class="db-label">اسم الملف (يظهر للعميل)</label>
                        <input type="text" name="label" class="db-input"
                               placeholder="مثال: الفيديو النهائي" required>
                    </div>
                    <div class="col-span-12 md:col-span-2">
                        <label class="db-label">النوع</label>
                        <select name="type" class="db-input">
                            <option value="video">فيديو</option>
                            <option value="zip">ZIP صور</option>
                            <option value="pdf">PDF</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>
                    <div class="col-span-12 md:col-span-4">
                        <label class="db-label">الملف (حد: 500 MB)</label>
                        <input type="file" name="file" class="db-input" required>
                    </div>
                    <div class="col-span-12 md:col-span-1">
                        <label class="db-label">مرئي؟</label>
                        <div class="flex items-center gap-2 mt-2">
                            <input type="checkbox" name="is_visible" class="rounded border-gray-300" value="1" checked>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-1 flex items-end">
                        <button type="submit" class="db-btn-success w-full">
                            <i class="bi bi-upload"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
