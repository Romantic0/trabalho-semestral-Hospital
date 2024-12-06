let questions = []; // Lista de perguntas

// Adiciona pergunta
document.getElementById("questionForm").addEventListener("submit", async function (event) {
    event.preventDefault();

    const questionText = document.getElementById("questionText").value.trim();

    if (!questionText) {
        alert("O texto da pergunta não pode estar vazio!");
        return; // Impede o envio se o texto da pergunta estiver vazio
    }

    try {
        // Enviar a pergunta para o servidor
        const response = await fetch('http://localhost/hospitalv2/backend/functions/perguntas.php?action=add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ texto: questionText })
        });

        // Verifica se a resposta foi bem-sucedida
        if (!response.ok) {
            throw new Error('Erro na requisição: ' + response.statusText);
        }

        const result = await response.json();

        if (result.message) {
            alert(result.message); // Mostra a mensagem de sucesso
            // Recarregar as perguntas após adicionar
            await loadQuestions(); // Agora vamos carregar novamente as perguntas
        } else {
            alert("Erro: " + result.error);
        }
    } catch (error) {
        console.error("Erro ao adicionar pergunta:", error);
        alert("Erro ao adicionar pergunta: " + error.message);
    }
});

// Função para carregar perguntas do servidor
async function loadQuestions() {
    try {
        const response = await fetch('http://localhost/hospitalv2/backend/functions/perguntas.php?action=list');
        
        // Verifique se a resposta foi bem-sucedida
        if (!response.ok) {
            throw new Error('Erro na requisição: ' + response.statusText);
        }

        // Tente analisar a resposta como JSON
        const data = await response.json();
        
        if (data && Array.isArray(data)) {
            questions = data; // Atualiza a lista de perguntas
            renderQuestions(); // Renderiza as perguntas
        } else {
            console.error("Resposta inválida:", data);
        }
    } catch (error) {
        console.error("Erro ao carregar perguntas:", error);
        alert("Erro ao carregar perguntas: " + error.message);
    }
}

// Função para renderizar as perguntas na tabela
function renderQuestions() {
    const questionsList = document.getElementById("questionsList");
    questionsList.innerHTML = ""; // Limpa a tabela antes de renderizar

    questions.forEach((question) => {
        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${question.id}</td>
            <td>${question.texto}</td>
            <td class="actions">
                <button class="edit" onclick="editQuestion(${question.id})">Editar</button>
                <button class="delete" onclick="deleteQuestion(${question.id})">Excluir</button>
            </td>
        `;
        questionsList.appendChild(row);
    });
}

// Função para editar pergunta
function editQuestion(id) {
    const question = questions.find((q) => q.id === id);
    const newText = prompt("Edite o texto da pergunta:", question.texto);
    if (newText) {
        question.texto = newText;
        renderQuestions();
    }
}

// Função para excluir pergunta
function deleteQuestion(id) {
    if (confirm("Tem certeza que deseja excluir esta pergunta?")) {
        questions = questions.filter((q) => q.id !== id);
        renderQuestions();
    }
}

// Carregar perguntas ao iniciar
loadQuestions();


// Função para excluir pergunta
async function deleteQuestion(id) {
    if (confirm("Tem certeza que deseja excluir esta pergunta?")) {
        try {
            const response = await fetch('http://localhost/hospitalv2/backend/functions/perguntas.php?action=delete', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id }) // Envia o ID da pergunta para ser excluída
            });

            const result = await response.json();

            if (result.success) {
                // Caso a exclusão tenha sido bem-sucedida, removemos da lista e atualizamos a interface
                questions = questions.filter((q) => q.id !== id);
                renderQuestions(); // Atualiza a interface
                alert("Pergunta excluída com sucesso!");
            } else {
                alert("Erro ao excluir a pergunta: " + result.message);
            }
        } catch (error) {
            console.error("Erro ao excluir pergunta:", error);
            alert("Erro ao excluir pergunta.");
        }
    }
}
