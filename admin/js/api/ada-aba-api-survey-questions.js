const addSurveyQuestion = async function (surveySlug, questionSlug) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/survey-questions`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
      'Content-Type': 'application/json',
    },
    method: 'POST',
    body: JSON.stringify({
      survey: surveySlug,
      question: questionSlug
    }),
  });
  return await response.json();
};

const deleteSurveyQuestion = async function (slug) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/survey-questions/${slug}`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
    },
    method: 'DELETE',
  });
  return await response.json();
};

const moveUpSurveyQuestion = async function (slug) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/survey-questions/${slug}/move_up`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
    },
    method: 'PATCH',
  });
  return await response.json();
};

const moveDownSurveyQuestion = async function (slug) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/survey-questions/${slug}/move_down`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
    },
    method: 'PATCH',
  });
  return await response.json();
};

const toggleSurveyQuestionOptional = async function (slug) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/survey-questions/${slug}/toggle_optional`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
    },
    method: 'PATCH',
  });
  return await response.json();
};
