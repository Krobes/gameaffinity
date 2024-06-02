import {createResponseElement, findFirstTextareaEmpty} from "./domHelper.js";
import {OPTIONS_CHATGPT, URL_CHATGPT} from "./api.js";

const button_tag = document.querySelector("button");
const div_chatgpt_container = document.querySelector(".chatgpt-container");
const prompt_tags = document.querySelector("textarea");
button_tag.disabled = true;

prompt_tags.addEventListener("keydown", (event) => {
    if (event.key === "Enter") {
        event.preventDefault();
        button_tag.click();
    }
});

prompt_tags.addEventListener("input", () => {
    prompt_tags.value === ""
        ? (button_tag.disabled = true)
        : (button_tag.disabled = false);
});

button_tag.addEventListener("click", async () => {
    let output = findFirstTextareaEmpty();
    if (output === undefined) {
        alert("All textareas are busy!!!.");
    } else {
        const response = await fetch(
            URL_CHATGPT,
            OPTIONS_CHATGPT(prompt_tags.value)
        );
        const data = await response.json();
        output.value = data.choices[0].message.content;
    }
});

createResponseElement(div_chatgpt_container, prompt_tags);
