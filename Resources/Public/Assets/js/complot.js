    /**
 * Created by poizcampbell on 17/09/14.
 */
;

function cs_tooltip (main, options){
	    let defaults = {
		    hover_duration:null,
		    hover_alpha:1,
		    easing:"easeInOutSine",
		    style_object: null,
		    tooltip_resource_attribute:"title",
		    use_tooltip:true,
		    add_click_evt:false
		
	    };
	    let config      =   $.extend({}, defaults, options);
	    let title_attr  =   null;
	    let relX        =   null;
	    let relY        =   null;
	    const cTip      = {
		    self :   this,
		    init: function(){
			    this.self = this;
			    main.hover(this.overFX, this.outFX);
			    main.mousemove(this.mouseMoveAction);
			    if(config.add_click_evt){
				    main.click(this.outFX);
			    }
			    this.activateHoverTimeOut();
		    },
		
		    mouseMoveAction:function(e){
			    let lbl_div = $("div.label_div");
			    relX        = e.pageX-(lbl_div.outerWidth()/2);
			    relY = e.pageY+15;
			
			    lbl_div.css({
				    top:relY + "px",
				    left:relX + "px"
			    });
		    },
		
		    overFX: function(e){
			    let dis     = $(this);
			    if( !dis.attr('data-blind')){
				    title_attr  = $(dis).attr(config.tooltip_resource_attribute);
				    let lbl_div = $('div.label_div');
				    relX        = e.pageX-( lbl_div.outerWidth()/2);
				    relY        = e.pageY+15;
				    title_attr  = title_attr.replace("\n", "<br />");
				
				    dis.data('title', title_attr );
				    dis.attr('title', '');
				    lbl_div.remove();
				
				    lbl_div     = $("<div />", {
					    html: dis.data('title'),
					    "class": "label_div"
				    }).appendTo("body");
				
				    if( dis.attr('pix_data') ){
					    $("<img />", {
						    "src": dis.attr('pix_data'),
						    "class": "tooltip_pix"
					    }).css({
						    "float":"left",
						    "clear":"both",
						    width:"50px"
					    }).appendTo("div.label_div");
				    }
				    if(config.style_object != null){
					    lbl_div.css(
						    config.style_object
					    );
				    }else{
					    lbl_div.css({
						    padding:"10px",
						    background:"rgba(10, 18, 200, 0.5)",
						    borderRadius:"5px",
						    textAlign:"center"
					    })
				    }
				    let alpha = (config.hover_alpha) ? config.hover_alpha : 1;
				    lbl_div.css({
					    position:"absolute",
					    top:relY + "px",
					    left:relX + "px",
					    zIndex:999999,
					    opacity: alpha,
					    backgroundPosition: "50% 50%"
				    }).hide();
				    //lbl_div.fadeIn({"duration":250, "easing":"easeInOutSine"});
				    lbl_div.fadeIn({"duration":250});
			    }
			    cTip.self.activateHoverTimeOut();
		    },
		
		    outFX: function(evt){
			    let dis = $(this);
			    let div = $("div.label_div");
			    if( !dis.attr('data-blind')){
				    //div.fadeOut( { "duration":250, "easing":"easeInOutSine","complete":function(e){div.remove();} } );
				    div.fadeOut( { "duration":250, "complete":function(e){div.remove();} } );
			    }
		    },
		
		    activateHoverTimeOut: function(){
			    let self = this;
			    if(config.hover_duration){
				    setTimeout(function () {
					    let event = document.createEvent('Event');
					    event.initEvent('removeToolTip', true, true);
					    self.outFX(event);
				    }, config.hover_duration);
			    }
		    }
		
	    };
	    cTip.init();
    }
    
