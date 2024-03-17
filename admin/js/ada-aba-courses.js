(function ($) {
  'use strict';

  let coursesDiv;

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
    coursesDiv.html(data.html);
    wireCourseActions();
  };

  const resetAddForm = function () {
    $('#ada-aba-courses-add-course-name').val('');
    $('#ada-aba-courses-add-course-url').val('');
  };

  const resetEditForm = function () {
    $('#ada-aba-courses-edit-course-name').val('');
    $('#ada-aba-courses-edit-course-url').val('');
    $('#ada-aba-courses-edit-course-slug').val('');
  };

  const editCourse = function (course) {
    $('#ada-aba-courses-edit-course-name').val(course.name);
    $('#ada-aba-courses-edit-course-url').val(course.url);
    $('#ada-aba-courses-edit-course-slug').val(course.slug);
  };

  const setupAddCourseForm = function () {
    const form = $('#ada-aba-courses-add-course');
    form.on('submit', async function (e) {
      e.preventDefault();
      const name = $('#ada-aba-courses-add-course-name').val();
      const url = $('#ada-aba-courses-add-course-url').val();
      resetAddForm();

      const response = await addCourse(name, url);
      await updateCourses();
    });
  };

  const setupEditCourseForm = function () {
    const form = $('#ada-aba-courses-edit-course');
    form.on('submit', async function (e) {
      e.preventDefault();

      const name = $('#ada-aba-courses-edit-course-name').val();
      const url = $('#ada-aba-courses-edit-course-url').val();
      const slug = $('#ada-aba-courses-edit-course-slug').val();
      resetEditForm();

      const response = await updateCourse(slug, name, url);
      await updateCourses();
    });

    form.on('reset', async function (e) {
      e.preventDefault();
      resetEditForm();
    });
  };

  $(function () {
    coursesDiv = $('#ada-aba-courses');
    setupAddCourseForm();
    setupEditCourseForm();
    wireCourseActions();
  });

})(jQuery);
