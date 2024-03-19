const getQuestionBuilder = async function (slug) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/ui/question_builders/${slug}/editor`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
    },
  });
  return await response.json();
  // console.log(data);
};

const getEditorForQuestion = async function (slug) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/ui/question_builders/${slug}/question`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
    },
  });
  return await response.json();
  // console.log(data);
};

const previewQuestion = async function (slug, data) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/ui/question_builders/${slug}/preview`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
      'Content-Type': 'application/json',
    },
    method: 'PATCH',
    body: JSON.stringify(data),
  });
  return await response.json();
  // console.log(data);
};

const saveQuestion = async function (slug, data) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/ui/question_builders/${slug}/save`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
      'Content-Type': 'application/json',
    },
    method: 'PATCH',
    body: JSON.stringify(data),
  });
  return await response.json();
  // console.log(data);
};
