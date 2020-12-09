Dropzone.autoDiscover = false;

var dropzone = new Dropzone("span.dropzone", {
    url: 'http://taskforce.local/task/create',
    autoProcessQueue: false,
    autoDiscover: false,
    acceptedFiles: 'image/*',
    paramName: "file",
    uploadMultiple: true,
    maxFiles: 6,
    dictDefaultMessage:'Добавить файлы',
    previewTemplate: '<a href="#"><img data-dz-thumbnail alt="Фото работ"></a>',
    accept: function(file, done) {
        console.log("uploaded");
        done();
    },
    error: function(file, msg){
        alert(msg);
    },
    sending: function(file, xhr, formData) {
        formData.append("_csrf-frontend", $("#csrf").val());
    },
});