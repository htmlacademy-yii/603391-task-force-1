Dropzone.autoDiscover = false;

var dropzone = new Dropzone("#dropzone", {
    url: 'http://taskforce.local/task/create',
    autoProcessQueue: false, // this is important as you dont want form to be submitted unless you have clicked the submit button
    autoDiscover: false,
    uploadMultiple: true,
    maxFiles: 6,
    accept: function(file, done) {
        console.log("uploaded");
        done();
    },
    error: function(file, msg){
        alert(msg);
    },
    sending: function(file, xhr, formData) {
        formData.append("_csrf-frontend", $("#csrf").val());
        formData.append("name", $("#name").val());
    },
    init: function() {
        var myDropzone = this;
        document.getElementById("submit-all").addEventListener("click", function(e) {
            // Make sure that the form isn't actually being sent.
            e.preventDefault();
            e.stopPropagation();
            myDropzone.processQueue();
        });
        // after this, your whole form will get submitted with all the inputs + your files and the php code will remain as usual
        //REMEMBER you DON'T have to call ajax or anything by yourself, dropzone will take care of that
    }
});