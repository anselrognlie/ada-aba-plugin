(function ($, global) {
  'use strict';

  class QuestionsPalette {
    constructor() {
      this.palette = [
        new NoResponseQuestionPlugin(),
        new ShortAnswerQuestionPlugin(),
        new ParagraphQuestionPlugin(),
      ];
    }

    getQuestionPlugin(builderSlug) {
      return this.palette.find((plugin) => plugin.getSlug() === builderSlug);
    }
  }

  $(function () {
    global.QuestionsPalette = QuestionsPalette;
  });


})(jQuery, window);
