(function ($, global) {
  'use strict';

  class QuestionBasePlugin {
    constructor() {
    }

    wireSurveyActions(el) {
      console.log(`wiring survey actions for ${this.getSlug()}`);
      this.wireSurveyActionsDerived(el);
    }

    validate(el) {
      const $question = $(el);
      if ($question.hasClass('ada-aba-survey-survey-question-required')
        && ! this.validateRequired(el)) {
        return false;
      }

      return true;
    }

    validateRequired(el) {
      return true;
    }
  }

  $(function () {
    global.AdaAba = { ...global.AdaAba, QuestionBasePlugin };
  });

})(jQuery, window);
