const getAvailableQuestionsHtml = async function (slug) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/ui/surveys/${slug}/available_questions`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
    },
  });
  return await response.json();
};

const getSurveyQuestionsHtml = async function (slug) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/ui/surveys/${slug}/survey_questions`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
    },
  });
  return await response.json();
};
