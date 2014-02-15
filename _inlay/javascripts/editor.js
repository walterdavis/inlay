document.observe('dom:loaded', function(){
  var page_path = function(){
    return window.location.href.toString().split(window.location.hostname).last().split('?').first();
  };
  var outer_block = $$('body > *').first();
  var edit_bar = new Element('div', {className: 'title-edit-bar'});
  edit_bar.update('<a id="inlay-icon" href="' + window.location.href.split('?').first() + '" title="Back to page">i</a>');
  if(undefined != $$('title').first().readAttribute('data-inlay-source')){
    edit_bar.insert('<div id="title-button"><span class="inlay-button">title</span><div id="page-title" style="display:none" data-inlay-source="title" data-inlay-format="string">' + document.title + '</div></div>');
  }
  $(document.body).insert(edit_bar);
  $('page-title').setStyle('font:' + outer_block.getStyle('font')); 
  $$('#title-button').invoke('observe', 'click', function(evt){
    evt.stop();
    $('page-title').toggle();
    if($('page-title').visible()) $('page-title').click();
  });
  $$('.editable, #page-title').invoke('observe', 'click', function(evt){
    evt.stop();
    var elm = this, editor, txt;
    if(elm.down('.editor')) return;
    txt = elm.innerHTML.toString().trim();
    if(elm.readAttribute('data-inlay-format') == 'markdown' || elm.innerHTML.stripTags().trim().match(/[\n\r]/)){
      editor = new Element('textarea', {'class':'editor'});
      if(elm.readAttribute('data-inlay-format') == 'markdown') editor.addClassName('markdown');
    }else{
      editor = new Element('input', {type: 'text', 'class':'editor'});
    }
    editor.writeAttribute('name', elm.readAttribute('data-inlay-source'));
    var data_key = (editor.name == 'title') ? $$('head title').first().readAttribute('data-inlay-key') : elm.readAttribute('data-inlay-key');
    new Ajax.Request($root_folder + '_inlay/get_raw.php', {
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
      var data_key = (editor.name == 'title') ? $$('head title').first().readAttribute('data-inlay-key') : elm.readAttribute('data-inlay-key');
      new Ajax.Updater(elm, $root_folder + '_inlay/set_raw.php', {
        parameters: {
          key: data_key,
          uri: page_path(),
          source: elm.readAttribute('data-inlay-source'),
          val: editor.getValue(),
          format: elm.readAttribute('data-inlay-format')
        },
        onSuccess: function(xhr){
          if(editor.name == 'title'){
            document.title = xhr.responseText;
            $('page-title').hide();
          }
          editor.remove(); 
        }
      });
    });
    elm.insert(editor);
  });
});