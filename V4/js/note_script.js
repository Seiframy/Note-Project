document.addEventListener('DOMContentLoaded', function () {
    const notesContainer = document.getElementById('notesContainer');
    const searchInput = document.getElementById('searchNotes');

    // Function to render notes as cards
    function renderNotes(notes) {
        notesContainer.innerHTML = ''; // Clear previous notes

        if (notes.length === 0) {
            notesContainer.innerHTML = '<p>No notes found.</p>';
            return;
        }

        notes.forEach(note => {
            const card = document.createElement('div');
            card.classList.add('card', 'pink-card', 'shadow-sm', 'mt-4');

            const cardBody = document.createElement('div');
            cardBody.classList.add('card-body');

            const cardTitle = document.createElement('h5');
            cardTitle.classList.add('card-title');
            cardTitle.textContent = note.title;

            const cardText = document.createElement('p');
            cardText.classList.add('card-text');
            cardText.textContent = note.content;

            cardBody.appendChild(cardTitle);
            cardBody.appendChild(cardText);
            card.appendChild(cardBody);

            notesContainer.appendChild(card);
        });
    }

    // Event listener for searching notes
    searchInput.addEventListener('input', function () {
        const searchTerm = searchInput.value.trim().toLowerCase();

        // Send AJAX request to fetch filtered notes
        if (searchTerm) {
            fetchNotes(searchTerm);
        } else {
            renderNotes([]); // No search term, render nothing
        }
    });

    // Function to fetch notes from the server
    function fetchNotes(query) {
        fetch('../php/search_notes.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ query: query })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderNotes(data.notes);
                } else {
                    renderNotes([]); // If no notes are found
                }
            })
            .catch(error => {
                console.error('Error fetching notes:', error);
            });
    }
});
