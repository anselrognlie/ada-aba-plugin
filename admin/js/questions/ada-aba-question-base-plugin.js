(function ($, global) {
  'use strict';

  class QuestionBasePlugin {
    constructor() {
    }

    wireEditorActions() {
      this.wireEditorActionsDerived();
    }

    getEditorData() {
      const prompt = $('#ada-aba-question-editor-panel-prompt').val();
      const description = $('#ada-aba-question-editor-panel-description').val();
      const data = { prompt, description };
      return this.getEditorDataDerived(data);
    }
  }

  $(function () {
    global.QuestionBasePlugin = QuestionBasePlugin;
  });

})(jQuery, window);
