document.addEventListener("DOMContentLoaded", function () {
    const perguntasContainer = document.getElementById("perguntasContainer");

    // Função para carregar as perguntas do banco de dados
    async function loadPerguntas() {
        try {
            const response = await fetch('http://localhost/hospitalv2/backend/functions/perguntas.php?action=list');
            if (!response.ok) {
                throw new Error('Erro na requisição: ' + response.statusText);
            }

            const perguntas = await response.json();

            if (Array.isArray(perguntas) && perguntas.length > 0) {
                perguntas.forEach((pergunta) => {
                    const divPergunta = document.createElement("div");
                    divPergunta.classList.add("form-group");

                    const label = document.createElement("label");
                    label.textContent = pergunta.texto; // Usando o texto da pergunta

                    const escala = document.createElement("div");
                    escala.classList.add("scale");

                    // Criando os botões para a escala de 1 a 10
                    for (let i = 1; i <= 10; i++) {
                        const button = document.createElement("button");
                        button.textContent = i;
                        button.classList.add("nota");
                        button.style.backgroundColor = `hsl(${(i - 1) * 36}, 80%, 70%)`;
                        button.dataset.value = i;

                        button.addEventListener("click", function (e) {
                            e.preventDefault();
                            // Remover seleção anterior
                            escala.querySelectorAll(".nota").forEach(btn => btn.classList.remove("selected"));
                            // Adicionar classe selecionada
                            button.classList.add("selected");
                            button.dataset.selected = "true";
                        });

                        escala.appendChild(button);
                    }

                    divPergunta.appendChild(label);
                    divPergunta.appendChild(escala);
                    perguntasContainer.appendChild(divPergunta);
                });
            } else {
                console.error("Nenhuma pergunta encontrada ou formato inválido");
            }
        } catch (error) {
            console.error("Erro ao carregar perguntas:", error);
            alert("Erro ao carregar perguntas.");
        }
    }

    loadPerguntas();

    const form = document.getElementById("avaliacaoForm");
    form.addEventListener("submit", async function (e) {
        e.preventDefault();

        // Coleta os dados selecionados
        const avaliacoes = [];
        perguntasContainer.querySelectorAll(".scale").forEach((escala, index) => {
            const selected = escala.querySelector(".selected");
            if (selected) {
                avaliacoes.push({ pergunta_id: index + 1, resposta: Number(selected.dataset.value) });
            }
        });

        const feedback = document.getElementById("feedback").value;

        // Envio ao PHP
        try {
            const response = await fetch("../routes/salvar_avaliacao.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ avaliacoes, feedback }),
            });

            const result = await response.json();
            if (result.success) {
                alert("Avaliação salva com sucesso!");
                form.reset();
            } else {
                alert("Erro ao salvar a avaliação: " + result.message);
            }
        } catch (error) {
            console.error("Erro na requisição:", error);
            alert("Erro ao salvar a avaliação. Verifique sua conexão.");
        }
    });
});
