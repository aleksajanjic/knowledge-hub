window.handleDropdownAction = function (e, questionId, type) {
    e.preventDefault();
    e.stopPropagation();

    const dropdown = document.getElementById(`dropdown-${questionId}`);
    if (dropdown) dropdown.style.display = "none";

    openEditModal(questionId, type);
};
