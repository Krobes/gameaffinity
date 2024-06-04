//const API_KEY_CHATGPT = ""; Uncomment that lane and adding your key!

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

