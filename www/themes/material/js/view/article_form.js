$(document).ready(function() {

if (! $('html').hasClass('ie8')) {



      $('#Article_description').summernote({
          lang: 'ru-RU', // default: 'en-US'
          height: null,  // set editor height
          minHeight: 200, 
          placeholder: 'Введите ваш текст...',
         // tabsize: 2,
        /*  toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['insert', ['link', 'picture']],
          ],*/
          toolbar: [
                           // ['style', ['style']],
                            ['font', ['bold', 'italic', 'underline',  'clear']],
                         //   ['color', ['color']],
                          //  ['para', ['ul', 'ol', 'paragraph']],
                          //  ['table', ['table']],
                            ['insert', [
                          //  'link', 
                            'picture', 
                          //  'video'
                          ]],
                          //  ['view', ['fullscreen', 'codeview']],
                          //  ['help', ['help']]
                          ],
          codemirror: { theme: 'monokai' },
          defaultFontName: 'Open Sans',
        //  disableResizeEditor: true,
        //  focus:true,
        callbacks : {
            onPaste : function (e)  {
              var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');

              e.preventDefault();

              setTimeout( function(){
                  document.execCommand( 'insertText', false, bufferText );
              }, 10 );
            }
        }
       
 });

}
});

function CleanPastedHTML(input) {
  // 1. remove line breaks / Mso classes
  var stringStripper = /(\n|\r| class=(")?Mso[a-zA-Z]+(")?)/g;
  var output = input.replace(stringStripper, ' ');
  // 2. strip Word generated HTML comments
  var commentSripper = new RegExp('<!--(.*?)-->','g');
  var output = output.replace(commentSripper, '');
  var tagStripper = new RegExp('<(/)*(meta|link|span|\\?xml:|st1:|o:|font)(.*?)>','gi');
  // 3. remove tags leave content if any
  output = output.replace(tagStripper, '');
  // 4. Remove everything in between and including tags '<style(.)style(.)>'
  var badTags = ['style', 'script','applet','embed','noframes','noscript'];

  for (var i=0; i< badTags.length; i++) {
    tagStripper = new RegExp('<'+badTags[i]+'.*?'+badTags[i]+'(.*?)>', 'gi');
    output = output.replace(tagStripper, '');
  }
  // 5. remove attributes ' style="..."'
  var badAttributes = ['style', 'start'];
  for (var i=0; i< badAttributes.length; i++) {
    var attributeStripper = new RegExp(' ' + badAttributes[i] + '="(.*?)"','gi');
    output = output.replace(attributeStripper, '');
  }
  return output;
}