const loadLessons = async function (excludeCourseSlug = '') {
  const params = {};
  if (excludeCourseSlug) {
    params.excludeCourseSlug = excludeCourseSlug;
  }

  const url = new URL(`${ada_aba_vars.root}ada-aba/v1/lessons`);
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

const getLesson = async function (slug) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/lessons/${slug}`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
    },
  });
  return await response.json();
  // console.log(data);
};

const addLesson = async function (name) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/lessons`, {
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

const updateLesson = async function (slug, name) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/lessons/${slug}`, {
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

const deleteLesson = async function (slug) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/lessons/${slug}`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
    },
    method: 'DELETE',
  });
  return await response.json();
  // console.log(data);
};
