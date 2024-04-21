(function ($) {
  'use strict';

  const wireReportsActions = function () {
    $('#ada-aba-survey-responses-button').on('click', async function (e) {
      e.preventDefault();

      const $select = $('#ada-aba-survey-responses-select')
      const surveyId = $select.val();
      const link = AdaAba.Links.getSurveyReportLink(surveyId);
      window.location.href = link;
      // console.log($select.val());
    });

    $('#ada-aba-course-progress-button').on('click', async function (e) {
      e.preventDefault();

      const $select = $('#ada-aba-course-progress-select')
      const courseId = $select.val();
      const link = AdaAba.Links.getProgressReportLink(courseId);
      window.location.href = link;
      // console.log($select.val());
    });

    $('#ada-aba-error-log-button').on('click', async function (e) {
      e.preventDefault();

      const $text = $('#ada-aba-error-log-path')
      const path = $text.val();
      const link = AdaAba.Links.getErrorLogReportLink(path);
      window.location.href = link;
      // console.log($select.val());
    });
  };

  $(function () {
    wireReportsActions();
  });

})(jQuery);
