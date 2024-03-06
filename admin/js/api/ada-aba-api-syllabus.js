const getAvailableLessonsHtml = async function (slug) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/ui/syllabus/${slug}/available_lessons`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
    },
  });
  return await response.json();
};

const getCourseLessonsHtml = async function (slug) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/ui/syllabus/${slug}/course_lessons`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
    },
  });
  return await response.json();
};
