(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);throw new Error("Cannot find module '"+o+"'")}var f=n[o]={exports:{}};t[o][0].call(f.exports,function(e){var n=t[o][1][e];return s(n?n:e)},f,f.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";

function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
/* global wpforms_settings */

(function () {
  /**
   * Predefine hint text to display.
   *
   * @since 1.5.6
   * @since 1.6.4 Added a new macros - {remaining}.
   *
   * @param {string} hintText Hint text.
   * @param {number} count    Current count.
   * @param {number} limit    Limit to.
   *
   * @return {string} Predefined hint text.
   */
  function renderHint(hintText, count, limit) {
    return hintText.replace('{count}', count).replace('{limit}', limit).replace('{remaining}', limit - count);
  }

  /**
   * Create HTMLElement hint element with text.
   *
   * @since 1.5.6
   *
   * @param {number|string} formId  Form id.
   * @param {number|string} fieldId Form field id.
   * @param {string}        text    Hint text.
   *
   * @return {Object} HTMLElement hint element with text.
   */
  function createHint(formId, fieldId, text) {
    var hint = document.createElement('div');
    formId = _typeof(formId) === 'object' ? '' : formId;
    fieldId = _typeof(fieldId) === 'object' ? '' : fieldId;
    hint.classList.add('wpforms-field-limit-text');
    hint.id = 'wpforms-field-limit-text-' + formId + '-' + fieldId;
    hint.setAttribute('aria-live', 'polite');
    hint.textContent = text;
    return hint;
  }

  /**
   * Keyup/Keydown event higher order function for characters limit.
   *
   * @since 1.5.6
   *
   * @param {Object} hint  HTMLElement hint element.
   * @param {number} limit Max allowed number of characters.
   *
   * @return {Function} Handler function.
   */
  function checkCharacters(hint, limit) {
    // noinspection JSUnusedLocalSymbols
    return function (e) {
      // eslint-disable-line no-unused-vars
      hint.textContent = renderHint(window.wpforms_settings.val_limit_characters, this.value.length, limit);
    };
  }

  /**
   * Count words in the string.
   *
   * @since 1.6.2
   *
   * @param {string} string String value.
   *
   * @return {number} Words count.
   */
  function countWords(string) {
    if (typeof string !== 'string') {
      return 0;
    }
    if (!string.length) {
      return 0;
    }
    [/([A-Z]+),([A-Z]+)/gi, /([0-9]+),([A-Z]+)/gi, /([A-Z]+),([0-9]+)/gi].forEach(function (pattern) {
      string = string.replace(pattern, '$1, $2');
    });
    return string.split(/\s+/).length;
  }

  /**
   * Keyup/Keydown event higher order function for words limit.
   *
   * @since 1.5.6
   *
   * @param {Object} hint  HTMLElement hint element.
   * @param {number} limit Max allowed number of characters.
   *
   * @return {Function} Handler function.
   */
  function checkWords(hint, limit) {
    return function (e) {
      var value = this.value.trim(),
        words = countWords(value);
      hint.textContent = renderHint(window.wpforms_settings.val_limit_words, words, limit);

      // We should prevent the keys: Enter, Space, Comma.
      if ([13, 32, 188].indexOf(e.keyCode) > -1 && words >= limit) {
        e.preventDefault();
      }
    };
  }

  /**
   * Get passed text from the clipboard.
   *
   * @since 1.5.6
   *
   * @param {ClipboardEvent} e Clipboard event.
   *
   * @return {string} Text from clipboard.
   */
  function getPastedText(e) {
    if (window.clipboardData && window.clipboardData.getData) {
      // IE
      return window.clipboardData.getData('Text');
    } else if (e.clipboardData && e.clipboardData.getData) {
      return e.clipboardData.getData('text/plain');
    }
    return '';
  }

  /**
   * Paste event higher order function for character limit.
   *
   * @since 1.6.7.1
   *
   * @param {number} limit Max allowed number of characters.
   *
   * @return {Function} Event handler.
   */
  function pasteText(limit) {
    return function (e) {
      e.preventDefault();
      var pastedText = getPastedText(e),
        newPosition = this.selectionStart + pastedText.length,
        newText = this.value.substring(0, this.selectionStart) + pastedText + this.value.substring(this.selectionStart);
      this.value = newText.substring(0, limit);
      this.setSelectionRange(newPosition, newPosition);
    };
  }

  /**
   * Limit string length to a certain number of words, preserving line breaks.
   *
   * @since 1.6.8
   *
   * @param {string} text  Text.
   * @param {number} limit Max allowed number of words.
   *
   * @return {string} Text with the limited number of words.
   */
  function limitWords(text, limit) {
    var result = '';

    // Regular expression pattern: match any space character.
    var regEx = /\s+/g;

    // Store separators for further join.
    var separators = text.trim().match(regEx) || [];

    // Split the new text by regular expression.
    var newTextArray = text.split(regEx);

    // Limit the number of words.
    newTextArray.splice(limit, newTextArray.length);

    // Join the words together using stored separators.
    for (var i = 0; i < newTextArray.length; i++) {
      result += newTextArray[i] + (separators[i] || '');
    }
    return result.trim();
  }

  /**
   * Paste event higher order function for words limit.
   *
   * @since 1.5.6
   *
   * @param {number} limit Max allowed number of words.
   *
   * @return {Function} Event handler.
   */
  function pasteWords(limit) {
    return function (e) {
      e.preventDefault();
      var pastedText = getPastedText(e),
        newPosition = this.selectionStart + pastedText.length,
        newText = this.value.substring(0, this.selectionStart) + pastedText + this.value.substring(this.selectionStart);
      this.value = limitWords(newText, limit);
      this.setSelectionRange(newPosition, newPosition);
    };
  }

  /**
   * Array.from polyfill.
   *
   * @since 1.5.6
   *
   * @param {Object} el Iterator.
   *
   * @return {Object} Array.
   */
  function arrFrom(el) {
    return [].slice.call(el);
  }

  /**
   * Remove existing hint.
   *
   * @since 1.9.5.2
   *
   * @param {Object} element Element.
   */
  var removeExistingHint = function removeExistingHint(element) {
    var existingHint = element.parentNode.querySelector('.wpforms-field-limit-text');
    if (existingHint) {
      existingHint.remove();
    }
  };

  /**
   * Public functions and properties.
   *
   * @since 1.8.9
   *
   * @type {Object}
   */
  var app = {
    /**
     * Init text limit hint.
     *
     * @since 1.8.9
     *
     * @param {string} context Context selector.
     */
    initHint: function initHint(context) {
      arrFrom(document.querySelectorAll(context + ' .wpforms-limit-characters-enabled')).map(function (e) {
        // eslint-disable-line array-callback-return
        var limit = parseInt(e.dataset.textLimit, 10) || 0;
        e.value = e.value.slice(0, limit);
        var hint = createHint(e.dataset.formId, e.dataset.fieldId, renderHint(wpforms_settings.val_limit_characters, e.value.length, limit));
        var fn = checkCharacters(hint, limit);
        removeExistingHint(e);
        e.parentNode.appendChild(hint);
        e.addEventListener('keydown', fn);
        e.addEventListener('keyup', fn);
        e.addEventListener('paste', pasteText(limit));
      });
      arrFrom(document.querySelectorAll(context + ' .wpforms-limit-words-enabled')).map(function (e) {
        // eslint-disable-line array-callback-return
        var limit = parseInt(e.dataset.textLimit, 10) || 0;
        e.value = limitWords(e.value, limit);
        var hint = createHint(e.dataset.formId, e.dataset.fieldId, renderHint(wpforms_settings.val_limit_words, countWords(e.value.trim()), limit));
        var fn = checkWords(hint, limit);
        removeExistingHint(e);
        e.parentNode.appendChild(hint);
        e.addEventListener('keydown', fn);
        e.addEventListener('keyup', fn);
        e.addEventListener('paste', pasteWords(limit));
      });
    }
  };

  /**
   * DOMContentLoaded handler.
   *
   * @since 1.5.6
   */
  function ready() {
    // Expose to the world.
    window.WPFormsTextLimit = app;
    app.initHint('body');
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', ready);
  } else {
    ready();
  }
})();
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6WyJyZW5kZXJIaW50IiwiaGludFRleHQiLCJjb3VudCIsImxpbWl0IiwicmVwbGFjZSIsImNyZWF0ZUhpbnQiLCJmb3JtSWQiLCJmaWVsZElkIiwidGV4dCIsImhpbnQiLCJkb2N1bWVudCIsImNyZWF0ZUVsZW1lbnQiLCJfdHlwZW9mIiwiY2xhc3NMaXN0IiwiYWRkIiwiaWQiLCJzZXRBdHRyaWJ1dGUiLCJ0ZXh0Q29udGVudCIsImNoZWNrQ2hhcmFjdGVycyIsImUiLCJ3aW5kb3ciLCJ3cGZvcm1zX3NldHRpbmdzIiwidmFsX2xpbWl0X2NoYXJhY3RlcnMiLCJ2YWx1ZSIsImxlbmd0aCIsImNvdW50V29yZHMiLCJzdHJpbmciLCJmb3JFYWNoIiwicGF0dGVybiIsInNwbGl0IiwiY2hlY2tXb3JkcyIsInRyaW0iLCJ3b3JkcyIsInZhbF9saW1pdF93b3JkcyIsImluZGV4T2YiLCJrZXlDb2RlIiwicHJldmVudERlZmF1bHQiLCJnZXRQYXN0ZWRUZXh0IiwiY2xpcGJvYXJkRGF0YSIsImdldERhdGEiLCJwYXN0ZVRleHQiLCJwYXN0ZWRUZXh0IiwibmV3UG9zaXRpb24iLCJzZWxlY3Rpb25TdGFydCIsIm5ld1RleHQiLCJzdWJzdHJpbmciLCJzZXRTZWxlY3Rpb25SYW5nZSIsImxpbWl0V29yZHMiLCJyZXN1bHQiLCJyZWdFeCIsInNlcGFyYXRvcnMiLCJtYXRjaCIsIm5ld1RleHRBcnJheSIsInNwbGljZSIsImkiLCJwYXN0ZVdvcmRzIiwiYXJyRnJvbSIsImVsIiwic2xpY2UiLCJjYWxsIiwicmVtb3ZlRXhpc3RpbmdIaW50IiwiZWxlbWVudCIsImV4aXN0aW5nSGludCIsInBhcmVudE5vZGUiLCJxdWVyeVNlbGVjdG9yIiwicmVtb3ZlIiwiYXBwIiwiaW5pdEhpbnQiLCJjb250ZXh0IiwicXVlcnlTZWxlY3RvckFsbCIsIm1hcCIsInBhcnNlSW50IiwiZGF0YXNldCIsInRleHRMaW1pdCIsImZuIiwiYXBwZW5kQ2hpbGQiLCJhZGRFdmVudExpc3RlbmVyIiwicmVhZHkiLCJXUEZvcm1zVGV4dExpbWl0IiwicmVhZHlTdGF0ZSJdLCJzb3VyY2VzIjpbImZha2VfZDJmZGZhZGUuanMiXSwic291cmNlc0NvbnRlbnQiOlsiLyogZ2xvYmFsIHdwZm9ybXNfc2V0dGluZ3MgKi9cblxuKCBmdW5jdGlvbigpIHtcblx0LyoqXG5cdCAqIFByZWRlZmluZSBoaW50IHRleHQgdG8gZGlzcGxheS5cblx0ICpcblx0ICogQHNpbmNlIDEuNS42XG5cdCAqIEBzaW5jZSAxLjYuNCBBZGRlZCBhIG5ldyBtYWNyb3MgLSB7cmVtYWluaW5nfS5cblx0ICpcblx0ICogQHBhcmFtIHtzdHJpbmd9IGhpbnRUZXh0IEhpbnQgdGV4dC5cblx0ICogQHBhcmFtIHtudW1iZXJ9IGNvdW50ICAgIEN1cnJlbnQgY291bnQuXG5cdCAqIEBwYXJhbSB7bnVtYmVyfSBsaW1pdCAgICBMaW1pdCB0by5cblx0ICpcblx0ICogQHJldHVybiB7c3RyaW5nfSBQcmVkZWZpbmVkIGhpbnQgdGV4dC5cblx0ICovXG5cdGZ1bmN0aW9uIHJlbmRlckhpbnQoIGhpbnRUZXh0LCBjb3VudCwgbGltaXQgKSB7XG5cdFx0cmV0dXJuIGhpbnRUZXh0LnJlcGxhY2UoICd7Y291bnR9JywgY291bnQgKS5yZXBsYWNlKCAne2xpbWl0fScsIGxpbWl0ICkucmVwbGFjZSggJ3tyZW1haW5pbmd9JywgbGltaXQgLSBjb3VudCApO1xuXHR9XG5cblx0LyoqXG5cdCAqIENyZWF0ZSBIVE1MRWxlbWVudCBoaW50IGVsZW1lbnQgd2l0aCB0ZXh0LlxuXHQgKlxuXHQgKiBAc2luY2UgMS41LjZcblx0ICpcblx0ICogQHBhcmFtIHtudW1iZXJ8c3RyaW5nfSBmb3JtSWQgIEZvcm0gaWQuXG5cdCAqIEBwYXJhbSB7bnVtYmVyfHN0cmluZ30gZmllbGRJZCBGb3JtIGZpZWxkIGlkLlxuXHQgKiBAcGFyYW0ge3N0cmluZ30gICAgICAgIHRleHQgICAgSGludCB0ZXh0LlxuXHQgKlxuXHQgKiBAcmV0dXJuIHtPYmplY3R9IEhUTUxFbGVtZW50IGhpbnQgZWxlbWVudCB3aXRoIHRleHQuXG5cdCAqL1xuXHRmdW5jdGlvbiBjcmVhdGVIaW50KCBmb3JtSWQsIGZpZWxkSWQsIHRleHQgKSB7XG5cdFx0Y29uc3QgaGludCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoICdkaXYnICk7XG5cblx0XHRmb3JtSWQgPSB0eXBlb2YgZm9ybUlkID09PSAnb2JqZWN0JyA/ICcnIDogZm9ybUlkO1xuXHRcdGZpZWxkSWQgPSB0eXBlb2YgZmllbGRJZCA9PT0gJ29iamVjdCcgPyAnJyA6IGZpZWxkSWQ7XG5cblx0XHRoaW50LmNsYXNzTGlzdC5hZGQoICd3cGZvcm1zLWZpZWxkLWxpbWl0LXRleHQnICk7XG5cdFx0aGludC5pZCA9ICd3cGZvcm1zLWZpZWxkLWxpbWl0LXRleHQtJyArIGZvcm1JZCArICctJyArIGZpZWxkSWQ7XG5cdFx0aGludC5zZXRBdHRyaWJ1dGUoICdhcmlhLWxpdmUnLCAncG9saXRlJyApO1xuXHRcdGhpbnQudGV4dENvbnRlbnQgPSB0ZXh0O1xuXG5cdFx0cmV0dXJuIGhpbnQ7XG5cdH1cblxuXHQvKipcblx0ICogS2V5dXAvS2V5ZG93biBldmVudCBoaWdoZXIgb3JkZXIgZnVuY3Rpb24gZm9yIGNoYXJhY3RlcnMgbGltaXQuXG5cdCAqXG5cdCAqIEBzaW5jZSAxLjUuNlxuXHQgKlxuXHQgKiBAcGFyYW0ge09iamVjdH0gaGludCAgSFRNTEVsZW1lbnQgaGludCBlbGVtZW50LlxuXHQgKiBAcGFyYW0ge251bWJlcn0gbGltaXQgTWF4IGFsbG93ZWQgbnVtYmVyIG9mIGNoYXJhY3RlcnMuXG5cdCAqXG5cdCAqIEByZXR1cm4ge0Z1bmN0aW9ufSBIYW5kbGVyIGZ1bmN0aW9uLlxuXHQgKi9cblx0ZnVuY3Rpb24gY2hlY2tDaGFyYWN0ZXJzKCBoaW50LCBsaW1pdCApIHtcblx0XHQvLyBub2luc3BlY3Rpb24gSlNVbnVzZWRMb2NhbFN5bWJvbHNcblx0XHRyZXR1cm4gZnVuY3Rpb24oIGUgKSB7IC8vIGVzbGludC1kaXNhYmxlLWxpbmUgbm8tdW51c2VkLXZhcnNcblx0XHRcdGhpbnQudGV4dENvbnRlbnQgPSByZW5kZXJIaW50KFxuXHRcdFx0XHR3aW5kb3cud3Bmb3Jtc19zZXR0aW5ncy52YWxfbGltaXRfY2hhcmFjdGVycyxcblx0XHRcdFx0dGhpcy52YWx1ZS5sZW5ndGgsXG5cdFx0XHRcdGxpbWl0XG5cdFx0XHQpO1xuXHRcdH07XG5cdH1cblxuXHQvKipcblx0ICogQ291bnQgd29yZHMgaW4gdGhlIHN0cmluZy5cblx0ICpcblx0ICogQHNpbmNlIDEuNi4yXG5cdCAqXG5cdCAqIEBwYXJhbSB7c3RyaW5nfSBzdHJpbmcgU3RyaW5nIHZhbHVlLlxuXHQgKlxuXHQgKiBAcmV0dXJuIHtudW1iZXJ9IFdvcmRzIGNvdW50LlxuXHQgKi9cblx0ZnVuY3Rpb24gY291bnRXb3Jkcyggc3RyaW5nICkge1xuXHRcdGlmICggdHlwZW9mIHN0cmluZyAhPT0gJ3N0cmluZycgKSB7XG5cdFx0XHRyZXR1cm4gMDtcblx0XHR9XG5cblx0XHRpZiAoICEgc3RyaW5nLmxlbmd0aCApIHtcblx0XHRcdHJldHVybiAwO1xuXHRcdH1cblxuXHRcdFtcblx0XHRcdC8oW0EtWl0rKSwoW0EtWl0rKS9naSxcblx0XHRcdC8oWzAtOV0rKSwoW0EtWl0rKS9naSxcblx0XHRcdC8oW0EtWl0rKSwoWzAtOV0rKS9naSxcblx0XHRdLmZvckVhY2goIGZ1bmN0aW9uKCBwYXR0ZXJuICkge1xuXHRcdFx0c3RyaW5nID0gc3RyaW5nLnJlcGxhY2UoIHBhdHRlcm4sICckMSwgJDInICk7XG5cdFx0fSApO1xuXG5cdFx0cmV0dXJuIHN0cmluZy5zcGxpdCggL1xccysvICkubGVuZ3RoO1xuXHR9XG5cblx0LyoqXG5cdCAqIEtleXVwL0tleWRvd24gZXZlbnQgaGlnaGVyIG9yZGVyIGZ1bmN0aW9uIGZvciB3b3JkcyBsaW1pdC5cblx0ICpcblx0ICogQHNpbmNlIDEuNS42XG5cdCAqXG5cdCAqIEBwYXJhbSB7T2JqZWN0fSBoaW50ICBIVE1MRWxlbWVudCBoaW50IGVsZW1lbnQuXG5cdCAqIEBwYXJhbSB7bnVtYmVyfSBsaW1pdCBNYXggYWxsb3dlZCBudW1iZXIgb2YgY2hhcmFjdGVycy5cblx0ICpcblx0ICogQHJldHVybiB7RnVuY3Rpb259IEhhbmRsZXIgZnVuY3Rpb24uXG5cdCAqL1xuXHRmdW5jdGlvbiBjaGVja1dvcmRzKCBoaW50LCBsaW1pdCApIHtcblx0XHRyZXR1cm4gZnVuY3Rpb24oIGUgKSB7XG5cdFx0XHRjb25zdCB2YWx1ZSA9IHRoaXMudmFsdWUudHJpbSgpLFxuXHRcdFx0XHR3b3JkcyA9IGNvdW50V29yZHMoIHZhbHVlICk7XG5cblx0XHRcdGhpbnQudGV4dENvbnRlbnQgPSByZW5kZXJIaW50KFxuXHRcdFx0XHR3aW5kb3cud3Bmb3Jtc19zZXR0aW5ncy52YWxfbGltaXRfd29yZHMsXG5cdFx0XHRcdHdvcmRzLFxuXHRcdFx0XHRsaW1pdFxuXHRcdFx0KTtcblxuXHRcdFx0Ly8gV2Ugc2hvdWxkIHByZXZlbnQgdGhlIGtleXM6IEVudGVyLCBTcGFjZSwgQ29tbWEuXG5cdFx0XHRpZiAoIFsgMTMsIDMyLCAxODggXS5pbmRleE9mKCBlLmtleUNvZGUgKSA+IC0xICYmIHdvcmRzID49IGxpbWl0ICkge1xuXHRcdFx0XHRlLnByZXZlbnREZWZhdWx0KCk7XG5cdFx0XHR9XG5cdFx0fTtcblx0fVxuXG5cdC8qKlxuXHQgKiBHZXQgcGFzc2VkIHRleHQgZnJvbSB0aGUgY2xpcGJvYXJkLlxuXHQgKlxuXHQgKiBAc2luY2UgMS41LjZcblx0ICpcblx0ICogQHBhcmFtIHtDbGlwYm9hcmRFdmVudH0gZSBDbGlwYm9hcmQgZXZlbnQuXG5cdCAqXG5cdCAqIEByZXR1cm4ge3N0cmluZ30gVGV4dCBmcm9tIGNsaXBib2FyZC5cblx0ICovXG5cdGZ1bmN0aW9uIGdldFBhc3RlZFRleHQoIGUgKSB7XG5cdFx0aWYgKCB3aW5kb3cuY2xpcGJvYXJkRGF0YSAmJiB3aW5kb3cuY2xpcGJvYXJkRGF0YS5nZXREYXRhICkgeyAvLyBJRVxuXHRcdFx0cmV0dXJuIHdpbmRvdy5jbGlwYm9hcmREYXRhLmdldERhdGEoICdUZXh0JyApO1xuXHRcdH0gZWxzZSBpZiAoIGUuY2xpcGJvYXJkRGF0YSAmJiBlLmNsaXBib2FyZERhdGEuZ2V0RGF0YSApIHtcblx0XHRcdHJldHVybiBlLmNsaXBib2FyZERhdGEuZ2V0RGF0YSggJ3RleHQvcGxhaW4nICk7XG5cdFx0fVxuXG5cdFx0cmV0dXJuICcnO1xuXHR9XG5cblx0LyoqXG5cdCAqIFBhc3RlIGV2ZW50IGhpZ2hlciBvcmRlciBmdW5jdGlvbiBmb3IgY2hhcmFjdGVyIGxpbWl0LlxuXHQgKlxuXHQgKiBAc2luY2UgMS42LjcuMVxuXHQgKlxuXHQgKiBAcGFyYW0ge251bWJlcn0gbGltaXQgTWF4IGFsbG93ZWQgbnVtYmVyIG9mIGNoYXJhY3RlcnMuXG5cdCAqXG5cdCAqIEByZXR1cm4ge0Z1bmN0aW9ufSBFdmVudCBoYW5kbGVyLlxuXHQgKi9cblx0ZnVuY3Rpb24gcGFzdGVUZXh0KCBsaW1pdCApIHtcblx0XHRyZXR1cm4gZnVuY3Rpb24oIGUgKSB7XG5cdFx0XHRlLnByZXZlbnREZWZhdWx0KCk7XG5cblx0XHRcdGNvbnN0IHBhc3RlZFRleHQgPSBnZXRQYXN0ZWRUZXh0KCBlICksXG5cdFx0XHRcdG5ld1Bvc2l0aW9uID0gdGhpcy5zZWxlY3Rpb25TdGFydCArIHBhc3RlZFRleHQubGVuZ3RoLFxuXHRcdFx0XHRuZXdUZXh0ID0gdGhpcy52YWx1ZS5zdWJzdHJpbmcoIDAsIHRoaXMuc2VsZWN0aW9uU3RhcnQgKSArIHBhc3RlZFRleHQgKyB0aGlzLnZhbHVlLnN1YnN0cmluZyggdGhpcy5zZWxlY3Rpb25TdGFydCApO1xuXG5cdFx0XHR0aGlzLnZhbHVlID0gbmV3VGV4dC5zdWJzdHJpbmcoIDAsIGxpbWl0ICk7XG5cdFx0XHR0aGlzLnNldFNlbGVjdGlvblJhbmdlKCBuZXdQb3NpdGlvbiwgbmV3UG9zaXRpb24gKTtcblx0XHR9O1xuXHR9XG5cblx0LyoqXG5cdCAqIExpbWl0IHN0cmluZyBsZW5ndGggdG8gYSBjZXJ0YWluIG51bWJlciBvZiB3b3JkcywgcHJlc2VydmluZyBsaW5lIGJyZWFrcy5cblx0ICpcblx0ICogQHNpbmNlIDEuNi44XG5cdCAqXG5cdCAqIEBwYXJhbSB7c3RyaW5nfSB0ZXh0ICBUZXh0LlxuXHQgKiBAcGFyYW0ge251bWJlcn0gbGltaXQgTWF4IGFsbG93ZWQgbnVtYmVyIG9mIHdvcmRzLlxuXHQgKlxuXHQgKiBAcmV0dXJuIHtzdHJpbmd9IFRleHQgd2l0aCB0aGUgbGltaXRlZCBudW1iZXIgb2Ygd29yZHMuXG5cdCAqL1xuXHRmdW5jdGlvbiBsaW1pdFdvcmRzKCB0ZXh0LCBsaW1pdCApIHtcblx0XHRsZXQgcmVzdWx0ID0gJyc7XG5cblx0XHQvLyBSZWd1bGFyIGV4cHJlc3Npb24gcGF0dGVybjogbWF0Y2ggYW55IHNwYWNlIGNoYXJhY3Rlci5cblx0XHRjb25zdCByZWdFeCA9IC9cXHMrL2c7XG5cblx0XHQvLyBTdG9yZSBzZXBhcmF0b3JzIGZvciBmdXJ0aGVyIGpvaW4uXG5cdFx0Y29uc3Qgc2VwYXJhdG9ycyA9IHRleHQudHJpbSgpLm1hdGNoKCByZWdFeCApIHx8IFtdO1xuXG5cdFx0Ly8gU3BsaXQgdGhlIG5ldyB0ZXh0IGJ5IHJlZ3VsYXIgZXhwcmVzc2lvbi5cblx0XHRjb25zdCBuZXdUZXh0QXJyYXkgPSB0ZXh0LnNwbGl0KCByZWdFeCApO1xuXG5cdFx0Ly8gTGltaXQgdGhlIG51bWJlciBvZiB3b3Jkcy5cblx0XHRuZXdUZXh0QXJyYXkuc3BsaWNlKCBsaW1pdCwgbmV3VGV4dEFycmF5Lmxlbmd0aCApO1xuXG5cdFx0Ly8gSm9pbiB0aGUgd29yZHMgdG9nZXRoZXIgdXNpbmcgc3RvcmVkIHNlcGFyYXRvcnMuXG5cdFx0Zm9yICggbGV0IGkgPSAwOyBpIDwgbmV3VGV4dEFycmF5Lmxlbmd0aDsgaSsrICkge1xuXHRcdFx0cmVzdWx0ICs9IG5ld1RleHRBcnJheVsgaSBdICsgKCBzZXBhcmF0b3JzWyBpIF0gfHwgJycgKTtcblx0XHR9XG5cblx0XHRyZXR1cm4gcmVzdWx0LnRyaW0oKTtcblx0fVxuXG5cdC8qKlxuXHQgKiBQYXN0ZSBldmVudCBoaWdoZXIgb3JkZXIgZnVuY3Rpb24gZm9yIHdvcmRzIGxpbWl0LlxuXHQgKlxuXHQgKiBAc2luY2UgMS41LjZcblx0ICpcblx0ICogQHBhcmFtIHtudW1iZXJ9IGxpbWl0IE1heCBhbGxvd2VkIG51bWJlciBvZiB3b3Jkcy5cblx0ICpcblx0ICogQHJldHVybiB7RnVuY3Rpb259IEV2ZW50IGhhbmRsZXIuXG5cdCAqL1xuXHRmdW5jdGlvbiBwYXN0ZVdvcmRzKCBsaW1pdCApIHtcblx0XHRyZXR1cm4gZnVuY3Rpb24oIGUgKSB7XG5cdFx0XHRlLnByZXZlbnREZWZhdWx0KCk7XG5cblx0XHRcdGNvbnN0IHBhc3RlZFRleHQgPSBnZXRQYXN0ZWRUZXh0KCBlICksXG5cdFx0XHRcdG5ld1Bvc2l0aW9uID0gdGhpcy5zZWxlY3Rpb25TdGFydCArIHBhc3RlZFRleHQubGVuZ3RoLFxuXHRcdFx0XHRuZXdUZXh0ID0gdGhpcy52YWx1ZS5zdWJzdHJpbmcoIDAsIHRoaXMuc2VsZWN0aW9uU3RhcnQgKSArIHBhc3RlZFRleHQgKyB0aGlzLnZhbHVlLnN1YnN0cmluZyggdGhpcy5zZWxlY3Rpb25TdGFydCApO1xuXG5cdFx0XHR0aGlzLnZhbHVlID0gbGltaXRXb3JkcyggbmV3VGV4dCwgbGltaXQgKTtcblx0XHRcdHRoaXMuc2V0U2VsZWN0aW9uUmFuZ2UoIG5ld1Bvc2l0aW9uLCBuZXdQb3NpdGlvbiApO1xuXHRcdH07XG5cdH1cblxuXHQvKipcblx0ICogQXJyYXkuZnJvbSBwb2x5ZmlsbC5cblx0ICpcblx0ICogQHNpbmNlIDEuNS42XG5cdCAqXG5cdCAqIEBwYXJhbSB7T2JqZWN0fSBlbCBJdGVyYXRvci5cblx0ICpcblx0ICogQHJldHVybiB7T2JqZWN0fSBBcnJheS5cblx0ICovXG5cdGZ1bmN0aW9uIGFyckZyb20oIGVsICkge1xuXHRcdHJldHVybiBbXS5zbGljZS5jYWxsKCBlbCApO1xuXHR9XG5cblx0LyoqXG5cdCAqIFJlbW92ZSBleGlzdGluZyBoaW50LlxuXHQgKlxuXHQgKiBAc2luY2Uge1ZFUlNJT059XG5cdCAqXG5cdCAqIEBwYXJhbSB7T2JqZWN0fSBlbGVtZW50IEVsZW1lbnQuXG5cdCAqL1xuXHRjb25zdCByZW1vdmVFeGlzdGluZ0hpbnQgPSAoIGVsZW1lbnQgKSA9PiB7XG5cdFx0Y29uc3QgZXhpc3RpbmdIaW50ID0gZWxlbWVudC5wYXJlbnROb2RlLnF1ZXJ5U2VsZWN0b3IoICcud3Bmb3Jtcy1maWVsZC1saW1pdC10ZXh0JyApO1xuXHRcdGlmICggZXhpc3RpbmdIaW50ICkge1xuXHRcdFx0ZXhpc3RpbmdIaW50LnJlbW92ZSgpO1xuXHRcdH1cblx0fTtcblxuXHQvKipcblx0ICogUHVibGljIGZ1bmN0aW9ucyBhbmQgcHJvcGVydGllcy5cblx0ICpcblx0ICogQHNpbmNlIDEuOC45XG5cdCAqXG5cdCAqIEB0eXBlIHtPYmplY3R9XG5cdCAqL1xuXHRjb25zdCBhcHAgPSB7XG5cdFx0LyoqXG5cdFx0ICogSW5pdCB0ZXh0IGxpbWl0IGhpbnQuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS44Ljlcblx0XHQgKlxuXHRcdCAqIEBwYXJhbSB7c3RyaW5nfSBjb250ZXh0IENvbnRleHQgc2VsZWN0b3IuXG5cdFx0ICovXG5cdFx0aW5pdEhpbnQoIGNvbnRleHQgKSB7XG5cdFx0XHRhcnJGcm9tKCBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCBjb250ZXh0ICsgJyAud3Bmb3Jtcy1saW1pdC1jaGFyYWN0ZXJzLWVuYWJsZWQnICkgKVxuXHRcdFx0XHQubWFwKFxuXHRcdFx0XHRcdGZ1bmN0aW9uKCBlICkgeyAvLyBlc2xpbnQtZGlzYWJsZS1saW5lIGFycmF5LWNhbGxiYWNrLXJldHVyblxuXHRcdFx0XHRcdFx0Y29uc3QgbGltaXQgPSBwYXJzZUludCggZS5kYXRhc2V0LnRleHRMaW1pdCwgMTAgKSB8fCAwO1xuXG5cdFx0XHRcdFx0XHRlLnZhbHVlID0gZS52YWx1ZS5zbGljZSggMCwgbGltaXQgKTtcblxuXHRcdFx0XHRcdFx0Y29uc3QgaGludCA9IGNyZWF0ZUhpbnQoXG5cdFx0XHRcdFx0XHRcdGUuZGF0YXNldC5mb3JtSWQsXG5cdFx0XHRcdFx0XHRcdGUuZGF0YXNldC5maWVsZElkLFxuXHRcdFx0XHRcdFx0XHRyZW5kZXJIaW50KFxuXHRcdFx0XHRcdFx0XHRcdHdwZm9ybXNfc2V0dGluZ3MudmFsX2xpbWl0X2NoYXJhY3RlcnMsXG5cdFx0XHRcdFx0XHRcdFx0ZS52YWx1ZS5sZW5ndGgsXG5cdFx0XHRcdFx0XHRcdFx0bGltaXRcblx0XHRcdFx0XHRcdFx0KVxuXHRcdFx0XHRcdFx0KTtcblxuXHRcdFx0XHRcdFx0Y29uc3QgZm4gPSBjaGVja0NoYXJhY3RlcnMoIGhpbnQsIGxpbWl0ICk7XG5cblx0XHRcdFx0XHRcdHJlbW92ZUV4aXN0aW5nSGludCggZSApO1xuXG5cdFx0XHRcdFx0XHRlLnBhcmVudE5vZGUuYXBwZW5kQ2hpbGQoIGhpbnQgKTtcblx0XHRcdFx0XHRcdGUuYWRkRXZlbnRMaXN0ZW5lciggJ2tleWRvd24nLCBmbiApO1xuXHRcdFx0XHRcdFx0ZS5hZGRFdmVudExpc3RlbmVyKCAna2V5dXAnLCBmbiApO1xuXHRcdFx0XHRcdFx0ZS5hZGRFdmVudExpc3RlbmVyKCAncGFzdGUnLCBwYXN0ZVRleHQoIGxpbWl0ICkgKTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdCk7XG5cblx0XHRcdGFyckZyb20oIGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoIGNvbnRleHQgKyAnIC53cGZvcm1zLWxpbWl0LXdvcmRzLWVuYWJsZWQnICkgKVxuXHRcdFx0XHQubWFwKFxuXHRcdFx0XHRcdGZ1bmN0aW9uKCBlICkgeyAvLyBlc2xpbnQtZGlzYWJsZS1saW5lIGFycmF5LWNhbGxiYWNrLXJldHVyblxuXHRcdFx0XHRcdFx0Y29uc3QgbGltaXQgPSBwYXJzZUludCggZS5kYXRhc2V0LnRleHRMaW1pdCwgMTAgKSB8fCAwO1xuXG5cdFx0XHRcdFx0XHRlLnZhbHVlID0gbGltaXRXb3JkcyggZS52YWx1ZSwgbGltaXQgKTtcblxuXHRcdFx0XHRcdFx0Y29uc3QgaGludCA9IGNyZWF0ZUhpbnQoXG5cdFx0XHRcdFx0XHRcdGUuZGF0YXNldC5mb3JtSWQsXG5cdFx0XHRcdFx0XHRcdGUuZGF0YXNldC5maWVsZElkLFxuXHRcdFx0XHRcdFx0XHRyZW5kZXJIaW50KFxuXHRcdFx0XHRcdFx0XHRcdHdwZm9ybXNfc2V0dGluZ3MudmFsX2xpbWl0X3dvcmRzLFxuXHRcdFx0XHRcdFx0XHRcdGNvdW50V29yZHMoIGUudmFsdWUudHJpbSgpICksXG5cdFx0XHRcdFx0XHRcdFx0bGltaXRcblx0XHRcdFx0XHRcdFx0KVxuXHRcdFx0XHRcdFx0KTtcblxuXHRcdFx0XHRcdFx0Y29uc3QgZm4gPSBjaGVja1dvcmRzKCBoaW50LCBsaW1pdCApO1xuXG5cdFx0XHRcdFx0XHRyZW1vdmVFeGlzdGluZ0hpbnQoIGUgKTtcblxuXHRcdFx0XHRcdFx0ZS5wYXJlbnROb2RlLmFwcGVuZENoaWxkKCBoaW50ICk7XG5cblx0XHRcdFx0XHRcdGUuYWRkRXZlbnRMaXN0ZW5lciggJ2tleWRvd24nLCBmbiApO1xuXHRcdFx0XHRcdFx0ZS5hZGRFdmVudExpc3RlbmVyKCAna2V5dXAnLCBmbiApO1xuXHRcdFx0XHRcdFx0ZS5hZGRFdmVudExpc3RlbmVyKCAncGFzdGUnLCBwYXN0ZVdvcmRzKCBsaW1pdCApICk7XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHQpO1xuXHRcdH0sXG5cdH07XG5cblx0LyoqXG5cdCAqIERPTUNvbnRlbnRMb2FkZWQgaGFuZGxlci5cblx0ICpcblx0ICogQHNpbmNlIDEuNS42XG5cdCAqL1xuXHRmdW5jdGlvbiByZWFkeSgpIHtcblx0XHQvLyBFeHBvc2UgdG8gdGhlIHdvcmxkLlxuXHRcdHdpbmRvdy5XUEZvcm1zVGV4dExpbWl0ID0gYXBwO1xuXG5cdFx0YXBwLmluaXRIaW50KCAnYm9keScgKTtcblx0fVxuXG5cdGlmICggZG9jdW1lbnQucmVhZHlTdGF0ZSA9PT0gJ2xvYWRpbmcnICkge1xuXHRcdGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoICdET01Db250ZW50TG9hZGVkJywgcmVhZHkgKTtcblx0fSBlbHNlIHtcblx0XHRyZWFkeSgpO1xuXHR9XG59KCkgKTtcbiJdLCJtYXBwaW5ncyI6Ijs7O0FBQUE7O0FBRUUsYUFBVztFQUNaO0FBQ0Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtFQUNDLFNBQVNBLFVBQVVBLENBQUVDLFFBQVEsRUFBRUMsS0FBSyxFQUFFQyxLQUFLLEVBQUc7SUFDN0MsT0FBT0YsUUFBUSxDQUFDRyxPQUFPLENBQUUsU0FBUyxFQUFFRixLQUFNLENBQUMsQ0FBQ0UsT0FBTyxDQUFFLFNBQVMsRUFBRUQsS0FBTSxDQUFDLENBQUNDLE9BQU8sQ0FBRSxhQUFhLEVBQUVELEtBQUssR0FBR0QsS0FBTSxDQUFDO0VBQ2hIOztFQUVBO0FBQ0Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7RUFDQyxTQUFTRyxVQUFVQSxDQUFFQyxNQUFNLEVBQUVDLE9BQU8sRUFBRUMsSUFBSSxFQUFHO0lBQzVDLElBQU1DLElBQUksR0FBR0MsUUFBUSxDQUFDQyxhQUFhLENBQUUsS0FBTSxDQUFDO0lBRTVDTCxNQUFNLEdBQUdNLE9BQUEsQ0FBT04sTUFBTSxNQUFLLFFBQVEsR0FBRyxFQUFFLEdBQUdBLE1BQU07SUFDakRDLE9BQU8sR0FBR0ssT0FBQSxDQUFPTCxPQUFPLE1BQUssUUFBUSxHQUFHLEVBQUUsR0FBR0EsT0FBTztJQUVwREUsSUFBSSxDQUFDSSxTQUFTLENBQUNDLEdBQUcsQ0FBRSwwQkFBMkIsQ0FBQztJQUNoREwsSUFBSSxDQUFDTSxFQUFFLEdBQUcsMkJBQTJCLEdBQUdULE1BQU0sR0FBRyxHQUFHLEdBQUdDLE9BQU87SUFDOURFLElBQUksQ0FBQ08sWUFBWSxDQUFFLFdBQVcsRUFBRSxRQUFTLENBQUM7SUFDMUNQLElBQUksQ0FBQ1EsV0FBVyxHQUFHVCxJQUFJO0lBRXZCLE9BQU9DLElBQUk7RUFDWjs7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
},{}]},{},[1])