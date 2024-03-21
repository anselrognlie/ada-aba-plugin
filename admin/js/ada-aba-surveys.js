(function ($) {
  'use strict';

  let surveysDiv;

  const wireSurveyActions = function () {
    // add delete button click event
    $('.ada-aba-surveys-delete').on('click', async function (e) {
      e.preventDefault();

      const dataSource = $(this).closest('.ada-aba-survey');
      const slug = dataSource.data('ada-aba-survey-slug');
      await deleteSurvey(slug);
      await updateSurveys();
    });

    // add activate button click event
    $('.ada-aba-surveys-activate').on('click', async function (e) {
      e.preventDefault();

      const dataSource = $(this).closest('.ada-aba-survey');
      const slug = dataSource.data('ada-aba-survey-slug');
      await activateSurvey(slug);
      await updateSurveys();
    });

    // add edit button click event
    $('.ada-aba-surveys-edit').on('click', async function (e) {
      e.preventDefault();

      const dataSource = $(this).closest('.ada-aba-survey');
      const slug = dataSource.data('ada-aba-survey-slug');

      // copy values into edit form
      const survey = await getSurvey(slug);
      editSurvey(survey);
    });
  };

  const updateSurveys = async function () {
    const data = await loadSurveys();
    surveysDiv.html(data.html);
    wireSurveyActions();
  };

  const resetAddForm = function () {
    $('#ada-aba-surveys-add-survey-name').val('');
  };

  const resetEditForm = function () {
    $('#ada-aba-surveys-edit-survey-name').val('');
    $('#ada-aba-surveys-edit-survey-slug').val('');
  };

  const editSurvey = function (survey) {
    $('#ada-aba-surveys-edit-survey-name').val(survey.name);
    $('#ada-aba-surveys-edit-survey-slug').val(survey.slug);
  };

  const setupAddSurveyForm = function () {
    const form = $('#ada-aba-surveys-add-survey');
    form.on('submit', async function (e) {
      e.preventDefault();
      const name = $('#ada-aba-surveys-add-survey-name').val();
      resetAddForm();

      const response = await addSurvey(name);
      await updateSurveys();
    });
  };

  const setupEditSurveyForm = function () {
    const form = $('#ada-aba-surveys-edit-survey');
    form.on('submit', async function (e) {
      e.preventDefault();

      const name = $('#ada-aba-surveys-edit-survey-name').val();
      const slug = $('#ada-aba-surveys-edit-survey-slug').val();
      resetEditForm();

      const response = await updateSurvey(slug, name);
      await updateSurveys();
    });

    form.on('reset', async function (e) {
      e.preventDefault();
      resetEditForm();
    });
  };

  $(function () {
    surveysDiv = $('#ada-aba-surveys');
    setupAddSurveyForm();
    setupEditSurveyForm();
    wireSurveyActions();
  });

})(jQuery);
