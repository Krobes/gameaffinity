/*
 * Descripción: En este archivo creamos los elementos del HTML según se vayan produciendo
 * los diferentes eventos.
 *
 * Autor: Rafael Bonilla Lara
 */

const createIconsContainer = (
    div_chatgpt_container,
    responseTextArea,
    prompt_tags
) => {
    const icons_container_div = document.createElement("div");
    icons_container_div.className = "icons-container";

    const icon_add = document.createElement("button");
    const icon_clear = document.createElement("button");
    const icon_delete = document.createElement("button");

    icon_add.className = "fas fa-plus"; // Add icon
    icon_clear.className = "fas fa-eraser"; // Clear icon
    icon_delete.className = "fas fa-trash"; // Delete icon

    icons_container_div.appendChild(icon_add);
    icons_container_div.appendChild(icon_clear);
    icons_container_div.appendChild(icon_delete);

    icon_add.addEventListener("click", () => {
        createResponseElement(div_chatgpt_container, prompt_tags);
    });

    icon_clear.addEventListener("click", () => {
        prompt_tags.value = "";
        responseTextArea.value = "";
    });

    icon_delete.addEventListener("click", () => {
        if (!isTheLastTextarea()) {
            icons_container_div.parentElement.remove();
        }
    });

    return icons_container_div;
};

export const createResponseElement = (div_chatgpt_container, prompt_tags) => {
    const responseTextArea = document.createElement("textarea");
    responseTextArea.placeholder = "Response";
    responseTextArea.setAttribute("readonly", true);
    responseTextArea.setAttribute("rows", "5");

    const response_container_div = document.createElement("div");
    response_container_div.className = "response-container";

    response_container_div.appendChild(responseTextArea);
    response_container_div.appendChild(
        createIconsContainer(div_chatgpt_container, responseTextArea, prompt_tags)
    );

    div_chatgpt_container.appendChild(response_container_div);
};

export const findFirstTextareaEmpty = () => {
    const responseTextAreas = document.querySelectorAll(
        ".response-container textarea"
    );
    return [...responseTextAreas].find((responseTextArea) => {
        return responseTextArea.value === "";
    });
};

export const isTheLastTextarea = () => {
    const responseTextAreas = document.querySelectorAll(".response-container");
    return responseTextAreas.length === 1;
};
