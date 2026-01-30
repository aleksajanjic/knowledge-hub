// Load tab content via AJAX
window.loadTab = function (tabName) {
    // Update tab styling
    document.querySelectorAll('[id^="tab-"]').forEach((btn) => {
        btn.style.background = "transparent";
        btn.style.color = "#A1A1AA";
        btn.style.borderBottom = "none";
    });

    const activeTab = document.getElementById(`tab-${tabName}`);
    if (!activeTab) {
        return;
    }

    activeTab.style.background = "#18181B";
    activeTab.style.color = "#10B981";
    activeTab.style.borderBottom = "2px solid #10B981";

    // Show loading state
    const tabContent = document.getElementById("tab-content");

    if (!tabContent) return;

    tabContent.innerHTML = `
        <div style="text-align: center; padding: 48px; color: #71717A;">
            <svg style="width: 48px; height: 48px; margin: 0 auto; color: #10B981; animation: spin 1s linear infinite;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            <p style="margin-top: 16px;">Loading...</p>
        </div>
    `;

    // Get saved page for this tab
    const savedPage = localStorage.getItem(`admin_${tabName}_page`) || 1; // Fixed: was using backtick

    // Fetch tab content
    let url = `/admin/${tabName}?page=${savedPage}`;

    if (tabName === "categories") {
        // Categories placeholder
        tabContent.innerHTML = `
            <div style="text-align: center; padding: 48px; color: #71717A;">
                <p style="font-size: 18px;">Categories coming soon...</p>
            </div>
        `;

        // Update URL
        const newUrl = new URL(window.location);
        newUrl.searchParams.set("tab", tabName);
        window.history.pushState({}, "", newUrl);

        return;
    }

    fetch(url, {
        headers: {
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then((response) => response.text())
        .then((html) => {
            tabContent.innerHTML = html;

            // Save current tab and page
            localStorage.setItem("admin_active_tab", tabName);
            localStorage.setItem(`admin_${tabName}_page`, savedPage); // Fixed: was using backtick

            // Update URL
            const newUrl = new URL(window.location);
            newUrl.searchParams.set("tab", tabName);
            newUrl.searchParams.set("page", savedPage);
            window.history.pushState({}, "", newUrl);
        })
        .catch((error) => {
            console.error("Error:", error);
            tabContent.innerHTML = `
            <div style="text-align: center; padding: 48px; color: #F43F5E;">
                <p>Failed to load ${tabName}</p>
            </div>
        `;
        });
};

// Handle pagination clicks to save page number
document.addEventListener("click", function (e) {
    if (e.target.closest(".pagination a")) {
        e.preventDefault();
        const link = e.target.closest("a");
        const url = new URL(link.href);
        const page = url.searchParams.get("page") || 1;
        const activeTab = localStorage.getItem("admin_active_tab") || "users";

        // Save page number
        localStorage.setItem(`admin_${activeTab}_page`, page); // Fixed: was using backtick

        // Load that page
        loadTab(activeTab);
    }
});

// On page load
document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab =
        urlParams.get("tab") ||
        localStorage.getItem("admin_active_tab") ||
        "users";

    loadTab(activeTab);
});
