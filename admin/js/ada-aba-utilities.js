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

        const htmlArr = [];
        htmlArr.push('<table class="ada-aba-execute-query-result">');
        htmlArr.push('<thead>');
        htmlArr.push('<tr>');
        let lastTableName = '--';
        let span = 0;
        for (const col of result['columns']) {
          let tableName = col.split('.')[0];
          if (tableName === col) {
            tableName = '';
          }

          if (lastTableName !== tableName) {
            // emit prior heading
            if (span > 0) {
              htmlArr.push(`<th scope="colgroup" colspan="${span}">${htmlEncode(lastTableName)}</th>`);
            }
            lastTableName = tableName;
            span = 1;
          } else {
            span += 1;
          }
        }

        htmlArr.push(`<th scope="colgroup" colspan="${span}">${htmlEncode(lastTableName)}</th>`);
        htmlArr.push('</tr>');
        htmlArr.push('<tr>');

        for (const col of result['columns']) {
          let colName = col.split('.')[1];
          if (! colName) {
            colName = col;
          }
          htmlArr.push(`<th scope="col">${htmlEncode(colName)}</th>`);
        }
        htmlArr.push('</tr>');
        htmlArr.push('</thead>');
        htmlArr.push('<tbody>');
        for (const row of result['rows']) {
          htmlArr.push('<tr>');
          for (const val of row) {
            htmlArr.push(`<td>${htmlEncode(val)}</td>`);
          }
          htmlArr.push('</tr>');
        }
        htmlArr.push('</tbody>');
        htmlArr.push('</table>');

        $('#ada-aba-execute-query-result').html(htmlArr.join(''));
      } catch (e) {
        $('#ada-aba-execute-query-result').html('query failed');
      }
    });
  };

  $(function () {
    wireExecuteQueryActions();
  });

})(jQuery);
