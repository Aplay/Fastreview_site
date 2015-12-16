$(document).ready(function() {

if (! $('html').hasClass('ie8')) {



      $('#Article_description').summernote({
          height: 200,
         // tabsize: 2,
        /*  toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['insert', ['link', 'picture']],
          ],*/
          toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
                         //   ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['table', ['table']],
                            ['insert', ['link', 'picture', 'video']],
                            ['view', ['fullscreen', 'codeview']],
                            ['help', ['help']]
                          ],
          codemirror: { theme: 'monokai' },
          defaultFontName: 'Open Sans',
        //  disableResizeEditor: true,
        //  focus:true,
          
          
                       
 });
}



});