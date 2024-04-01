(function ($, global) {
  'use strict';

  const WithOptionsQuestionPlugin = (parentClass) => class internalClass extends parentClass {
    constructor() {
      super();
    }

    wireOptionActions($option) {
      $option.find('.ada-aba-question-editor-panel-wo-option-remove').on('click', (event) => {
        const $target = $(event.target);
        const $option = $target.closest('.ada-aba-question-editor-panel-wo-option');
        $option.remove();
      });
      $option.find('.ada-aba-question-editor-panel-wo-option-up').on('click', (event) => {
        const $target = $(event.target);
        const $option = $target.closest('.ada-aba-question-editor-panel-wo-option');
        $option.insertBefore($option.prev());
      });
      $option.find('.ada-aba-question-editor-panel-wo-option-down').on('click', (event) => {
        const $target = $(event.target);
        const $option = $target.closest('.ada-aba-question-editor-panel-wo-option');
        $option.insertAfter($option.next());
      });
    }

    wireEditorActionsDerived() {
      const $options = $('#ada-aba-question-editor-panel-wo-options');
      this.wireOptionActions($options);

      $('.ada-aba-question-editor-panel-wo-options-add').on('click', (event) => {
        const $options = $('#ada-aba-question-editor-panel-wo-options');
        const $template = $('#ada-aba-question-editor-panel-wo-option-template');
        const $option = $template.contents().clone();
        $options.append($option);
        this.wireOptionActions($option);
      });
    }

    getEditorDataDerived(data) {
      const options = $('#ada-aba-question-editor-panel-wo-options')
        .find('textarea')
        .map((_, element) => {
          const $element = $(element);
          return $element.val();
        }).get();

      const show_other = $('#ada-aba-question-editor-panel-wo-show-other').is(':checked');
      const other_label = $('#ada-aba-question-editor-panel-wo-other-label').val();

      return {...data, options, show_other, other_label};
    }
  }

  $(function () {
    global.WithOptionsQuestionPlugin = WithOptionsQuestionPlugin(QuestionBasePlugin);
  });

})(jQuery, window);
