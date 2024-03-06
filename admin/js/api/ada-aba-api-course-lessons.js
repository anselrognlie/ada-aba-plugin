// const loadCourses = async function () {
//   const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/courses`, {
//     headers: {
//       'X-WP-Nonce': ada_aba_vars.nonce,
//       'Accept': 'text/html',
//     },
//   });
//   return await response.json();
//   // console.log(data);
// };

// const getCourse = async function (slug) {
//   const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/courses/${slug}`, {
//     headers: {
//       'X-WP-Nonce': ada_aba_vars.nonce,
//     },
//   });
//   return await response.json();
//   // console.log(data);
// };

// const addCourse = async function (name) {
//   const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/courses`, {
//     headers: {
//       'X-WP-Nonce': ada_aba_vars.nonce,
//       'Content-Type': 'application/json',
//     },
//     method: 'POST',
//     body: JSON.stringify({
//       name: name,
//     }),
//   });
//   return await response.json();
//   // console.log(data);
// };

// const updateCourse = async function (slug, name) {
//   const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/courses/${slug}`, {
//     headers: {
//       'X-WP-Nonce': ada_aba_vars.nonce,
//       'Content-Type': 'application/json',
//     },
//     method: 'PATCH',
//     body: JSON.stringify({
//       name: name,
//     }),
//   });
//   return await response.json();
//   // console.log(data);
// }

// const activateCourse = async function (slug) {
//   const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/courses/${slug}/activate`, {
//     headers: {
//       'X-WP-Nonce': ada_aba_vars.nonce,
//     },
//     method: 'PATCH',
//   });
//   return await response.json();
//   // console.log(data);
// };

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
