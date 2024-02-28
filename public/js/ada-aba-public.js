(function( $ ) {
  'use strict';

   $(function() {
    //  console.log(window.location.pathname);
    const doIt = async (url, nonce) => {
      const response = await fetch(url, {
        headers: {
          "X-WP-Nonce": nonce,
        },
      });
      const data = await response.json();
      console.log(data);
    }
    doIt('/wp-json/ada-aba/v1/products', ada_aba_vars.nonce);
   });
})( jQuery );
