/**
 * Custom contact management untuk sistem pasien-dokter
 * Override fungsi updateContactItem untuk memastikan chat history tetap ada
 */

// Override fungsi updateContactItem yang ada
const originalUpdateContactItem = window.updateContactItem;

window.updateContactItem = function(user_id) {
    if (user_id != auth_id) {
        $.ajax({
            url: url + "/updateContacts",
            method: "POST",
            data: {
                _token: csrfToken,
                user_id,
            },
            dataType: "JSON",
            success: (data) => {
                // Cari item yang sudah ada
                const existingItem = $(".listOfContacts").find(".messenger-list-item[data-contact=" + user_id + "]");
                
                if (existingItem.length > 0) {
                    // Jika item sudah ada, hapus dan pindahkan ke atas
                    existingItem.remove();
                }
                
                // Tambahkan item di posisi paling atas
                if (data.contactItem) {
                    $(".listOfContacts").prepend(data.contactItem);
                }
                
                // Update selected contact jika sedang chat dengan user ini
                if (user_id == getMessengerId()) {
                    updateSelectedContact(user_id);
                }
                
                // Show/hide message hint
                const totalContacts = $(".listOfContacts").find(".messenger-list-item")?.length || 0;
                if (totalContacts > 0) {
                    $(".listOfContacts").find(".message-hint").hide();
                } else {
                    $(".listOfContacts").find(".message-hint").show();
                }
                
                // Update data-action untuk responsive design
                cssMediaQueries();
            },
            error: (error) => {
                console.error("Error updating contact:", error);
            },
        });
    }
};

// Override fungsi getContacts untuk menggunakan endpoint custom
const originalGetContacts = window.getContacts;

window.getContacts = function() {
    if (!contactsLoading && !noMoreContacts) {
        setContactsLoading(true);
        $.ajax({
            url: url + "/getContacts",
            method: "GET",
            data: { _token: csrfToken, page: contactsPage },
            dataType: "JSON",
            success: (data) => {
                setContactsLoading(false);
                if (contactsPage < 2) {
                    $(".listOfContacts").html(data.contacts);
                } else {
                    $(".listOfContacts").append(data.contacts);
                }
                updateSelectedContact();
                cssMediaQueries();
                
                // Pagination lock
                noMoreContacts = contactsPage >= data?.last_page;
                if (!noMoreContacts) contactsPage += 1;
            },
            error: (error) => {
                setContactsLoading(false);
                console.error("Error loading contacts:", error);
            },
        });
    }
};

console.log("Custom contact management loaded");
