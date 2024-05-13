/**
 * ===================================
 *    Blog Description Editor 
 * ===================================
*/
var quill = new Quill('#blog-description', {
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


var input = document.querySelector('.blog-tags');

// initialize Tagify on the above input node reference
new Tagify(input)


/**
 * =======================
 *      Blog Category 
 * =======================
*/
var input = document.querySelector('input[name=category]');

new Tagify(input, {
    whitelist: ["Themeforest","Admin","Dashboard","Laravel","Sale","Vue","React","Cork Admin"],
    userInput: false
})