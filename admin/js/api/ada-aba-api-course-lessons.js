const addCourseLesson = async function (courseSlug, lessonSlug) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/course-lessons`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
      'Content-Type': 'application/json',
    },
    method: 'POST',
    body: JSON.stringify({
      course: courseSlug,
      lesson: lessonSlug
    }),
  });
  return await response.json();
};

const deleteCourseLesson = async function (slug) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/course-lessons/${slug}`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
    },
    method: 'DELETE',
  });
  return await response.json();
};

const moveUpCourseLesson = async function (slug) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/course-lessons/${slug}/move_up`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
    },
    method: 'PATCH',
  });
  return await response.json();
};

const moveDownCourseLesson = async function (slug) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/course-lessons/${slug}/move_down`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
    },
    method: 'PATCH',
  });
  return await response.json();
};

const toggleCourseLessonOptional = async function (slug) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/course-lessons/${slug}/toggle_optional`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
    },
    method: 'PATCH',
  });
  return await response.json();
};
