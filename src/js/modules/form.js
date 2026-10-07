export function initForm() {
  const form = document.querySelector("[data-form]");
  const success = document.querySelector("[data-form-success]");
  if (!form) return;

  const alertBox = form.querySelector("[data-form-error]");
  const submit = form.querySelector("[data-form-submit]");
  const reset = document.querySelector("[data-form-reset]");

  const hideError = () => {
    if (!alertBox) return;
    alertBox.hidden = true;
    alertBox.textContent = "";
  };

  const showError = (message) => {
    if (!alertBox) return;
    alertBox.textContent = message;
    alertBox.hidden = false;
  };

  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    if (submit.disabled) return;
    hideError();

    const label = submit.textContent;
    submit.disabled = true;
    submit.textContent = "Отправляем…";

    try {
      const response = await fetch(form.action, {
        method: "POST",
        headers: {
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: new FormData(form),
      });

      let data = {};
      try {
        data = await response.json();
      } catch {
        /* пустой ответ */
      }

      if (response.ok && data.ok) {
        form.hidden = true;
        if (success) success.hidden = false;
        history.replaceState(null, "", location.pathname);
        return;
      }

      showError(
        data.error || "Не удалось отправить заявку. Попробуйте позже.",
      );
    } catch {
      showError("Сеть недоступна. Попробуйте позже или свяжитесь напрямую.");
    } finally {
      submit.disabled = false;
      submit.textContent = label;
    }
  });

  reset?.addEventListener("click", () => {
    if (success) success.hidden = true;
    form.hidden = false;
    form.reset();
    hideError();
    form.querySelector("input")?.focus();
  });
}
