(function ($, global) {
  'use strict';

  const MultipleChoiceQuestionPlugin = (parentClass) => class internalClass extends parentClass {
    constructor() {
      super();
    }

    getSlug() {
      return 'multiple-choice';  // must match the server-side slug
    }
  }

  $(function () {
    global.AdaAba = {
      ...global.AdaAba,
      MultipleChoiceQuestionPlugin: MultipleChoiceQuestionPlugin(global.AdaAba.WithOptionsQuestionPlugin)
    };
  });

})(jQuery, window);
