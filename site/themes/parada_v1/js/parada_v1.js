// Device.js
// (c) 2014 Matthew Hudson
// Device.js is freely distributable under the MIT license.
// For all details and documentation:
// http://matthewhudson.me/projects/device.js/

(function() {

  var device,
    previousDevice,
    addClass,
    documentElement,
    find,
    handleOrientation,
    hasClass,
    orientationEvent,
    removeClass,
    userAgent;

  // Save the previous value of the device variable.
  previousDevice = window.device;

  device = {};

  // Add device as a global object.
  window.device = device;

  // The <html> element.
  documentElement = window.document.documentElement;

  // The client user agent string.
  // Lowercase, so we can use the more efficient indexOf(), instead of Regex
  userAgent = window.navigator.userAgent.toLowerCase();

  // Main functions
  // --------------

  device.ios = function () {
    return device.iphone() || device.ipod() || device.ipad();
  };

  device.iphone = function () {
    return !device.windows() && find('iphone');
  };

  device.ipod = function () {
    return find('ipod');
  };

  device.ipad = function () {
    return find('ipad');
  };

  device.android = function () {
    return !device.windows() && find('android');
  };

  device.androidPhone = function () {
    return device.android() && find('mobile');
  };

  device.androidTablet = function () {
    return device.android() && !find('mobile');
  };

  device.blackberry = function () {
    return find('blackberry') || find('bb10') || find('rim');
  };

  device.blackberryPhone = function () {
    return device.blackberry() && !find('tablet');
  };

  device.blackberryTablet = function () {
    return device.blackberry() && find('tablet');
  };

  device.windows = function () {
    return find('windows');
  };

  device.windowsPhone = function () {
    return device.windows() && find('phone');
  };

  device.windowsTablet = function () {
    return device.windows() && (find('touch') && !device.windowsPhone());
  };

  device.fxos = function () {
    return (find('(mobile;') || find('(tablet;')) && find('; rv:');
  };

  device.fxosPhone = function () {
    return device.fxos() && find('mobile');
  };

  device.fxosTablet = function () {
    return device.fxos() && find('tablet');
  };

  device.meego = function () {
    return find('meego');
  };

  device.cordova = function () {
    return window.cordova && location.protocol === 'file:';
  };

  device.nodeWebkit = function () {
    return typeof window.process === 'object';
  };

  device.mobile = function () {
    return device.androidPhone() || device.iphone() || device.ipod() || device.windowsPhone() || device.blackberryPhone() || device.fxosPhone() || device.meego();
  };

  device.tablet = function () {
    return device.ipad() || device.androidTablet() || device.blackberryTablet() || device.windowsTablet() || device.fxosTablet();
  };

  device.desktop = function () {
    return !device.tablet() && !device.mobile();
  };

  device.television = function() {
    var i, tvString;

    television = [
      "googletv",
      "viera",
      "smarttv",
      "internet.tv",
      "netcast",
      "nettv",
      "appletv",
      "boxee",
      "kylo",
      "roku",
      "dlnadoc",
      "roku",
      "pov_tv",
      "hbbtv",
      "ce-html"
    ];

    i = 0;
    while (i < television.length) {
      if (find(television[i])) {
        return true;
      }
      i++;
    }
    return false;
  };

  device.portrait = function () {
    return (window.innerHeight / window.innerWidth) > 1;
  };

  device.landscape = function () {
    return (window.innerHeight / window.innerWidth) < 1;
  };

  // Public Utility Functions
  // ------------------------

  // Run device.js in noConflict mode,
  // returning the device variable to its previous owner.
  device.noConflict = function () {
    window.device = previousDevice;
    return this;
  };

  // Private Utility Functions
  // -------------------------

  // Simple UA string search
  find = function (needle) {
    return userAgent.indexOf(needle) !== -1;
  };

  // Check if documentElement already has a given class.
  hasClass = function (className) {
    var regex;
    regex = new RegExp(className, 'i');
    return documentElement.className.match(regex);
  };

  // Add one or more CSS classes to the <html> element.
  addClass = function (className) {
    var currentClassNames = null;
    if (!hasClass(className)) {
      currentClassNames = documentElement.className.replace(/^\s+|\s+$/g, '');
      documentElement.className = currentClassNames + " " + className;
    }
  };

  // Remove single CSS class from the <html> element.
  removeClass = function (className) {
    if (hasClass(className)) {
      documentElement.className = documentElement.className.replace(" " + className, "");
    }
  };

  // HTML Element Handling
  // ---------------------

  // Insert the appropriate CSS class based on the _user_agent.

  if (device.ios()) {
    if (device.ipad()) {
      addClass("ios ipad tablet");
    } else if (device.iphone()) {
      addClass("ios iphone mobile");
    } else if (device.ipod()) {
      addClass("ios ipod mobile");
    }
  } else if (device.android()) {
    if (device.androidTablet()) {
      addClass("android tablet");
    } else {
      addClass("android mobile");
    }
  } else if (device.blackberry()) {
    if (device.blackberryTablet()) {
      addClass("blackberry tablet");
    } else {
      addClass("blackberry mobile");
    }
  } else if (device.windows()) {
    if (device.windowsTablet()) {
      addClass("windows tablet");
    } else if (device.windowsPhone()) {
      addClass("windows mobile");
    } else {
      addClass("desktop");
    }
  } else if (device.fxos()) {
    if (device.fxosTablet()) {
      addClass("fxos tablet");
    } else {
      addClass("fxos mobile");
    }
  } else if (device.meego()) {
    addClass("meego mobile");
  } else if (device.nodeWebkit()) {
    addClass("node-webkit");
  } else if (device.television()) {
    addClass("television");
  } else if (device.desktop()) {
    addClass("desktop");
  }

  if (device.cordova()) {
    addClass("cordova");
  }

  // Orientation Handling
  // --------------------

  // Handle device orientation changes.
  handleOrientation = function () {
    if (device.landscape()) {
      removeClass("portrait");
      addClass("landscape");
    } else {
      removeClass("landscape");
      addClass("portrait");
    }
    return;
  };

  // Detect whether device supports orientationchange event,
  // otherwise fall back to the resize event.
  if (Object.prototype.hasOwnProperty.call(window, "onorientationchange")) {
    orientationEvent = "orientationchange";
  } else {
    orientationEvent = "resize";
  }

  // Listen for changes in orientation.
  if (window.addEventListener) {
    window.addEventListener(orientationEvent, handleOrientation, false);
  } else if (window.attachEvent) {
    window.attachEvent(orientationEvent, handleOrientation);
  } else {
    window[orientationEvent] = handleOrientation;
  }

  handleOrientation();

  if (typeof define === 'function' && typeof define.amd === 'object' && define.amd) {
    define(function() {
      return device;
    });
  } else if (typeof module !== 'undefined' && module.exports) {
    module.exports = device;
  } else {
    window.device = device;
  }

}).call(this);

