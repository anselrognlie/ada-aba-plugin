(function ($) {
  'use strict';

  let questionsDiv;
  let questionPlugin;

  const wireQuestionActions = function () {
    // add delete button click event
    $('.ada-aba-questions-delete').on('click', async function (e) {
      e.preventDefault();

      const dataSource = $(this).closest('.ada-aba-question');
      const slug = dataSource.data('ada-aba-question-slug');
      const id = `${dataSource.data('ada-aba-question-id')}`;
      console.log('id', id);
      if (!confirm('Are you sure you want to delete this question?')) {
        return;
      }

      const editorId = $('#ada-aba-question-editor-id').val();
      console.log('editorId', editorId);
      if (id === editorId) {
        console.log('clearing editor');
        $('#ada-aba-question-editor-id').val('');
      }

      await deleteQuestion(slug);
      await updateQuestions();
    });

    // add edit button click event
    $('.ada-aba-questions-edit').on('click', async function (e) {
      e.preventDefault();

      const dataSource = $(this).closest('.ada-aba-question');
      const slug = dataSource.data('ada-aba-question-slug');

      // copy values into edit form
      const response = await getEditorForQuestion(slug);

      // insert into page
      insertEditorPanel(response.json.builder_slug, response.html);

      // update id info in editor
      updateEditorIdAndSlug(response.json);
    });
  };

  const wirePageActions = function () {
    // add new button click event
    $('#ada-aba-question-new').on('click', async function (e) {
      e.preventDefault();

      const builderSlug = $('#ada-aba-question-builders').val()
      // console.log('builderSlug', builderSlug);

      // copy values into edit form
      const response = await getQuestionBuilder(builderSlug);

      // insert into page
      insertEditorPanel(builderSlug, response.html);
    });
  };

  const insertEditorPanel = function (builderSlug, editPane) {
    $('#ada-aba-question-editor-panel').html(editPane);
    $('#ada-aba-question-editor').addClass('active');
    $('#ada-aba-question-editor-id').val('');
    $('#ada-aba-question-editor-slug').val('');

    // wire editor
    // common actions
    wireCommonEditorActions();

    // per-question actions
    const palette = new QuestionsPalette();
    questionPlugin = palette.getQuestionPlugin(builderSlug);
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
      await updateQuestions();

      // update id info in editor
      updateEditorIdAndSlug(response.json);

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
    questionsDiv = $('#ada-aba-questions-list');
    // setupAddQuestionForm();
    setupEditQuestionForm();
    wirePageActions();
    wireQuestionActions();
  });

})(jQuery);
