document.observe('dom:loaded', function(){
  var page_path = function(){
    return window.location.href.toString().split(window.location.hostname).last().split('?').first();
  };
  $('PageDiv').setStyle('padding-top: 40px;');
  var eyebrow = new Element('div', {className: 'title-eyebrow'});
  eyebrow.update('<a id="back-to-show" href="' + window.location.href.split('?').first() + '">⇦</a>');
  if(undefined != $$('title').first().readAttribute('data-source')){
    eyebrow.insert('<div class="editable" id="page-title" data-source="title" data-format="string">' + document.title + '</div>');
  }
  $(document.body).insert(eyebrow);
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
    new Ajax.Request($root_folder + '/_inlay/get_raw.php', {
      parameters: {
        key: data_key,
        uri: page_path()
      },
      onSuccess: function(xhr){
        editor.setValue(xhr.responseText);
        editor.focus();
      }
    });
    editor.observe('blur', function(){
      var data_key = (editor.name == 'title') ? $$('head title').first().readAttribute('data-key') : elm.readAttribute('data-key');
      new Ajax.Updater(elm, $root_folder + '/_inlay/set_raw.php', {
        parameters: {
          key: data_key,
          uri: page_path(),
          source: elm.readAttribute('data-source'),
          val: editor.getValue(),
          format: elm.readAttribute('data-format')
        },
        onSuccess: function(xhr){
          if(editor.name == 'title') document.title = xhr.responseText;
          editor.remove(); 
        }
      });
    });
    elm.insert(editor);
  });
});