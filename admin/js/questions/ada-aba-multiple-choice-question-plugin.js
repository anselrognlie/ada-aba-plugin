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
    global.MultipleChoiceQuestionPlugin = MultipleChoiceQuestionPlugin(WithOptionsQuestionPlugin);
  });

})(jQuery, window);
