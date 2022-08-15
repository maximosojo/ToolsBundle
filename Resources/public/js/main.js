
/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

'use strict';

var separatorDecimal = ',';
var separatorMilion = '.';

export function generateUrl(route, parameters, cache) {
    if (parameters == null | parameters == undefined) {
        parameters = {};
    }

    if (!cache) {
        parameters['_dc'] = generateId();
    }
    
    return Routing.generate(route, parameters);
}

function s4() {
    return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
}

export function generateId() {
    var separator = "_";
    return s4() + s4() + separator + s4() + separator + s4() + separator +
            s4()
            + separator + s4() + s4() + s4();
}

export function log(p) {
    console.log(p);
}

export function parse_url(str, component) {
  //       discuss at: http://phpjs.org/functions/parse_url/
  //      original by: Steven Levithan (http://blog.stevenlevithan.com)
  // reimplemented by: Brett Zamir (http://brett-zamir.me)
  //         input by: Lorenzo Pisani
  //         input by: Tony
  //      improved by: Brett Zamir (http://brett-zamir.me)
  //             note: original by http://stevenlevithan.com/demo/parseuri/js/assets/parseuri.js
  //             note: blog post at http://blog.stevenlevithan.com/archives/parseuri
  //             note: demo at http://stevenlevithan.com/demo/parseuri/js/assets/parseuri.js
  //             note: Does not replace invalid characters with '_' as in PHP, nor does it return false with
  //             note: a seriously malformed URL.
  //             note: Besides function name, is essentially the same as parseUri as well as our allowing
  //             note: an extra slash after the scheme/protocol (to allow file:/// as in PHP)
  //        example 1: parse_url('http://username:password@hostname/path?arg=value#anchor');
  //        returns 1: {scheme: 'http', host: 'hostname', user: 'username', pass: 'password', path: '/path', query: 'arg=value', fragment: 'anchor'}
  //        example 2: parse_url('http://en.wikipedia.org/wiki/%22@%22_%28album%29');
  //        returns 2: {scheme: 'http', host: 'en.wikipedia.org', path: '/wiki/%22@%22_%28album%29'}
  //        example 3: parse_url('https://host.domain.tld/a@b.c/folder')
  //        returns 3: {scheme: 'https', host: 'host.domain.tld', path: '/a@b.c/folder'}
  //        example 4: parse_url('https://gooduser:secretpassword@www.example.com/a@b.c/folder?foo=bar');
  //        returns 4: { scheme: 'https', host: 'www.example.com', path: '/a@b.c/folder', query: 'foo=bar', user: 'gooduser', pass: 'secretpassword' }

  // try {
  //   php_js = php_js || {};
  // } catch (e) {
    var php_js = {};
  // }

  var query;
  var ini = (php_js && php_js.ini) || {};
  var mode = (ini['phpjs.parse_url.mode'] && ini['phpjs.parse_url.mode'].local_value) || 'php';
  var key = [
    'source',
    'scheme',
    'authority',
    'userInfo',
    'user',
    'pass',
    'host',
    'port',
    'relative',
    'path',
    'directory',
    'file',
    'query',
    'fragment'
  ];
  var parser = {
    php    : /^(?:([^:\/?#]+):)?(?:\/\/()(?:(?:()(?:([^:@\/]*):?([^:@\/]*))?@)?([^:\/?#]*)(?::(\d*))?))?()(?:(()(?:(?:[^?#\/]*\/)*)()(?:[^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
    strict : /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@\/]*):?([^:@\/]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
    loose  : /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/\/?)?((?:(([^:@\/]*):?([^:@\/]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/ // Added one optional slash to post-scheme to catch file:/// (should restrict this)
  };

  var m = parser[mode].exec(str);
  var uri = {};
  var i = 14;

  while (i--) {
    if (m[i]) {
      uri[key[i]] = m[i];
    }
  }

  if (component) {
    return uri[component.replace('PHP_URL_', '')
      .toLowerCase()];
  }

  if (mode !== 'php') {
    var name = (ini['phpjs.parse_url.queryKey'] &&
      ini['phpjs.parse_url.queryKey'].local_value) || 'queryKey';
    parser = /(?:^|&)([^&=]*)=?([^&]*)/g;
    uri[name] = {};
    query = uri[key[12]] || '';
    query.replace(parser, function($0, $1, $2) {
      if ($1) {
        uri[name][$1] = $2;
      }
    });
  }

  delete uri.source;
  return uri;
}

function numberFormatId(id) {
    $(id).each(function (e) {
        if ($(this).attr("data-mask-money") != "true") {
            $(this).maskMoney({
                "thousands": separatorMilion,
                "decimal": separatorDecimal,
                affixesStay: false,
            });
        }
    });
}

function onlyNumberById(id) {
    $(id).keypress(function (evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;

    });
}

function clearFilters(id,type) {
    var input = $("#"+id);
    if (type == "select"){
      input.select2("val", "");
      var html = input.html();
      input.html("");
      input.html(html);
      input.each(function () {
          $(this).prop('selectedIndex', 0);
          $('option', this).removeAttr('selected');
      });
      input.trigger("change");
    } else if (type == "select2") {
        input.select2("val", "");
        input.html("");
        input.trigger("change");
    } else if (type == "input"){
        input.val("");
        input.val(null);
        input.trigger("change");
    }
}

function clearAllFilters() {
  $(".eraser").click();
}

window.numberFormatId = numberFormatId;
window.onlyNumberById = onlyNumberById;
window.clearFilters = clearFilters;
window.clearAllFilters = clearAllFilters;