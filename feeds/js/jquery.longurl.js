var longurlplease = {
  // At the moment clients must maintain a list of services which they will attempt to lengthen short urls for
  shortUrlsPattern : new RegExp("^(http(s?)://(307.to|adjix.com|b23.ru|bacn.me|bit.ly|bloat.me|budurl.com|cli.gs|clipurl.us|cort.as|dFL8.me|digg.com|dwarfurl.com|fb.me|ff.im|fff.to|href.in|idek.net|is.gd|j.mp|kl.am|korta.nu|lin.cr|livesi.de|ln-s.net|loopt.us|lost.in|memurl.com|merky.de|migre.me|moourl.com|nanourl.se|om.ly|ow.ly|peaurl.com|ping.fm|piurl.com|plurl.me|pnt.me|poprl.com|post.ly|rde.me|reallytinyurl.com|redir.ec|retwt.me|rubyurl.com|short.ie|short.to|smallr.com|sn.im|sn.vc|snipr.com|snipurl.com|snurl.com|su.pr|tiny.cc|tinysong.com|tinyurl.com|togoto.us|tr.im|tra.kz|trg.li|twurl.cc|twurl.nl|u.mavrev.com|u.nu|ur1.ca|url.az|url.ie|urlx.ie|w34.us|xrl.us|yep.it|zi.ma|zurl.ws)/[a-zA-Z0-9_-]+)$|((^http(s?)://[a-zA-Z0-9_-]+.notlong.com)|(^http(s?)://[a-zA-Z0-9_-]+.qlnk.net)|(^http(s?)://chilp.it/[?][a-zA-Z0-9_-]+)|(^http(s?)://goo.gl/fb/[a-zA-Z0-9_-]+)|(^http(s?)://trim.li/nk/[a-zA-Z0-9_-]+)|(^http(s?)://url4.eu/[a-zA-Z0-9_-]+))[/]?$"),
  numberOfUrlsPerBatch : 4,
  lengthen : function(options) {
    if (typeof(options) == 'undefined') {
      options = {};
    }
    var makeRequest = function() {
      alert('not sure how to call api');
    };
    if (options.transport !== null) {
      if (options.transport.toLowerCase() == 'air') {
        makeRequest = longurlplease.makeRequestWithAir;
      } else if (options.transport.toLowerCase() == 'flxhr') {
        makeRequest = longurlplease.makeRequestWithFlxhr;
      } else if (options.transport.toLowerCase() == 'jquery') {
        makeRequest = longurlplease.makeRequestWithJQuery;
      }
    }

    var urlToElements = options.urlToElements;
    var toLengthen = options.toLengthen;
    if (toLengthen === null || urlToElements === null) {
      var parent = document;
      if (options.element !== null) {
        parent = options.element;
      }
      urlToElements = {};
      toLengthen = [];
      var els = parent.getElementsByTagName('a');
      for (var elIndex = 0; elIndex < els.length; elIndex++) {
        var el = els[elIndex];
        if (longurlplease.shortUrlsPattern.test(el.href)) {
          toLengthen.push(el.href);
          var listOfElements = urlToElements[el.href];
          if (listOfElements === null) {
            listOfElements = [];
          }
          listOfElements.push(el);
          urlToElements[el.href] = listOfElements;
        }
      }
    }

    var lengthenShortUrl = longurlplease.defaultExpandMethod;

    if (options.lengthenShortUrl !== null) {
      if (typeof options.lengthenShortUrl == 'function') {
        lengthenShortUrl = options.lengthenShortUrl;
      } else if (typeof options.lengthenShortUrl == 'string') {
        if (options.lengthenShortUrl == 'href-only') {
          lengthenShortUrl = longurlplease.hrefOnlyExpandMethod;
        } else if (options.lengthenShortUrl == 'full') {
          lengthenShortUrl = longurlplease.fullExpandMethod;
        } else if (options.lengthenShortUrl == 'text-and-title') {
          lengthenShortUrl = longurlplease.textAndTitleExpandMethod;
        }
      }
    }

    var handleResponseEntry = function(shortUrl, longUrl) {
      var aTags = urlToElements[shortUrl];
      for (var ai = 0; ai < aTags.length; ai++) {
        lengthenShortUrl(aTags[ai], longUrl);
      }
    };
    var subArray, i = 0;
    while (i < toLengthen.length) {
      subArray = toLengthen.slice(i, i + longurlplease.numberOfUrlsPerBatch);
      var paramString = longurlplease.toParamString(subArray);
      makeRequest(paramString, handleResponseEntry);
      i = i + longurlplease.numberOfUrlsPerBatch;
    }
  },
  defaultExpandMethod: function(aTag, longUrl) {
    // You can customize this - my intention here is to alter the visible text to use as much of the long url
    // as possible, but maintain the same number of characters to help keep visual consistancy.
    if (aTag.href == aTag.innerHTML) {
      var linkText = longUrl.replace(/^http(s?):\/\//, '').replace(/^www\./, '');
      aTag.innerHTML = linkText.substring(0, aTag.innerHTML.length - 3) + '...';
    }
    aTag.href = longUrl;
  },
  hrefOnlyExpandMethod : function(aTag, longUrl) {
    aTag.href = longUrl;
  },
  fullExpandMethod : function(aTag, longUrl) {
    aTag.href = longUrl;
    aTag.innerHTML = longUrl;
  },
  textAndTitleExpandMethod : function(aTag, longUrl) {
    var linkText = longUrl.replace(/^http(s?):\/\//, '').replace(/^www\./, '');
    aTag.innerHTML = linkText.substring(0, aTag.innerHTML.length - 3) + '...';
    aTag.title = longUrl;
  },
  toParamString : function(shortUrls) {
    var paramString = "";
    for (var j = 0; j < shortUrls.length; j++) {
      var href = shortUrls[j];
      paramString += "q=";
      paramString += encodeURI(href);
      if (j < shortUrls.length - 1) {
        paramString += '&';
      }
    }
    return paramString;
  },
  apiUrl : function() {
    return (("https:" == document.location.protocol) ? "https" : "http") + "://longurlplease.appspot.com/api/v1.1";
  },
  makeRequestWithAir : function(paramString, callback) {
    var loader = new air.URLLoader();
    loader.addEventListener(air.Event.COMPLETE, function (event) {
      JSON.parse(event.target.data, function (key, val) {
        if (typeof val === 'string' && val !== null) {
          callback(key, val);
        }
      });
    });
    var request = new air.URLRequest(longurlplease.apiUrl() + "?ua=air&" + paramString);
    loader.load(request);
  },
  // made possible by http://flxhr.flensed.com/
  makeRequestWithFlxhr : function(paramString, callback) {
    var flproxy = new flensed.flXHR({ autoUpdatePlayer:true, xmlResponseText:false, instancePooling:true, onreadystatechange:function (XHRobj) {
      if (XHRobj.readyState == 4) {
        JSON.parse(XHRobj.responseText, function (key, val) {
          if (typeof val === 'string' && val !== null) {
            callback(key, val);
          }
        });
      }
    }});
    flproxy.open("GET", longurlplease.apiUrl() + "?ua=flxhr&" + paramString);
    flproxy.send();
  },
  makeRequestWithJQuery : function(paramString, callback) {
    jQuery.getJSON(longurlplease.apiUrl() + "?ua=jquery&" + paramString + "&callback=?",
        function(data) {
          jQuery.each(data, function(key, val) {
            if (val !== null) {
              callback(key, val);
            }
          });
        });
  }
};

if (typeof(jQuery) != 'undefined') {
  jQuery.longurlplease = function(options) {
    jQuery('body').longurlplease(options);
  };
  jQuery.fn.longurlplease = function(options) {
    if (typeof(options) == 'undefined') {
      options = {};
    }
    options.transport = 'jquery';
    var toLengthen = [];
    var urlToElements = {};
    this.find('a').filter(function() {
      return this.href.match(longurlplease.shortUrlsPattern);
    }).each(function() {
      toLengthen.push(this.href);
      var listOfElements = urlToElements[this.href];
      if (typeof(listOfElements) == 'undefined') {
        listOfElements = [];
      }
      listOfElements.push(this);
      urlToElements[this.href] = listOfElements;
    });
    options.toLengthen = toLengthen;
    options.urlToElements = urlToElements;
    longurlplease.lengthen(options);
    return this;
  };
}