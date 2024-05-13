
var quill = new Quill('#product-description', {
    modules: {
        toolbar: [
        [{ header: [1, 2, false] }],
        ['bold', 'italic', 'underline'],
        ['image', 'code-block']
        ]
    },
    placeholder: 'Write product description...',
    theme: 'snow'  // or 'bubble'
});



FilePond.registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginImageExifOrientation,
    FilePondPluginFileValidateSize,
    // FilePondPluginImageEdit
);

var ecommerce = FilePond.create(document.querySelector('.file-upload-multiple'));

var input = document.querySelector('.product-tags');

// initialize Tagify on the above input node reference
new Tagify(input)