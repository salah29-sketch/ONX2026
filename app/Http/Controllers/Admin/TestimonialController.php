<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $query = Testimonial::orderByRaw("CASE status WHEN 'pending' THEN 0 WHEN 'approved' THEN 1 ELSE 2 END")
            ->orderBy('id');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $testimonials = $query->paginate(20)->withQueryString();
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'client_role' => 'nullable|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'content'     => 'required|string|max:2000',
            'rating'      => 'nullable|integer|min:1|max:5',
            'initial'     => 'nullable|string|max:10',
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        Testimonial::create([
            'client_name' => $request->client_name,
            'client_role' => $request->client_role,
            'subtitle'    => $request->subtitle,
            'content'     => $request->content,
            'rating'      => $request->rating ?? 5,
            'initial'     => $request->initial ?: mb_substr($request->client_name, 0, 1),
            'sort_order'  => $request->sort_order ?? 0,
            'is_active'   => $request->boolean('is_active', true),
            'status'      => Testimonial::STATUS_APPROVED,
        ]);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('message', 'تم إضافة الرأي بنجاح.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'client_role' => 'nullable|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'content'     => 'required|string|max:2000',
            'rating'      => 'nullable|integer|min:1|max:5',
            'initial'     => 'nullable|string|max:10',
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        $testimonial->update([
            'client_name' => $request->client_name,
            'client_role' => $request->client_role,
            'subtitle'    => $request->subtitle,
            'content'     => $request->content,
            'rating'      => $request->rating ?? 5,
            'initial'     => $request->initial ?: mb_substr($request->client_name, 0, 1),
            'sort_order'  => $request->sort_order ?? 0,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('message', 'تم تحديث الرأي بنجاح.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()
            ->route('admin.testimonials.index')
            ->with('message', 'تم حذف الرأي.');
    }

    public function approve(Testimonial $testimonial)
    {
        $testimonial->update(['status' => Testimonial::STATUS_APPROVED, 'is_active' => true]);
        return redirect()->route('admin.testimonials.index')->with('message', 'تمت المصادقة على الرأي.');
    }

    public function reject(Testimonial $testimonial)
    {
        $testimonial->update(['status' => Testimonial::STATUS_REJECTED, 'is_active' => false]);
        return redirect()->route('admin.testimonials.index')->with('message', 'تم رفض الرأي.');
    }
}
