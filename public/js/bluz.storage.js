/**
 * Bluz storage handler
 *
 * @author   Anton Shevchuk
 * @created  23.10.12 10:02
 */
/* global define,require,window */
define([], function () {
  'use strict';
  // initial local storage
  let storage = null;
  let storageCheck = false;
  let data = {};

  if ('localStorage' in window) {
    try {
      window.localStorage.setItem('_test', 'test');
      storageCheck = true;
      window.localStorage.removeItem('_test');
    } catch (BogusQuotaExceededErrorOnIos5) {
      // Thanks be to iOS5 Private Browsing mode which throws
      // QUOTA_EXCEEDED_ERRROR DOM Exception 22.
    }
  }

  // storage fail
  // use simple object
  // TODO: add cookie save handler
  if (!storageCheck) {
    storage = {
      setItem: (key, value) => {
        data[key] = value;
      },
      getItem: key => {
        if (data[key] !== undefined) {
          return data[key];
        }
        return false;
      },
      removeItem: key => {
        if (data[key] !== undefined) {
          delete data[key];
        }
      },
      clear: () => {
        data = {};
      }
    };
  } else {
    storage = window.localStorage;
  }

  // return default API
  return {
    setItem: (key, value) => {
      storage.setItem('bluz-' + key, value);
    },
    getItem: key => storage.getItem('bluz-' + key),
    removeItem: key => {
      storage.removeItem('bluz-' + key);
    },
    clear: () => {
      storage.clear();
    }
  };
});
