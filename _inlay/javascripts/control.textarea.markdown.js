/**
 * @author Ryan Johnson <ryan@livepipe.net>
 * @copyright 2007 LivePipe LLC
 * @package Control.TextArea.ToolBar.Markdown
 * @license MIT
 * @url http://livepipe.net/projects/control_textarea/
 * @version 1.0.1
 */

Control.TextArea.ToolBar.Markdown = Class.create();
Object.extend(Control.TextArea.ToolBar.Markdown.prototype,{
	textarea: false,
	toolbar: false,
	options: {},
	initialize: function(textarea,options){
		this.textarea = new Control.TextArea(textarea);
		this.toolbar = new Control.TextArea.ToolBar(this.textarea);
		this.options = {};

		//buttons
		this.toolbar.addButton('Italics',function(){
			this.wrapSelection('*','*');
		},{
			className: 'markdown_italics_button'
		});
		
		this.toolbar.addButton('Bold',function(){
			this.wrapSelection('**','**');
		},{
			className: 'markdown_bold_button'
		});
		
		this.toolbar.addButton('Link',function(){
			var selection = this.getSelection();
			var response = prompt('Enter Link URL','http://');
			if(response == null)
				return;
			this.replaceSelection('[' + (selection == '' ? 'Link Text' : selection) + '](' + (response == '' ? 'http://link_url/' : response) + ')');
		},{
			className: 'markdown_link_button'
		});
		
    // this.toolbar.addButton('LocalImage',function(evt){
    //  var editor = this;
    //  new Ajax.Updater('overlay','/images/picker', {method:'get', evalScripts:true, onSuccess: function(){
    //    var chooseImage = function(evt){
    //      var elm;
    //      if(elm = evt.findElement('img.chooser')){
    //        evt.stop();
    //        new Ajax.Updater(
    //          elm.up('div'),
    //          '/images/' + elm.readAttribute('data-id') + '/crop', {
    //            method: 'get',
    //            onCreate: function(){
    //              elm.up('div').update('<img src="/images/loading-bar-gray.gif" width="128" height="15" class="loading-bar" alt="" />');
    //            },
    //            evalScripts: true
    //          }
    //        );
    //      }
    //      if(elm = evt.findElement('#insert_image')){
    //        evt.stop();
    //        chosen_tags = $H({});
    //        editor.replaceSelection('![' + (selection == '' ? 'Image Alt Text' : selection) + '](' + $F('crop_url') + ')');
    //        $('overlay').update().hide();
    //        $('overlay').stopObserving('click', chooseImage);
    //      }
    //    };
    //    var selection = editor.getSelection();
    //    
    //    $('overlay').show().observe('click', chooseImage);
    //  }});
    // },{
    //  className: 'markdown_image_button local'
    // });
		
			this.toolbar.addButton('Image',function(){
				var selection = this.getSelection();
				var response = prompt('Enter Image URL','');
				if(!!!response)
					return;
				this.replaceSelection('![' + (selection == '' ? 'Image Alt Text' : selection) + '](' + (response == '' ? 'http://image_url/' : response) + ')'); //.replace(/^(?!(f|ht)tps?:\/\/)/,'http://')
			},{
				className: 'markdown_image_button'
			});
		
			this.toolbar.addButton('Heading',function(){
				var selection = this.getSelection();
				if(selection == '')
					selection = 'Heading';
				this.replaceSelection("###" + selection + "\n");
			},{
				className: 'markdown_heading_button'
			});
		
			this.toolbar.addButton('Unordered List',function(event){
				this.injectEachSelectedLine(function(lines,line){
					lines.push((event.shiftKey ? (line.match(/^\*{2,}/) ? line.replace(/^\*/,'') : line.replace(/^\*\s/,'')) : (line.match(/\*+\s/) ? '*' : '* ') + line));
					return lines;
				});
			},{
				className: 'markdown_unordered_list_button'
			});
		
			this.toolbar.addButton('Ordered List',function(event){
				var i = 0;
				this.injectEachSelectedLine(function(lines,line){
					if(line.match(/^.+/)){
						++i;
						lines.push((event.shiftKey ? line.replace(/^\d+\.\s/,'') : (line.match(/\d+\.\s/) ? '' : i + '. ') + line));
					}
					return lines;
				});
			},{
				className: 'markdown_ordered_list_button'
			});
		
			this.toolbar.addButton('Block Quote',function(event){
				this.injectEachSelectedLine(function(lines,line){
					lines.push((event.shiftKey ? line.replace(/^\> /,'') : '> ' + line));
					return lines;
				});
			},{
				className: 'markdown_quote_button'
			});
		
			this.toolbar.addButton('Code Block',function(event){
				this.injectEachSelectedLine(function(lines,line){
					lines.push((event.shiftKey ? line.replace(/    /,'') : '    ' + line));
					return lines;
				});
			},{
				className: 'markdown_code_button'
			});
		
		this.toolbar.addButton('Help',function(){
			window.open('http://daringfireball.net/projects/markdown/dingus');
		},{
			className: 'markdown_help_button'
		});
	}
});