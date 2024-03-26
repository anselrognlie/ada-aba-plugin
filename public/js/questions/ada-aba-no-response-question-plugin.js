(function ($, global) {
  'use strict';

  const NoResponseQuestionPlugin = (parentClass) => class internalClass extends parentClass {
    constructor() {
      super();
    }

    getSlug() {
      return 'no-response';  // must match the server-side slug
    }

    wireSurveyActionsDerived(el) {
    }
  }

  $(function () {
    global.AdaAba = {
      ...global.AdaAba,
      NoResponseQuestionPlugin: NoResponseQuestionPlugin(global.AdaAba.QuestionBasePlugin)
    };
  });

})(jQuery, window);
