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

    validateRequired(el) {
      const $el = $(el);
      const $options = $el.find('.ada-aba-survey-survey-option');
      const $other = $el.find('.ada-aba-survey-survey-option-other-input');
      const $textarea = $el.find('.ada-aba-survey-survey-option-other textarea');

      let valid = false;
      $options.each(function () {
        const $option = $(this);
        if ($option.is(':checked')) {
          valid = true;
        }
      });

      if ($other.is(':checked') && $textarea.val().length === 0) {
        valid = false;
      }
      
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
