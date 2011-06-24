/*
--------------------------------------------------------
incsearch.js - Incremental Search
Version 2.2 (Update 2008/04/02)

Copyright (c) 2006-2008 onozaty (http://www.enjoyxstudy.com)

Released under an MIT-style license.

For details, see the web site:
 http://www.enjoyxstudy.com/javascript/incsearch

--------------------------------------------------------
*/
var clearList = false;
var searchList = false;
var oldGroup = '';
var dispDefault;

// wondowのonloadイベントに追加
var start = function(){
        new IncSearch.ViewSelect(
                'keytext',        // 入力が行われるエレメントのID
                'view_incsearch', // 検索結果を表示するエレメントのID
                inc_search_list,  // 検索対象のリスト
                {dispMax: 50,     // オプション
                startElementText: '<select id="select_idplist" name="select_idplist" size=10 tabindex=6 style="margin-top: 0px;margin-bottom: 0px; width: 100%;" onBlur="clearListArea();">',
                ignoreCase: true,
                initDispNon: true,
                createObjId: 'select_idplist'
                }
        );
};
window.addEventListener ?
        window.addEventListener('load', start, false) :
        window.attachEvent('onload', start);

function setEntityID(){

        var idp_name = document.getElementById('keytext').value;
        var chkFlg = false;

        if (initdisp != idp_name) {
                for (var i = 0, len = inc_search_list.length; i < len; i++) {
                        if (idp_name == inc_search_list[i][2]) {
                                chkFlg = true;
                                document.getElementById('user_idp').value = inc_search_list[i][0];
                                break;
                        }
                }
        }
        return chkFlg;
}

function clearKeyText(){

        document.IdPList.keytext.value = "";
        document.IdPList.keytext.focus();
        searchList = true;
}

function searchKeyText(prmEvent){

        if (initdisp == document.IdPList.keytext.value){
                document.IdPList.keytext.value = "";
        }
	if (prmEvent == 'click'){
        	searchList = true;
	}
}

function clearListArea(){

        clearList = true;
}


/*-----------------------------------------------------*/
/*-- Incremental Search Start -------------------------*/
/*-----------------------------------------------------*/
if (!IncSearch) {
  var IncSearch = {};
}

/*-- Utils --------------------------------------------*/
IncSearch._copyProperties = function(dest, src) {
  for (var property in src) {
    dest[property] = src[property];
  }
  return dest;
};

IncSearch._copyProperties = function(dest, src) {
  for (var property in src) {
    dest[property] = src[property];
  }
  return dest;
};

IncSearch._getElement = function(element) {
  return (typeof element == 'string') ? document.getElementById(element) : element;
};

IncSearch._addEvent = (window.addEventListener ?
  function(element, type, func) {
    element.addEventListener(type, func, false);
  } :
  function(element, type, func) {
    element.attachEvent('on' + type, func);
  });

IncSearch._stopEvent = function(event) {
  if (event.preventDefault) {
    event.preventDefault();
    event.stopPropagation();
  } else {
    event.returnValue = false;
    event.cancelBubble = true;
  }
};

IncSearch._getEventElement = function(event) {
  return event.target || event.srcElement;
};

