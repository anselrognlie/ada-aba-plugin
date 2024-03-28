const executeQuery = async function (query) {
  const response = await fetch(`${ada_aba_vars.root}ada-aba/v1/queries`, {
    headers: {
      'X-WP-Nonce': ada_aba_vars.nonce,
      'Content-Type': 'application/json',
    },
    method: 'POST',
    body: JSON.stringify({ 
      query: query ,
    }),
  });
  if (response.status !== 200) {
    throw new Error('Failed to execute query');
  }
  return await response.json();
};