/*! modernizr 3.3.1 (Custom Build) | MIT *
 * https://modernizr.com/download/?-applicationcache-audio-cookies-cssanimations-emoji-fullscreen-history-json-svg-touchevents-video-mq-setclasses !*/
!function(e,n,t){function o(e,n){return typeof e===n}function a(){var e,n,t,a,r,i,s;for(var c in x)if(x.hasOwnProperty(c)){if(e=[],n=x[c],n.name&&(e.push(n.name.toLowerCase()),n.options&&n.options.aliases&&n.options.aliases.length))for(t=0;t<n.options.aliases.length;t++)e.push(n.options.aliases[t].toLowerCase());for(a=o(n.fn,"function")?n.fn():n.fn,r=0;r<e.length;r++)i=e[r],s=i.split("."),1===s.length?Modernizr[s[0]]=a:(!Modernizr[s[0]]||Modernizr[s[0]]instanceof Boolean||(Modernizr[s[0]]=new Boolean(Modernizr[s[0]])),Modernizr[s[0]][s[1]]=a),g.push((a?"":"no-")+s.join("-"))}}function r(e){var n=C.className,t=Modernizr._config.classPrefix||"";if(w&&(n=n.baseVal),Modernizr._config.enableJSClass){var o=new RegExp("(^|\\s)"+t+"no-js(\\s|$)");n=n.replace(o,"$1"+t+"js$2")}Modernizr._config.enableClasses&&(n+=" "+t+e.join(" "+t),w?C.className.baseVal=n:C.className=n)}function i(){return"function"!=typeof n.createElement?n.createElement(arguments[0]):w?n.createElementNS.call(n,"http://www.w3.org/2000/svg",arguments[0]):n.createElement.apply(n,arguments)}function s(){var e=n.body;return e||(e=i(w?"svg":"body"),e.fake=!0),e}function c(e,t,o,a){var r,c,l,u,d="modernizr",f=i("div"),p=s();if(parseInt(o,10))for(;o--;)l=i("div"),l.id=a?a[o]:d+(o+1),f.appendChild(l);return r=i("style"),r.type="text/css",r.id="s"+d,(p.fake?p:f).appendChild(r),p.appendChild(f),r.styleSheet?r.styleSheet.cssText=e:r.appendChild(n.createTextNode(e)),f.id=d,p.fake&&(p.style.background="",p.style.overflow="hidden",u=C.style.overflow,C.style.overflow="hidden",C.appendChild(p)),c=t(f,e),p.fake?(p.parentNode.removeChild(p),C.style.overflow=u,C.offsetHeight):f.parentNode.removeChild(f),!!c}function l(e){return e.replace(/([a-z])-([a-z])/g,function(e,n,t){return n+t.toUpperCase()}).replace(/^-/,"")}function u(e,n){return!!~(""+e).indexOf(n)}function d(e,n){return function(){return e.apply(n,arguments)}}function f(e,n,t){var a;for(var r in e)if(e[r]in n)return t===!1?e[r]:(a=n[e[r]],o(a,"function")?d(a,t||n):a);return!1}function p(e){return e.replace(/([A-Z])/g,function(e,n){return"-"+n.toLowerCase()}).replace(/^ms-/,"-ms-")}function v(n,o){var a=n.length;if("CSS"in e&&"supports"in e.CSS){for(;a--;)if(e.CSS.supports(p(n[a]),o))return!0;return!1}if("CSSSupportsRule"in e){for(var r=[];a--;)r.push("("+p(n[a])+":"+o+")");return r=r.join(" or "),c("@supports ("+r+") { #modernizr { position: absolute; } }",function(e){return"absolute"==getComputedStyle(e,null).position})}return t}function m(e,n,a,r){function s(){d&&(delete z.style,delete z.modElem)}if(r=o(r,"undefined")?!1:r,!o(a,"undefined")){var c=v(e,a);if(!o(c,"undefined"))return c}for(var d,f,p,m,y,h=["modernizr","tspan","samp"];!z.style&&h.length;)d=!0,z.modElem=i(h.shift()),z.style=z.modElem.style;for(p=e.length,f=0;p>f;f++)if(m=e[f],y=z.style[m],u(m,"-")&&(m=l(m)),z.style[m]!==t){if(r||o(a,"undefined"))return s(),"pfx"==n?m:!0;try{z.style[m]=a}catch(g){}if(z.style[m]!=y)return s(),"pfx"==n?m:!0}return s(),!1}function y(e,n,t,a,r){var i=e.charAt(0).toUpperCase()+e.slice(1),s=(e+" "+E.join(i+" ")+i).split(" ");return o(n,"string")||o(n,"undefined")?m(s,n,a,r):(s=(e+" "+O.join(i+" ")+i).split(" "),f(s,n,t))}function h(e,n,o){return y(e,t,t,n,o)}var g=[],x=[],T={_version:"3.3.1",_config:{classPrefix:"",enableClasses:!0,enableJSClass:!0,usePrefixes:!0},_q:[],on:function(e,n){var t=this;setTimeout(function(){n(t[e])},0)},addTest:function(e,n,t){x.push({name:e,fn:n,options:t})},addAsyncTest:function(e){x.push({name:null,fn:e})}},Modernizr=function(){};Modernizr.prototype=T,Modernizr=new Modernizr,Modernizr.addTest("applicationcache","applicationCache"in e),Modernizr.addTest("cookies",function(){try{n.cookie="cookietest=1";var e=-1!=n.cookie.indexOf("cookietest=");return n.cookie="cookietest=1; expires=Thu, 01-Jan-1970 00:00:01 GMT",e}catch(t){return!1}}),Modernizr.addTest("history",function(){var n=navigator.userAgent;return-1===n.indexOf("Android 2.")&&-1===n.indexOf("Android 4.0")||-1===n.indexOf("Mobile Safari")||-1!==n.indexOf("Chrome")||-1!==n.indexOf("Windows Phone")?e.history&&"pushState"in e.history:!1}),Modernizr.addTest("json","JSON"in e&&"parse"in JSON&&"stringify"in JSON),Modernizr.addTest("svg",!!n.createElementNS&&!!n.createElementNS("http://www.w3.org/2000/svg","svg").createSVGRect);var C=n.documentElement,w="svg"===C.nodeName.toLowerCase();Modernizr.addTest("audio",function(){var e=i("audio"),n=!1;try{(n=!!e.canPlayType)&&(n=new Boolean(n),n.ogg=e.canPlayType('audio/ogg; codecs="vorbis"').replace(/^no$/,""),n.mp3=e.canPlayType('audio/mpeg; codecs="mp3"').replace(/^no$/,""),n.opus=e.canPlayType('audio/ogg; codecs="opus"')||e.canPlayType('audio/webm; codecs="opus"').replace(/^no$/,""),n.wav=e.canPlayType('audio/wav; codecs="1"').replace(/^no$/,""),n.m4a=(e.canPlayType("audio/x-m4a;")||e.canPlayType("audio/aac;")).replace(/^no$/,""))}catch(t){}return n}),Modernizr.addTest("video",function(){var e=i("video"),n=!1;try{(n=!!e.canPlayType)&&(n=new Boolean(n),n.ogg=e.canPlayType('video/ogg; codecs="theora"').replace(/^no$/,""),n.h264=e.canPlayType('video/mp4; codecs="avc1.42E01E"').replace(/^no$/,""),n.webm=e.canPlayType('video/webm; codecs="vp8, vorbis"').replace(/^no$/,""),n.vp9=e.canPlayType('video/webm; codecs="vp9"').replace(/^no$/,""),n.hls=e.canPlayType('application/x-mpegURL; codecs="avc1.42E01E"').replace(/^no$/,""))}catch(t){}return n});var S=T._config.usePrefixes?" -webkit- -moz- -o- -ms- ".split(" "):["",""];T._prefixes=S;var P=function(){var n=e.matchMedia||e.msMatchMedia;return n?function(e){var t=n(e);return t&&t.matches||!1}:function(n){var t=!1;return c("@media "+n+" { #modernizr { position: absolute; } }",function(n){t="absolute"==(e.getComputedStyle?e.getComputedStyle(n,null):n.currentStyle).position}),t}}();T.mq=P;var b=T.testStyles=c;Modernizr.addTest("touchevents",function(){var t;if("ontouchstart"in e||e.DocumentTouch&&n instanceof DocumentTouch)t=!0;else{var o=["@media (",S.join("touch-enabled),("),"heartz",")","{#modernizr{top:9px;position:absolute}}"].join("");b(o,function(e){t=9===e.offsetTop})}return t}),Modernizr.addTest("canvas",function(){var e=i("canvas");return!(!e.getContext||!e.getContext("2d"))}),Modernizr.addTest("canvastext",function(){return Modernizr.canvas===!1?!1:"function"==typeof i("canvas").getContext("2d").fillText}),Modernizr.addTest("emoji",function(){if(!Modernizr.canvastext)return!1;var n=e.devicePixelRatio||1,t=12*n,o=i("canvas"),a=o.getContext("2d");return a.fillStyle="#f00",a.textBaseline="top",a.font="32px Arial",a.fillText("🐨",0,0),0!==a.getImageData(t,t,1,1).data[0]});var _="Moz O ms Webkit",E=T._config.usePrefixes?_.split(" "):[];T._cssomPrefixes=E;var N=function(n){var o,a=S.length,r=e.CSSRule;if("undefined"==typeof r)return t;if(!n)return!1;if(n=n.replace(/^@/,""),o=n.replace(/-/g,"_").toUpperCase()+"_RULE",o in r)return"@"+n;for(var i=0;a>i;i++){var s=S[i],c=s.toUpperCase()+"_"+o;if(c in r)return"@-"+s.toLowerCase()+"-"+n}return!1};T.atRule=N;var O=T._config.usePrefixes?_.toLowerCase().split(" "):[];T._domPrefixes=O;var k={elem:i("modernizr")};Modernizr._q.push(function(){delete k.elem});var z={style:k.elem.style};Modernizr._q.unshift(function(){delete z.style}),T.testAllProps=y;var $=T.prefixed=function(e,n,t){return 0===e.indexOf("@")?N(e):(-1!=e.indexOf("-")&&(e=l(e)),n?y(e,n,t):y(e,"pfx"))};Modernizr.addTest("fullscreen",!(!$("exitFullscreen",n,!1)&&!$("cancelFullScreen",n,!1))),T.testAllProps=h,Modernizr.addTest("cssanimations",h("animationName","a",!0)),a(),r(g),delete T.addTest,delete T.addAsyncTest;for(var j=0;j<Modernizr._q.length;j++)Modernizr._q[j]();e.Modernizr=Modernizr}(window,document);
$(function() {

});

//# sourceMappingURL=parada_v1.js.map
