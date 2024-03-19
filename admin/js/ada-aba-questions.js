(function ($) {
  'use strict';

  let questionsDiv;
  let questionPlugin;

  const wireQuestionActions = function () {
    // add new button click event
    $('#ada-aba-question-new').on('click', async function (e) {
      e.preventDefault();

      const builderSlug = $('#ada-aba-question-builders').val()
      // console.log('builderSlug', builderSlug);
      const palette = new QuestionsPalette();
      questionPlugin = palette.getQuestionPlugin(builderSlug);

      // copy values into edit form
      const response = await getQuestionEditor(builderSlug);

      // insert into page
      insertEditorPanel(response.html);
    });
  };

  const insertEditorPanel = function (editPane) {
    $('#ada-aba-question-editor-panel').html(editPane);
    $('#ada-aba-question-editor').addClass('active');

    // wire editor
    // common actions
    wireCommonEditorActions();
    // per-question actions
    questionPlugin.wireEditorActions();
  }

  const insertPreviewPanel = function (previewPane) {
    $('#ada-aba-question-preview-panel').html(previewPane);
  }

  const updateEditorIdAndSlug = function (data) {
    $('#ada-aba-question-editor-id').val(data.id);
    $('#ada-aba-question-editor-slug').val(data.slug);
  }

  const getEditorIdAndSlug = function () {
    const id = $('#ada-aba-question-editor-id').val();
    const slug = $('#ada-aba-question-editor-slug').val();
    return { id, slug };
  }

  const wireCommonEditorActions = function () {
    $('#ada-aba-question-editor-preview').on('click', async function (e) {
      e.preventDefault();

      const slug = questionPlugin.getSlug();
      const data = questionPlugin.getEditorData();

      const response = await previewQuestion(slug, data);

      // insert into page
      insertPreviewPanel(response.html);
    });

    $('#ada-aba-question-editor-save').on('click', async function (e) {
      e.preventDefault();

      const slug = questionPlugin.getSlug();
      const idAndSlug = getEditorIdAndSlug();
      const data = { ...idAndSlug, ...questionPlugin.getEditorData() };

      const response = await saveQuestion(slug, data);

      // update questions list

      // update id info in editor
      updateEditorIdAndSlug(response.data);

      // insert into page
      insertPreviewPanel(response.html);
    });

    $('#ada-aba-question-editor-cancel').on('click', function (e) {
      e.preventDefault();

      $('#ada-aba-question-preview-panel').html('');

      $('#ada-aba-question-editor-panel').html('');
      $('#ada-aba-question-editor').removeClass('active');
      $('#ada-aba-question-editor-id').val('');
      $('#ada-aba-question-editor-slug').val('');
    });
  };

  const updateQuestions = async function () {
    const data = await loadQuestions();
    questionsDiv.html(data.html);
    wireQuestionActions();
  };

  const resetAddForm = function () {
    $('#ada-aba-questions-add-question-name').val('');
    $('#ada-aba-questions-add-question-url').val('');
    $('#ada-aba-questions-add-question-complete').prop('checked', false);
  };

  const resetEditForm = function () {
    $('#ada-aba-questions-edit-question-name').val('');
    $('#ada-aba-questions-edit-question-url').val('');
    $('#ada-aba-questions-edit-question-slug').val('');
    $('#ada-aba-questions-edit-question-complete').prop('checked', false);
  };

  const editQuestion = function (question) {
    $('#ada-aba-questions-edit-question-name').val(question.name);
    $('#ada-aba-questions-edit-question-url').val(question.url);
    $('#ada-aba-questions-edit-question-slug').val(question.slug);
    $('#ada-aba-questions-edit-question-complete').prop('checked', question.complete_on_progress);
  };

  const setupAddQuestionForm = function () {
    const form = $('#ada-aba-questions-add-question');
    form.on('submit', async function (e) {
      e.preventDefault();
      const name = $('#ada-aba-questions-add-question-name').val();
      const url = $('#ada-aba-questions-add-question-url').val();
      const completeOnProgress = $('#ada-aba-questions-add-question-complete').prop('checked');
      resetAddForm();

      const response = await addQuestion(name, url, completeOnProgress);
      await updateQuestions();
    });
  };

  const setupEditQuestionForm = function () {
    const form = $('#ada-aba-question-editor');
    form.on('submit', async function (e) {
      e.preventDefault();
    });
  };

  $(function () {
    questionsDiv = $('#ada-aba-questions');
    // setupAddQuestionForm();
    setupEditQuestionForm();
    wireQuestionActions();
  });

})(jQuery);
