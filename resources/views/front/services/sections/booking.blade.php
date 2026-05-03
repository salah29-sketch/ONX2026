{{-- Booking redirect — replaced old JS modal with link to unified /book page --}}
<script>
function openSbModal(opts) {
    const params = new URLSearchParams();
    params.set('type', 'event');
    if (typeof opts === 'object' && opts.serviceId) {
        params.set('service', opts.serviceId);
    } else if (typeof window.sbServiceId !== 'undefined') {
        params.set('service', window.sbServiceId);
    }
    window.location.href = '{{ route("book") }}?' + params.toString();
}
@isset($serviceId)
window.sbServiceId = {{ $serviceId }};
@endisset
</script>