(function ($) {
    $(document).ready(function (e) {
        let trashCsvFileIcon    = $(".pz-trash-pix");
        let actionButton        = $(".pz-action");
        let toolTipEnabled      = $("[data-tip]");
		const tooltip_config    =  {
		    hover_duration:2000,
			    hover_alpha:1,
			    easing:"swing", // easeInOutSine
			    tooltip_div:"div#tooltip",
			    tooltip_resource_attribute:"data-tip",
			    use_tooltip:true,
			    add_click_evt:true,
			    style_object: {
			    padding:"5px 7px",
				    background:"rgba(0, 0, 0, 0.75)",
				    borderRadius:"7px 0 7px 0",
				    textAlign:"center",
				    letterSpacing:"1px",
				    fontFamily:"Helvetica",
				    color:"#FFF",
				    fontSize:"11px",
				    "box-shadow":"1px -1px 2px rgb(24, 24, 24)"
		    }
	    };
		
	    cs_tooltip(toolTipEnabled, tooltip_config);
        trashCsvFileIcon.on("click", deleteUploaded);
	    actionButton.on("click", processCSVEntryAction);

        function deleteUploaded(event){
            let disObject       = $(this);
            doJQAjaxRequest({'filePath': disObject.attr('data-file-path'), 'uid': disObject.attr('data-file-id') }, "POST", disObject);
        }
        
        function processCSVEntryAction(e) {
            let dataChunks  = $(this).data();
            let fields      = $(dataChunks.rowId).find("input");
            let extraChunks = {};
            
            fields.each(function () {
                const udField           = $(this).attr("data-update-field");
                extraChunks[udField]    = {
	                updateField: udField,
	                updateValue: $(this).val(),
                };
            });
            dataChunks.updatePackets = extraChunks;
	        doJQAjaxRequest(dataChunks, "POST", $(this), "json", true);
            console.log(dataChunks);
        }

        function doJQAjaxRequest(payLoad, sendMethod,  targetObj, contentType, isEntry){
            let processor           = targetObj.attr('data-processor');
            contentType     = (contentType === undefined) ? "x-www-form-urlencoded" : "json";
            let req = $.ajax({
                dataType        : 'JSON',
                type            : sendMethod,
                url             : processor + "?action=" + targetObj.attr('data-action'),
                data            : payLoad,
                'Content-Type'  : 'application/' + contentType  + "; charset=UTF-8",

                success			: function (data, textStatus, jqXHR){
                    if(data){
	                    let dataObj	= JSON.parse(data);
                        if(isEntry === undefined || !isEntry){
	                        targetObj.parent("div.col-md-12").slideUp(350, function(e){
		                        targetObj.parent("div.col-md-12").remove();
	                        });
                        }else{
                           if( /trash/i.test(payLoad.action) ){
                               console.log($(payLoad.rowId));
                               $(payLoad.rowId).fadeOut(750, function(){ $(payLoad.rowId).remove();});
                           }
                        }
                        if(dataObj.status){
                            PoizAlert(dataObj.status, 2000);
                        }
                    }
                },

                error 			: function (jqXHR, textStatus, errorThrown){
                    console.log('The following error occurred: ' + textStatus, errorThrown);
                }

            });
        }
	
	    function PoizAlert(msgText, delay){
		    msgText         = (msgText !== undefined) ? msgText : "Das Bild wurde kopiert!";
		    delay           = (delay !== undefined) ? delay: 2000;
		    let objDim      = getWindowParams();
		    let message     = "<span " +
			    "style='display:block;padding:30px;margin:0 auto;width:350px;max-width:350px;color:#000000;" +
			    "text-align:center;background:rgba(189,189,189,0.9);border-radius:7px;border:double 3px rgba(255,255,255,0.4);" +
			    "font-size:14px;font-weight:500;letter-spacing:0.01em;'>" + msgText +
			    "</span>";
		    let message2     = "<span " +
			    "style='display:block;padding:30px;margin:0 auto;width:350px;max-width:350px;" +
			    "text-align:center;background:rgba(181, 63, 180, 0.95);border-radius:7px;border:double 3px rgba(255,255,255,0.4);" +
			    "font-size:14px;font-weight:300;letter-spacing:0.01em;color:#FFFFFF;'>" + msgText +
			    "</span>";
		
		    //CREATE WRAPPER OVERLAY-DIV
		    let alertBox    = $("<div />", {
			    id: "pz-alert",
			    "class": "pz-alert"
		    }).css( {
			    position    : "fixed",
			    width       : "100%",
			    height      : "80px",
			    background  : "transparent",
			    display     : "none",
			    margin      : 0,
			    padding     : 0,
			    left        : 0,
			    zIndex      : 9999,
			    top         : ((objDim.winHeight - 140)/2) + "px"
		    } ).append($(message));
		    $("body").append(alertBox);
		    alertBox.fadeIn(500, function(){
			    setTimeout(function(){
				    alertBox.fadeOut(1000, function(){alertBox.remove();});
			    }, delay);
		    });
	    }
	
	    function getWindowParams(){
		    return {
			    winWidth    : window.innerWidth,
			    winHeight   : window.innerHeight,
			    docWidth    : $(document).width(),
			    docHeight   : $(document).height(),
		    };
	    }
    });
})(jQuery);

