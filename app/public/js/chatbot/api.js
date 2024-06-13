/*
 * Descripción: En este archivo se gestiona la consulta a la API de ChatGPT.
 * Como podemos apreciar, añadiremos en el body un role system, que lo que hace
 * es preparar al chatbotr para que solo responda a preguntas relacionadas con
 * videojuegos.
 *
 * Autor: Rafael Bonilla Lara
 */

//const API_KEY_CHATGPT;

export const URL_CHATGPT =
    "https://chatgpt-best-price.p.rapidapi.com/v1/chat/completions";

export const OPTIONS_CHATGPT = (prompt) => {
    return {
        method: "POST",
        headers: {
            "content-type": "application/json",
            "X-RapidAPI-Key": API_KEY_CHATGPT,
            "X-RapidAPI-Host": "chatgpt-best-price.p.rapidapi.com",
        },
        body: JSON.stringify({
            model: "gpt-3.5-turbo",
            messages: [
                {
                    role: "system",
                    content: "You are an expert in recommending video games. Please help users with professional opinions and recommendations about video games. If asked about anything else, politely inform them that you can only respond to questions related to video game opinions and recommendations."
                },
                {
                    role: "user",
                    content: prompt,
                },
            ],
        }),
    };
};

