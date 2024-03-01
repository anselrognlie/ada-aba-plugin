(function ($) {
  'use strict';

  let coursesDiv;
  let addCourseForm;
  let editCourseForm;
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

  const getCourse = async function (slug) {
    const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/courses/${slug}`, {
      headers: {
        'X-WP-Nonce': ada_aba_vars.nonce,
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

  const updateCourse = async function (slug, name) {
    const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/courses/${slug}`, {
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

    // add edit button click event
    $('.ada-aba-courses-edit').on('click', async function (e) {
      e.preventDefault();

      const dataSource = $(this).closest('.ada-aba-course');
      const slug = dataSource.data('ada-aba-course-slug');

      // copy values into edit form
      const course = await getCourse(slug);
      editCourse(course);
    });
  };

  const updateCourses = async function () {
    const data = await loadCourses();
    coursesData = data.json;
    coursesDiv.html(data.html);
    wireCourseActions();
  };

  const resetAddForm = function () {
    $('#ada-aba-courses-add-course-name').val('');
  };

  const resetEditForm = function () {
    $('#ada-aba-courses-edit-course-name').val('');
    $('#ada-aba-courses-edit-course-slug').val('');
};

  const editCourse = function (course) {
    $('#ada-aba-courses-edit-course-name').val(course.name);
    $('#ada-aba-courses-edit-course-slug').val(course.slug);
  };

  const setupAddCourseForm = function () {
    addCourseForm.on('submit', async function (e) {
      e.preventDefault();
      const name = $('#ada-aba-courses-add-course-name').val();
      resetAddForm();

      const response = await addCourse(name);
      await updateCourses();
    });
  };

  const setupEditCourseForm = function () {
    const form = $('#ada-aba-courses-edit-course');
    form.on('submit', async function (e) {
      e.preventDefault();

      const name = $('#ada-aba-courses-edit-course-name').val();
      const slug = $('#ada-aba-courses-edit-course-slug').val();
      resetEditForm();

      const response = await updateCourse(slug, name);
      await updateCourses();
    });

    form.on('reset', async function (e) {
      e.preventDefault();
      resetEditForm();
    });
  };

  $(function () {
    coursesDiv = $('#ada-aba-courses');
    addCourseForm = $('#ada-aba-courses-add-course');
    setupAddCourseForm();
    setupEditCourseForm();
    wireCourseActions();
  });

})(jQuery);
