function pageGeneratorProQuickTagsRegister(e){!function($){for(const i in e)QTags.addButton("page_generator_pro_"+i,e[i].title,(function(){$.post(ajaxurl,{action:"page_generator_pro_output_tinymce_modal",nonce:page_generator_pro_tinymce.nonces.tinymce,editor_type:"quicktags",shortcode:i},(function(t){wpZincQuickTagsModal.open(),$("div.wpzinc-quicktags-modal div.media-modal.wp-core-ui").css({width:e[i].modal.width+"px",height:e[i].modal.height+20+"px"}),$("#wpzinc-quicktags-modal .media-frame-title h1").text(e[i].title),$("#wpzinc-quicktags-modal .media-frame-content").html(t),$("div.wpzinc-quicktags-modal div.media-modal.wp-core-ui div.wpzinc-vertical-tabbed-ui").css({height:e[i].modal.height-50+"px"}),wp_zinc_tabs_init(),page_generator_pro_reinit_selectize(),"undefined"!=typeof wp_zinc_autocomplete_initialize&&wp_zinc_autocomplete_initialize(),page_generator_pro_conditional_fields_initialize(),$("select.wpzinc-conditional, .wpzinc-conditional select").trigger("change"),$('form.wpzinc-tinymce-popup select[name="maptype"]').trigger("change.page-generator-pro")}))}))}(jQuery)}pageGeneratorProQuickTagsRegister(page_generator_pro_quicktags);