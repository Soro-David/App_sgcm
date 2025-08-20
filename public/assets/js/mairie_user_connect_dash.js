document.addEventListener('DOMContentLoaded', function () {

    const tableBody = document.getElementById('users-status-table-body');

    if (tableBody) {
        
        const fetchUrl = tableBody.dataset.url;

        async function fetchUsersStatus() {
            try {
                const response = await fetch(fetchUrl);
                if (!response.ok) {
                   throw new Error('Erreur réseau lors de la récupération des données.');
                }
                const users = await response.json();

                tableBody.innerHTML = '';

                if (users.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="5" class="text-center">Aucun autre utilisateur actif récemment.</td></tr>`;
                    return;
                }

                users.forEach(user => {
                    const statusClass = user.status === 'En ligne' ? 'bg-success' : 'bg-secondary';
                    const row = `
                        <tr>
                            <td>${user.name}</td>
                            <td>${user.role}</td>
                            <td>${user.login_time}</td>
                            <td>${user.logout_time}</td>
                            <td><span class="badge ${statusClass}">${user.status}</span></td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });

            } catch (error) {
                console.error("Impossible de mettre à jour la liste des utilisateurs:", error);
                tableBody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Erreur lors du chargement.</td></tr>`;
            }
        }

        fetchUsersStatus();

        setInterval(fetchUsersStatus, 10000);
    }
});