document.observe('dom:loaded', function(){
  $('PageDiv').setStyle('padding-top: 40px;');
  $(document.body).insert('<div class="title-eyebrow"><a id="back-to-show" href="' + window.location.href.split('?').first() + '">⇦</a><div class="editable" id="page-title" data-source="title" data-format="string">' + document.title + '</div></div>');
  $('page-title').setStyle('width:' + $('PageDiv').getStyle('width') + '; margin: auto; font:' + $('PageDiv').getStyle('font'))
  $$('.editable').invoke('observe', 'dblclick', function(evt){
    var elm = this, editor, txt;
    if(elm.down('.editor')) return;
    txt = elm.innerHTML.toString().trim();
    if(elm.hasClassName('markdown')){
      editor = new Element('textarea', {'class':'editor'});
    }else{
      editor = new Element('input', {type: 'text', 'class':'editor'});
    }
    editor.writeAttribute('name', elm.readAttribute('data-source'));
    new Ajax.Request('get_raw.php', {
      parameters: {
        source: window.location.pathname.sub('/fwCMS/', ''),
        key: elm.readAttribute('data-source')
      },
      onSuccess: function(xhr){
        editor.setValue(xhr.responseText);
        editor.focus();
      }
    });
    editor.observe('blur', function(){
      new Ajax.Updater(elm, 'set_raw.php', {
        parameters: {
          key: editor.name,
          val: editor.getValue(),
          source: window.location.pathname.sub('/fwCMS/', ''),
          markdown: (!!elm.hasClassName('markdown'))
        },
        onSuccess: function(xhr){
          if(editor.name == 'title') document.title = xhr.responseText;
          editor.remove(); 
        }
      })
    });
    elm.insert(editor);
  });
});