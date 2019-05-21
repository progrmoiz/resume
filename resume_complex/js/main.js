const $ = document.querySelector.bind(document);

const inputElement = $('#resume');
const submitElement = $('#submit');

// inputElement.addEventListener('change', function() {
//     $('.custom-file-label').textContent = this.files.item(0).name;
// });



// Register plugins
FilePond.registerPlugin(
    FilePondPluginFileValidateSize,
    FilePondPluginFileValidateType,
);

FilePond.setOptions({

    allowFileTypeValidation: true,
    acceptedFileTypes: ['application/pdf'],

    allowMultiple: true,

    // maximum allowed file size
    maxFileSize: '5MB',

    // upload to this server end point
    server: 'api/'
});

const pond = FilePond.create( inputElement );

pond.on('processfile', () => submitElement.disabled = false);
pond.on('error', () => submitElement.disabled = true);
pond.on('warning', () => submitElement.disabled = true);
pond.on('updatefiles', (items) => submitElement.disabled = items.length === 0);