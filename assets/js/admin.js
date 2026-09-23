/* Savta dashboard: sub-tabs, repeaters, media picker, unsaved-changes guard. No jQuery. */
(function () {
  'use strict';
  var i18n = window.savtaAdmin || {};

  /* Sub-tabs (remember the last one per page) */
  var key = 'savtaTab:' + location.search;
  var btns = document.querySelectorAll('[data-subtab]');
  function showTab(slug) {
    btns.forEach(function (b) {
      var on = b.getAttribute('data-subtab') === slug;
      b.classList.toggle('is-active', on);
      b.setAttribute('aria-selected', on ? 'true' : 'false');
    });
    document.querySelectorAll('[data-panel]').forEach(function (p) { p.hidden = p.getAttribute('data-panel') !== slug; });
    try { localStorage.setItem(key, slug); } catch (e) { /* private mode */ }
  }
  if (btns.length) {
    var saved = null;
    try { saved = localStorage.getItem(key); } catch (e) { saved = null; }
    if (saved && document.querySelector('[data-panel="' + saved + '"]')) { showTab(saved); }
    btns.forEach(function (b) { b.addEventListener('click', function () { showTab(b.getAttribute('data-subtab')); }); });
  }

  /* Repeaters */
  document.querySelectorAll('[data-repeater]').forEach(function (rep) {
    var rows = rep.querySelector('[data-rows]');
    var tpl = rep.querySelector('[data-row-template]');
    function nextIndex() {
      var max = -1;
      rows.querySelectorAll('[data-row] [name]').forEach(function (el) {
        var m = el.name.match(/\[(\d+)\]\[[^\]]+\]$/);
        if (m) { max = Math.max(max, +m[1]); }
      });
      return max + 1;
    }
    rep.querySelector('[data-add]').addEventListener('click', function () {
      var html = tpl.innerHTML.replace(/__i__/g, String(nextIndex()));
      var wrap = document.createElement('div');
      wrap.innerHTML = html;
      var row = wrap.firstElementChild;
      rows.appendChild(row);
      var first = row.querySelector('input, textarea');
      if (first) { first.focus(); }
      markDirty();
    });
    rows.addEventListener('click', function (e) {
      var row = e.target.closest('[data-row]');
      if (!row) { return; }
      if (e.target.closest('[data-remove]')) {
        if (window.confirm(i18n.confirmRm || 'Remove?')) { row.remove(); markDirty(); }
      } else if (e.target.closest('[data-up]') && row.previousElementSibling) {
        rows.insertBefore(row, row.previousElementSibling); markDirty();
      } else if (e.target.closest('[data-down]') && row.nextElementSibling) {
        rows.insertBefore(row.nextElementSibling, row); markDirty();
      }
    });
  });

  /* Media picker */
  document.querySelectorAll('[data-media]').forEach(function (box) {
    var input = box.querySelector('[data-media-id]');
    var preview = box.querySelector('[data-media-preview]');
    var clear = box.querySelector('[data-media-clear]');
    var frame = null;
    box.querySelector('[data-media-pick]').addEventListener('click', function () {
      if (!window.wp || !wp.media) { return; }
      if (!frame) {
        frame = wp.media({ title: i18n.chooseImage || '', button: { text: i18n.useImage || '' }, library: { type: 'image' }, multiple: false });
        frame.on('select', function () {
          var att = frame.state().get('selection').first().toJSON();
          var src = (att.sizes && att.sizes.medium ? att.sizes.medium.url : att.url);
          input.value = att.id;
          preview.innerHTML = '';
          var img = document.createElement('img');
          img.src = src; img.alt = '';
          preview.appendChild(img);
          clear.hidden = false;
          markDirty();
        });
      }
      frame.open();
    });
    clear.addEventListener('click', function () {
      input.value = '0';
      preview.innerHTML = '';
      clear.hidden = true;
      markDirty();
    });
  });

  /* Reset confirmation + unsaved-changes guard */
  var form = document.querySelector('[data-savta-form]');
  var dirty = false;
  function markDirty() { dirty = true; }
  if (form) {
    form.addEventListener('input', markDirty);
    form.addEventListener('submit', function (e) {
      var btn = e.submitter;
      if (btn && btn.hasAttribute('data-confirm') && !window.confirm(btn.getAttribute('data-confirm'))) { e.preventDefault(); return; }
      dirty = false;
    });
    window.addEventListener('beforeunload', function (e) {
      if (dirty) { e.preventDefault(); e.returnValue = i18n.unsaved || ''; }
    });
  }
})();
