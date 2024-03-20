(function ($, global) {
  'use strict';

  const ShortAnswerQuestionPlugin = (parentClass) => class internalClass extends parentClass {
    constructor() {
      super();
    }

    getSlug() {
      return 'short-answer';  // must match the server-side slug
    }

    wireEditorActionsDerived() {
    }

    getEditorDataDerived(data) {
      return data;
    }
  }

  $(function () {
    global.ShortAnswerQuestionPlugin = ShortAnswerQuestionPlugin(QuestionBasePlugin);
  });

})(jQuery, window);
