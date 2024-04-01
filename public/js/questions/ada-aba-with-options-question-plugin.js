(function ($, global) {
  'use strict';

  const WithOptionsQuestionPlugin = (parentClass) => class internalClass extends parentClass {
    constructor() {
      super();
    }

    wireSurveyActionsDerived(el) {
      const $el = $(el);
      const $other = $el.find('.ada-aba-survey-survey-option-other-input');
      const $textarea = $el.find('.ada-aba-survey-survey-option-other textarea');

      $other.on('change', (e) => {
        e.preventDefault();
        if (e.target.checked) {
          $textarea.trigger('focus');
        }
      });

      $textarea.on('input', (e) => {
        e.preventDefault();
        if (e.target.value.length > 0) {
          $other.prop('checked', true);
        }
      });
    }

    validate(el) {
      const $question = $(el);
      if ($question.hasClass('ada-aba-survey-survey-question-required')
        && !this.validateRequired(el)) {
        return false;
      }

      // regardless of whether this is required, if the 'other' option is 
      // selected, the textarea must have a value
      const $other = $question.find('.ada-aba-survey-survey-option-other-input');
      const $textarea = $question.find('.ada-aba-survey-survey-option-other textarea');
      if ($other.is(':checked') && $textarea.val().length === 0) {
        return false;
      }

      return true;
    }

    validateRequired(el) {
      const $el = $(el);
      const $options = $el.find('.ada-aba-survey-survey-option');

      let valid = false;
      $options.each(function () {
        const $option = $(this);
        if ($option.is(':checked')) {
          valid = true;
        }
      });

      return valid;
    }
  }

  $(function () {
    global.AdaAba = {
      ...global.AdaAba,
      WithOptionsQuestionPlugin: WithOptionsQuestionPlugin(global.AdaAba.QuestionBasePlugin)
    };
  });

})(jQuery, window);
