{{-- قيم افتراضية مشتركة لجدول DataTables يعمل بـ Ajax (لغة عربية، خيارات موحدة) --}}
<script>
(function () {
    window.dtArabicAjaxDefaults = {
        processing: true,
        serverSide: true,
        retrieve: true,
        searching: true,
        lengthChange: false,
        pageLength: 25,
        info: true,
        paging: true,
        ordering: true,
        aaSorting: [[0, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Arabic.json',
            search: '',
            searchPlaceholder: 'بحث...'
        },
        scrollX: false,
        dom: '<"flex flex-wrap mb-3"<"w-full md:w-1/2"f>>rt<"flex flex-wrap mt-2 items-center"<"w-full md:w-5/12"i><"w-full md:w-7/12"p>>'
    };
})();
</script>
