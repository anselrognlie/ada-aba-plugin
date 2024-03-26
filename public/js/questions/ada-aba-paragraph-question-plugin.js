(function ($, global) {
  'use strict';

  const ParagraphQuestionPlugin = (parentClass) => class internalClass extends parentClass {
    constructor() {
      super();
    }

    getSlug() {
      return 'paragraph';  // must match the server-side slug
    }

    wireSurveyActionsDerived(el) {
    }

    validateRequired(el) {
      const $el = $(el);
      const $textarea = $el.find('textarea');

      if ($textarea.val().length === 0) {
        return false;
      }
      
      return true;
    }

  }

  $(function () {
    global.AdaAba = {
      ...global.AdaAba,
      ParagraphQuestionPlugin: ParagraphQuestionPlugin(global.AdaAba.QuestionBasePlugin)
    };
  });

})(jQuery, window);
