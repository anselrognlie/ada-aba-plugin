(function ($) {
  'use strict';

  let availableLessonsDiv;
  let courseLessonsDiv;

  const refreshUI = async function () {
    const selectedCourse = getSelectedCourse();
    const courseLessons = await getCourseLessonsHtml(selectedCourse);
    const availableLessons = await getAvailableLessonsHtml(selectedCourse);
    courseLessonsDiv.html(courseLessons.html);
    availableLessonsDiv.html(availableLessons.html);
    wireCourseLessonsActions();
    wireAvailableLessonsActions();
  };

  const getSelectedCourse = () => {
    const courseSelect = $('#ada-aba-course-select');
    return courseSelect.val();
  }

  const getCourseLessonSlug = (el) => {
    const dataSource = $(el).closest('.ada-aba-course-lesson');
    return dataSource.data('ada-aba-course-lesson-slug');
  }

  const wireCourseActions = function () {
    // add add button click event
    $('#ada-aba-course-select').on('change', async function (e) {
      e.preventDefault();

      await refreshUI();
    });
  };

  const wireAvailableLessonsActions = function () {
    // add add button click event
    $('.ada-aba-available-lessons-add').on('click', async function (e) {
      e.preventDefault();

      const selectedCourse = getSelectedCourse();
      
      const dataSource = $(this).closest('.ada-aba-lesson');
      const slug = dataSource.data('ada-aba-lesson-slug');
      console.log({selectedCourse, slug});
      await addCourseLesson(selectedCourse, slug);
      await refreshUI();
    });
  };

  const wireCourseLessonsActions = function () {
    // add add button click event
    $('.ada-aba-course-lessons-remove').on('click', async function (e) {
      e.preventDefault();
      
      const slug = getCourseLessonSlug(this);
      console.log({slug});
      await deleteCourseLesson(slug);
      await refreshUI();
    });

    $('.ada-aba-course-lessons-up').on('click', async function (e) {
      e.preventDefault();
      
      const slug = getCourseLessonSlug(this);
      console.log({slug});
      await moveUpCourseLesson(slug);
      await refreshUI();
    });

    $('.ada-aba-course-lessons-down').on('click', async function (e) {
      e.preventDefault();
      
      const slug = getCourseLessonSlug(this);
      console.log({slug});
      await moveDownCourseLesson(slug);
      await refreshUI();
    });

    $('.ada-aba-course-lessons-toggle-option').on('click', async function (e) {
      e.preventDefault();
      
      const slug = getCourseLessonSlug(this);
      console.log({slug});
      await toggleCourseLessonOptional(slug);
      await refreshUI();
    });
  };

  $(function () {
    availableLessonsDiv = $('#ada-aba-available-lessons');
    courseLessonsDiv = $('#ada-aba-course-lessons');
    wireCourseActions();
    wireAvailableLessonsActions();
    wireCourseLessonsActions();
  });

})(jQuery);
