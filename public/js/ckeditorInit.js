// Initialisation CKEditor Balloon
BalloonEditor
    .create(document.querySelector('#editor'))
    .then(editor => {
        document.querySelector("#creationArticle form").addEventListener(
            "submit",
            function(e) {
                e.preventDefault();
                this.querySelector("#editor + input").value = editor.getData();
                this.submit();
            }
        );
    });
