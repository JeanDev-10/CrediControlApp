document.addEventListener("DOMContentLoaded", () => {
    const systemPrefDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
    const userPref = localStorage.getItem("theme");

    const setTheme = (value) => {
        if (value === "dark") {
            document.documentElement.classList.add("dark");
            localStorage.setItem("theme", "dark");
            themeLabel.textContent = "Oscuro";
            themeIcon.textContent = "🌙";
        } else if (value === "light") {
            document.documentElement.classList.remove("dark");
            localStorage.setItem("theme", "light");
            themeLabel.textContent = "Claro";
            themeIcon.textContent = "☀️";
        } else {
            localStorage.removeItem("theme");
            if (systemPrefDark) {
                document.documentElement.classList.add("dark");
            } else {
                document.documentElement.classList.remove("dark");
            }
            themeLabel.textContent = "Sistema";
            themeIcon.textContent = "💻";
        }
    };

    // Elementos
    const themeButton = document.getElementById("theme-button");
    const themeOptions = document.getElementById("theme-options");
    window.themeLabel = document.getElementById("theme-label");
    window.themeIcon = document.getElementById("theme-icon");

    // Inicializar
    setTheme(userPref || "system");

    // Toggle opciones
    themeButton.addEventListener("click", () => {
        themeOptions.classList.toggle("hidden");
    });

    // Selección
    document.querySelectorAll("#theme-options button").forEach(btn => {
        btn.addEventListener("click", () => {
            setTheme(btn.dataset.theme);
            themeOptions.classList.add("hidden");
        });
    });
});
