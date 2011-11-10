var BrowserDetect = {
  init: function () {
    this.browser = this.searchString(this.dataBrowser) || "An unknown browser";
    this.version = this.searchVersion(navigator.userAgent)
      || this.searchVersion(navigator.appVersion)
      || "an unknown version";
    this.OS = this.searchString(this.dataOS) || "an unknown OS";
  },
  searchString: function (data) {
    for (var i=0;i<data.length;i++) {
      var dataString = data[i].string;
      var dataProp = data[i].prop;
      this.versionSearchString = data[i].versionSearch || data[i].identity;
      if (dataString) {
        if (dataString.indexOf(data[i].subString) != -1)
          return data[i].identity;
      }
      else if (dataProp)
        return data[i].identity;
    }
  },
  searchVersion: function (dataString) {
    var index = dataString.indexOf(this.versionSearchString);
    if (index == -1) return;
    return parseFloat(dataString.substring(index+this.versionSearchString.length+1));
  },
  dataBrowser: [
    {
      string: navigator.userAgent,
      subString: "Chrome",
      identity: "Chrome"
    },
    {   string: navigator.userAgent,
      subString: "OmniWeb",
      versionSearch: "OmniWeb/",
      identity: "OmniWeb"
    },
    {
      string: navigator.vendor,
      subString: "Apple",
      identity: "Safari",
      versionSearch: "Version"
    },
    {
      prop: window.opera,
      identity: "Opera",
      versionSearch: "Version"
    },
    {
      string: navigator.vendor,
      subString: "iCab",
      identity: "iCab"
    },
    {
      string: navigator.vendor,
      subString: "KDE",
      identity: "Konqueror"
    },
    {
      string: navigator.userAgent,
      subString: "Firefox",
      identity: "Firefox"
    },
    {
      string: navigator.vendor,
      subString: "Camino",
      identity: "Camino"
    },
    {   // for newer Netscapes (6+)
      string: navigator.userAgent,
      subString: "Netscape",
      identity: "Netscape"
    },
    {
      string: navigator.userAgent,
      subString: "MSIE",
      identity: "Explorer",
      versionSearch: "MSIE"
    },
    {
      string: navigator.userAgent,
      subString: "Gecko",
      identity: "Mozilla",
      versionSearch: "rv"
    },
    {     // for older Netscapes (4-)
      string: navigator.userAgent,
      subString: "Mozilla",
      identity: "Netscape",
      versionSearch: "Mozilla"
    }
  ],
  dataOS : [
    {
      string: navigator.platform,
      subString: "Win",
      identity: "Windows"
    },
    {
      string: navigator.platform,
      subString: "Mac",
      identity: "Mac"
    },
    {
         string: navigator.userAgent,
         subString: "iPhone",
         identity: "iPhone/iPod"
      },
    {
      string: navigator.platform,
      subString: "Linux",
      identity: "Linux"
    }
  ]
};

BrowserDetect.init();

