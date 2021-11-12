
jQuery(() => {
  jQuery(function($){
 
    // on upload button click
    $('body').on( 'click', '.goldencat-admin-term-field-image-upload', function(e){
   
      e.preventDefault();
   
      var button = $(this),
      custom_uploader = wp.media({
        title: 'Insert image',
        library : {
          // uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
          type : 'image'
        },
        button: {
          text: 'Use this image' // button label text
        },
        multiple: false
      }).on('select', function() { // it also has "open" and "close" events
        var attachment = custom_uploader.state().get('selection').first().toJSON();
        button.html('<img src="' + attachment.sizes.thumbnail.url + '">').next().next().val(attachment.id).next().show();
      }).open();
   
    });
   
    // on remove button click
    $('body').on('click', '.goldencat-admin-term-field-image-remove', function(e){
   
      e.preventDefault();
   
      var button = $(this);
      button.next().val(''); // emptying the hidden field
      button.hide().prev().html('Upload image');
    });
   
  });
})
