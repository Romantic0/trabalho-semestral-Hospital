document.addEventListener("DOMContentLoaded", async function () {
    try {
        // Buscar dados das médias das avaliações
        const responseMedias = await fetch("../routes/obter_medias.php");
        const dataMedias = await responseMedias.json();
        console.log("Resposta da API de médias:", dataMedias); // Verifique os dados aqui

        // Verifique se os dados possuem médias válidas
        dataMedias.forEach(item => {
            // Converte a média de string para número e arredonda
            const mediaText = (typeof item.media === 'string' && !isNaN(parseFloat(item.media))) 
                ? parseFloat(item.media).toFixed(1) 
                : "Sem avaliações"; // Converte a média para número

            const perguntaTexto = item.pergunta_texto;
            console.log("Pergunta:", perguntaTexto); // Verifique qual pergunta está sendo processada

            // Preencher os cards com as médias
            if (perguntaTexto.includes("limpeza")) {
                console.log("Preenchendo Limpeza com média:", mediaText); // Log para garantir que está preenchendo
                document.getElementById("mediaLimpeza").textContent = `Média: ${mediaText}`;
            } else if (perguntaTexto.includes("recepção")) {
                console.log("Preenchendo Recepção com média:", mediaText); // Log para garantir que está preenchendo
                document.getElementById("mediaRecepcao").textContent = `Média: ${mediaText}`;
            } else if (perguntaTexto.includes("agilidade")) {
                console.log("Preenchendo Agilidade com média:", mediaText); // Log para garantir que está preenchendo
                document.getElementById("mediaAgilidade").textContent = `Média: ${mediaText}`;
            } else if (perguntaTexto.includes("serviços médicos")) {
                console.log("Preenchendo Serviços Médicos com média:", mediaText); // Log para garantir que está preenchendo
                document.getElementById("mediaServicos").textContent = `Média: ${mediaText}`;
            }
        });

        // Buscar as avaliações detalhadas
        const responseAvaliacoes = await fetch("../routes/buscar_avaliacoes.php");
        const dataAvaliacoes = await responseAvaliacoes.json();

        if (dataAvaliacoes.error) {
            console.error(dataAvaliacoes.error);
            return;
        }

        // Exibir avaliações detalhadas
        const avaliacoesContainer = document.getElementById("avaliacoesContainer");
        dataAvaliacoes.forEach(avaliacao => {
            const divAvaliacao = document.createElement("div");
            divAvaliacao.classList.add("avaliacao");

            divAvaliacao.innerHTML = `
                <h4>${avaliacao.pergunta_texto}</h4>
                <p><strong>Resposta:</strong> ${avaliacao.resposta}</p>
                <p><strong>Feedback:</strong> ${avaliacao.feedback || "Nenhum comentário."}</p>
                <p><strong>Data:</strong> ${new Date(avaliacao.data_hora).toLocaleString()}</p>
            `;

            avaliacoesContainer.appendChild(divAvaliacao);
        });

    } catch (error) {
        console.error("Erro ao carregar os dados:", error);
    }
});
