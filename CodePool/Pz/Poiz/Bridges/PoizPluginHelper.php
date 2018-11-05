<?php

namespace CodePool\Pz\Poiz\Bridges;

class PoizPluginHelper {

	public static function dropZoneConfig(){
		$dzJS               = JS_URI  . "dropzoneX.js";
		$dzCSS              = CSS_URI . "dropzone.css";

		$dzConfigScript     =<<<PHP_E
	<link type="text/css" rel="stylesheet" href="{$dzCSS}" media="all" />
	<script type="text/javascript" href="{$dzJS}"></script>
PHP_E;

		return $dzConfigScript;
	}

	public static function dropZoneConfigSupplemental($id, $allowedFiles=['.jpg', '.jpeg', '.gif', '.png', '.otf', '.ttf', '.bmp']){
		$strAllowed         = implode(",", $allowedFiles);
		$oKIcon             = ICONS_URI . "ok_icon.png";
		$notOKIcon          = ICONS_URI . "not_ok_icon.png";
		$preLoaderIcon      = ICONS_URI . "preloader_transparent.gif";
		$processor          = AJAX_URI;
		$dzConfigScript     =<<<PHP_E
<script type="text/javascript">

(function ($) {
    $(document).ready(function (e) {
        var pixBox      = $("#{$id}");
        var uploadURL   = pixBox.attr("data-url");	
        var pzData 		= {};

        pzData.errorIcon            = "{$notOKIcon}";
        pzData.allOKIcon            = "{$oKIcon}";
        pzData.preLoaderIcon        = "{$preLoaderIcon}";
        pzData.processor            = "{$processor}";
        
        var dZone = new Dropzone("div#{$id}", {
            url				: uploadURL,
            params          : {
		         'csrfToken'    : $("input[name=csrfToken]").val(),
		         'locationID'   : $("#locationId").val(),
		         'employerID'   : $("#employerId").val(),
		         'typeID'       : $("#typeId").val(),
		         'path'         : $("#photoPath").val(),
		         'intent'       : "file-upload",
		    },
            paramName		: "pz_item_photo",
            maxFilesize		: 1,        // 1MB
            dataType		: "JSON",
            uploadMultiple	: false,
			acceptedFiles   : "{$strAllowed}",
            success: function (ditto, data) {                     
	            var dataObj	= JSON.parse(data);
                if(dataObj){
                    var infoListBlock   = $("#pz-info-list");
                    var statusUpdate    = '<li class="list-group-item col-md-12" style="letter-spacing:0.035em;font-size:11px;word-wrap: break-word;font-weight:300;position:relative;">';
                    statusUpdate       += '<span class="fa fa-trash pz-trash-uploaded" style="padding:0 5px;color:#b53fb4;position:absolute;display:block;font-size: 16px;top:5px;right:5px;cursor:pointer;z-index:9999;" data-file-path="' + dataObj.fileLocation + '" data-action="deleteUploadedFileByPath"></span>';
                    statusUpdate       += '<div class="col-lg-2" style="padding:0 5px;font-weight:700;color:#007c9a;">';
                    statusUpdate       += '<img class="img-responsive thumbnail" src="' + dataObj.icon + '" alt="Icon" style="margin-bottom: 0;" />';
                    statusUpdate       += '</div>';
                    statusUpdate       += '<div class="col-lg-10" style="font-weight:300;color:#b53fb4;padding-top:5px;">';
                    statusUpdate       += '<strong style="">' + dataObj.fileRealName + '</strong><br /><strong style="color:#212121;">' + dataObj.fileSize + '</strong>  <strong style="color:#AB2121;">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: rgb(3, 139, 170);text-decoration: overline;"><i class="fa fa-tag"></i>' + dataObj.fileType.toUpperCase() + '</span></strong>';
                    statusUpdate       += '<br /><strong style="color:#613fb5;">Machine Name: </strong><em style="color:#4d4d4d;">' + dataObj.fileName + '</em>';
                    statusUpdate       += '</div>';
                    statusUpdate       += '</li>';

                    infoListBlock.prepend($(statusUpdate));

                    infoListBlock.find(".pz-trash-uploaded").on("click", deleteUploaded);
                    pixBox.find("#uploadTrackBg").css("width", "0");
                    pixBox.find("#uploadTrack").text(null);
                        
                    if(dataObj.serialized){               
		                var szStr	= dataObj.serialized;					                
		                pixBox.attr("data-value", szStr);
		                pixBox.find("input[type=hidden]").val(szStr );
                    }
                }
            }
        }).
        on("dragover", function(e){
            pixBox.css({
                "background": "rgba(16, 22, 99, 1)",    
                "border": "dashed 2px white"   
            })
        }).
        on("drop", function(e){
            pixBox.css({
                "background": "rgba(181, 63, 180, 0.08)",  
                "border": "solid 1px rgba(181, 63, 180, 0.26)"
            })
        }).
        on("dragleave", function(e){
           pixBox.css({
                "background": "rgba(181, 63, 180, 0.08)",
                "border": "solid 1px rgba(181, 63, 180, 0.26)"
            })
        }).
        on("uploadprogress", function(first, progress, bytesSent){
           pixBox.find("#uploadTrackBg").css("width", progress.toFixed(5) + "%");
            pixBox.find("#uploadTrack").text( progress.toFixed(2) + "%");
        }).
        on("error", function(a, b, c){
            $("#uploadTrackBg").css("width", "0");
            $("#uploadTrack").text(null);
            var infoListBlock   = $("#pz-info-list");
            var statusUpdate    = '<li class="list-group-item col-md-12" style="letter-spacing:0.035em;font-size:11px;word-wrap: break-word;font-weight:300;position:relative;">';
            statusUpdate       += '<div class="col-md-2" style="padding:0 5px;font-weight:700;color:#007c9a;">';
            statusUpdate       += '<img class="img-responsive thumbnail" src="' + pzData.errorIcon + '" alt="Icon" style="margin-bottom: 0;" />';
            statusUpdate       += '</div>';
            statusUpdate       += '<div class="col-md-10" style="font-weight:300;color:#b53fb4;padding-top:5px;">';
            statusUpdate       += '<strong style="">Error:</strong> <em>Cannot upload File — </em><strong style="color:#613fb5;">' + a.name + "</strong>";
            statusUpdate       += '<br /><strong style="color:#613fb5;"><em style="color:#4d4d4d;">' + b + '</em></strong>';
            statusUpdate       += '</div>';
            statusUpdate       += '</li>';

            infoListBlock.prepend($(statusUpdate));
        }).
        on("maxfilesexceeded", function(first){
            console.log("Max File-Size Exceeded!");
            alert("Max File-Size Exceeded!");
        });
        
        var infoListBlock 	= $("#pz-info-list");
		infoListBlock.find(".pz-trash-uploaded").on("click", deleteUploaded);
                    
        function deleteUploaded(event){
            var disObject           = $(this);
            doJQAjaxRequest({'filePath': disObject.attr('data-file-path')}, "POST", disObject);
        }
        
        function doJQAjaxRequest(payLoad, sendMethod,  targetObj, contentType){
            contentType     = (contentType === undefined) ? "x-www-form-urlencoded" : "json";
            var req = $.ajax({
                dataType        : 'JSON',
                type            : sendMethod,
                url             : pzData.processor + "?action=" + targetObj.attr('data-action'),
                data            : payLoad,
                'Content-Type'  : 'application/' + contentType  + "; charset=UTF-8",
                
                success			: function (data, textStatus, jqXHR){
                    if(data){
                        var dataObj	= JSON.parse(data);
	                    targetObj.parent("li.list-group-item").slideUp(350, function(e){
	                        targetObj.parent("li.list-group-item").remove();
	                    });
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
        
	    function PoizAlert(msgText, iTimeOut){
	        msgText                     = (msgText !== undefined)   ? msgText   : "All is well that ends well!";
	        iTimeOut                    = (iTimeOut !== undefined)  ? iTimeOut  : 2000;
	        var message                 = "<span " +
	            "style='display:block;padding:30px;margin:0 auto;width:350px;max-width:350px;color:#000;" +
	            "text-align:center;background:rgba(181, 63, 180, 0.95);border-radius:7px;border:double 3px rgba(255,255,255,0.4);" +
	            "font-size:16px;font-weight:300;letter-spacing:0.01em;color:#FFFFFF;'>" + msgText +
	            "</span>";
	
	        //CREATE MAIN WRAPPER DIV
	        var alertBox    = $("<div />", {
	            id          : "pz-alert",
	            "class"     : "pz-alert"
	        }).css( {
	            position    : "fixed",
	            width       : "100%",
	            height      : "80px",
	            background  : "transparent",
	            display     : "none",
	            margin      : 0,
	            padding     : 0,
	            left        : 0,
	            zIndex      : 2147483646,
	            top         : 0      
	        } ).append($(message));
	
	        $(".pz-form-wrapper").parentsUntil("body").parent("body").append(alertBox);
	        alertBox.fadeIn(500, function(e){
	            setTimeout(function(){
	                alertBox.fadeOut(500, function(e){alertBox.remove();});
	            }, iTimeOut);
	        });
	    }
        
    });
})(jQuery);
	</script>
PHP_E;

		return $dzConfigScript;
	}

	public static function getCurrentPageURL($justBase=false) {
		$pageURL = 'http';

		if ((isset($_SERVER["HTTPS"])) && ($_SERVER["HTTPS"] == "on"))
			$pageURL .= "s";

		$pageURL .= "://";

		if ($_SERVER["SERVER_PORT"] != "80")
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];

		else
			$pageURL .= $_SERVER["SERVER_NAME"];

		if(!$justBase){
			$pageURL .= $_SERVER["REQUEST_URI"];
		}else{
			if($_SERVER["SERVER_NAME"] == "127.0.0.1" || $_SERVER["SERVER_NAME"] == "localhost" ){
				$requestURI             = $_SERVER["REQUEST_URI"];
				$arrServerNameExtract   = preg_split("#\/#", preg_replace("#^\/#", "", $requestURI));
				$serverNameExtract      = array_shift($arrServerNameExtract);
				$pageURL               .= "/" . $serverNameExtract;
			}
		}

		if ( function_exists('apply_filters') ) apply_filters('wppb_curpageurl', $pageURL);
		return $pageURL;
	}

	public static function getActivePageURL($justBase=false) {
		return self::getCurrentPageURL($justBase);
	}
}