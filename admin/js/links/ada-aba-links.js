(function ($, global) {

  const Links = {
    getSurveyReportLink: (surveyId) => {
      return `${ada_aba_vars.root}ada-build?report=survey&survey=${surveyId}`;
    },

    getProgressReportLink: (courseId) => {
      return `${ada_aba_vars.root}ada-build?report=progress&course=${courseId}`;
    },

    getErrorLogReportLink: (path) => {
      return `${ada_aba_vars.root}ada-build?report=error-log&path=${encodeURIComponent(path)}`;
    },
  };

  $(function () {
    global.AdaAba = {
      ...global.AdaAba,
      Links,
    };
  });

})(jQuery, window);