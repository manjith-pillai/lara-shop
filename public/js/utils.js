var App = App || {};

App.utils = {
	$$: function(id) {
		return document.getElementById(id);
	},

	$t: function(tg_name) {
		return document.getElementsByTagName(tg_name);
	},

	$c: function(cl_name) {
		return document.getElementsByClassName(cl_name);
	},

	$c1: function(cl_name) {
		return App.utils.$c(cl_name)[0];
	},

	create: function(tag, id, className) {
		var ele = document.createElement(tag.toUpperCase());
		if (id) ele.id = id;
		if (className) ele.className = className;
		return ele;
	},

	appendHtml: function(parent, html) {
		if (parent==='body') {
			parent = document.getElementsByTagName('body')[0];
		}
		parent.insertAdjacentHTML('beforeend', html);
	},

	getParentByClass: function(ele, cl_name) {
		var cur = ele.parentNode;
		while(cur && !App.utils.hasClass(cur, cl_name)){
			cur = cur.parentNode;
		}
		return cur;
	},

	hasClass: function(element, cl_name) {
		if (!element) return;
		if ( (" " + element.className + " ").replace(/[\t\r\n\f]/g, " ").indexOf( " "+cl_name+" " ) > -1 ) {
			return true;
		}
		return false;
	},

	addClass: function(element, cl_name) {
		if (App.utils.hasClass(element, cl_name)) {
			return;
		}
		if (element.className.length >= 1) {
			element.className += " " + cl_name;
		}else{
			element.className = cl_name;
		}
	},

	removeClass: function(element, cl_name) {
		if (element.className.indexOf(cl_name)>=0) {
			element.className = element.className.replace(cl_name, '').replace(/^\s+|\s+$$/g, "");
		}
	},

	show: function(element) {
		if (element){
			element.style.display = 'block';
		}
	},

	hide: function(element) {
		if (element) {
			element.style.display = 'none';
		}
	},

	remove: function(element) {
		if (element) {
			element.parentNode.removeChild(element);
		}
	},

	each: function(elements, callback) {
		for(var i=0; i<elements.length; i++) {
			callback(elements[i], i);
		}
	},

	on: function(element, type, func) {

		if (!element) {
			return;
		}
		if (type.indexOf("on") >= 0) {
			element[type] = func;
		}else{
			element.addEventListener(type, func);
		}
	},

	onload: function(func) {
		document.addEventListener("DOMContentLoaded", func);
	},

	ajax: function(url, options) {
		var request;
		var response;
		var callback;
		var method;
		
		function createCORSRequest(method, url, async) {
			var xhr = new XMLHttpRequest();
			if (typeof async == "undefined") async = true;
			try {
				if (method == 'undefined') {
					method = 'GET';
				}
				xhr.open(method, url, async);
				if (method.toUpperCase() == "POST") {
					xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				}
				if (url.indexOf("http")==-1){
					xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
				}
			} catch (e) {
				App.utils.log(e.message);
			}
			return xhr;
		}

		if (typeof options.callback == 'function') {
			callback = options.callback;
		}
		method = options.method;
		if (method == 'undefined' || method == null || method == '') {
			method = "GET";
		}
		request = createCORSRequest(method, url, options.async);

		function stateChange() {
			if (request.readyState == 4) {
				if (request.status === 200 || request.status === 0) {
					if (typeof options.headersCallback == 'function') {
						options.headersCallback(request);
					}
					callback(request.responseText, options.data);
				}
			}
		};

		request.onreadystatechange = stateChange;
		try {
			if (method == 'GET') {
				request.send(null);
			} else if (method == 'POST') {
				request.send(options.postData);
			}
		} catch (ex) {}
		return request;
	},

	post: function (path, params, method) {
	    method = method || "post";

	    var form = document.createElement("form");
	    form.setAttribute("method", method);
	    form.setAttribute("action", path);

	    for(var key in params) {
	        if(params.hasOwnProperty(key)) {
	            var hiddenField = document.createElement("input");
	            hiddenField.setAttribute("type", "hidden");
	            hiddenField.setAttribute("name", key);
	            hiddenField.setAttribute("value", params[key]);
	            form.appendChild(hiddenField);
	         }
	    }

	    document.body.appendChild(form);
	    form.submit();
	},

	storage: function() {
		// if (localStorage in window) {
			return window.localStorage;
		// }
	}, 

	cookie: function() {
		return {
		  getItem: function (sKey) {
			if (!sKey) { return null; }
			return decodeURIComponent(document.cookie.replace(new RegExp("(?:(?:^|.*;)\\s*" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=\\s*([^;]*).*$)|^.*$"), "$1")) || null;
		  },
		  setItem: function (sKey, sValue, vEnd, sPath, sDomain, bSecure) {
			if (!sKey || /^(?:expires|max\-age|path|domain|secure)$/i.test(sKey)) { return false; }
			var sExpires = "";
			if (vEnd) {
			  switch (vEnd.constructor) {
				case Number:
				  sExpires = vEnd === Infinity ? "; expires=Fri, 31 Dec 9999 23:59:59 GMT" : "; max-age=" + vEnd;
				  break;
				case String:
				  sExpires = "; expires=" + vEnd;
				  break;
				case Date:
				  sExpires = "; expires=" + vEnd.toUTCString();
				  break;
			  }
			}
			document.cookie = encodeURIComponent(sKey) + "=" + encodeURIComponent(sValue) + sExpires + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "") + (bSecure ? "; secure" : "");
			return true;
		  },
		  removeItem: function (sKey, sPath, sDomain) {
			if (!this.hasItem(sKey)) { return false; }
			document.cookie = encodeURIComponent(sKey) + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT" + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "");
			return true;
		  },
		  hasItem: function (sKey) {
			if (!sKey) { return false; }
			return (new RegExp("(?:^|;\\s*)" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=")).test(document.cookie);
		  },
		  keys: function () {
			var aKeys = document.cookie.replace(/((?:^|\s*;)[^\=]+)(?=;|$)|^\s*|\s*(?:\=[^;]*)?(?:\1|$)/g, "").split(/\s*(?:\=[^;]*)?;\s*/);
			for (var nLen = aKeys.length, nIdx = 0; nIdx < nLen; nIdx++) { aKeys[nIdx] = decodeURIComponent(aKeys[nIdx]); }
			return aKeys;
		  }
		};
	},

	stripTags: function(html) {
		var div = document.createElement("div");
		div.innerHTML = html;
		var text = div.textContent || div.innerText || "";
		return text;
	},

	loadJS: function(src, callback) {
		var gm = document.createElement('script');
		gm.type = 'text/javascript';
		gm.src = src;
		if (typeof callback == 'function') {
			if (gm.readyState) { //IE
				gm.onreadystatechange = function () {
					if (gm.readyState == "loaded" || gm.readyState == "complete") {
						gm.onreadystatechange = null;
						callback();
					}
				};
			} else { //Others
				gm.onload = function () {
					callback();
				};
			}
		}

		document.body.appendChild(gm);
	},

	trackEvent: function(category, action, label, value) {
		label = label!=null?label:'';
		value = value!=null?value:0;
		try{
			ga('send', 'event', category, action, label, value);
		}catch(e) {
			// console.log(e);
		}

		try{
			// wizrocket.event.push(category, { "action": action, "label":label, "value":value});
		}catch(e) {
			// console.log(e);
		}
	},

	trackWZProfile: function(data) {
		try{
			wizrocket.profile.push({"Site": {
				"Name":data.Name,
				"Identity": data.userId,
				"Email": data.emailId,
				"Phone": "+91"+ data.mobile
			}});
		}catch(e) {}
	},

	trackPage: function(page) {
		try {
			ga('send', 'pageview', page);
		}catch(e){

		}
	},

	urlParams: function () {
		var match,
			pl     = /\+/g,  // Regex for replacing addition symbol with a space
			search = /([^&=]+)=?([^&]*)/g,
			decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); },
			query  = window.location.search.substring(1);

		var urlParams = {};
		while (match = search.exec(query))
		   urlParams[decode(match[1])] = decode(match[2]);
		return urlParams;
	},

	resetForm: function(formid) {
		App.utils.each($$(formid).querySelectorAll('input'), function(each) {
			var type = each.getAttribute('type');
			if (type != "hidden" && type!="button" && type!=="submit"){
				each.value = "";toggleLabel(each);
			}
		});
	},

	showError: function(message) {
		App.utils.show($$('global_errors'));
		$$('global_errors').querySelector('.err-box').innerHTML = message;
	},

	hideError: function() {
		App.utils.hide($$('global_errors'));
	}



};

$$ = App.utils.$$;
$c = App.utils.$c1;
on = App.utils.on;

App.TimeTracker = function(params) {
	return {
		category:params.category,
		timingValue:params.value,

		start: function() {
			this._start = new Date().getTime();
		},

		end: function() {
			this._end = new Date().getTime()
		},

		_getElaspsedTime: function() {
			return this._end - this._start;
		},

		track: function() {
			this.end();
			var elapsed_time = this._getElaspsedTime();
			if (elapsed_time>0 && elapsed_time<(10*60*1000)) {
				try{
					 ga('send', 'timing', this.category, this.timingValue , elapsed_time);
				}catch(e){

				}
			}
		}
	}
};

// async loader : http://css-tricks.com/snippets/javascript/async-script-loader-with-callback/
App.Loader = function () { 
	return {
		require: function (scripts, callback) {
			this.loadCount      = 0;
			this.totalRequired  = scripts.length;
			this.callback       = callback;

			for (var i = 0; i < scripts.length; i++) {
				this.writeScript(scripts[i]);
			}
		},
		loaded: function (evt) {
			this.loadCount++;

			if (this.loadCount == this.totalRequired && typeof this.callback == 'function') this.callback.call();
		},
		writeScript: function (src) {
			var self = this;
			var s = document.createElement('script');
			s.type = "text/javascript";
			s.async = true;
			s.src = src;
			s.addEventListener('load', function (e) { self.loaded(e); }, false);
			var head = document.getElementsByTagName('head')[0];
			head.appendChild(s);
		}
	}
};

//JS templating
(function() {
	this.xtpl = function(t, n) {
		var r = "",
			i = "",
			s = "";
		try {
			var o =  /{{\s*(.+?)\s*}}/g,
				u = /{{\s*\(\s*(.+?)\s*\)\s*}}/g,
				a = "var p='" + t.replace(/[\r\t\v\n\b]/g, "").split("'").join("\\'").split("{%js").join("';").split("js%}").join("; p+='") + "'; return p;";
			var f = new Function("xtplData", a.replace(u, "'; p+=(xtplData.$1); p+='").replace(o, "'; p+=((xtplData.$1)?xtplData.$1:''); p+='"))
		} catch (i) {
			s = "xtpl: There is an error inside the template string.";
			if (window.console && window.console.log) {
				console.log(s);
				console.log("Template: " + t)
			}
			return ""
		}
		if (!n) {
			return f
		}
		try {
			r = f(n)
		} catch (i) {
			s = "xtpl: Error occured when generating the HTML from the template.";
			if (window.console && window.console.log) {
				console.log(s);
				console.log("Template: " + t)
			}
			return ""
		}
		return r
	}
})();

// wrapper for template
App.utils.getHTML = function(template_id, data) {
	var template = $$(template_id).innerHTML;
	var compiled = xtpl(template);
	return compiled( data );	
}


App.showLoader = function(message) {
	if (!message) {
		message = 'Wait while loading...';
	}
	// App.showOverlay('loading_modal');
	// document.querySelector('#loading_modal .load-text').innerHTML = message;
};

/*
 FastClick: polyfill to remove click delays on browsers with touch UIs.

 @version 1.0.3
 @codingstandard ftlabs-jsv2
 @copyright The Financial Times Limited [All Rights Reserved]
 @license MIT License (see LICENSE.txt)
*/
!function(){"use strict";function t(n,o){function i(t,e){return function(){return t.apply(e,arguments)}}var r;if(o=o||{},this.trackingClick=!1,this.trackingClickStart=0,this.targetElement=null,this.touchStartX=0,this.touchStartY=0,this.lastTouchIdentifier=0,this.touchBoundary=o.touchBoundary||10,this.layer=n,this.tapDelay=o.tapDelay||200,!t.notNeeded(n)){for(var a=["onMouse","onClick","onTouchStart","onTouchMove","onTouchEnd","onTouchCancel"],c=this,s=0,u=a.length;u>s;s++)c[a[s]]=i(c[a[s]],c);e&&(n.addEventListener("mouseover",this.onMouse,!0),n.addEventListener("mousedown",this.onMouse,!0),n.addEventListener("mouseup",this.onMouse,!0)),n.addEventListener("click",this.onClick,!0),n.addEventListener("touchstart",this.onTouchStart,!1),n.addEventListener("touchmove",this.onTouchMove,!1),n.addEventListener("touchend",this.onTouchEnd,!1),n.addEventListener("touchcancel",this.onTouchCancel,!1),Event.prototype.stopImmediatePropagation||(n.removeEventListener=function(t,e,o){var i=Node.prototype.removeEventListener;"click"===t?i.call(n,t,e.hijacked||e,o):i.call(n,t,e,o)},n.addEventListener=function(t,e,o){var i=Node.prototype.addEventListener;"click"===t?i.call(n,t,e.hijacked||(e.hijacked=function(t){t.propagationStopped||e(t)}),o):i.call(n,t,e,o)}),"function"==typeof n.onclick&&(r=n.onclick,n.addEventListener("click",function(t){r(t)},!1),n.onclick=null)}}var e=navigator.userAgent.indexOf("Android")>0,n=/iP(ad|hone|od)/.test(navigator.userAgent),o=n&&/OS 4_\d(_\d)?/.test(navigator.userAgent),i=n&&/OS ([6-9]|\d{2})_\d/.test(navigator.userAgent),r=navigator.userAgent.indexOf("BB10")>0;t.prototype.needsClick=function(t){switch(t.nodeName.toLowerCase()){case"button":case"select":case"textarea":if(t.disabled)return!0;break;case"input":if(n&&"file"===t.type||t.disabled)return!0;break;case"label":case"video":return!0}return/\bneedsclick\b/.test(t.className)},t.prototype.needsFocus=function(t){switch(t.nodeName.toLowerCase()){case"textarea":return!0;case"select":return!e;case"input":switch(t.type){case"button":case"checkbox":case"file":case"image":case"radio":case"submit":return!1}return!t.disabled&&!t.readOnly;default:return/\bneedsfocus\b/.test(t.className)}},t.prototype.sendClick=function(t,e){var n,o;document.activeElement&&document.activeElement!==t&&document.activeElement.blur(),o=e.changedTouches[0],n=document.createEvent("MouseEvents"),n.initMouseEvent(this.determineEventType(t),!0,!0,window,1,o.screenX,o.screenY,o.clientX,o.clientY,!1,!1,!1,!1,0,null),n.forwardedTouchEvent=!0,t.dispatchEvent(n)},t.prototype.determineEventType=function(t){return e&&"select"===t.tagName.toLowerCase()?"mousedown":"click"},t.prototype.focus=function(t){var e;n&&t.setSelectionRange&&0!==t.type.indexOf("date")&&"time"!==t.type&&"month"!==t.type?(e=t.value.length,t.setSelectionRange(e,e)):t.focus()},t.prototype.updateScrollParent=function(t){var e,n;if(e=t.fastClickScrollParent,!e||!e.contains(t)){n=t;do{if(n.scrollHeight>n.offsetHeight){e=n,t.fastClickScrollParent=n;break}n=n.parentElement}while(n)}e&&(e.fastClickLastScrollTop=e.scrollTop)},t.prototype.getTargetElementFromEventTarget=function(t){return t.nodeType===Node.TEXT_NODE?t.parentNode:t},t.prototype.onTouchStart=function(t){var e,i,r;if(t.targetTouches.length>1)return!0;if(e=this.getTargetElementFromEventTarget(t.target),i=t.targetTouches[0],n){if(r=window.getSelection(),r.rangeCount&&!r.isCollapsed)return!0;if(!o){if(i.identifier&&i.identifier===this.lastTouchIdentifier)return t.preventDefault(),!1;this.lastTouchIdentifier=i.identifier,this.updateScrollParent(e)}}return this.trackingClick=!0,this.trackingClickStart=t.timeStamp,this.targetElement=e,this.touchStartX=i.pageX,this.touchStartY=i.pageY,t.timeStamp-this.lastClickTime<this.tapDelay&&t.preventDefault(),!0},t.prototype.touchHasMoved=function(t){var e=t.changedTouches[0],n=this.touchBoundary;return Math.abs(e.pageX-this.touchStartX)>n||Math.abs(e.pageY-this.touchStartY)>n?!0:!1},t.prototype.onTouchMove=function(t){return this.trackingClick?((this.targetElement!==this.getTargetElementFromEventTarget(t.target)||this.touchHasMoved(t))&&(this.trackingClick=!1,this.targetElement=null),!0):!0},t.prototype.findControl=function(t){return void 0!==t.control?t.control:t.htmlFor?document.getElementById(t.htmlFor):t.querySelector("button, input:not([type=hidden]), keygen, meter, output, progress, select, textarea")},t.prototype.onTouchEnd=function(t){var r,a,c,s,u,l=this.targetElement;if(!this.trackingClick)return!0;if(t.timeStamp-this.lastClickTime<this.tapDelay)return this.cancelNextClick=!0,!0;if(this.cancelNextClick=!1,this.lastClickTime=t.timeStamp,a=this.trackingClickStart,this.trackingClick=!1,this.trackingClickStart=0,i&&(u=t.changedTouches[0],l=document.elementFromPoint(u.pageX-window.pageXOffset,u.pageY-window.pageYOffset)||l,l.fastClickScrollParent=this.targetElement.fastClickScrollParent),c=l.tagName.toLowerCase(),"label"===c){if(r=this.findControl(l)){if(this.focus(l),e)return!1;l=r}}else if(this.needsFocus(l))return t.timeStamp-a>100||n&&window.top!==window&&"input"===c?(this.targetElement=null,!1):(this.focus(l),this.sendClick(l,t),n&&"select"===c||(this.targetElement=null,t.preventDefault()),!1);return n&&!o&&(s=l.fastClickScrollParent,s&&s.fastClickLastScrollTop!==s.scrollTop)?!0:(this.needsClick(l)||(t.preventDefault(),this.sendClick(l,t)),!1)},t.prototype.onTouchCancel=function(){this.trackingClick=!1,this.targetElement=null},t.prototype.onMouse=function(t){return this.targetElement?t.forwardedTouchEvent?!0:t.cancelable&&(!this.needsClick(this.targetElement)||this.cancelNextClick)?(t.stopImmediatePropagation?t.stopImmediatePropagation():t.propagationStopped=!0,t.stopPropagation(),t.preventDefault(),!1):!0:!0},t.prototype.onClick=function(t){var e;return this.trackingClick?(this.targetElement=null,this.trackingClick=!1,!0):"submit"===t.target.type&&0===t.detail?!0:(e=this.onMouse(t),e||(this.targetElement=null),e)},t.prototype.destroy=function(){var t=this.layer;e&&(t.removeEventListener("mouseover",this.onMouse,!0),t.removeEventListener("mousedown",this.onMouse,!0),t.removeEventListener("mouseup",this.onMouse,!0)),t.removeEventListener("click",this.onClick,!0),t.removeEventListener("touchstart",this.onTouchStart,!1),t.removeEventListener("touchmove",this.onTouchMove,!1),t.removeEventListener("touchend",this.onTouchEnd,!1),t.removeEventListener("touchcancel",this.onTouchCancel,!1)},t.notNeeded=function(t){var n,o,i;if("undefined"==typeof window.ontouchstart)return!0;if(o=+(/Chrome\/([0-9]+)/.exec(navigator.userAgent)||[,0])[1]){if(!e)return!0;if(n=document.querySelector("meta[name=viewport]")){if(-1!==n.content.indexOf("user-scalable=no"))return!0;if(o>31&&document.documentElement.scrollWidth<=window.outerWidth)return!0}}if(r&&(i=navigator.userAgent.match(/Version\/([0-9]*)\.([0-9]*)/),i[1]>=10&&i[2]>=3&&(n=document.querySelector("meta[name=viewport]")))){if(-1!==n.content.indexOf("user-scalable=no"))return!0;if(document.documentElement.scrollWidth<=window.outerWidth)return!0}return"none"===t.style.msTouchAction?!0:!1},t.attach=function(e,n){return new t(e,n)},"function"==typeof define&&"object"==typeof define.amd&&define.amd?define(function(){return t}):"undefined"!=typeof module&&module.exports?(module.exports=t.attach,module.exports.FastClick=t):window.FastClick=t}();


window.addEventListener('load', function() {
	//FastClick.attach(document.body);
}, false);

function editStateInput(e){
		if(e.target.tagName=='INPUT' && e.target.readOnly!=true)
        	e.target.style.borderBottom='1px solid #00becc';
	}
function blurStateInput(e){
    if(e.target.tagName=='INPUT')
	    e.target.style.borderBottom='1px solid #c4c4c4';
}
// function toggleLabelEvent(e) {
// 	// console.log(e);
// 	var input = e.target;
// 	toggleLabel(input);
// };

// function toggleLabel(ele) {
// 	if (!ele.parentNode) return; 
// 	var span = ele.parentNode.querySelector('SPAN');
// 	if (span) {
// 		setTimeout(function() {
// 			if (!ele.value) {
// 				span.style.visibility =  '';
// 			} else {
// 				span.style.visibility =  'hidden';
// 			}
// 		}, 0);
// 	}
// }

// on(document, 'oncut', toggleLabelEvent);
// on(document, 'onkeydown', toggleLabelEvent);
// on(document, 'onpaste', toggleLabelEvent);
// on(document, 'onchange', toggleLabelEvent);
//on(document, 'onblur',);

 on(document, 'focusin', editStateInput);
 on(document, 'focusout', blurStateInput);
// 	var ele = e.target;
// 	if(ele.tagName == 'INPUT' || ele.tagName == "TEXTAREA") {
// 		var span = ele.parentNode.querySelector('SPAN');
// 		if (span) {
// 			// span.style.color = '#b3b3b3';
// 		}
// 	}
// });

// on(document, 'focusout', function(e) {
// 	var ele = e.target;
// 	if(ele.tagName == 'INPUT' || ele.tagName == "TEXTAREA") {
// 		var span = ele.parentNode.querySelector('SPAN');
// 		if (span) {
// 			// span.style.color = '#b3b3b3';
// 		}
// 	}
// });

function init() {
	// App.utils.each(document.querySelectorAll('input, textarea'), function(item) {
	// 	if (item.getAttribute('type')!=="hidden" && item.getAttribute('type')!=="button" && item.getAttribute('type')!=="submit" ){
	// 		// console.log(item.getAttribute('type'));
	// 		toggleLabel(item);
	// 	}
	// });

	// App.utils.each(App.utils.$c('js-routes'), function(item){
	// 	on(item, 'click', function(e) {
	// 		e.preventDefault();
	// 		App.Windows.hideLeftMenu();
	// 		App.History.add(item.getAttribute('href'));
	// 	});
	// });
}

// Set things up as soon as the DOM is ready.
App.utils.onload(init);
