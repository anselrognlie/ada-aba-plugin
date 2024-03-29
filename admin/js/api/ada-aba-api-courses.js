const loadCourses = async function () {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/courses`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
      'Accept': 'text/html',
    },
  });
  return await response.json();
  // console.log(data);
};

const getCourse = async function (slug) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/courses/${slug}`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
    },
  });
  return await response.json();
  // console.log(data);
};

const addCourse = async function (name, url) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/courses`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
      'Content-Type': 'application/json',
    },
    method: 'POST',
    body: JSON.stringify({
      name: name,
      url: url,
    }),
  });
  return await response.json();
  // console.log(data);
};

const updateCourse = async function (slug, name, url) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/courses/${slug}`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
      'Content-Type': 'application/json',
    },
    method: 'PATCH',
    body: JSON.stringify({
      name: name,
      url: url,
    }),
  });
  return await response.json();
  // console.log(data);
}

const deleteCourse = async function (slug) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/courses/${slug}`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
    },
    method: 'DELETE',
  });
  return await response.json();
  // console.log(data);
};

const activateCourse = async function (slug) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/courses/${slug}/activate`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
    },
    method: 'PATCH',
  });
  return await response.json();
  // console.log(data);
};
