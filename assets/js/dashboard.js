document.addEventListener('DOMContentLoaded', function () {
    let currentTab = 'all';
    loadContestants(currentTab);

    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelector('.tab-btn.active').classList.remove('active');
            btn.classList.add('active');
            currentTab = btn.dataset.status;
            loadContestants(currentTab);
        });
    });

    function loadContestants(status) {
        const tableContainer = document.getElementById('contestantTable');
        tableContainer.innerHTML = '<div class="loader">Loading contestants...</div>';

        fetch(`get_contestants.php?status=${status}`)
            .then(res => res.text())
            .then(html => {
                tableContainer.innerHTML = html;
                document.querySelectorAll('.view-btn').forEach(viewBtn => {
                    viewBtn.addEventListener('click', () => {
                        viewContestant(viewBtn.dataset.id);
                    });
                });
            })
            .catch(err => {
                console.error(err);
                tableContainer.innerHTML = '<p>Error loading contestants.</p>';
            });
    }

    function viewContestant(id) {
        const modal = document.getElementById('modal');
        const modalBody = document.getElementById('modalBody');
        modal.style.display = 'block';
        modalBody.innerHTML = 'Loading...';

        fetch(`view_contestant.php?id=${id}`)
            .then(res => res.text())
            .then(html => {
                modalBody.innerHTML = html;

                // Add event listener for modal close
                document.querySelector('.close-btn').addEventListener('click', () => {
                    modal.style.display = 'none';
                });
            });
    }

    // Global listener for approve/decline buttons loaded in modal
    window.approveContestant = function (id) {
        Swal.fire({
            title: 'Approve this contestant?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Approve',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('actions.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${id}&action=approve`
                }).then(res => res.json())
                  .then(data => {
                      Swal.fire(data.status, data.message, data.status);
                      setTimeout(() => {
                          document.getElementById('modal').style.display = 'none';
                          loadContestants(currentTab);
                      }, 1500);
                  });
            }
        });
    };

    window.submitDecline = function (id) {
        let reasonSelect = document.getElementById(`declineReason${id}`);
        let reason = reasonSelect.value;
        if (reason === 'Other') {
            reason = document.getElementById(`declineOther${id}`).value;
        }

        Swal.fire({
            title: 'Decline this contestant?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Decline',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('actions.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${id}&action=decline&reason=${encodeURIComponent(reason)}`
                }).then(res => res.json())
                  .then(data => {
                      Swal.fire(data.status, data.message, data.status);
                      setTimeout(() => {
                          document.getElementById('modal').style.display = 'none';
                          loadContestants(currentTab);
                      }, 1500);
                  });
            }
        });
    };
});
