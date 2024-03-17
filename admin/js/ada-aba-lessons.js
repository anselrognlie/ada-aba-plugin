(function ($) {
  'use strict';

  let lessonsDiv;

  const wireLessonActions = function () {
    // add delete button click event
    $('.ada-aba-lessons-delete').on('click', async function (e) {
      e.preventDefault();

      const dataSource = $(this).closest('.ada-aba-lesson');
      const slug = dataSource.data('ada-aba-lesson-slug');
      await deleteLesson(slug);
      await updateLessons();
    });

    // add activate button click event
    $('.ada-aba-lessons-activate').on('click', async function (e) {
      e.preventDefault();

      const dataSource = $(this).closest('.ada-aba-lesson');
      const slug = dataSource.data('ada-aba-lesson-slug');
      await activateLesson(slug);
      await updateLessons();
    });

    // add edit button click event
    $('.ada-aba-lessons-edit').on('click', async function (e) {
      e.preventDefault();

      const dataSource = $(this).closest('.ada-aba-lesson');
      const slug = dataSource.data('ada-aba-lesson-slug');

      // copy values into edit form
      const lesson = await getLesson(slug);
      editLesson(lesson);
    });
  };

  const updateLessons = async function () {
    const data = await loadLessons();
    lessonsDiv.html(data.html);
    wireLessonActions();
  };

  const resetAddForm = function () {
    $('#ada-aba-lessons-add-lesson-name').val('');
    $('#ada-aba-lessons-add-lesson-url').val('');
  };

  const resetEditForm = function () {
    $('#ada-aba-lessons-edit-lesson-name').val('');
    $('#ada-aba-lessons-edit-lesson-url').val('');
    $('#ada-aba-lessons-edit-lesson-slug').val('');
  };

  const editLesson = function (lesson) {
    $('#ada-aba-lessons-edit-lesson-name').val(lesson.name);
    $('#ada-aba-lessons-edit-lesson-url').val(lesson.url);
    $('#ada-aba-lessons-edit-lesson-slug').val(lesson.slug);
  };

  const setupAddLessonForm = function () {
    const form = $('#ada-aba-lessons-add-lesson');
    form.on('submit', async function (e) {
      e.preventDefault();
      const name = $('#ada-aba-lessons-add-lesson-name').val();
      const url = $('#ada-aba-lessons-add-lesson-url').val();
      resetAddForm();

      const response = await addLesson(name, url);
      await updateLessons();
    });
  };

  const setupEditLessonForm = function () {
    const form = $('#ada-aba-lessons-edit-lesson');
    form.on('submit', async function (e) {
      e.preventDefault();

      const name = $('#ada-aba-lessons-edit-lesson-name').val();
      const url = $('#ada-aba-lessons-edit-lesson-url').val();
      const slug = $('#ada-aba-lessons-edit-lesson-slug').val();
      resetEditForm();

      const response = await updateLesson(slug, name, url);
      await updateLessons();
    });

    form.on('reset', async function (e) {
      e.preventDefault();
      resetEditForm();
    });
  };

  $(function () {
    lessonsDiv = $('#ada-aba-lessons');
    setupAddLessonForm();
    setupEditLessonForm();
    wireLessonActions();
  });

})(jQuery);
