document.observe('dom:loaded', function(){
  var page_path = function(){
    return window.location.href.toString().split(window.location.hostname).last().split('?').first();
  };
  var outer_block = $$('body > *').first();
  var edit_bar = new Element('div', {className: 'title-edit-bar'});
  edit_bar.update('<a id="inlay-icon" href="' + window.location.href.split('?').first() + '" title="Back to page">i</a>');
  if(undefined != $$('head title').first().readAttribute('data-inlay-source')){
    edit_bar.insert('<div id="title-button"><span class="inlay-button">title</span><div id="page-title" class="meta-tag" style="display:none" data-inlay-source="title" data-inlay-format="string">' + document.title + '</div></div>');
  }
  var metas = $$('meta[data-inlay-source]');
  if(undefined != metas.select(function(m){ return m.readAttribute('name').toLowerCase().match('keywords');}).first()){
    var meta_keywords = metas.select(function(m){ return m.readAttribute('name').toLowerCase().match('keywords');}).first();
    edit_bar.insert('<div id="key-button"><span class="inlay-button">keywords</span><div id="page-keywords" class="meta-tag" style="display:none" data-inlay-source="inlay_keywords" data-inlay-format="string" data-inlay-key="' + meta_keywords.readAttribute('data-inlay-key') + '">' + meta_keywords.content + '</div></div>');
  }
  if(undefined != metas.select(function(m){ return m.readAttribute('name').toLowerCase().match('description');}).first()){
    var meta_description = metas.select(function(m){ return m.readAttribute('name').toLowerCase().match('description');}).first();
    edit_bar.insert('<div id="desc-button"><span class="inlay-button">description</span><div id="page-description" class="meta-tag" style="display:none" data-inlay-source="inlay_description" data-inlay-format="string" data-inlay-key="' + meta_description.readAttribute('data-inlay-key') + '">' + meta_description.content + '</div></div>');
  }
  $(document.body).insert(edit_bar);
  $('page-title').setStyle('font:' + outer_block.getStyle('font')); 
  $$('#title-button').invoke('observe', 'click', function(evt){
    evt.stop();
    $('page-title').toggle();
    if($('page-title').visible()) $('page-title').click();
  });
  $$('#key-button').invoke('observe', 'click', function(evt){
    evt.stop();
    $('page-keywords').toggle();
    if($('page-keywords').visible()) $('page-keywords').click();
  });
  $$('#desc-button').invoke('observe', 'click', function(evt){
    evt.stop();
    $('page-description').toggle();
    if($('page-description').visible()) $('page-description').click();
  });
  $$('.editable, #page-title, #page-keywords, #page-description').invoke('observe', 'click', function(evt){
    evt.stop();
    var elm = this, editor, txt;
    if(elm.down('.editor')) return;
    txt = elm.innerHTML.toString().trim();
    if(elm.readAttribute('data-inlay-format') == 'markdown' || elm.innerHTML.stripTags().trim().match(/[\n\r]/)){
      editor = new Element('textarea', {'class':'editor'});
      if(elm.readAttribute('data-inlay-format') == 'markdown'){
        editor.addClassName('markdown');
      }
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
        editor.store('initial-value', editor.value);
        editor.focus();
      },
      onComplete: function(xhr){
        if(xhr.status == 404)
          window.location.href = window.location.href.toString();;
      }
    });
    
    document.observe('click', function(evt){
      if(evt.findElement('.wrap, .editor')) return;
      var data_key = (editor.name == 'title') ? $$('head title').first().readAttribute('data-inlay-key') : elm.readAttribute('data-inlay-key');
      if($F(editor) != editor.retrieve('initial-value')){
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
            }
            if(editor.up('.meta-tag')) editor.up('.meta-tag').hide();
            if(!!editor) editor.remove(); 
          },
          onComplete: function(xhr){
            if(xhr.status == 404)
              window.location.href = window.location.href.toString();
          }
        });
      }else{
        if(editor.up('.meta-tag')) editor.up('.meta-tag').hide();
        if(!!editor && !!editor.up('.wrap')) editor.up('.wrap').remove(); 
        if(!!editor) editor.remove(); 
      }
    });
    elm.insert(editor);
    if(editor.hasClassName('markdown')){
      var wrapper = new Element('div');
      wrapper.className = "wrap";
      editor.insert({after: wrapper});
      wrapper.insert(editor.remove());
      new Control.TextArea.ToolBar.Markdown(editor);
    }
  });
  $$('*[data-inlay-collection]').each(function(elm){
    var button = new Element('button', {className: 'add-to-collection'}).update('+');
    elm.insert({after: button})
  })
  document.on('click', '.add-to-collection', function(evt, elm){
    var collection = elm.previous('*[data-inlay-collection]');
    if(collection){
      var template = collection.down('.inlay-template').readAttribute('data-template');
      // send Ajax request to server to create the new element, send back a modified template to insert
      collection.insert({bottom: template});
    }
  });
});