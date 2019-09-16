<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo ANALYTICS_ID; ?>"></script>
<script>
(function (i, s, o, g, r, a, m) {
    i['GoogleAnalyticsObject'] = r;
    i[r] = i[r] || function () {
        (i[r].q = i[r].q || []).push(arguments);
    },
            i[r].l = 1 * new Date();
    a = s.createElement(o), m = s.getElementsByTagName(o)[0];
    a.async = 1;
    a.src = g;
    m.parentNode.insertBefore(a, m);
})(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', analytics_id);
  ga('create', analytics_id, 'auto', {'allowLinker': true});
    function fn_note_event(category, action, label) {
        ga('send', 'event', category, action, label);
    }
  function fn_ecommerce_track(id, category, amount, charges, pro_name) {
    ga('require', 'ecommerce');
    ga('ecommerce:addTransaction', {
        'id': id,
        'affiliation': pro_name,
        'revenue': amount,
        'shipping': charges,
        'tax': '0'
    });
    ga('ecommerce:addItem', {
        'id': id,
        'name': pro_name,
        'sku': pro_name,
        'category': category,
        'price': amount,
        'quantity': '1',
    });
    ga('ecommerce:send');
}

</script>

