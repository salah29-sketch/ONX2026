{{-- Portfolio Section — receives: $items, $title, $subtitle, $description, $badge --}}
<section class="mx-auto max-w-7xl px-6 py-20 lg:px-8">
    @include('partials.portfolio-works-grid', [
        'items'              => $items,
        'sectionSubtitle'    => $subtitle,
        'sectionTitle'       => $title,
        'sectionDescription' => $description,
        'badgeText'          => $badge,
    ])
</section>
