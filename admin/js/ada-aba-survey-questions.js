(function ($) {
  'use strict';

  let availableQuestionsDiv;
  let surveyQuestionsDiv;

  const refreshUI = async function () {
    const selectedSurvey = getSelectedSurvey();
    const surveyQuestions = await getSurveyQuestionsHtml(selectedSurvey);
    const availableQuestions = await getAvailableQuestionsHtml(selectedSurvey);
    surveyQuestionsDiv.html(surveyQuestions.html);
    availableQuestionsDiv.html(availableQuestions.html);
    wireSurveyQuestionsActions();
    wireAvailableQuestionsActions();
  };

  const getSelectedSurvey = () => {
    const surveySelect = $('#ada-aba-survey-select');
    return surveySelect.val();
  }

  const getSurveyQuestionSlug = (el) => {
    const dataSource = $(el).closest('.ada-aba-survey-question');
    return dataSource.data('ada-aba-survey-question-slug');
  }

  const wireSurveyActions = function () {
    // add add button click event
    $('#ada-aba-survey-select').on('change', async function (e) {
      e.preventDefault();

      await refreshUI();
    });
  };

  const wireAvailableQuestionsActions = function () {
    // add add button click event
    $('.ada-aba-available-questions-add').on('click', async function (e) {
      e.preventDefault();

      const selectedSurvey = getSelectedSurvey();
      
      const dataSource = $(this).closest('.ada-aba-question');
      const slug = dataSource.data('ada-aba-question-slug');
      console.log({selectedSurvey, slug});
      await addSurveyQuestion(selectedSurvey, slug);
      await refreshUI();
    });
  };

  const wireSurveyQuestionsActions = function () {
    // add remove button click event
    $('.ada-aba-survey-questions-remove').on('click', async function (e) {
      e.preventDefault();
      
      const slug = getSurveyQuestionSlug(this);
      console.log({slug});
      await deleteSurveyQuestion(slug);
      await refreshUI();
    });

    $('.ada-aba-survey-questions-up').on('click', async function (e) {
      e.preventDefault();
      
      const slug = getSurveyQuestionSlug(this);
      console.log({slug});
      await moveUpSurveyQuestion(slug);
      await refreshUI();
    });

    $('.ada-aba-survey-questions-down').on('click', async function (e) {
      e.preventDefault();
      
      const slug = getSurveyQuestionSlug(this);
      console.log({slug});
      await moveDownSurveyQuestion(slug);
      await refreshUI();
    });

    $('.ada-aba-survey-questions-toggle-option').on('click', async function (e) {
      e.preventDefault();
      
      const slug = getSurveyQuestionSlug(this);
      console.log({slug});
      await toggleSurveyQuestionOptional(slug);
      await refreshUI();
    });
  };

  $(function () {
    availableQuestionsDiv = $('#ada-aba-available-questions');
    surveyQuestionsDiv = $('#ada-aba-survey-survey-questions');
    wireSurveyActions();
    wireAvailableQuestionsActions();
    wireSurveyQuestionsActions();
  });

})(jQuery);
