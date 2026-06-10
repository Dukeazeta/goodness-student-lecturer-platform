const chatForm = document.querySelector("#chatForm");
const chatLog = document.querySelector("#chatLog");
const sidebarToggle = document.querySelector("[data-sidebar-toggle]");
const sidebarClose = document.querySelector("[data-sidebar-close]");

if (sidebarToggle) {
    sidebarToggle.addEventListener("click", () => {
        document.body.classList.toggle("sidebar-open");
    });
}

if (sidebarClose) {
    sidebarClose.addEventListener("click", () => {
        document.body.classList.remove("sidebar-open");
    });
}

document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
        document.body.classList.remove("sidebar-open");
    }
});

if (chatForm && chatLog) {
    chatForm.addEventListener("submit", async (event) => {
        event.preventDefault();
        const input = chatForm.querySelector("input[name='question']");
        const submitButton = chatForm.querySelector("button[type='submit']");
        const question = input.value.trim();
        if (!question) return;

        const payload = new FormData(chatForm);
        payload.set("question", question);
        appendBubble(question, "user-bubble");
        input.value = "";
        input.disabled = true;
        if (submitButton) submitButton.disabled = true;

        const loading = appendTypingBubble();
        const startedAt = Date.now();
        try {
            const response = await fetch(chatForm.action, {
                method: "POST",
                body: payload,
                headers: { "X-Requested-With": "XMLHttpRequest" },
            });
            const data = await response.json();
            await holdTypingBubble(startedAt);
            loading.classList.remove("typing-bubble");
            loading.textContent = data.answer;
            loading.classList.add("bubble-settled");
        } catch (error) {
            await holdTypingBubble(startedAt);
            loading.classList.remove("typing-bubble");
            loading.textContent = "The chatbot could not respond right now. Please try again.";
            loading.classList.add("bubble-settled");
        } finally {
            input.disabled = false;
            if (submitButton) submitButton.disabled = false;
            input.focus();
        }
    });
}

function appendBubble(text, className) {
    const bubble = document.createElement("div");
    bubble.className = `${className} bubble-animate`;
    bubble.textContent = text;
    chatLog.appendChild(bubble);
    chatLog.scrollTop = chatLog.scrollHeight;
    return bubble;
}

function holdTypingBubble(startedAt) {
    const minimumTypingTime = 950;
    const remaining = minimumTypingTime - (Date.now() - startedAt);
    return new Promise((resolve) => {
        window.setTimeout(resolve, Math.max(0, remaining));
    });
}

function appendTypingBubble() {
    const bubble = document.createElement("div");
    bubble.className = "bot-bubble bubble-animate typing-bubble";
    bubble.setAttribute("aria-label", "Chatbot is typing");
    bubble.innerHTML = "<span></span><span></span><span></span>";
    chatLog.appendChild(bubble);
    chatLog.scrollTop = chatLog.scrollHeight;
    return bubble;
}
