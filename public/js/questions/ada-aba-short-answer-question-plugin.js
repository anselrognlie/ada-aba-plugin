(function ($, global) {
  'use strict';

  const ShortAnswerQuestionPlugin = (parentClass) => class internalClass extends parentClass {
    constructor() {
      super();
    }

    getSlug() {
      return 'short-answer';  // must match the server-side slug
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
      ShortAnswerQuestionPlugin: ShortAnswerQuestionPlugin(global.AdaAba.QuestionBasePlugin)
    };
  });

})(jQuery, window);
