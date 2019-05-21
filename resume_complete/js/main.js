const $ = document.querySelector.bind(document);

$('#resume').addEventListener('change', function() {
    $('.custom-file-label').textContent = this.files.item(0).name;
});