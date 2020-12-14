Dropzone.autoDiscover = false;

var dropzone = new Dropzone("#dropzone", {
    url: 'http://taskforce.local/work/add',
    addRemoveLinks: true,
    autoDiscover: false,
    acceptedFiles: 'image/*',
    dictRemoveFileConfirmation: "Вы уверенны?",
    maxFilesize: 1,
    paramName: "file",
    maxFiles: 6,
    dictDefaultMessage: 'Добавить файлы',
    dictRemoveFile: 'Удалить',
    previewTemplate: `<div class="dz-preview dz-file-preview">
     <img  class="dz-image__size"data-dz-thumbnail  alt=""/>
 </div>
       <button data-dz-remove class="btn btn-danger delete">
        <i class="glyphicon glyphicon-trash"></i>
        <span>Delete</span>
      </button>
`,
    accept: function (file, done) {
        if (done) {
            done()
        } else {
            file.previewElement.remove();
        }
    },
    error: function (file, message, xhr) {
        console.log(message);
        file.previewElement.remove(file);
    },
    removedfile: function (file) {
        $.ajax({
            url: 'http://taskforce.local/work/remove?filename=' + file.name,
            type: 'GET',
            header: {
                "_csrf-frontend": $(":input[name='_csrf-frontend']", $("form")).val()
            },
            success: function (data) {
            },
            error: function (xhr, status, error) {
            }
        });
        file.previewElement.remove();
    },
    sending: function (file, xhr, formData) {
        if (this.files.length >= 5) {

        return false ;
        }
        formData.append("_csrf-frontend", $(":input[name='_csrf-frontend']", $("form")).val());
    },
    init: function () {
        const thisDropzone = this;
        $.ajax({
            url: 'http://taskforce.local/work/list',
            type: 'GET',
            success: function (data) {
                $.each(JSON.parse(data), function (key, value) {
                    let mockFile = {name: value.name, size: value.size};
                    thisDropzone.options.thumbnail.call(thisDropzone, mockFile, "http://taskforce.local/uploads/works/" + value.id + '/' + value.name);
                    thisDropzone.emit("addedfile", mockFile);
                    thisDropzone.emit("thumbnail", mockFile, "http://taskforce.local/uploads/works/" + value.id + '/' + value.name);
                    thisDropzone.emit("complete", mockFile);
                })
            },
            error: function (xhr, status, error) {
                file.previewElement.remove();
            }
        });
    }
});