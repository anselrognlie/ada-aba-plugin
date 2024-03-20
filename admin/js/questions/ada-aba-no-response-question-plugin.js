(function ($, global) {
  'use strict';

  const NoResponseQuestionPlugin = (parentClass) => class internalClass extends parentClass {
    constructor() {
      super();
    }

    getSlug() {
      return 'no-response';  // must match the server-side slug
    }

    wireEditorActionsDerived() {
    }

    getEditorDataDerived(data) {
      return data;
    }
  }

  $(function () {
    global.NoResponseQuestionPlugin = NoResponseQuestionPlugin(QuestionBasePlugin);
  });

})(jQuery, window);
