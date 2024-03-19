const loadQuestions = async function (excludeCourseSlug = '') {
  const params = {};
  if (excludeCourseSlug) {
    params.excludeCourseSlug = excludeCourseSlug;
  }

  const url = new URL(`${ada_aba_vars.root}ada-aba/v1/questions`);
  Object.keys(params).forEach(key => url.searchParams.append(key, params[key]))

  const response = await fetch(url, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
      'Accept': 'text/html',
    },
  });
  return await response.json();
  // console.log(data);
};

const getQuestion = async function (slug) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/questions/${slug}`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
    },
  });
  return await response.json();
  // console.log(data);
};

const addQuestion = async function (name, url, completeOnProgress) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/questions`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
      'Content-Type': 'application/json',
    },
    method: 'POST',
    body: JSON.stringify({
      name: name,
      url: url,
      complete_on_progress: completeOnProgress,
    }),
  });
  return await response.json();
  // console.log(data);
};

const updateQuestion = async function (slug, name, url, completeOnProgress) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/questions/${slug}`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
      'Content-Type': 'application/json',
    },
    method: 'PATCH',
    body: JSON.stringify({
      name: name,
      url: url,
      complete_on_progress: completeOnProgress,
    }),
  });
  return await response.json();
  // console.log(data);
}

const deleteQuestion = async function (slug) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/questions/${slug}`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
    },
    method: 'DELETE',
  });
  return await response.json();
  // console.log(data);
};
