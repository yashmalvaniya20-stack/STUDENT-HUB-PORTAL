document.addEventListener("DOMContentLoaded", function() {
    const studentForm = document.getElementById("studentForm");
    const tableBody = document.getElementById("studentTableBody");
    const alertBox = document.getElementById("alertBox");
    const cancelBtn = document.getElementById("cancelBtn");
    const formTitle = document.getElementById("formTitle");
    const saveBtn = document.getElementById("saveBtn");
    const searchInput = document.getElementById("searchInput");

    let allStudents = [];

    function showAlert(type, message) {
        alertBox.className = "alert alert-" + type;
        alertBox.textContent = message;
        alertBox.style.display = "block";
        setTimeout(function() {
            alertBox.style.display = "none";
        }, 3500);
    }

    function loadStudents() {
        fetch("api.php?action=list")
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    allStudents = data.data;
                    renderTable(allStudents);
                } else {
                    showAlert("danger", data.message || "Failed to load students.");
                }
            })
            .catch(() => {
                showAlert("danger", "Network error when calling Fetch API.");
            });
    }

    function renderTable(list) {
        tableBody.innerHTML = "";
        if (!list || list.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="6" style="text-align:center;">No students found.</td></tr>';
            return;
        }

        list.forEach(student => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td><strong>${escapeHtml(student.student_code)}</strong></td>
                <td>${escapeHtml(student.name)}</td>
                <td>${escapeHtml(student.email)}</td>
                <td>${escapeHtml(student.course)}</td>
                <td>${escapeHtml(student.year)}</td>
                <td>
                    <button class="btn-sm btn-edit" data-id="${student.student_id}">Edit</button>
                    <button class="btn-sm btn-delete" data-id="${student.student_id}">Delete</button>
                </td>
            `;
            tableBody.appendChild(tr);
        });
    }

    function escapeHtml(str) {
        if (!str) return "";
        return String(str).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;");
    }

    searchInput.addEventListener("input", function() {
        const query = searchInput.value.toLowerCase().trim();
        const filtered = allStudents.filter(s =>
            s.name.toLowerCase().includes(query) ||
            s.student_code.toLowerCase().includes(query) ||
            s.email.toLowerCase().includes(query) ||
            s.course.toLowerCase().includes(query)
        );
        renderTable(filtered);
    });

    studentForm.addEventListener("submit", function(e) {
        e.preventDefault();

        const studentId = document.getElementById("student_id").value;
        const formData = new FormData(studentForm);
        formData.append("action", studentId ? "edit" : "add");

        fetch("api.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === "success") {
                showAlert("success", res.message);
                resetForm();
                loadStudents();
            } else {
                showAlert("danger", res.message);
            }
        })
        .catch(() => {
            showAlert("danger", "Error communicating with server.");
        });
    });

    tableBody.addEventListener("click", function(e) {
        const target = e.target;
        if (target.classList.contains("btn-edit")) {
            const id = target.getAttribute("data-id");
            fetch("api.php?action=get&id=" + id)
                .then(res => res.json())
                .then(res => {
                    if (res.status === "success" && res.data) {
                        const s = res.data;
                        document.getElementById("student_id").value = s.student_id;
                        document.getElementById("student_code").value = s.student_code;
                        document.getElementById("name").value = s.name;
                        document.getElementById("email").value = s.email;
                        document.getElementById("mobile").value = s.mobile;
                        document.getElementById("course").value = s.course;
                        document.getElementById("year").value = s.year;

                        formTitle.textContent = "Edit Student #" + s.student_id;
                        saveBtn.textContent = "Update Student";
                        cancelBtn.style.display = "inline-block";
                        window.scrollTo({ top: 0, behavior: "smooth" });
                    }
                });
        } else if (target.classList.contains("btn-delete")) {
            const id = target.getAttribute("data-id");
            if (confirm("Are you sure you want to delete this student asynchronously?")) {
                const formData = new FormData();
                formData.append("action", "delete");
                formData.append("student_id", id);

                fetch("api.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status === "success") {
                        showAlert("success", res.message);
                        loadStudents();
                    } else {
                        showAlert("danger", res.message);
                    }
                });
            }
        }
    });

    cancelBtn.addEventListener("click", resetForm);

    function resetForm() {
        studentForm.reset();
        document.getElementById("student_id").value = "";
        formTitle.textContent = "Add Student (No Page Reload)";
        saveBtn.textContent = "Save Student";
        cancelBtn.style.display = "none";
    }

    loadStudents();
});