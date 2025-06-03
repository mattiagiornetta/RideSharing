function getSuggestions(idInput, idDestinazione) {
    let comuneInput = document.getElementById(idInput).value;
    fetch(`suggerisci_comune.php?citta=${comuneInput}`)
    .then(response => response.json())
    .then(data => {
        let suggerimenti = document.getElementById('suggestions');
        suggerimenti.innerHTML = '';
        data.forEach(comune => {
            let comuneSuggerimento = document.createElement('li');
            comuneSuggerimento.textContent = comune['nome'] + " ("+comune['sigla_automobilistica']+")";
            comuneSuggerimento.classList.add('p-2', 'cursor-pointer', 'hover:bg-gray-100');
            comuneSuggerimento.onclick = () => {
                document.getElementById(idInput).value = comune['nome'];
                document.getElementById(idDestinazione).value = comune['id'];

                suggerimenti.classList.add('hidden');
            };
            suggerimenti.appendChild(comuneSuggerimento);
        });

        suggerimenti.classList.remove('hidden');
    });
}