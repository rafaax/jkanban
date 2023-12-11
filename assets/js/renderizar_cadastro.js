function renderizarTela() {

    var elemento1 = document.createElement('div');
    elemento1.className = 'col-sm-3';

    var card1 = document.createElement('div');
    card1.className = 'card';

    var cardBody1 = document.createElement('div');
    cardBody1.className = 'card-body';

    var titulo1 = document.createElement('h5');
    titulo1.className = 'card-title';
    titulo1.textContent = 'Tarefa padrão';

    var texto1 = document.createElement('p');
    texto1.className = 'card-text';
    texto1.textContent = 'Tarefa atrelada a um ou mais usuários.';

    var link1 = document.createElement('a');
    link1.href = 'index?cadastro=padrao';
    link1.className = 'btn btn-primary';
    link1.textContent = 'Cadastrar';

    cardBody1.appendChild(titulo1);
    cardBody1.appendChild(texto1);
    cardBody1.appendChild(link1);

    card1.appendChild(cardBody1);
    elemento1.appendChild(card1);

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    var elemento2 = document.createElement('div');
    elemento2.className = 'col-sm-3';

    var card2 = document.createElement('div');
    card2.className = 'card';

    var cardBody2 = document.createElement('div');
    cardBody2.className = 'card-body';

    var titulo2 = document.createElement('h5');
    titulo2.className = 'card-title';
    titulo2.textContent = 'Tarefa com Sequência';

    var texto2 = document.createElement('p');
    texto2.className = 'card-text';
    texto2.textContent = 'Tarefa step-by-step, com 1 ou mais passos, no qual o segundo depende do primeiro para iniciar.';

    var link2 = document.createElement('a');
    link2.href = 'index?cadastro=sequencial';
    link2.className = 'btn btn-primary';
    link2.textContent = 'Cadastrar';

    cardBody2.appendChild(titulo2);
    cardBody2.appendChild(texto2);
    cardBody2.appendChild(link2);

    card2.appendChild(cardBody2);
    elemento2.appendChild(card2);


    var container = document.getElementById('container'); 
    container.appendChild(elemento1);
    container.appendChild(elemento2);

    
}