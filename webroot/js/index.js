/**
 * Save Items' checked status to the database
 * @param {domElement} item
 */
async function saveItemStatus(item) {
    let id = item.parentNode.parentNode.dataset.id;
    let completed = item.checked;
    let url = "/items/save";
    let data = {
        id: id,
        completed: completed,
    };

    try {
        let res = await fetch(url, {
            method: "POST",
            body: JSON.stringify(data),
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-Token": csrfToken,
            },
        });

        // Réponse en JSON
        let json = await res.json();
        if (json.success) {
            document.querySelector("#advancement").innerHTML = json.advancement;
        }
    } catch (error) {
        console.log(error);
    }
}

/**
 * Save Item's new label to the database
 * @param {domElement} li
 */
function saveItem(li) {
    let id = li.dataset.id;
    let elementTxt = li.querySelector(".element_input").value;
    let deadlineValue = li.querySelector(".deadline_input").value;
    deadlineValue = deadlineValue.replace("T", " ");
    deadlineValue += ":00";
    let url = "/items/save";
    let data = {
        id: id,
        element: elementTxt,
        deadline: deadlineValue,
    };

    try {
        fetch(url, {
            method: "POST",
            body: JSON.stringify(data),
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-Token": csrfToken,
            },
        })
        .then(async function (response) {
            if (!response.ok) {
                // make the promise be rejected if we didn't get a 2xx response
                const err = new Error("Not 2xx response");
                err.response = response;
                throw err;
            } else {
                const texte =li.querySelector(".element_text");
                const input = li.querySelector(".element_input");
                const deadlineText =li.querySelector(".deadline");
                const deadline = li.querySelector(".deadline_input");

                texte.classList.toggle("hidden");
                input.classList.toggle("hidden");
                deadlineText.classList.toggle("hidden");
                deadline.classList.toggle("hidden");

                if (!response.redirected) {
                    let json = await response.json();
                    if (json.success) {
                        // Récupère la deadline en français et si elle est outdated, on ajoute la class outdated à .deadline
                        let outdated = json.outdated;
                        
                        // Remplace la deadline par la nouvelle deadline
                        texte.innerHTML = input.value;
                        deadlineText.innerHTML = json.deadline;

                        if (outdated) {
                            li.querySelector(".deadline").classList.add(
                                "outdated"
                            );
                        } else {
                            li.querySelector(".deadline").classList.remove(
                                "outdated"
                            );
                        }
                        document.querySelector("#error_message").classList.add("hidden");
                        return true;
                    }
                }
                document.querySelector("#error_message").classList.remove("hidden");
                return false
            }
        });
    } catch (error) {
        console.log(error);
    }
}

/**
 * Save Notification's seen statement to the database
 * @param {domElement} li
 */
async function seenNotification(li) {
    let id = li.dataset.id;
    let url = "/notifications/seen";
    let data = {
        id: id,
    };

    try {
        let res = await fetch(url, {
            method: "POST",
            body: JSON.stringify(data),
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-Token": csrfToken,
            },
        });

        // Réponse en JSON
        let json = await res.json();
        if (json.success) {
            li.classList.toggle("seen");

            const icon = li.querySelector(".seen_trigger i");
            icon.classList.toggle("fa-eye");
            icon.classList.toggle("fa-eye-slash");

            if (json.readby) {
                li.querySelector(
                    ".readby"
                ).innerHTML = `Seen on ${json.readby}`;
            }
            li.querySelector(".readby").classList.toggle("hidden");

            if (json.nbUnread > 0) {
                document
                    .querySelector("#notifications__trigger")
                    .classList.add("active");
            } else {
                document
                    .querySelector("#notifications__trigger")
                    .classList.remove("active");
            }
        }
    } catch (error) {
        console.log(error);
    }
}

/**
 * Delete Notification from the database
 * @param {domElement} li
 */
async function deleteNotification(li) {
    let id = li.dataset.id;
    let url = "/notifications/delete";
    let data = {
        id: id,
    };

    try {
        let res = await fetch(url, {
            method: "POST",
            body: JSON.stringify(data),
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-Token": csrfToken,
            },
        });

        // Réponse en JSON
        let json = await res.json();
        if (json.success) {
            li.remove();

            // Si le nombre de notif est 0, alors on affiche le texte (pas de notif), et on retire la pastille des notifs
            // Si il y a des notifs non lues, on affiche la pastille des notifs
            if (json.nbNotifs > 0) {
                if (json.nbUnread > 0) {
                    document
                        .querySelector("#notifications__trigger")
                        .classList.add("active");
                }
            } else {
                document
                    .querySelector("#notifications__trigger")
                    .classList.remove("active");
                document
                    .querySelector(".notifs_empty")
                    .classList.remove("hidden");
            }
        }
    } catch (error) {
        console.log(error);
    }
}

window.onload = function () {
    // Event click sur .edit_items pour edit l'input .element_input
    const edit_items = document.querySelectorAll(".edit_items");
    edit_items.forEach(function (element) {
        element.addEventListener("click", function (e) {
            e.preventDefault();

            if (
                element
                    .querySelector("i")
                    .classList.contains("fa-pen-to-square")
            ) {
                // Si l'element est l'icone de modification
                const input =
                    this.parentNode.parentNode.querySelector(".element_input");
                input.classList.toggle("hidden");
                input.focus();

                const inputDate =
                    this.parentNode.parentNode.querySelector(".deadline_input");
                inputDate.classList.toggle("hidden");

                const texte =
                    this.parentNode.parentNode.querySelector(".element_text");
                texte.classList.toggle("hidden");

                const deadline =
                    this.parentNode.parentNode.querySelector(".deadline");
                deadline.classList.toggle("hidden");

                element.querySelector("i").classList.toggle("fa-pen-to-square");
                element.querySelector("i").classList.toggle("fa-check");
            } else {
                // Si l'element est l'icone de validation
                const input =
                    this.parentNode.parentNode.querySelector(".element_input");
                if (input.value) {
                    // Si l'input n'est pas vide

                    saveItem(this.parentNode.parentNode);

                    element
                        .querySelector("i")
                        .classList.toggle("fa-pen-to-square");
                    element.querySelector("i").classList.toggle("fa-check");
                }
            }
        });
    });

    // Event click sur .notification .seen_trigger pour marquer comme vu
    const seen_trigger = document.querySelectorAll(
        ".notification .seen_trigger"
    );
    seen_trigger.forEach(function (element) {
        element.addEventListener("click", function (e) {
            e.preventDefault();
            seenNotification(this.parentNode.parentNode);
        });
    });

    // Event click sur .notification .delete_trigger pour supprimer la notification
    const delete_trigger = document.querySelectorAll(
        ".notification .delete_trigger"
    );
    delete_trigger.forEach(function (element) {
        element.addEventListener("click", function (e) {
            e.preventDefault();
            // AJAX /notifications/delete
            if (confirm("Do you want to delete this notification ?")) {
                deleteNotification(this.parentNode.parentNode);
            }
        });
    });

    // Open #notification dialog when clicking on #notifications__trigger
    const notifications_trigger = document.querySelector(
        "#notifications__trigger"
    );
    notifications_trigger.addEventListener("click", function (e) {
        e.preventDefault();
        // document.querySelector("#notifications").toggleAttribute("open");
        document.querySelector("#notifications").showModal();
    });
};
