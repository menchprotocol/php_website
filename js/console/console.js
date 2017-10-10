//Loadup Algolia:
client = algoliasearch('49OCX1ZXLJ', 'ca3cf5f541daee514976bc49f8399716');
algolia_index = client.initIndex('bootcamps');


//To update fancy dropdown which is usually used for STATUS updates:
function update_dropdown(name,intvalue,count){
	//Update hidden field with value:
	$('#'+name).val(intvalue);
	//Update dropdown UI:
	$('#ui_'+name).html( $('#'+name+'_'+count).html() + '<b class="caret"></b>' );
	//Reload tooldip:
	$('[data-toggle="tooltip"]').addClass('').tooltip();
}

//Quill Text editor variables:
var setting_full = {
	modules: {
		syntax: true,
		toolbar: [
		  [{ 'header': [1, 2, false] }],
		  ['bold', 'italic', 'underline' ],
		  ['link', 'blockquote', 'code-block'],
		  [{ 'list': 'ordered'}, { 'list': 'bullet' }],
		]
	},
	theme: 'snow'
};
var setting_listo = {
	modules: {
		syntax: false,
		toolbar: [
			[{ 'list': 'ordered'}],
		]
	},
	placeholder: 'List items here...',
	theme: 'snow'
};
var setting_listu = {
	modules: {
		syntax: false,
		toolbar: [
			[{ 'list': 'bullet'}],
			['bold', 'italic' ],
		]
	},
	placeholder: 'List items here...',
	theme: 'snow'
};