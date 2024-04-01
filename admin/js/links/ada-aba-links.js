(function ($, global) {

  const linkFunctions = {
    getSurveyReportLink: (surveyId) => {
      return `${ada_aba_vars.root}ada-build?report=survey&survey=${surveyId}`;
    }
  };

  $(function () {
    global.AdaAba = {
      ...global.AdaAba,
      ...linkFunctions,
    };
  });

})(jQuery, window);