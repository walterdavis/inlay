document.observe('dom:loaded', function(){
  $('PageDiv').setStyle('padding-top: 40px;');
  $(document.body).insert('<div class="title-eyebrow"><a id="back-to-show" href="' + window.location.href.split('?').first() + '">⇦</a><div class="editable" id="page-title" data-source="title" data-format="string">' + document.title + '</div></div>');
  $('page-title').setStyle('width:' + $('PageDiv').getStyle('width') + '; margin: auto; font:' + $('PageDiv').getStyle('font'))
  $$('.editable').invoke('observe', 'click', function(evt){
    var elm = this, editor, txt;
    if(elm.down('.editor')) return;
    txt = elm.innerHTML.toString().trim();
    if(elm.readAttribute('data-format') == 'markdown'){
      editor = new Element('textarea', {'class':'editor'});
    }else{
      editor = new Element('input', {type: 'text', 'class':'editor'});
    }
    editor.writeAttribute('name', elm.readAttribute('data-source'));
    var data_key = (editor.name == 'title') ? $$('head title').first().readAttribute('data-key') : elm.readAttribute('data-key');
    new Ajax.Request($root_folder + '/get_raw.php', {
      parameters: {
        key: data_key
      },
      onSuccess: function(xhr){
        editor.setValue(xhr.responseText);
        editor.focus();
      }
    });
    editor.observe('blur', function(){
      var data_key = (editor.name == 'title') ? $$('head title').first().readAttribute('data-key') : elm.readAttribute('data-key');
      new Ajax.Updater(elm, $root_folder + '/set_raw.php', {
        parameters: {
          key: data_key,
          source: elm.readAttribute('data-source'),
          val: editor.getValue(),
          format: elm.readAttribute('data-format')
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