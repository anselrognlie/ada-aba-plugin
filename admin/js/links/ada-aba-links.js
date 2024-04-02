(function ($, global) {

  const Links = {
    getSurveyReportLink: (surveyId) => {
      return `${ada_aba_vars.root}ada-build?report=survey&survey=${surveyId}`;
    },

    getProgressReportLink: (courseId) => {
      return `${ada_aba_vars.root}ada-build?report=progress&course=${courseId}`;
    },
  };

  $(function () {
    global.AdaAba = {
      ...global.AdaAba,
      Links,
    };
  });

})(jQuery, window);