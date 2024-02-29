(function ($) {
  'use strict';

  let coursesDiv;
  let addCourseForm;
  let coursesData;

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

  const addCourse = async function (name) {
    const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/courses`, {
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

  const wireCourseActions = function () {
    // add delete button click event
    $('.ada-aba-courses-delete').on('click', async function (e) {
      e.preventDefault();

      const dataSource = $(this).closest('.ada-aba-course');
      const slug = dataSource.data('ada-aba-course-slug');
      await deleteCourse(slug);
      await updateCourses();
    });

    // add activate button click event
    $('.ada-aba-courses-activate').on('click', async function (e) {
      e.preventDefault();

      const dataSource = $(this).closest('.ada-aba-course');
      const slug = dataSource.data('ada-aba-course-slug');
      await activateCourse(slug);
      await updateCourses();
    });
  };

  const updateCourses = async function () {
    const data = await loadCourses();
    coursesData = data.json;
    coursesDiv.html(data.html);
    wireCourseActions();
  };

  const resetForm = function () {
    $('#ada-aba-courses-add-course-name').val('');
  };

  const setupAddCourseForm = function () {
    addCourseForm.on('submit', async function (e) {
      e.preventDefault();
      const name = $('#ada-aba-courses-add-course-name').val();
      resetForm();

      const response = await addCourse(name);
      updateCourses();
    });
  };

  $(function () {
    coursesDiv = $('#ada-aba-courses');
    addCourseForm = $('#ada-aba-courses-add-course');
    setupAddCourseForm();
    // updateCourses();
    wireCourseActions();
  });

})(jQuery);
