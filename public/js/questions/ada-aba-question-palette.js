(function ($, global) {
  'use strict';

  class QuestionsPalette {
    constructor() {
      this.palette = [
        new global.AdaAba.NoResponseQuestionPlugin(),
        new global.AdaAba.ShortAnswerQuestionPlugin(),
        new global.AdaAba.ParagraphQuestionPlugin(),
        new global.AdaAba.MultipleChoiceQuestionPlugin(),
        new global.AdaAba.CheckboxesQuestionPlugin(),
      ];
    }

    getQuestionPlugin(builderSlug) {
      return this.palette.find((plugin) => plugin.getSlug() === builderSlug);
    }
  }

  $(function () {
    global.AdaAba = { ...global.AdaAba, QuestionsPalette };
  });


})(jQuery, window);
