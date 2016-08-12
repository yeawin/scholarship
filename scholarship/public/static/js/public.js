/*********************************************************************************/
/******************************************	公用函数   **************************/
/**********************************************************************************/
var PROJECT_BASE_URL = "/scholarship";

function includeHeadScript(url){ 
	"use strict";
	document.write('<script src="'+ url + '" type="text/javascript"></script>'); 
}
function includeHeadLink(url){ 
	"use strict";
	document.write('<link href="'+ url + '" media="screen" rel="stylesheet" type="text/css">'); 
}
if (typeof BootstrapDialog != 'function' || typeof BootstrapDialog != 'object') {
	//includeHeadScript(PROJECT_BASE_URL + '/jquery.plugins/bootstrap-dialog/dist/js/bootstrap-dialog.min.js');
	//includeHeadLink(PROJECT_BASE_URL + '/jquery.plugins/bootstrap-dialog/dist/css/bootstrap-dialog.min.css'); // Tiny nav
}


var PublicClass = function(){
	this.getBaseUrl = function (url) {
		if ("undefined" == typeof url) {
			return PROJECT_BASE_URL;
		} else {
			return PROJECT_BASE_URL + url;
		}
		
	};
	
	this.ShowLoading = function(){
		var loading = '<div class="modal-backdrop fade in" id="loading"><div><img src="' + PROJECT_BASE_URL + 'images/loading_small.gif"></div></div>';		
		$("body").append(loading);	
		$('#loading').css({'z-index':2000});
		var width = $(window).width();
		var height =$(window).height();
		var padding = (height - 25) / 2 + 'px ' + (width - 25) / 2 + 'px';
		$('#loading div').css({'padding':padding});
		return true;
	};
	
	this.HideLoading = function(){
		$("#loading").remove();
		return true;
	};

	this.ShowAjaxError = function(XMLHttpRequest, textStatus, errorThrown){  
		var ErrorInfo = "";
		if(XMLHttpRequest.readyState == 4){
	    	ErrorInfo = "<h4>请联系管理员！  错误代码：<b>" + XMLHttpRequest.status + "</b></h4>";
		}else{
			ErrorInfo = "<h4>未知错误！请联系管理员！</h4>";
		}
		BootstrapDialog.show({
			title: '服务器遇到了意料不到的情况，不能完成您的请求!',
	        message: ErrorInfo,
	        type: BootstrapDialog.TYPE_DANGER,
	        closable: true,
	        closeByBackdrop: false,
	        closeByKeyboard: false,
	        buttons: [ {
	            label: '确定',
	            cssClass: "btn-danger",
	            action: function(dialogRef){
	                dialogRef.close();
	            }
	        },]
	    });
//		swal({
//			title: "服务器遇到了意料不到的情况，不能完成您的请求!",
//			text: ErrorInfo,
//			type: "error",
//			html: true });
		//swal("服务器遇到了意料不到的情况，不能完成您的请求", ErrorInfo, 'error')
		return false;
	};
	
	
	//阻止冒泡函数
	this.stopBubble = function (e){   
	    if(e && e.stopPropagation){
	        e.stopPropagation();    //w3c
	    }else{
	        window.event.cancelBubble=true; //IE
	    }
	};
};
Array.prototype.remove=function(dx)
{
	if (isNaN(dx) || dx > this.length) {return false;}
	for(var i = 0, n = 0;i < this.length; i++)
	{
		if(this[i] != this[dx])
		{
			this[n ++] = this[i];
		}
	}
	this.length -= 1
}