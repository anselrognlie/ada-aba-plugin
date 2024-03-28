(function ($) {
  'use strict';

  let encoder = null;

  function htmlEncode(input) {
    if (!encoder) {
      encoder = document.createElement("textarea");
    }
    encoder.innerText = input;
    return encoder.innerHTML.split("<br>").join("\n");
  }

  const wireExecuteQueryActions = function () {
    // add delete button click event
    $('#ada-aba-execute-query-form').on('submit', async function (e) {
      e.preventDefault();

      const query = $(this).find('textarea').val();
      try
      {
        const result = await executeQuery(query);

        if (result['columns'].length == 0) {
          $('#ada-aba-execute-query-result').html('success');
          return;
        }

        const html_arr = [];
        html_arr.push('<table class="table">');
        html_arr.push('<thead>');
        html_arr.push('<tr>');
        for (const col of result['columns']) {
          html_arr.push(`<th scope="col">${htmlEncode(col)}</th>`);
        }
        html_arr.push('</tr>');
        html_arr.push('</thead>');
        html_arr.push('<tbody>');
        for (const row of result['rows']) {
          html_arr.push('<tr>');
          for (const val of row) {
            html_arr.push(`<td>${htmlEncode(val)}</td>`);
          }
          html_arr.push('</tr>');
        }
        html_arr.push('</tbody>');
        html_arr.push('</table>');

        $('#ada-aba-execute-query-result').html(html_arr.join(''));
      } catch (e) {
        $('#ada-aba-execute-query-result').html('query failed');
      }
    });
  };

  $(function () {
    wireExecuteQueryActions();
  });

})(jQuery);
