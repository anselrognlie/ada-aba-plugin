(function ($, global) {
  'use strict';

  const CheckboxesQuestionPlugin = (parentClass) => class internalClass extends parentClass {
    constructor() {
      super();
    }

    getSlug() {
      return 'checkboxes';  // must match the server-side slug
    }
  }

  $(function () {
    global.AdaAba = {
      ...global.AdaAba,
      CheckboxesQuestionPlugin: CheckboxesQuestionPlugin(global.AdaAba.WithOptionsQuestionPlugin)
    };
  });

})(jQuery, window);