/*-- IncSearch.ViewBase -------------------------------*/
IncSearch.ViewBase = function() {
  this.initialize.apply(this, arguments);
};
IncSearch.ViewBase.prototype = {
  initialize: function(input, viewArea, searchValues) {
    this.input = IncSearch._getElement(input);
    this.viewArea = IncSearch._getElement(viewArea);
    this.searchValues = searchValues;
    this.checkLoopTimer = null;
    this.oldInput = null;

    this.matchList = null;
    this.setOptions(arguments[3] || {});

    // check loop start
    this.checkLoop();
  },

  // options
  interval: 500,
  delay: 0,
  dispMax: 20,
  initDispNon: false,
  ignoreCase: true,
  highlight: true,
  highClassName: 'high',
  highClassNum: 4,
  //delim: ' ',
  escape: false,
  pagePrevName: 'prev',
  pageNextName: 'next',
  useHotkey: true,
  focusRowClassName: 'focus',
  moveRow: false,
  createObjId: 'select_idplist',

  setOptions: function(options) {

    IncSearch._copyProperties(this, options);

    if (this.useHotkey) {
      var keyevent = (window.opera) ? 'keypress' : 'keydown';
      IncSearch._addEvent(document, keyevent, this._bindEvent(this.hotkey));
    }
  },

  checkLoop: function() {
    var input = this.getInput();
    if (input != initdisp || searchList || clearList){
      if (clearList) {
        clearList = false;
        if (document.activeElement.id != this.createObjId && document.activeElement.id != this.input.id){
           this.clearViewArea(true);
        }
      } else if (this.isChange(input)) {
        this.oldInput = input;
        if (this.delay == 0) {
          this.startSearch(input);
        } else {
          if (this.startSearchTimer) clearTimeout(this.startSearchTimer);
          this.startSearchTimer = setTimeout(this._bind(this.startSearch, input), this.delay);
        }
      }
    }
    if (this.checkLoopTimer) clearTimeout(this.checkLoopTimer);
    this.checkLoopTimer = setTimeout(this._bind(this.checkLoop), this.interval);
  },

  isChange: function(input) {
    if (this.oldInput != "" && this.oldInput != null && input == "" && !clearList) {
      searchList = true;
    }
    return (!this.oldInput || (input.join(this.delim) != this.oldInput.join(this.delim)) || searchList);
  },

  startSearch: function(input) {
    // init
    this.clearViewArea(false);
    if (!this.initDispNon || input.length != 0 || searchList) {
      searchList = false;
      if (dispDefault == undefined || dispDefault == "") {
        this.search(input);
        this.createViewArea(0, this.dispMax, input);
      } else {
        dispDefault = "";
      }
    }
  },

  hotkey: function(event) {
    switch(event.keyCode) {
      case 13:  // Enter
      case 77:  // m (Enter Max OS X)
        if (document.activeElement.id == this.createObjId){
          var target_object = document.getElementById(this.createObjId);
          if (target_object){
            var index = target_object.selectedIndex;
            if (index < 0){
              target_object.selectedIndex = 0;
            }
            this.listClick();
            IncSearch._stopEvent(event);
          }
        }
        break;
      case 37:  // Left
//        alert('left');
//        IncSearch._stopEvent(event);
        break;
      case 38:  // Up
//        alert('up');
        if (document.activeElement.id == this.createObjId){
          var target_object = document.getElementById(this.createObjId);
          if (target_object){
            var index = target_object.selectedIndex;
            if (index == 0){
              document.IdPList.keytext.focus();
              this.clearViewArea(false);
              if (initdisp == document.IdPList.keytext.value){
                document.IdPList.keytext.value = "";
              }
            }
          }
        }
        break;
      case 39:  // Right
//        alert('right');
//        IncSearch._stopEvent(event);
        break;
      case 40:  // Down
//        alert('down');
        if (document.activeElement.id == this.input.id){
          var target_object = document.getElementById(this.createObjId);
          if (target_object){
            target_object.focus();
            var index = target_object.selectedIndex;
            if (index < 0){
              if ((navigator.userAgent.indexOf("Chrome") != -1) ||
                  ((navigator.userAgent.indexOf("Opera") != -1) && (target_object.length == 1))){
                target_object.selectedIndex = 0;
              }
            }
          } else {
            if (initdisp == document.IdPList.keytext.value){
              document.IdPList.keytext.value = "";
            }
            searchList = true;
          }
        }  
        break;
      default:
        break;
    }
  },


  createViewArea: function(start, count, patternList) {
    var elementText = [];
    var end = this.matchList.length;
    if (count != 0 && end > (start + count)) {
      end = start + count;
    }

    for (var i = start; i < end; i++) {
      elementText.push(this.createLineElement(this.matchList[i], patternList, i));
    }
    oldGroup = '';

    if (elementText.length > 0) {
      if (this.startElementText) elementText.unshift(this.startElementText);
      if (this.endElementText) elementText.push(this.endElementText);
      var element = document.createElement('div');
      element.innerHTML = elementText.join('');
      element.style.overflow = "hidden";
      this.viewArea.appendChild(element);
      IncSearch._addEvent(element, 'click', this._bindEvent(this.listClick));

      this.viewArea.style.display = '';
      this.viewArea.scrollTop = 0;
    }
  },

  clearViewArea: function(setInitDisp) {
    this.viewArea.innerHTML = '';
    this.matchList = null;
    this.viewArea.style.display = 'none';
    if (this.input.value == '' && setInitDisp){
      this.input.value = initdisp;
    }
    clearList = false;
  },

  search: function(patternList) {
    patternList = patternList || this.oldInput;

    this.matchList = [];

    for (var i = 0, len = this.searchValues.length; i < len; i++) {
      for (var j = 3, len2 = this.searchValues[i].length; j < len2; j++) {
        if ((this.searchValues[i][j] != undefined) && (this.searchValues[i][j] != null)){
          if (this.isMatch(this.searchValues[i][j], patternList)) {
            this.matchList.push(i);
            break;
          }
        } else {
          break;
        }
      }
    }
    return this.matchList.length;
  },

  createElement: function(value, patternList, tagName, highlight) {

    return ['<', tagName, '>',
            this.createText(value, patternList, highlight),
            '</', tagName, '>'].join('');
  },

  createText: function(value, patternList, highlight) {

    var textList = [];

    if (highlight == null) highlight = this.highlight;

    if (highlight) {

      var first = this.getFirstMatch(value, patternList);

      while (first.listIndex != -1) {
        textList.push(this._escapeHTML(value.substr(0, first.matchIndex)));
        textList.push('<strong class="');
        textList.push(this.highClassName);
        textList.push((first.listIndex % this.highClassNum) + 1);
        textList.push('">');
        textList.push(this._escapeHTML(value.substr(first.matchIndex, patternList[first.listIndex].length)));
        textList.push('</strong>');

        value = value.substr(first.matchIndex + patternList[first.listIndex].length);
        first = this.getFirstMatch(value, patternList);
      }
    }

    return textList.join('');
  },

  matchIndex: function(value, pattern) {

    if (this.ignoreCase) {
      return value.toLowerCase().indexOf(pattern.toLowerCase());
    } else {
      return value.indexOf(pattern);
    }
  },

  getFirstMatch: function(value, patternList) {

    var first = {};
    first.listIndex = -1;
    first.matchIndex = value.length;

    for (var i = 0, len = patternList.length; i < len; i++) {
      var index = this.matchIndex(value, patternList[i]);
      if (index != -1 && index < first.matchIndex) {
        first.listIndex = i;
        first.matchIndex = index;
      }
    }

    return first;
  },

  getInput: function() {

    var value = this.input.value;

    if (!value) {
      return [];
    } else if (this.delim) {
      var list = value.split(this.delim);
      var inputs = [];
      for (var i = 0, len = list.length; i < len; i++) {
        if (list[i]) inputs.push(list[i]);
      }
      return inputs;
    } else {
      return [value];
    }
  },

  listClick: function() {
    var index = document.getElementById(this.createObjId).selectedIndex;
    if (index >= 0){
      var keytext = document.getElementById(this.createObjId).options[index].text;
      this.input.value = keytext.substring(0, keytext.length - 5);
      this.clearViewArea(true);
      this.oldInput = this.getInput();
      this.input.focus();
    }
  },

  // Utils
  _bind: function(func) {
    var self = this;
    var args = Array.prototype.slice.call(arguments, 1);
    return function(){ func.apply(self, args); };
  },
  _bindEvent: function(func) {
    var self = this;
    var args = Array.prototype.slice.call(arguments, 1);
    return function(event){ event = event || window.event; func.apply(self, [event].concat(args)); };
  },
  _escapeHTML: function(value) {
    if (this.escape) {
      return value.replace(/\&/g, '&amp;').replace( /</g, '&lt;').replace(/>/g, '&gt;')
                .replace(/\"/g, '&quot;').replace(/\'/g, '&#39;').replace(/\n|\r\n/g, '<br />');
    } else {
      return value;
    }
  }

}

/*-- IncSearch.ViewSelect -------------------------------*/
IncSearch.ViewSelect =  function() {
  this.initialize.apply(this, arguments);
};
IncSearch._copyProperties(IncSearch.ViewSelect.prototype, IncSearch.ViewBase.prototype);

IncSearch.ViewSelect.prototype.startElementText = '<select>';
IncSearch.ViewSelect.prototype.endElementText = '</select>';
IncSearch.ViewSelect.prototype.isMatch = function(valueArray, patternList) {

  for (var i = 0, len = patternList.length; i < len; i++) {
    if (this.matchIndex(valueArray, patternList[i]) == -1) {
      return false;
    }
  }

  return true;
};

IncSearch.ViewSelect.prototype.createLineElement = function(index, patternList, cnt) {

  var text = [''];

  if (oldGroup != this.searchValues[index][1]){
    oldGroup = this.searchValues[index][1];
    text.push('<optgroup label="');
    text.push(this.searchValues[index][1]);
    text.push('">');
  }

  text.push('<option value="');
  text.push(this.searchValues[index][0]);
  text.push('"');

  text.push('>');
  text.push(this.searchValues[index][2] + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
  text.push('</option>');

  return text.join('');

};