/*
 * jQuery Templates Plugin 1.0.0pre
 * http://github.com/jquery/jquery-tmpl
 * Requires jQuery 1.4.2
 *
 * Copyright Software Freedom Conservancy, Inc.
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 */
(function(a){var r=a.fn.domManip,d="_tmplitem",q=/^[^<]*(<[\w\W]+>)[^>]*$|\{\{\! /,b={},f={},e,p={key:0,data:{}},i=0,c=0,l=[];function g(g,d,h,e){var c={data:e||(e===0||e===false)?e:d?d.data:{},_wrap:d?d._wrap:null,tmpl:null,parent:d||null,nodes:[],calls:u,nest:w,wrap:x,html:v,update:t};g&&a.extend(c,g,{nodes:[],parent:d});if(h){c.tmpl=h;c._ctnt=c._ctnt||c.tmpl(a,c);c.key=++i;(l.length?f:b)[i]=c}return c}a.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},function(f,d){a.fn[f]=function(n){var g=[],i=a(n),k,h,m,l,j=this.length===1&&this[0].parentNode;e=b||{};if(j&&j.nodeType===11&&j.childNodes.length===1&&i.length===1){i[d](this[0]);g=this}else{for(h=0,m=i.length;h<m;h++){c=h;k=(h>0?this.clone(true):this).get();a(i[h])[d](k);g=g.concat(k)}c=0;g=this.pushStack(g,f,i.selector)}l=e;e=null;a.tmpl.complete(l);return g}});a.fn.extend({tmpl:function(d,c,b){return a.tmpl(this[0],d,c,b)},tmplItem:function(){return a.tmplItem(this[0])},template:function(b){return a.template(b,this[0])},domManip:function(d,m,k){if(d[0]&&a.isArray(d[0])){var g=a.makeArray(arguments),h=d[0],j=h.length,i=0,f;while(i<j&&!(f=a.data(h[i++],"tmplItem")));if(f&&c)g[2]=function(b){a.tmpl.afterManip(this,b,k)};r.apply(this,g)}else r.apply(this,arguments);c=0;!e&&a.tmpl.complete(b);return this}});a.extend({tmpl:function(d,h,e,c){var i,k=!c;if(k){c=p;d=a.template[d]||a.template(null,d);f={}}else if(!d){d=c.tmpl;b[c.key]=c;c.nodes=[];c.wrapped&&n(c,c.wrapped);return a(j(c,null,c.tmpl(a,c)))}if(!d)return[];if(typeof h==="function")h=h.call(c||{});e&&e.wrapped&&n(e,e.wrapped);i=a.isArray(h)?a.map(h,function(a){return a?g(e,c,d,a):null}):[g(e,c,d,h)];return k?a(j(c,null,i)):i},tmplItem:function(b){var c;if(b instanceof a)b=b[0];while(b&&b.nodeType===1&&!(c=a.data(b,"tmplItem"))&&(b=b.parentNode));return c||p},template:function(c,b){if(b){if(typeof b==="string")b=o(b);else if(b instanceof a)b=b[0]||{};if(b.nodeType)b=a.data(b,"tmpl")||a.data(b,"tmpl",o(b.innerHTML));return typeof c==="string"?(a.template[c]=b):b}return c?typeof c!=="string"?a.template(null,c):a.template[c]||a.template(null,q.test(c)?c:a(c)):null},encode:function(a){return(""+a).split("<").join("&lt;").split(">").join("&gt;").split('"').join("&#34;").split("'").join("&#39;")}});a.extend(a.tmpl,{tag:{tmpl:{_default:{$2:"null"},open:"if($notnull_1){__=__.concat($item.nest($1,$2));}"},wrap:{_default:{$2:"null"},open:"$item.calls(__,$1,$2);__=[];",close:"call=$item.calls();__=call._.concat($item.wrap(call,__));"},each:{_default:{$2:"$index, $value"},open:"if($notnull_1){$.each($1a,function($2){with(this){",close:"}});}"},"if":{open:"if(($notnull_1) && $1a){",close:"}"},"else":{_default:{$1:"true"},open:"}else if(($notnull_1) && $1a){"},html:{open:"if($notnull_1){__.push($1a);}"},"=":{_default:{$1:"$data"},open:"if($notnull_1){__.push($.encode($1a));}"},"!":{open:""}},complete:function(){b={}},afterManip:function(f,b,d){var e=b.nodeType===11?a.makeArray(b.childNodes):b.nodeType===1?[b]:[];d.call(f,b);m(e);c++}});function j(e,g,f){var b,c=f?a.map(f,function(a){return typeof a==="string"?e.key?a.replace(/(<\w+)(?=[\s>])(?![^>]*_tmplitem)([^>]*)/g,"$1 "+d+'="'+e.key+'" $2'):a:j(a,e,a._ctnt)}):e;if(g)return c;c=c.join("");c.replace(/^\s*([^<\s][^<]*)?(<[\w\W]+>)([^>]*[^>\s])?\s*$/,function(f,c,e,d){b=a(e).get();m(b);if(c)b=k(c).concat(b);if(d)b=b.concat(k(d))});return b?b:k(c)}function k(c){var b=document.createElement("div");b.innerHTML=c;return a.makeArray(b.childNodes)}function o(b){return new Function("jQuery","$item","var $=jQuery,call,__=[],$data=$item.data;with($data){__.push('"+a.trim(b).replace(/([\\'])/g,"\\$1").replace(/[\r\t\n]/g," ").replace(/\$\{([^\}]*)\}/g,"{{= $1}}").replace(/\{\{(\/?)(\w+|.)(?:\(((?:[^\}]|\}(?!\}))*?)?\))?(?:\s+(.*?)?)?(\(((?:[^\}]|\}(?!\}))*?)\))?\s*\}\}/g,function(m,l,k,g,b,c,d){var j=a.tmpl.tag[k],i,e,f;if(!j)throw"Unknown template tag: "+k;i=j._default||[];if(c&&!/\w$/.test(b)){b+=c;c=""}if(b){b=h(b);d=d?","+h(d)+")":c?")":"";e=c?b.indexOf(".")>-1?b+h(c):"("+b+").call($item"+d:b;f=c?e:"(typeof("+b+")==='function'?("+b+").call($item):("+b+"))"}else f=e=i.$1||"null";g=h(g);return"');"+j[l?"close":"open"].split("$notnull_1").join(b?"typeof("+b+")!=='undefined' && ("+b+")!=null":"true").split("$1a").join(f).split("$1").join(e).split("$2").join(g||i.$2||"")+"__.push('"})+"');}return __;")}function n(c,b){c._wrap=j(c,true,a.isArray(b)?b:[q.test(b)?b:a(b).html()]).join("")}function h(a){return a?a.replace(/\\'/g,"'").replace(/\\\\/g,"\\"):null}function s(b){var a=document.createElement("div");a.appendChild(b.cloneNode(true));return a.innerHTML}function m(o){var n="_"+c,k,j,l={},e,p,h;for(e=0,p=o.length;e<p;e++){if((k=o[e]).nodeType!==1)continue;j=k.getElementsByTagName("*");for(h=j.length-1;h>=0;h--)m(j[h]);m(k)}function m(j){var p,h=j,k,e,m;if(m=j.getAttribute(d)){while(h.parentNode&&(h=h.parentNode).nodeType===1&&!(p=h.getAttribute(d)));if(p!==m){h=h.parentNode?h.nodeType===11?0:h.getAttribute(d)||0:0;if(!(e=b[m])){e=f[m];e=g(e,b[h]||f[h]);e.key=++i;b[i]=e}c&&o(m)}j.removeAttribute(d)}else if(c&&(e=a.data(j,"tmplItem"))){o(e.key);b[e.key]=e;h=a.data(j.parentNode,"tmplItem");h=h?h.key:0}if(e){k=e;while(k&&k.key!=h){k.nodes.push(j);k=k.parent}delete e._ctnt;delete e._wrap;a.data(j,"tmplItem",e)}function o(a){a=a+n;e=l[a]=l[a]||g(e,b[e.parent.key+n]||e.parent)}}}function u(a,d,c,b){if(!a)return l.pop();l.push({_:a,tmpl:d,item:this,data:c,options:b})}function w(d,c,b){return a.tmpl(a.template(d),c,b,this)}function x(b,d){var c=b.options||{};c.wrapped=d;return a.tmpl(a.template(b.tmpl),b.data,c,b.item)}function v(d,c){var b=this._wrap;return a.map(a(a.isArray(b)?b.join(""):b).filter(d||"*"),function(a){return c?a.innerText||a.textContent:a.outerHTML||s(a)})}function t(){var b=this.nodes;a.tmpl(null,null,null,this).insertBefore(b[0]);a(b).remove()}})(jQuery);

/*
 * sensible-datetime: a jQuery plugin, version: 0.0.1
 * Last modified: 09/26/2011
 *
 * Sensible DateTime is a jQuery plugin that makes it easy to format ISO datetime into something sensible as you see fit.
 *
 * http://crossbreeze.github.com/jquery-sensible-datetime/
 *
 * Licensed under the MIT:
 * http://www.opensource.org/licenses/mit-license.php
 *
 * Copyright (c) 2011, Jaewoong Kim (jwoongkim@gmail.com)
 */
(function($) {

  $.fn.sensible= function(options) {
    if (options) {
      $.extend(settings , options);
    }

    var self = this;
    self.each(refresh);
    setInterval(function() {
      self.each(refresh);
    }, settings.refreshRate);

    return self;
  };

  var settings = {
    shortDayNames: [
      'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'
    ],
    longDayNames: [
      'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
    ],
    shortMonthNames: [
      'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
    ],
    longMonthNames: [
      'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
    ],
    refreshRate: 60000,
    pastMask: '%mmmm %d, %yyyy at %h:%MM %TT',
    futureMasks: [
      {
        // within a minute
        distance: -60,
        mask: 'just now'
      },
      {
        // within 2 minutes
        distance: -120,
        mask: 'in about a minute'
      },
      {
        // within 1 hour
        distance: -3600,
        mask: 'in %xm minutes'
      },
      {
        // within 2 hours
        distance: -7200,
        mask: 'in about an hour'
      },
      {
        // within 24 hours
        distance: -86400,
        mask: 'in %xh hours'
      },
      {
        // within 48 hours
        distance: -172800,
        mask: 'Tomorrow at %H:%MM'
      },
      {
        // within this year
        distance: -31556926,
        mask: '%mmmm %d at %h:%MM %TT'
      }
    ],
    masks: [
      {
        // within a minute
        distance: 60,
        mask: 'just now'
      },
      {
        // within 2 minutes
        distance: 120,
        mask: 'about a minute ago'
      },
      {
        // within 1 hour
        distance: 3600,
        mask: '%xm minutes ago'
      },
      {
        // within 2 hours
        distance: 7200,
        mask: 'about an hour ago'
      },
      {
        // within 24 hours
        distance: 86400,
        mask: '%xh hours ago'
      },
      {
        // within 48 hours
        distance: 172800,
        mask: 'Yesterday at %h:%MM %TT'
      },
      {
        // within this year
        distance: 31556926,
        mask: '%mmmm %d at %h:%MM %TT'
      }
    ]
  };

  // private functions
  function refresh() {
    var datetime = prepareData(this);

    if (datetime) {
      $(this).text(datetimeInWords(datetime));
      return this;
    }
  }

  function prepareData(element) {
    element = $(element);
    if (!element.data('datetime')) {
      element.data('datetime', datetime(element));
      var text = $.trim(element.text());
      if (text.length > 0) {
        element.attr('title', text);
      }
    }

    return element.data('datetime');
  }

  function datetime(elem) {
    var isTime = $(elem).get(0).tagName.toLowerCase() === 'time';
    var iso8601 = isTime ? $(elem).attr('datetime') : $(elem).attr('title');
    return parse(iso8601);
  }

  function parse(iso8601) {
    var s = $.trim(iso8601);
    s = s.replace(/\.\d\d\d+/,'');
    s = s.replace(/-/,'/').replace(/-/,'/');
    s = s.replace(/T/,' ').replace(/Z/,' UTC');
    s = s.replace(/([\+\-]\d\d)\:?(\d\d)/,' $1$2');

    var datetime = new Date(s);
    return isNaN(datetime) ? null : datetime;
  }

  function datetimeInWords(datetime) {
    var distanceSeconds = (new Date().getTime() - datetime.getTime()) / 1000;

    // future
    if (distanceSeconds < 0) {
      for (var i = 0, length = settings.futureMasks.length; i < length; ++i) {
        if (distanceSeconds > settings.futureMasks[i].distance) {
          return stringFromMask(datetime, settings.futureMasks[i].mask);
        }
      }
    }

    // past
    for (var i = 0, length = settings.masks.length; i < length; ++i) {
      if (distanceSeconds < settings.masks[i].distance) {
        return stringFromMask(datetime, settings.masks[i].mask);
      }
    }

    return stringFromMask(datetime, settings.pastMask);
  }

  function stringFromMask(datetime, mask) {
    var re = /\%(d{1,4}|m{1,4}|yy(?:yy)?|[HhMsTt]{1,2}|x[smhdy]|[S])/g;
    var distanceSeconds = (new Date().getTime() - datetime.getTime()) / 1000;

    // future fix: leave out the minus
    if(distanceSeconds < 0)
    {
      distanceSeconds = ~distanceSeconds;
    }

    var distanceMinutes = distanceSeconds / 60;
    var distanceHours = distanceMinutes / 60;
    var distanceDays = distanceHours / 24;
    var distanceYears = distanceDays / 365;

    return mask.replace(re, function(match) {
      switch (match.substr(1, match.length)) {
        case 'd':
        return datetime.getDate();
        case 'dd':
        return pad(datetime.getDate());
        case 'ddd':
        return settings.shortDayNames[datetime.getDay()];
        case 'dddd':
        return settings.longDayNames[datetime.getDay()];
        case 'm':
        return datetime.getMonth() + 1;
        case 'mm':
        return pad(datetime.getMonth() + 1);
        case 'mmm':
        return settings.shortMonthNames[datetime.getMonth()];
        case 'mmmm':
        return settings.longMonthNames[datetime.getMonth()];
        case 'yy':
        return String(datetime.getFullYear()).slice(2);
        case 'yyyy':
        return datetime.getFullYear();
        case 'h':
        return datetime.getHours() % 12 || 12;
        case 'hh':
        return pad(datetime.getHours() % 12 || 12);
        case 'H':
        return datetime.getHours();
        case 'HH':
        return pad(datetime.getHours());
        case 'M':
        return datetime.getMinutes();
        case 'MM':
        return pad(datetime.getMinutes());
        case 's':
        return datetime.getSeconds();
        case 'ss':
        return pad(datetime.getSeconds());
        case 't':
        return datetime.getHours() < 12 ? 'a' : 'p';
        case 'tt':
        return datetime.getHours() < 12 ? 'am' : 'pm';
        case 'T':
        return datetime.getHours() < 12 ? 'A' : 'P';
        case 'TT':
        return datetime.getHours() < 12 ? 'AM' : 'PM';
        case 'S':
        return ['th', 'st', 'nd', 'rd'][datetime.getDate() % 10 > 3 ? 0 : (datetime.getDate() % 100 - datetime.getDate() % 10 != 10) * datetime.getDate() % 10];
        case 'xs':
        return Math.round(distanceSeconds);
        case 'xm':
        return Math.round(distanceMinutes);
        case 'xh':
        return Math.round(distanceHours);
        case 'xd':
        return Math.floor(distanceDays);
        case 'xy':
        return Math.floor(distanceYears);
        default:
        return match;
      }
    });
  }

  function pad(val, len) {
    val = String(val);
    len = len || 2;
    while (val.length < len) {
      val = '0' + val;
    }
    return val;
  }

  // fix for IE6
  document.createElement('abbr');
  document.createElement('time');

})(jQuery);

// Underscore.js 1.2.1
// (c) 2011 Jeremy Ashkenas, DocumentCloud Inc.
// Underscore is freely distributable under the MIT license.
// Portions of Underscore are inspired or borrowed from Prototype,
// Oliver Steele's Functional, and John Resig's Micro-Templating.
// For all details and documentation:
// http://documentcloud.github.com/underscore
(function(){function u(a,c,d){if(a===c)return a!==0||1/a==1/c;if(a==null||c==null)return a===c;if(a._chain)a=a._wrapped;if(c._chain)c=c._wrapped;if(b.isFunction(a.isEqual))return a.isEqual(c);if(b.isFunction(c.isEqual))return c.isEqual(a);var e=typeof a;if(e!=typeof c)return false;if(!a!=!c)return false;if(b.isNaN(a))return b.isNaN(c);var g=b.isString(a),f=b.isString(c);if(g||f)return g&&f&&String(a)==String(c);g=b.isNumber(a);f=b.isNumber(c);if(g||f)return g&&f&&+a==+c;g=b.isBoolean(a);f=b.isBoolean(c);
if(g||f)return g&&f&&+a==+c;g=b.isDate(a);f=b.isDate(c);if(g||f)return g&&f&&a.getTime()==c.getTime();g=b.isRegExp(a);f=b.isRegExp(c);if(g||f)return g&&f&&a.source==c.source&&a.global==c.global&&a.multiline==c.multiline&&a.ignoreCase==c.ignoreCase;if(e!="object")return false;if(a.length!==c.length)return false;if(a.constructor!==c.constructor)return false;for(e=d.length;e--;)if(d[e]==a)return true;d.push(a);var e=0,g=true,h;for(h in a)if(m.call(a,h)&&(e++,!(g=m.call(c,h)&&u(a[h],c[h],d))))break;if(g){for(h in c)if(m.call(c,
h)&&!e--)break;g=!e}d.pop();return g}var r=this,F=r._,o={},k=Array.prototype,p=Object.prototype,i=k.slice,G=k.unshift,l=p.toString,m=p.hasOwnProperty,v=k.forEach,w=k.map,x=k.reduce,y=k.reduceRight,z=k.filter,A=k.every,B=k.some,q=k.indexOf,C=k.lastIndexOf,p=Array.isArray,H=Object.keys,s=Function.prototype.bind,b=function(a){return new n(a)};if(typeof exports!=="undefined"){if(typeof module!=="undefined"&&module.exports)exports=module.exports=b;exports._=b}else typeof define==="function"&&define.amd?
define("underscore",function(){return b}):r._=b;b.VERSION="1.2.1";var j=b.each=b.forEach=function(a,c,b){if(a!=null)if(v&&a.forEach===v)a.forEach(c,b);else if(a.length===+a.length)for(var e=0,g=a.length;e<g;e++){if(e in a&&c.call(b,a[e],e,a)===o)break}else for(e in a)if(m.call(a,e)&&c.call(b,a[e],e,a)===o)break};b.map=function(a,c,b){var e=[];if(a==null)return e;if(w&&a.map===w)return a.map(c,b);j(a,function(a,f,h){e[e.length]=c.call(b,a,f,h)});return e};b.reduce=b.foldl=b.inject=function(a,c,d,e){var g=
d!==void 0;a==null&&(a=[]);if(x&&a.reduce===x)return e&&(c=b.bind(c,e)),g?a.reduce(c,d):a.reduce(c);j(a,function(a,b,i){g?d=c.call(e,d,a,b,i):(d=a,g=true)});if(!g)throw new TypeError("Reduce of empty array with no initial value");return d};b.reduceRight=b.foldr=function(a,c,d,e){a==null&&(a=[]);if(y&&a.reduceRight===y)return e&&(c=b.bind(c,e)),d!==void 0?a.reduceRight(c,d):a.reduceRight(c);a=(b.isArray(a)?a.slice():b.toArray(a)).reverse();return b.reduce(a,c,d,e)};b.find=b.detect=function(a,c,b){var e;
D(a,function(a,f,h){if(c.call(b,a,f,h))return e=a,true});return e};b.filter=b.select=function(a,c,b){var e=[];if(a==null)return e;if(z&&a.filter===z)return a.filter(c,b);j(a,function(a,f,h){c.call(b,a,f,h)&&(e[e.length]=a)});return e};b.reject=function(a,c,b){var e=[];if(a==null)return e;j(a,function(a,f,h){c.call(b,a,f,h)||(e[e.length]=a)});return e};b.every=b.all=function(a,c,b){var e=true;if(a==null)return e;if(A&&a.every===A)return a.every(c,b);j(a,function(a,f,h){if(!(e=e&&c.call(b,a,f,h)))return o});
return e};var D=b.some=b.any=function(a,c,d){var c=c||b.identity,e=false;if(a==null)return e;if(B&&a.some===B)return a.some(c,d);j(a,function(a,b,h){if(e|=c.call(d,a,b,h))return o});return!!e};b.include=b.contains=function(a,c){var b=false;if(a==null)return b;return q&&a.indexOf===q?a.indexOf(c)!=-1:b=D(a,function(a){if(a===c)return true})};b.invoke=function(a,c){var d=i.call(arguments,2);return b.map(a,function(a){return(c.call?c||a:a[c]).apply(a,d)})};b.pluck=function(a,c){return b.map(a,function(a){return a[c]})};
b.max=function(a,c,d){if(!c&&b.isArray(a))return Math.max.apply(Math,a);if(!c&&b.isEmpty(a))return-Infinity;var e={computed:-Infinity};j(a,function(a,b,h){b=c?c.call(d,a,b,h):a;b>=e.computed&&(e={value:a,computed:b})});return e.value};b.min=function(a,c,d){if(!c&&b.isArray(a))return Math.min.apply(Math,a);if(!c&&b.isEmpty(a))return Infinity;var e={computed:Infinity};j(a,function(a,b,h){b=c?c.call(d,a,b,h):a;b<e.computed&&(e={value:a,computed:b})});return e.value};b.shuffle=function(a){var b=[],d;
j(a,function(a,g){g==0?b[0]=a:(d=Math.floor(Math.random()*(g+1)),b[g]=b[d],b[d]=a)});return b};b.sortBy=function(a,c,d){return b.pluck(b.map(a,function(a,b,f){return{value:a,criteria:c.call(d,a,b,f)}}).sort(function(a,b){var c=a.criteria,d=b.criteria;return c<d?-1:c>d?1:0}),"value")};b.groupBy=function(a,c){var d={},e=b.isFunction(c)?c:function(a){return a[c]};j(a,function(a,b){var c=e(a,b);(d[c]||(d[c]=[])).push(a)});return d};b.sortedIndex=function(a,c,d){d||(d=b.identity);for(var e=0,g=a.length;e<
g;){var f=e+g>>1;d(a[f])<d(c)?e=f+1:g=f}return e};b.toArray=function(a){return!a?[]:a.toArray?a.toArray():b.isArray(a)?i.call(a):b.isArguments(a)?i.call(a):b.values(a)};b.size=function(a){return b.toArray(a).length};b.first=b.head=function(a,b,d){return b!=null&&!d?i.call(a,0,b):a[0]};b.initial=function(a,b,d){return i.call(a,0,a.length-(b==null||d?1:b))};b.last=function(a,b,d){return b!=null&&!d?i.call(a,a.length-b):a[a.length-1]};b.rest=b.tail=function(a,b,d){return i.call(a,b==null||d?1:b)};b.compact=
function(a){return b.filter(a,function(a){return!!a})};b.flatten=function(a,c){return b.reduce(a,function(a,e){if(b.isArray(e))return a.concat(c?e:b.flatten(e));a[a.length]=e;return a},[])};b.without=function(a){return b.difference(a,i.call(arguments,1))};b.uniq=b.unique=function(a,c,d){var d=d?b.map(a,d):a,e=[];b.reduce(d,function(d,f,h){if(0==h||(c===true?b.last(d)!=f:!b.include(d,f)))d[d.length]=f,e[e.length]=a[h];return d},[]);return e};b.union=function(){return b.uniq(b.flatten(arguments,true))};
b.intersection=b.intersect=function(a){var c=i.call(arguments,1);return b.filter(b.uniq(a),function(a){return b.every(c,function(c){return b.indexOf(c,a)>=0})})};b.difference=function(a,c){return b.filter(a,function(a){return!b.include(c,a)})};b.zip=function(){for(var a=i.call(arguments),c=b.max(b.pluck(a,"length")),d=Array(c),e=0;e<c;e++)d[e]=b.pluck(a,""+e);return d};b.indexOf=function(a,c,d){if(a==null)return-1;var e;if(d)return d=b.sortedIndex(a,c),a[d]===c?d:-1;if(q&&a.indexOf===q)return a.indexOf(c);
for(d=0,e=a.length;d<e;d++)if(a[d]===c)return d;return-1};b.lastIndexOf=function(a,b){if(a==null)return-1;if(C&&a.lastIndexOf===C)return a.lastIndexOf(b);for(var d=a.length;d--;)if(a[d]===b)return d;return-1};b.range=function(a,b,d){arguments.length<=1&&(b=a||0,a=0);for(var d=arguments[2]||1,e=Math.max(Math.ceil((b-a)/d),0),g=0,f=Array(e);g<e;)f[g++]=a,a+=d;return f};var E=function(){};b.bind=function(a,c){var d,e;if(a.bind===s&&s)return s.apply(a,i.call(arguments,1));if(!b.isFunction(a))throw new TypeError;
e=i.call(arguments,2);return d=function(){if(!(this instanceof d))return a.apply(c,e.concat(i.call(arguments)));E.prototype=a.prototype;var b=new E,f=a.apply(b,e.concat(i.call(arguments)));return Object(f)===f?f:b}};b.bindAll=function(a){var c=i.call(arguments,1);c.length==0&&(c=b.functions(a));j(c,function(c){a[c]=b.bind(a[c],a)});return a};b.memoize=function(a,c){var d={};c||(c=b.identity);return function(){var b=c.apply(this,arguments);return m.call(d,b)?d[b]:d[b]=a.apply(this,arguments)}};b.delay=
function(a,b){var d=i.call(arguments,2);return setTimeout(function(){return a.apply(a,d)},b)};b.defer=function(a){return b.delay.apply(b,[a,1].concat(i.call(arguments,1)))};b.throttle=function(a,c){var d,e,g,f,h;h=b.debounce(function(){f=false},c);return function(){e=this;g=arguments;var b;d||(d=setTimeout(function(){d=null;a.apply(e,g);h()},c));f||a.apply(e,g);h&&h();f=true}};b.debounce=function(a,b){var d;return function(){var e=this,g=arguments;clearTimeout(d);d=setTimeout(function(){d=null;a.apply(e,
g)},b)}};b.once=function(a){var b=false,d;return function(){if(b)return d;b=true;return d=a.apply(this,arguments)}};b.wrap=function(a,b){return function(){var d=[a].concat(i.call(arguments));return b.apply(this,d)}};b.compose=function(){var a=i.call(arguments);return function(){for(var b=i.call(arguments),d=a.length-1;d>=0;d--)b=[a[d].apply(this,b)];return b[0]}};b.after=function(a,b){return function(){if(--a<1)return b.apply(this,arguments)}};b.keys=H||function(a){if(a!==Object(a))throw new TypeError("Invalid object");
var b=[],d;for(d in a)m.call(a,d)&&(b[b.length]=d);return b};b.values=function(a){return b.map(a,b.identity)};b.functions=b.methods=function(a){var c=[],d;for(d in a)b.isFunction(a[d])&&c.push(d);return c.sort()};b.extend=function(a){j(i.call(arguments,1),function(b){for(var d in b)b[d]!==void 0&&(a[d]=b[d])});return a};b.defaults=function(a){j(i.call(arguments,1),function(b){for(var d in b)a[d]==null&&(a[d]=b[d])});return a};b.clone=function(a){return!b.isObject(a)?a:b.isArray(a)?a.slice():b.extend({},
a)};b.tap=function(a,b){b(a);return a};b.isEqual=function(a,b){return u(a,b,[])};b.isEmpty=function(a){if(b.isArray(a)||b.isString(a))return a.length===0;for(var c in a)if(m.call(a,c))return false;return true};b.isElement=function(a){return!!(a&&a.nodeType==1)};b.isArray=p||function(a){return l.call(a)=="[object Array]"};b.isObject=function(a){return a===Object(a)};b.isArguments=l.call(arguments)=="[object Arguments]"?function(a){return l.call(a)=="[object Arguments]"}:function(a){return!(!a||!m.call(a,
"callee"))};b.isFunction=function(a){return l.call(a)=="[object Function]"};b.isString=function(a){return l.call(a)=="[object String]"};b.isNumber=function(a){return l.call(a)=="[object Number]"};b.isNaN=function(a){return a!==a};b.isBoolean=function(a){return a===true||a===false||l.call(a)=="[object Boolean]"};b.isDate=function(a){return l.call(a)=="[object Date]"};b.isRegExp=function(a){return l.call(a)=="[object RegExp]"};b.isNull=function(a){return a===null};b.isUndefined=function(a){return a===
void 0};b.noConflict=function(){r._=F;return this};b.identity=function(a){return a};b.times=function(a,b,d){for(var e=0;e<a;e++)b.call(d,e)};b.escape=function(a){return(""+a).replace(/&(?!\w+;|#\d+;|#x[\da-f]+;)/gi,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;").replace(/'/g,"&#x27;").replace(/\//g,"&#x2F;")};b.mixin=function(a){j(b.functions(a),function(c){I(c,b[c]=a[c])})};var J=0;b.uniqueId=function(a){var b=J++;return a?a+b:b};b.templateSettings={evaluate:/<%([\s\S]+?)%>/g,
interpolate:/<%=([\s\S]+?)%>/g,escape:/<%-([\s\S]+?)%>/g};b.template=function(a,c){var d=b.templateSettings,d="var __p=[],print=function(){__p.push.apply(__p,arguments);};with(obj||{}){__p.push('"+a.replace(/\\/g,"\\\\").replace(/'/g,"\\'").replace(d.escape,function(a,b){return"',_.escape("+b.replace(/\\'/g,"'")+"),'"}).replace(d.interpolate,function(a,b){return"',"+b.replace(/\\'/g,"'")+",'"}).replace(d.evaluate||null,function(a,b){return"');"+b.replace(/\\'/g,"'").replace(/[\r\n\t]/g," ")+"__p.push('"}).replace(/\r/g,
"\\r").replace(/\n/g,"\\n").replace(/\t/g,"\\t")+"');}return __p.join('');",d=new Function("obj",d);return c?d(c):d};var n=function(a){this._wrapped=a};b.prototype=n.prototype;var t=function(a,c){return c?b(a).chain():a},I=function(a,c){n.prototype[a]=function(){var a=i.call(arguments);G.call(a,this._wrapped);return t(c.apply(b,a),this._chain)}};b.mixin(b);j("pop,push,reverse,shift,sort,splice,unshift".split(","),function(a){var b=k[a];n.prototype[a]=function(){b.apply(this._wrapped,arguments);return t(this._wrapped,
this._chain)}});j(["concat","join","slice"],function(a){var b=k[a];n.prototype[a]=function(){return t(b.apply(this._wrapped,arguments),this._chain)}});n.prototype.chain=function(){this._chain=true;return this};n.prototype.value=function(){return this._wrapped}})();

//     Backbone.js 0.5.3
//     (c) 2010 Jeremy Ashkenas, DocumentCloud Inc.
//     Backbone may be freely distributed under the MIT license.
//     For all details and documentation:
//     http://documentcloud.github.com/backbone

(function(){

  // Initial Setup
  // -------------

  // Save a reference to the global object.
  var root = this;

  // Save the previous value of the `Backbone` variable.
  var previousBackbone = root.Backbone;

  // The top-level namespace. All public Backbone classes and modules will
  // be attached to this. Exported for both CommonJS and the browser.
  var Backbone;
  if (typeof exports !== 'undefined') {
    Backbone = exports;
  } else {
    Backbone = root.Backbone = {};
  }

  // Current version of the library. Keep in sync with `package.json`.
  Backbone.VERSION = '0.5.3';

  // Require Underscore, if we're on the server, and it's not already present.
  var _ = root._;
  if (!_ && (typeof require !== 'undefined')) _ = require('underscore')._;

  // For Backbone's purposes, jQuery or Zepto owns the `$` variable.
  var $ = root.jQuery || root.Zepto;

  // Runs Backbone.js in *noConflict* mode, returning the `Backbone` variable
  // to its previous owner. Returns a reference to this Backbone object.
  Backbone.noConflict = function() {
    root.Backbone = previousBackbone;
    return this;
  };

  // Turn on `emulateHTTP` to support legacy HTTP servers. Setting this option will
  // fake `"PUT"` and `"DELETE"` requests via the `_method` parameter and set a
  // `X-Http-Method-Override` header.
  Backbone.emulateHTTP = false;

  // Turn on `emulateJSON` to support legacy servers that can't deal with direct
  // `application/json` requests ... will encode the body as
  // `application/x-www-form-urlencoded` instead and will send the model in a
  // form param named `model`.
  Backbone.emulateJSON = false;

  // Backbone.Events
  // -----------------

  // A module that can be mixed in to *any object* in order to provide it with
  // custom events. You may `bind` or `unbind` a callback function to an event;
  // `trigger`-ing an event fires all callbacks in succession.
  //
  //     var object = {};
  //     _.extend(object, Backbone.Events);
  //     object.bind('expand', function(){ alert('expanded'); });
  //     object.trigger('expand');
  //
  Backbone.Events = {

    // Bind an event, specified by a string name, `ev`, to a `callback` function.
    // Passing `"all"` will bind the callback to all events fired.
    bind : function(ev, callback, context) {
      var calls = this._callbacks || (this._callbacks = {});
      var list  = calls[ev] || (calls[ev] = []);
      list.push([callback, context]);
      return this;
    },

    // Remove one or many callbacks. If `callback` is null, removes all
    // callbacks for the event. If `ev` is null, removes all bound callbacks
    // for all events.
    unbind : function(ev, callback) {
      var calls;
      if (!ev) {
        this._callbacks = {};
      } else if (calls = this._callbacks) {
        if (!callback) {
          calls[ev] = [];
        } else {
          var list = calls[ev];
          if (!list) return this;
          for (var i = 0, l = list.length; i < l; i++) {
            if (list[i] && callback === list[i][0]) {
              list[i] = null;
              break;
            }
          }
        }
      }
      return this;
    },

    // Trigger an event, firing all bound callbacks. Callbacks are passed the
    // same arguments as `trigger` is, apart from the event name.
    // Listening for `"all"` passes the true event name as the first argument.
    trigger : function(eventName) {
      var list, calls, ev, callback, args;
      var both = 2;
      if (!(calls = this._callbacks)) return this;
      while (both--) {
        ev = both ? eventName : 'all';
        if (list = calls[ev]) {
          for (var i = 0, l = list.length; i < l; i++) {
            if (!(callback = list[i])) {
              list.splice(i, 1); i--; l--;
            } else {
              args = both ? Array.prototype.slice.call(arguments, 1) : arguments;
              callback[0].apply(callback[1] || this, args);
            }
          }
        }
      }
      return this;
    }

  };

  // Backbone.Model
  // --------------

  // Create a new model, with defined attributes. A client id (`cid`)
  // is automatically generated and assigned for you.
  Backbone.Model = function(attributes, options) {
    var defaults;
    attributes || (attributes = {});
    if (defaults = this.defaults) {
      if (_.isFunction(defaults)) defaults = defaults.call(this);
      attributes = _.extend({}, defaults, attributes);
    }
    this.attributes = {};
    this._escapedAttributes = {};
    this.cid = _.uniqueId('c');
    this.set(attributes, {silent : true});
    this._changed = false;
    this._previousAttributes = _.clone(this.attributes);
    if (options && options.collection) this.collection = options.collection;
    this.initialize(attributes, options);
  };

  // Attach all inheritable methods to the Model prototype.
  _.extend(Backbone.Model.prototype, Backbone.Events, {

    // A snapshot of the model's previous attributes, taken immediately
    // after the last `"change"` event was fired.
    _previousAttributes : null,

    // Has the item been changed since the last `"change"` event?
    _changed : false,

    // The default name for the JSON `id` attribute is `"id"`. MongoDB and
    // CouchDB users may want to set this to `"_id"`.
    idAttribute : 'id',

    // Initialize is an empty function by default. Override it with your own
    // initialization logic.
    initialize : function(){},

    // Return a copy of the model's `attributes` object.
    toJSON : function() {
      return _.clone(this.attributes);
    },

    // Get the value of an attribute.
    get : function(attr) {
      return this.attributes[attr];
    },

    // Get the HTML-escaped value of an attribute.
    escape : function(attr) {
      var html;
      if (html = this._escapedAttributes[attr]) return html;
      var val = this.attributes[attr];
      return this._escapedAttributes[attr] = escapeHTML(val == null ? '' : '' + val);
    },

    // Returns `true` if the attribute contains a value that is not null
    // or undefined.
    has : function(attr) {
      return this.attributes[attr] != null;
    },

    // Set a hash of model attributes on the object, firing `"change"` unless you
    // choose to silence it.
    set : function(attrs, options) {

      // Extract attributes and options.
      options || (options = {});
      if (!attrs) return this;
      if (attrs.attributes) attrs = attrs.attributes;
      var now = this.attributes, escaped = this._escapedAttributes;

      // Run validation.
      if (!options.silent && this.validate && !this._performValidation(attrs, options)) return false;

      // Check for changes of `id`.
      if (this.idAttribute in attrs) this.id = attrs[this.idAttribute];

      // We're about to start triggering change events.
      var alreadyChanging = this._changing;
      this._changing = true;

      // Update attributes.
      for (var attr in attrs) {
        var val = attrs[attr];
        if (!_.isEqual(now[attr], val)) {
          now[attr] = val;
          delete escaped[attr];
          this._changed = true;
          if (!options.silent) this.trigger('change:' + attr, this, val, options);
        }
      }

      // Fire the `"change"` event, if the model has been changed.
      if (!alreadyChanging && !options.silent && this._changed) this.change(options);
      this._changing = false;
      return this;
    },

    // Remove an attribute from the model, firing `"change"` unless you choose
    // to silence it. `unset` is a noop if the attribute doesn't exist.
    unset : function(attr, options) {
      if (!(attr in this.attributes)) return this;
      options || (options = {});
      var value = this.attributes[attr];

      // Run validation.
      var validObj = {};
      validObj[attr] = void 0;
      if (!options.silent && this.validate && !this._performValidation(validObj, options)) return false;

      // Remove the attribute.
      delete this.attributes[attr];
      delete this._escapedAttributes[attr];
      if (attr == this.idAttribute) delete this.id;
      this._changed = true;
      if (!options.silent) {
        this.trigger('change:' + attr, this, void 0, options);
        this.change(options);
      }
      return this;
    },

    // Clear all attributes on the model, firing `"change"` unless you choose
    // to silence it.
    clear : function(options) {
      options || (options = {});
      var attr;
      var old = this.attributes;

      // Run validation.
      var validObj = {};
      for (attr in old) validObj[attr] = void 0;
      if (!options.silent && this.validate && !this._performValidation(validObj, options)) return false;

      this.attributes = {};
      this._escapedAttributes = {};
      this._changed = true;
      if (!options.silent) {
        for (attr in old) {
          this.trigger('change:' + attr, this, void 0, options);
        }
        this.change(options);
      }
      return this;
    },

    // Fetch the model from the server. If the server's representation of the
    // model differs from its current attributes, they will be overriden,
    // triggering a `"change"` event.
    fetch : function(options) {
      options || (options = {});
      var model = this;
      var success = options.success;
      options.success = function(resp, status, xhr) {
        if (!model.set(model.parse(resp, xhr), options)) return false;
        if (success) success(model, resp);
      };
      options.error = wrapError(options.error, model, options);
      return (this.sync || Backbone.sync).call(this, 'read', this, options);
    },

    // Set a hash of model attributes, and sync the model to the server.
    // If the server returns an attributes hash that differs, the model's
    // state will be `set` again.
    save : function(attrs, options) {
      options || (options = {});
      if (attrs && !this.set(attrs, options)) return false;
      var model = this;
      var success = options.success;
      options.success = function(resp, status, xhr) {
        if (!model.set(model.parse(resp, xhr), options)) return false;
        if (success) success(model, resp, xhr);
      };
      options.error = wrapError(options.error, model, options);
      var method = this.isNew() ? 'create' : 'update';
      return (this.sync || Backbone.sync).call(this, method, this, options);
    },

    // Destroy this model on the server if it was already persisted. Upon success, the model is removed
    // from its collection, if it has one.
    destroy : function(options) {
      options || (options = {});
      if (this.isNew()) return this.trigger('destroy', this, this.collection, options);
      var model = this;
      var success = options.success;
      options.success = function(resp) {
        model.trigger('destroy', model, model.collection, options);
        if (success) success(model, resp);
      };
      options.error = wrapError(options.error, model, options);
      return (this.sync || Backbone.sync).call(this, 'delete', this, options);
    },

    // Default URL for the model's representation on the server -- if you're
    // using Backbone's restful methods, override this to change the endpoint
    // that will be called.
    url : function() {
      var base = getUrl(this.collection) || this.urlRoot || urlError();
      if (this.isNew()) return base;
      return base + (base.charAt(base.length - 1) == '/' ? '' : '/') + encodeURIComponent(this.id);
    },

    // **parse** converts a response into the hash of attributes to be `set` on
    // the model. The default implementation is just to pass the response along.
    parse : function(resp, xhr) {
      return resp;
    },

    // Create a new model with identical attributes to this one.
    clone : function() {
      return new this.constructor(this);
    },

    // A model is new if it has never been saved to the server, and lacks an id.
    isNew : function() {
      return this.id == null;
    },

    // Call this method to manually fire a `change` event for this model.
    // Calling this will cause all objects observing the model to update.
    change : function(options) {
      this.trigger('change', this, options);
      this._previousAttributes = _.clone(this.attributes);
      this._changed = false;
    },

    // Determine if the model has changed since the last `"change"` event.
    // If you specify an attribute name, determine if that attribute has changed.
    hasChanged : function(attr) {
      if (attr) return this._previousAttributes[attr] != this.attributes[attr];
      return this._changed;
    },

    // Return an object containing all the attributes that have changed, or false
    // if there are no changed attributes. Useful for determining what parts of a
    // view need to be updated and/or what attributes need to be persisted to
    // the server.
    changedAttributes : function(now) {
      now || (now = this.attributes);
      var old = this._previousAttributes;
      var changed = false;
      for (var attr in now) {
        if (!_.isEqual(old[attr], now[attr])) {
          changed = changed || {};
          changed[attr] = now[attr];
        }
      }
      return changed;
    },

    // Get the previous value of an attribute, recorded at the time the last
    // `"change"` event was fired.
    previous : function(attr) {
      if (!attr || !this._previousAttributes) return null;
      return this._previousAttributes[attr];
    },

    // Get all of the attributes of the model at the time of the previous
    // `"change"` event.
    previousAttributes : function() {
      return _.clone(this._previousAttributes);
    },

    // Run validation against a set of incoming attributes, returning `true`
    // if all is well. If a specific `error` callback has been passed,
    // call that instead of firing the general `"error"` event.
    _performValidation : function(attrs, options) {
      var error = this.validate(attrs);
      if (error) {
        if (options.error) {
          options.error(this, error, options);
        } else {
          this.trigger('error', this, error, options);
        }
        return false;
      }
      return true;
    }

  });

  // Backbone.Collection
  // -------------------

  // Provides a standard collection class for our sets of models, ordered
  // or unordered. If a `comparator` is specified, the Collection will maintain
  // its models in sort order, as they're added and removed.
  Backbone.Collection = function(models, options) {
    options || (options = {});
    if (options.comparator) this.comparator = options.comparator;
    _.bindAll(this, '_onModelEvent', '_removeReference');
    this._reset();
    if (models) this.reset(models, {silent: true});
    this.initialize.apply(this, arguments);
  };

  // Define the Collection's inheritable methods.
  _.extend(Backbone.Collection.prototype, Backbone.Events, {

    // The default model for a collection is just a **Backbone.Model**.
    // This should be overridden in most cases.
    model : Backbone.Model,

    // Initialize is an empty function by default. Override it with your own
    // initialization logic.
    initialize : function(){},

    // The JSON representation of a Collection is an array of the
    // models' attributes.
    toJSON : function() {
      return this.map(function(model){ return model.toJSON(); });
    },

    // Add a model, or list of models to the set. Pass **silent** to avoid
    // firing the `added` event for every new model.
    add : function(models, options) {
      if (_.isArray(models)) {
        for (var i = 0, l = models.length; i < l; i++) {
          this._add(models[i], options);
        }
      } else {
        this._add(models, options);
      }
      return this;
    },

    // Remove a model, or a list of models from the set. Pass silent to avoid
    // firing the `removed` event for every model removed.
    remove : function(models, options) {
      if (_.isArray(models)) {
        for (var i = 0, l = models.length; i < l; i++) {
          this._remove(models[i], options);
        }
      } else {
        this._remove(models, options);
      }
      return this;
    },

    // Get a model from the set by id.
    get : function(id) {
      if (id == null) return null;
      return this._byId[id.id != null ? id.id : id];
    },

    // Get a model from the set by client id.
    getByCid : function(cid) {
      return cid && this._byCid[cid.cid || cid];
    },

    // Get the model at the given index.
    at: function(index) {
      return this.models[index];
    },

    // Force the collection to re-sort itself. You don't need to call this under normal
    // circumstances, as the set will maintain sort order as each item is added.
    sort : function(options) {
      options || (options = {});
      if (!this.comparator) throw new Error('Cannot sort a set without a comparator');
      this.models = this.sortBy(this.comparator);
      if (!options.silent) this.trigger('reset', this, options);
      return this;
    },

    // Pluck an attribute from each model in the collection.
    pluck : function(attr) {
      return _.map(this.models, function(model){ return model.get(attr); });
    },

    // When you have more items than you want to add or remove individually,
    // you can reset the entire set with a new list of models, without firing
    // any `added` or `removed` events. Fires `reset` when finished.
    reset : function(models, options) {
      models  || (models = []);
      options || (options = {});
      this.each(this._removeReference);
      this._reset();
      this.add(models, {silent: true});
      if (!options.silent) this.trigger('reset', this, options);
      return this;
    },

    // Fetch the default set of models for this collection, resetting the
    // collection when they arrive. If `add: true` is passed, appends the
    // models to the collection instead of resetting.
    fetch : function(options) {
      options || (options = {});
      var collection = this;
      var success = options.success;
      options.success = function(resp, status, xhr) {
        collection[options.add ? 'add' : 'reset'](collection.parse(resp, xhr), options);
        if (success) success(collection, resp);
      };
      options.error = wrapError(options.error, collection, options);
      return (this.sync || Backbone.sync).call(this, 'read', this, options);
    },

    // Create a new instance of a model in this collection. After the model
    // has been created on the server, it will be added to the collection.
    // Returns the model, or 'false' if validation on a new model fails.
    create : function(model, options) {
      var coll = this;
      options || (options = {});
      model = this._prepareModel(model, options);
      if (!model) return false;
      var success = options.success;
      options.success = function(nextModel, resp, xhr) {
        coll.add(nextModel, options);
        if (success) success(nextModel, resp, xhr);
      };
      model.save(null, options);
      return model;
    },

    // **parse** converts a response into a list of models to be added to the
    // collection. The default implementation is just to pass it through.
    parse : function(resp, xhr) {
      return resp;
    },

    // Proxy to _'s chain. Can't be proxied the same way the rest of the
    // underscore methods are proxied because it relies on the underscore
    // constructor.
    chain: function () {
      return _(this.models).chain();
    },

    // Reset all internal state. Called when the collection is reset.
    _reset : function(options) {
      this.length = 0;
      this.models = [];
      this._byId  = {};
      this._byCid = {};
    },

    // Prepare a model to be added to this collection
    _prepareModel: function(model, options) {
      if (!(model instanceof Backbone.Model)) {
        var attrs = model;
        model = new this.model(attrs, {collection: this});
        if (model.validate && !model._performValidation(attrs, options)) model = false;
      } else if (!model.collection) {
        model.collection = this;
      }
      return model;
    },

    // Internal implementation of adding a single model to the set, updating
    // hash indexes for `id` and `cid` lookups.
    // Returns the model, or 'false' if validation on a new model fails.
    _add : function(model, options) {
      options || (options = {});
      model = this._prepareModel(model, options);
      if (!model) return false;
      var already = this.getByCid(model);
      if (already) throw new Error(["Can't add the same model to a set twice", already.id]);
      this._byId[model.id] = model;
      this._byCid[model.cid] = model;
      var index = options.at != null ? options.at :
                  this.comparator ? this.sortedIndex(model, this.comparator) :
                  this.length;
      this.models.splice(index, 0, model);
      model.bind('all', this._onModelEvent);
      this.length++;
      if (!options.silent) model.trigger('add', model, this, options);
      return model;
    },

    // Internal implementation of removing a single model from the set, updating
    // hash indexes for `id` and `cid` lookups.
    _remove : function(model, options) {
      options || (options = {});
      model = this.getByCid(model) || this.get(model);
      if (!model) return null;
      delete this._byId[model.id];
      delete this._byCid[model.cid];
      this.models.splice(this.indexOf(model), 1);
      this.length--;
      if (!options.silent) model.trigger('remove', model, this, options);
      this._removeReference(model);
      return model;
    },

    // Internal method to remove a model's ties to a collection.
    _removeReference : function(model) {
      if (this == model.collection) {
        delete model.collection;
      }
      model.unbind('all', this._onModelEvent);
    },

    // Internal method called every time a model in the set fires an event.
    // Sets need to update their indexes when models change ids. All other
    // events simply proxy through. "add" and "remove" events that originate
    // in other collections are ignored.
    _onModelEvent : function(ev, model, collection, options) {
      if ((ev == 'add' || ev == 'remove') && collection != this) return;
      if (ev == 'destroy') {
        this._remove(model, options);
      }
      if (model && ev === 'change:' + model.idAttribute) {
        delete this._byId[model.previous(model.idAttribute)];
        this._byId[model.id] = model;
      }
      this.trigger.apply(this, arguments);
    }

  });

  // Underscore methods that we want to implement on the Collection.
  var methods = ['forEach', 'each', 'map', 'reduce', 'reduceRight', 'find', 'detect',
    'filter', 'select', 'reject', 'every', 'all', 'some', 'any', 'include',
    'contains', 'invoke', 'max', 'min', 'sortBy', 'sortedIndex', 'toArray', 'size',
    'first', 'rest', 'last', 'without', 'indexOf', 'lastIndexOf', 'isEmpty', 'groupBy'];

  // Mix in each Underscore method as a proxy to `Collection#models`.
  _.each(methods, function(method) {
    Backbone.Collection.prototype[method] = function() {
      return _[method].apply(_, [this.models].concat(_.toArray(arguments)));
    };
  });

  // Backbone.Router
  // -------------------

  // Routers map faux-URLs to actions, and fire events when routes are
  // matched. Creating a new one sets its `routes` hash, if not set statically.
  Backbone.Router = function(options) {
    options || (options = {});
    if (options.routes) this.routes = options.routes;
    this._bindRoutes();
    this.initialize.apply(this, arguments);
  };

  // Cached regular expressions for matching named param parts and splatted
  // parts of route strings.
  var namedParam    = /:([\w\d]+)/g;
  var splatParam    = /\*([\w\d]+)/g;
  var escapeRegExp  = /[-[\]{}()+?.,\\^$|#\s]/g;

  // Set up all inheritable **Backbone.Router** properties and methods.
  _.extend(Backbone.Router.prototype, Backbone.Events, {

    // Initialize is an empty function by default. Override it with your own
    // initialization logic.
    initialize : function(){},

    // Manually bind a single named route to a callback. For example:
    //
    //     this.route('search/:query/p:num', 'search', function(query, num) {
    //       ...
    //     });
    //
    route : function(route, name, callback) {
      Backbone.history || (Backbone.history = new Backbone.History);
      if (!_.isRegExp(route)) route = this._routeToRegExp(route);
      Backbone.history.route(route, _.bind(function(fragment) {
        var args = this._extractParameters(route, fragment);
        callback.apply(this, args);
        this.trigger.apply(this, ['route:' + name].concat(args));
      }, this));
    },

    // Simple proxy to `Backbone.history` to save a fragment into the history.
    navigate : function(fragment, triggerRoute) {
      Backbone.history.navigate(fragment, triggerRoute);
    },

    // Bind all defined routes to `Backbone.history`. We have to reverse the
    // order of the routes here to support behavior where the most general
    // routes can be defined at the bottom of the route map.
    _bindRoutes : function() {
      if (!this.routes) return;
      var routes = [];
      for (var route in this.routes) {
        routes.unshift([route, this.routes[route]]);
      }
      for (var i = 0, l = routes.length; i < l; i++) {
        this.route(routes[i][0], routes[i][1], this[routes[i][1]]);
      }
    },

    // Convert a route string into a regular expression, suitable for matching
    // against the current location hash.
    _routeToRegExp : function(route) {
      route = route.replace(escapeRegExp, "\\$&")
                   .replace(namedParam, "([^\/]*)")
                   .replace(splatParam, "(.*?)");
      return new RegExp('^' + route + '$');
    },

    // Given a route, and a URL fragment that it matches, return the array of
    // extracted parameters.
    _extractParameters : function(route, fragment) {
      return route.exec(fragment).slice(1);
    }

  });

  // Backbone.History
  // ----------------

  // Handles cross-browser history management, based on URL fragments. If the
  // browser does not support `onhashchange`, falls back to polling.
  Backbone.History = function() {
    this.handlers = [];
    _.bindAll(this, 'checkUrl');
  };

  // Cached regex for cleaning hashes.
  var hashStrip = /^#*/;

  // Cached regex for detecting MSIE.
  var isExplorer = /msie [\w.]+/;

  // Has the history handling already been started?
  var historyStarted = false;

  // Set up all inheritable **Backbone.History** properties and methods.
  _.extend(Backbone.History.prototype, {

    // The default interval to poll for hash changes, if necessary, is
    // twenty times a second.
    interval: 50,

    // Get the cross-browser normalized URL fragment, either from the URL,
    // the hash, or the override.
    getFragment : function(fragment, forcePushState) {
      if (fragment == null) {
        if (this._hasPushState || forcePushState) {
          fragment = window.location.pathname;
          var search = window.location.search;
          if (search) fragment += search;
          if (fragment.indexOf(this.options.root) == 0) fragment = fragment.substr(this.options.root.length);
        } else {
          fragment = window.location.hash;
        }
      }
      return decodeURIComponent(fragment.replace(hashStrip, ''));
    },

    // Start the hash change handling, returning `true` if the current URL matches
    // an existing route, and `false` otherwise.
    start : function(options) {

      // Figure out the initial configuration. Do we need an iframe?
      // Is pushState desired ... is it available?
      if (historyStarted) throw new Error("Backbone.history has already been started");
      this.options          = _.extend({}, {root: '/'}, this.options, options);
      this._wantsPushState  = !!this.options.pushState;
      this._hasPushState    = !!(this.options.pushState && window.history && window.history.pushState);
      var fragment          = this.getFragment();
      var docMode           = document.documentMode;
      var oldIE             = (isExplorer.exec(navigator.userAgent.toLowerCase()) && (!docMode || docMode <= 7));
      if (oldIE) {
        this.iframe = $('<iframe src="javascript:0" tabindex="-1" />').hide().appendTo('body')[0].contentWindow;
        this.navigate(fragment);
      }

      // Depending on whether we're using pushState or hashes, and whether
      // 'onhashchange' is supported, determine how we check the URL state.
      if (this._hasPushState) {
        $(window).bind('popstate', this.checkUrl);
      } else if ('onhashchange' in window && !oldIE) {
        $(window).bind('hashchange', this.checkUrl);
      } else {
        setInterval(this.checkUrl, this.interval);
      }

      // Determine if we need to change the base url, for a pushState link
      // opened by a non-pushState browser.
      this.fragment = fragment;
      historyStarted = true;
      var loc = window.location;
      var atRoot  = loc.pathname == this.options.root;
      if (this._wantsPushState && !this._hasPushState && !atRoot) {
        this.fragment = this.getFragment(null, true);
        window.location.replace(this.options.root + '#' + this.fragment);
        // Return immediately as browser will do redirect to new url
        return true;
      } else if (this._wantsPushState && this._hasPushState && atRoot && loc.hash) {
        this.fragment = loc.hash.replace(hashStrip, '');
        window.history.replaceState({}, document.title, loc.protocol + '//' + loc.host + this.options.root + this.fragment);
      }

      if (!this.options.silent) {
        return this.loadUrl();
      }
    },

    // Add a route to be tested when the fragment changes. Routes added later may
    // override previous routes.
    route : function(route, callback) {
      this.handlers.unshift({route : route, callback : callback});
    },

    // Checks the current URL to see if it has changed, and if it has,
    // calls `loadUrl`, normalizing across the hidden iframe.
    checkUrl : function(e) {
      var current = this.getFragment();
      if (current == this.fragment && this.iframe) current = this.getFragment(this.iframe.location.hash);
      if (current == this.fragment || current == decodeURIComponent(this.fragment)) return false;
      if (this.iframe) this.navigate(current);
      this.loadUrl() || this.loadUrl(window.location.hash);
    },

    // Attempt to load the current URL fragment. If a route succeeds with a
    // match, returns `true`. If no defined routes matches the fragment,
    // returns `false`.
    loadUrl : function(fragmentOverride) {
      var fragment = this.fragment = this.getFragment(fragmentOverride);
      var matched = _.any(this.handlers, function(handler) {
        if (handler.route.test(fragment)) {
          handler.callback(fragment);
          return true;
        }
      });
      return matched;
    },

    // Save a fragment into the hash history. You are responsible for properly
    // URL-encoding the fragment in advance. This does not trigger
    // a `hashchange` event.
    navigate : function(fragment, triggerRoute) {
      var frag = (fragment || '').replace(hashStrip, '');
      if (this.fragment == frag || this.fragment == decodeURIComponent(frag)) return;
      if (this._hasPushState) {
        var loc = window.location;
        if (frag.indexOf(this.options.root) != 0) frag = this.options.root + frag;
        this.fragment = frag;
        window.history.pushState({}, document.title, loc.protocol + '//' + loc.host + frag);
      } else {
        window.location.hash = this.fragment = frag;
        if (this.iframe && (frag != this.getFragment(this.iframe.location.hash))) {
          this.iframe.document.open().close();
          this.iframe.location.hash = frag;
        }
      }
      if (triggerRoute) this.loadUrl(fragment);
    }

  });

  // Backbone.View
  // -------------

  // Creating a Backbone.View creates its initial element outside of the DOM,
  // if an existing element is not provided...
  Backbone.View = function(options) {
    this.cid = _.uniqueId('view');
    this._configure(options || {});
    this._ensureElement();
    this.delegateEvents();
    this.initialize.apply(this, arguments);
  };

  // Element lookup, scoped to DOM elements within the current view.
  // This should be prefered to global lookups, if you're dealing with
  // a specific view.
  var selectorDelegate = function(selector) {
    return $(selector, this.el);
  };

  // Cached regex to split keys for `delegate`.
  var eventSplitter = /^(\S+)\s*(.*)$/;

  // List of view options to be merged as properties.
  var viewOptions = ['model', 'collection', 'el', 'id', 'attributes', 'className', 'tagName'];

  // Set up all inheritable **Backbone.View** properties and methods.
  _.extend(Backbone.View.prototype, Backbone.Events, {

    // The default `tagName` of a View's element is `"div"`.
    tagName : 'div',

    // Attach the `selectorDelegate` function as the `$` property.
    $       : selectorDelegate,

    // Initialize is an empty function by default. Override it with your own
    // initialization logic.
    initialize : function(){},

    // **render** is the core function that your view should override, in order
    // to populate its element (`this.el`), with the appropriate HTML. The
    // convention is for **render** to always return `this`.
    render : function() {
      return this;
    },

    // Remove this view from the DOM. Note that the view isn't present in the
    // DOM by default, so calling this method may be a no-op.
    remove : function() {
      $(this.el).remove();
      return this;
    },

    // For small amounts of DOM Elements, where a full-blown template isn't
    // needed, use **make** to manufacture elements, one at a time.
    //
    //     var el = this.make('li', {'class': 'row'}, this.model.escape('title'));
    //
    make : function(tagName, attributes, content) {
      var el = document.createElement(tagName);
      if (attributes) $(el).attr(attributes);
      if (content) $(el).html(content);
      return el;
    },

    // Set callbacks, where `this.callbacks` is a hash of
    //
    // *{"event selector": "callback"}*
    //
    //     {
    //       'mousedown .title':  'edit',
    //       'click .button':     'save'
    //     }
    //
    // pairs. Callbacks will be bound to the view, with `this` set properly.
    // Uses event delegation for efficiency.
    // Omitting the selector binds the event to `this.el`.
    // This only works for delegate-able events: not `focus`, `blur`, and
    // not `change`, `submit`, and `reset` in Internet Explorer.
    delegateEvents : function(events) {
      if (!(events || (events = this.events))) return;
      if (_.isFunction(events)) events = events.call(this);
      $(this.el).unbind('.delegateEvents' + this.cid);
      for (var key in events) {
        var method = this[events[key]];
        if (!method) throw new Error('Event "' + events[key] + '" does not exist');
        var match = key.match(eventSplitter);
        var eventName = match[1], selector = match[2];
        method = _.bind(method, this);
        eventName += '.delegateEvents' + this.cid;
        if (selector === '') {
          $(this.el).bind(eventName, method);
        } else {
          $(this.el).delegate(selector, eventName, method);
        }
      }
    },

    // Performs the initial configuration of a View with a set of options.
    // Keys with special meaning *(model, collection, id, className)*, are
    // attached directly to the view.
    _configure : function(options) {
      if (this.options) options = _.extend({}, this.options, options);
      for (var i = 0, l = viewOptions.length; i < l; i++) {
        var attr = viewOptions[i];
        if (options[attr]) this[attr] = options[attr];
      }
      this.options = options;
    },

    // Ensure that the View has a DOM element to render into.
    // If `this.el` is a string, pass it through `$()`, take the first
    // matching element, and re-assign it to `el`. Otherwise, create
    // an element from the `id`, `className` and `tagName` proeprties.
    _ensureElement : function() {
      if (!this.el) {
        var attrs = this.attributes || {};
        if (this.id) attrs.id = this.id;
        if (this.className) attrs['class'] = this.className;
        this.el = this.make(this.tagName, attrs);
      } else if (_.isString(this.el)) {
        this.el = $(this.el).get(0);
      }
    }

  });

  // The self-propagating extend function that Backbone classes use.
  var extend = function (protoProps, classProps) {
    var child = inherits(this, protoProps, classProps);
    child.extend = this.extend;
    return child;
  };

  // Set up inheritance for the model, collection, and view.
  Backbone.Model.extend = Backbone.Collection.extend =
    Backbone.Router.extend = Backbone.View.extend = extend;

  // Map from CRUD to HTTP for our default `Backbone.sync` implementation.
  var methodMap = {
    'create': 'POST',
    'update': 'PUT',
    'delete': 'DELETE',
    'read'  : 'GET'
  };

  // Backbone.sync
  // -------------

  // Override this function to change the manner in which Backbone persists
  // models to the server. You will be passed the type of request, and the
  // model in question. By default, uses makes a RESTful Ajax request
  // to the model's `url()`. Some possible customizations could be:
  //
  // * Use `setTimeout` to batch rapid-fire updates into a single request.
  // * Send up the models as XML instead of JSON.
  // * Persist models via WebSockets instead of Ajax.
  //
  // Turn on `Backbone.emulateHTTP` in order to send `PUT` and `DELETE` requests
  // as `POST`, with a `_method` parameter containing the true HTTP method,
  // as well as all requests with the body as `application/x-www-form-urlencoded` instead of
  // `application/json` with the model in a param named `model`.
  // Useful when interfacing with server-side languages like **PHP** that make
  // it difficult to read the body of `PUT` requests.
  Backbone.sync = function(method, model, options) {
    var type = methodMap[method];

    // Default JSON-request options.
    var params = _.extend({
      type:         type,
      dataType:     'json'
    }, options);

    // Ensure that we have a URL.
    if (!params.url) {
      params.url = getUrl(model) || urlError();
    }

    // Ensure that we have the appropriate request data.
    if (!params.data && model && (method == 'create' || method == 'update')) {
      params.contentType = 'application/json';
      params.data = JSON.stringify(model.toJSON());
    }

    // For older servers, emulate JSON by encoding the request into an HTML-form.
    if (Backbone.emulateJSON) {
      params.contentType = 'application/x-www-form-urlencoded';
      params.data        = params.data ? {model : params.data} : {};
    }

    // For older servers, emulate HTTP by mimicking the HTTP method with `_method`
    // And an `X-HTTP-Method-Override` header.
    if (Backbone.emulateHTTP) {
      if (type === 'PUT' || type === 'DELETE') {
        if (Backbone.emulateJSON) params.data._method = type;
        params.type = 'POST';
        params.beforeSend = function(xhr) {
          xhr.setRequestHeader('X-HTTP-Method-Override', type);
        };
      }
    }

    // Don't process data on a non-GET request.
    if (params.type !== 'GET' && !Backbone.emulateJSON) {
      params.processData = false;
    }

    // Make the request.
    return $.ajax(params);
  };

  // Helpers
  // -------

  // Shared empty constructor function to aid in prototype-chain creation.
  var ctor = function(){};

  // Helper function to correctly set up the prototype chain, for subclasses.
  // Similar to `goog.inherits`, but uses a hash of prototype properties and
  // class properties to be extended.
  var inherits = function(parent, protoProps, staticProps) {
    var child;

    // The constructor function for the new subclass is either defined by you
    // (the "constructor" property in your `extend` definition), or defaulted
    // by us to simply call `super()`.
    if (protoProps && protoProps.hasOwnProperty('constructor')) {
      child = protoProps.constructor;
    } else {
      child = function(){ return parent.apply(this, arguments); };
    }

    // Inherit class (static) properties from parent.
    _.extend(child, parent);

    // Set the prototype chain to inherit from `parent`, without calling
    // `parent`'s constructor function.
    ctor.prototype = parent.prototype;
    child.prototype = new ctor();

    // Add prototype properties (instance properties) to the subclass,
    // if supplied.
    if (protoProps) _.extend(child.prototype, protoProps);

    // Add static properties to the constructor function, if supplied.
    if (staticProps) _.extend(child, staticProps);

    // Correctly set child's `prototype.constructor`.
    child.prototype.constructor = child;

    // Set a convenience property in case the parent's prototype is needed later.
    child.__super__ = parent.prototype;

    return child;
  };

  // Helper function to get a URL from a Model or Collection as a property
  // or as a function.
  var getUrl = function(object) {
    if (!(object && object.url)) return null;
    return _.isFunction(object.url) ? object.url() : object.url;
  };

  // Throw an error when a URL is needed, and none is supplied.
  var urlError = function() {
    throw new Error('A "url" property or function must be specified');
  };

  // Wrap an optional error callback with a fallback error event.
  var wrapError = function(onError, model, options) {
    return function(resp) {
      if (onError) {
        onError(model, resp, options);
      } else {
        model.trigger('error', model, resp, options);
      }
    };
  };

  // Helper function to escape a string for HTML rendering.
  var escapeHTML = function(string) {
    return string.replace(/&(?!\w+;|#\d+;|#x[\da-f]+;)/gi, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#x27;').replace(/\//g,'&#x2F;');
  };

}).call(this);

/* ============================================================
 * bootstrap-dropdown.js v1.4.0
 * http://twitter.github.com/bootstrap/javascript.html#dropdown
 * ============================================================
 * Copyright 2011 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ============================================================ */


!function( $ ){

  "use strict"

  /* DROPDOWN PLUGIN DEFINITION
   * ========================== */

  $.fn.dropdown = function ( selector ) {
    return this.each(function () {
      $(this).delegate(selector || d, 'click', function (e) {
        var li = $(this).parent('li')
          , isActive = li.hasClass('open')

        clearMenus()
        !isActive && li.toggleClass('open')
        return false
      })
    })
  }

  /* APPLY TO STANDARD DROPDOWN ELEMENTS
   * =================================== */

  var d = 'a.menu, .dropdown-toggle'

  function clearMenus() {
    $(d).parent('li').removeClass('open')
  }

  $(function () {
    $('html').bind("click", clearMenus)
    $('body').dropdown( '[data-dropdown] a.menu, [data-dropdown] .dropdown-toggle' )
  })

}( window.jQuery || window.ender );

/* =========================================================
 * bootstrap-modal.js v1.4.0
 * http://twitter.github.com/bootstrap/javascript.html#modal
 * =========================================================
 * Copyright 2011 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================= */


!function( $ ){

  "use strict"

 /* CSS TRANSITION SUPPORT (https://gist.github.com/373874)
  * ======================================================= */

  var transitionEnd

  $(document).ready(function () {

    $.support.transition = (function () {
      var thisBody = document.body || document.documentElement
        , thisStyle = thisBody.style
        , support = thisStyle.transition !== undefined || thisStyle.WebkitTransition !== undefined || thisStyle.MozTransition !== undefined || thisStyle.MsTransition !== undefined || thisStyle.OTransition !== undefined
      return support
    })()

    // set CSS transition event type
    if ( $.support.transition ) {
      transitionEnd = "TransitionEnd"
      if ( $.browser.webkit ) {
      	transitionEnd = "webkitTransitionEnd"
      } else if ( $.browser.mozilla ) {
      	transitionEnd = "transitionend"
      } else if ( $.browser.opera ) {
      	transitionEnd = "oTransitionEnd"
      }
    }

  })


 /* MODAL PUBLIC CLASS DEFINITION
  * ============================= */

  var Modal = function ( content, options ) {
    this.settings = $.extend({}, $.fn.modal.defaults, options)
    this.$element = $(content)
      .delegate('.close', 'click.modal', $.proxy(this.hide, this))

    if ( this.settings.show ) {
      this.show()
    }

    return this
  }

  Modal.prototype = {

      toggle: function () {
        return this[!this.isShown ? 'show' : 'hide']()
      }

    , show: function () {
        var that = this
        this.isShown = true
        this.$element.trigger('show')

        escape.call(this)
        backdrop.call(this, function () {
          var transition = $.support.transition && that.$element.hasClass('fade')

          that.$element
            .appendTo(document.body)
            .show()

          if (transition) {
            that.$element[0].offsetWidth // force reflow
          }

          that.$element.addClass('in')

          transition ?
            that.$element.one(transitionEnd, function () { that.$element.trigger('shown') }) :
            that.$element.trigger('shown')

        })

        return this
      }

    , hide: function (e) {
        e && e.preventDefault()

        if ( !this.isShown ) {
          return this
        }

        var that = this
        this.isShown = false

        escape.call(this)

        this.$element
          .trigger('hide')
          .removeClass('in')

        $.support.transition && this.$element.hasClass('fade') ?
          hideWithTransition.call(this) :
          hideModal.call(this)

        return this
      }

  }


 /* MODAL PRIVATE METHODS
  * ===================== */

  function hideWithTransition() {
    // firefox drops transitionEnd events :{o
    var that = this
      , timeout = setTimeout(function () {
          that.$element.unbind(transitionEnd)
          hideModal.call(that)
        }, 500)

    this.$element.one(transitionEnd, function () {
      clearTimeout(timeout)
      hideModal.call(that)
    })
  }

  function hideModal (that) {
    this.$element
      .hide()
      .trigger('hidden')

    backdrop.call(this)
  }

  function backdrop ( callback ) {
    var that = this
      , animate = this.$element.hasClass('fade') ? 'fade' : ''
    if ( this.isShown && this.settings.backdrop ) {
      var doAnimate = $.support.transition && animate

      this.$backdrop = $('<div class="modal-backdrop ' + animate + '" />')
        .appendTo(document.body)

      if ( this.settings.backdrop != 'static' ) {
        this.$backdrop.click($.proxy(this.hide, this))
      }

      if ( doAnimate ) {
        this.$backdrop[0].offsetWidth // force reflow
      }

      this.$backdrop.addClass('in')

      doAnimate ?
        this.$backdrop.one(transitionEnd, callback) :
        callback()

    } else if ( !this.isShown && this.$backdrop ) {
      this.$backdrop.removeClass('in')

      $.support.transition && this.$element.hasClass('fade')?
        this.$backdrop.one(transitionEnd, $.proxy(removeBackdrop, this)) :
        removeBackdrop.call(this)

    } else if ( callback ) {
       callback()
    }
  }

  function removeBackdrop() {
    this.$backdrop.remove()
    this.$backdrop = null
  }

  function escape() {
    var that = this
    if ( this.isShown && this.settings.keyboard ) {
      $(document).bind('keyup.modal', function ( e ) {
        if ( e.which == 27 ) {
          that.hide()
        }
      })
    } else if ( !this.isShown ) {
      $(document).unbind('keyup.modal')
    }
  }


 /* MODAL PLUGIN DEFINITION
  * ======================= */

  $.fn.modal = function ( options ) {
    var modal = this.data('modal')

    if (!modal) {

      if (typeof options == 'string') {
        options = {
          show: /show|toggle/.test(options)
        }
      }

      return this.each(function () {
        $(this).data('modal', new Modal(this, options))
      })
    }

    if ( options === true ) {
      return modal
    }

    if ( typeof options == 'string' ) {
      modal[options]()
    } else if ( modal ) {
      modal.toggle()
    }

    return this
  }

  $.fn.modal.Modal = Modal

  $.fn.modal.defaults = {
    backdrop: false
  , keyboard: false
  , show: false
  }


 /* MODAL DATA- IMPLEMENTATION
  * ========================== */

  $(document).ready(function () {
    $('body').delegate('[data-controls-modal]', 'click', function (e) {
      e.preventDefault()
      var $this = $(this).data('show', true)
      $('#' + $this.attr('data-controls-modal')).modal( $this.data() )
    })
  })

}( window.jQuery || window.ender );

(function() {
  var build_root;
  build_root = '';
  window.AppConfig = {
    public_root: "" + build_root,
    assets_root: "" + build_root + "/assets",
    rpc_urlroot: "/jsonrpc/api/v1/public/",
    log_silence: false
  };
  window.App = {
    Controllers: {},
    Models: {},
    Collections: {},
    Views: {},
    Log: function(args) {
      if (typeof console !== 'undefined' && window.AppConfig.log_silence === false) {
        return console.log(args);
      }
    },
    Start: function() {
      window.App.Log('Starting App');
      window.App.Log(['Browser', BrowserDetect.OS, BrowserDetect.browser, BrowserDetect.version]);
      if (BrowserDetect.browser === 'Explorer' && BrowserDetect.version < 9) {
        return $('#browser-notification').modal('show');
      } else {
        window.AppController = new App.Controllers.App();
        return Backbone.history.start({
          root: window.AppConfig.public_root
        });
      }
    }
  };
}).call(this);

(function() {
  window.App.Log('loading controller...');
  App.Controllers.App = Backbone.Router.extend({
    routes: {
      "": "feed",
      "!/": "feed",
      "!/": "profile"
    },
    initialize: function() {
      window.App.Log(['init controller']);
      window.Member = new App.Models.Member;
      return window.ViewLayout = new App.Views.Layout;
    },
    profile: function() {
      return window.App.Log(['init profile']);
    },
    feed: function() {
      return window.App.Log(['init feed']);
    }
  });
}).call(this);

(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  window.App.Log('loading model...member');
  App.Models.Member = Backbone.Model.extend({
    urlRoot: window.AppConfig.rpc_urlroot,
    defaults: {
      id: 'new',
      has_session: false,
      events: []
    },
    initialize: function() {},
    getInitialData: function() {
      window.App.Log('getInitializeData');
      return this.fetch({});
    },
    getAppFriends: function() {
      return window.App.Log('getAppFriends');
    },
    login: function(response) {
      window.App.Log('member.login');
      FB.api('/me', __bind(function(response) {
        return window.App.Log(['FB Me', response]);
      }, this));
      return window.Member.attributes.has_session = true;
    },
    logout: function() {
      window.App.Log('member.logout');
      return FB.logout(__bind(function(response) {
        $('.topbar').hide();
        this.attributes.has_session = false;
        return window.AppController.navigate("#!/login", true);
      }, this));
    },
    track_event: function(event_id) {
      if (!_.include(this.get("events"), event_id)) {
        this.add_event(event_id);
      } else {
        this.remove_event(event_id);
      }
      return this.save();
    },
    request: function(params) {
      return this.fetch();
    }
  });
  App.Models.Member.prototype.sync = function(method, model, options) {
    return $.ajax({
      url: '/jsonrpc/api/v1/app/',
      type: 'POST',
      contentType: 'application/json',
      data: '{"id": 1, "method": "App.User.getInitialData", "params": []}',
      dataType: 'json',
      success: function(resp, status) {
        return options.success(resp.result);
      }
    });
  };
}).call(this);

(function() {
  window.App.Log('loading view...layout');
  App.Views.Layout = Backbone.View.extend({
    el: $('#matrix'),
    initialize: function() {}
  });
}).call(this);