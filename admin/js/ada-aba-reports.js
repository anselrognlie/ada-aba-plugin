(function ($) {
  'use strict';

  const wireReportsActions = function () {
    $('#ada-aba-survey-responses-button').on('click', async function (e) {
      e.preventDefault();

      const $select = $('#ada-aba-survey-responses-select')
      const surveyId = $select.val();
      const link = AdaAba.getSurveyReportLink(surveyId);
      window.location.href = link;
      // console.log($select.val());
    });
  };

  $(function () {
    wireReportsActions();
  });

})(jQuery);
