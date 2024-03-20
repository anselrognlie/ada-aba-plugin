(function ($, global) {
  'use strict';

  const ParagraphQuestionPlugin = (parentClass) => class internalClass extends parentClass {
    constructor() {
      super();
    }

    getSlug() {
      return 'paragraph';  // must match the server-side slug
    }

    wireEditorActionsDerived() {
    }

    getEditorDataDerived(data) {
      return data;
    }
  }

  $(function () {
    global.ParagraphQuestionPlugin = ParagraphQuestionPlugin(QuestionBasePlugin);
  });

})(jQuery, window);
