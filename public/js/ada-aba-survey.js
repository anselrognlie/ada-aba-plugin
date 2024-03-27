(function ($, global) {
  'use strict';

  function validateSurvey(survey) {
    let valid = true;
    const palette = new global.AdaAba.QuestionsPalette();
    const $survey = $(survey);
    $survey.find('.ada-aba-survey-survey-question').each(function () {
      const $question = $(this);
      const questionType = $question.data('ada-aba-question-type');
      const plugin = palette.getQuestionPlugin(questionType);
      if (! plugin.validate($question.get())) {
        valid = false;
      }
    });

    if (! valid) {
      const $error = $survey.find('.ada-aba-survey-error');
      $error.html('Please answer all required (*) questions.');
    } else {
      const $error = $survey.find('.ada-aba-survey-error');
      $error.html('');
    }

    return valid;
  }

  function wireSurvey() {
    const palette = new global.AdaAba.QuestionsPalette();
    $('.ada-aba-survey').each(function () {
      const $survey = $(this);
      $survey.find('.ada-aba-survey-survey-question').each(function () {
        const $question = $(this);
        const questionType = $question.data('ada-aba-question-type');
        const plugin = palette.getQuestionPlugin(questionType);
        plugin.wireSurveyActions($question.get());
      });

      $survey.on('submit', function (e) {
        if (! validateSurvey($survey.get())) {
          e.preventDefault();
          return;
        }
      });
    });
  }

  $(function () {
    wireSurvey();
  });
})(jQuery, window);
