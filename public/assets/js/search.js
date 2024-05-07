// Récupérer les éléments HTML
const searchInput = document.getElementById('searchInput');
const projectTableBody = document.querySelector('tbody');

// Ajouter un écouteur d'événements pour le champ de recherche
searchInput.addEventListener('input', function() {
    // Récupérer le terme de recherche saisi par l'utilisateur
    const searchTerm = searchInput.value.trim();

    // Effectuer une requête AJAX uniquement si le terme de recherche n'est pas vide
    if (searchTerm !== '') {
        // Envoyer une requête AJAX au serveur pour récupérer les projets correspondant au terme de recherche
        fetch(`/projet/search?term=${searchTerm}`)
            .then(response => response.json())
            .then(data => {
                // Effacer le contenu de la table
                projectTableBody.innerHTML = '';

                // Afficher les projets correspondants dans la table
                if (data.length > 0) {
                    data.forEach(project => {
                        projectTableBody.innerHTML += `
                            <tr>
                                <td>${project.id}</td>
                                <td>${project.nomprojet}</td>
                                <td>${project.description}</td>
                                <td>${project.nomentreprise}</td>
                                <td>${project.email}</td>
                                <td>
                                    <a href="${project.showUrl}">Show</a>
                                    <a href="${project.editUrl}">Edit</a>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    // Afficher un message si aucun projet n'est trouvé
                    projectTableBody.innerHTML = `
                        <tr>
                            <td colspan="6">No records found</td>
                        </tr>
                    `;
                }
            })
            .catch(error => console.error('Error:', error));
    } else {
        // Si le champ de recherche est vide, afficher tous les projets
        fetch(`/projet`)
            .then(response => response.json())
            .then(data => {
                projectTableBody.innerHTML = '';

                if (data.length > 0) {
                    data.forEach(project => {
                        projectTableBody.innerHTML += `
                            <tr>
                                <td>${project.id}</td>
                                <td>${project.nomprojet}</td>
                                <td>${project.description}</td>
                                <td>${project.nomentreprise}</td>
                                <td>${project.email}</td>
                                <td>
                                    <a href="${project.showUrl}">Show</a>
                                    <a href="${project.editUrl}">Edit</a>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    projectTableBody.innerHTML = `
                        <tr>
                            <td colspan="6">No records found</td>
                        </tr>
                    `;
                }
            })
            .catch(error => console.error('Error:', error));
    }
});
