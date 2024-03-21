const loadSurveys = async function () {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/surveys`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
      'Accept': 'text/html',
    },
  });
  return await response.json();
  // console.log(data);
};

const getSurvey = async function (slug) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/surveys/${slug}`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
    },
  });
  return await response.json();
  // console.log(data);
};

const addSurvey = async function (name) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/surveys`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
      'Content-Type': 'application/json',
    },
    method: 'POST',
    body: JSON.stringify({
      name: name,
    }),
  });
  return await response.json();
  // console.log(data);
};

const updateSurvey = async function (slug, name) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/surveys/${slug}`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
      'Content-Type': 'application/json',
    },
    method: 'PATCH',
    body: JSON.stringify({
      name: name,
    }),
  });
  return await response.json();
  // console.log(data);
}

const deleteSurvey = async function (slug) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/surveys/${slug}`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
    },
    method: 'DELETE',
  });
  return await response.json();
  // console.log(data);
};

const activateSurvey = async function (slug) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/surveys/${slug}/activate`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
    },
    method: 'PATCH',
  });
  return await response.json();
  // console.log(data);
};
