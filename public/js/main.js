const sortableHeader = document.querySelectorAll('th.sortable-header');

// Sort table on click header
if (sortableHeader.length) {
    for (i = 0; i < sortableHeader.length; i++) {
        sortableHeader[i].addEventListener('click', function(event) {
            // Add active class to the element
            if (this.classList.contains('active')) {
                this.classList.toggle('desc');
            } else {
                // Remove active class from all elements
                for (j = 0; j < sortableHeader.length; j++) {
                    sortableHeader[j].classList.remove('active');
                    sortableHeader[j].classList.remove('desc');
                }

                this.classList.add('active');
            }

            const sortBy = this.dataset.sortable;
            const sortByAsc = this.classList.contains('desc') ? false : true;

            // Call livewire event to sort data
            window.livewire.emit('action:sort-table', sortBy, sortByAsc);
        });
    }
}