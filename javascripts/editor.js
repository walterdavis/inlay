document.observe('dom:loaded', function(){
  $$('.editable').invoke('observe', 'dblclick', function(evt){
    var elm = this, editor, txt;
    if(elm.down('.editor')) return;
    elm.setStyle('position:relative');
    txt = elm.innerHTML.toString().trim();
    console.log(txt)
    if(elm.hasClassName('markdown')){
      editor = new Element('textarea', {'class':'editor'});
    }else{
      editor = new Element('input', {type: 'text', 'class':'editor'});
    }
    editor.writeAttribute('name', elm.id);
    new Ajax.Request('get_raw.php', {
      parameters: { key: elm.readAttribute('data-source') },
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
          markdown: (!!elm.hasClassName('markdown'))
        },
        onSuccess: function(){
          editor.remove(); 
        }
      })
    });
    elm.insert(editor);
  });
});