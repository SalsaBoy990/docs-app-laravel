<script src="{{ asset('/build/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea#update-content-editor',
        plugins: 'link code table lists codesample',
        height: 500,
        browser_spellcheck: true,
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | codesample',
        forced_root_block: false,
    });
</script>
