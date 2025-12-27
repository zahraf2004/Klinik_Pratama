/**
 * Custom Chatify - All in One
 * - Custom contact management (dokter-pasien)
 * - Profile modal popup (WhatsApp style)
 * - No auto-open popup
 */

// Wait for all scripts to load
setTimeout(function() {
    console.log('Initializing custom chatify...');
    
    // Ensure variables are available
    if (typeof url === 'undefined') {
        window.url = $('meta[name="url"]').attr('content');
    }
    if (typeof csrfToken === 'undefined') {
        window.csrfToken = $('meta[name="csrf-token"]').attr('content');
    }
    
    console.log('URL:', url);
    console.log('CSRF Token:', csrfToken);
    
    // ========== CUSTOM CONTACT MANAGEMENT ==========
    
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
    
    // ========== PROFILE MODAL POPUP ==========
    
    // Sembunyikan sidebar info default
    $('.messenger-infoView').hide();
    
    // Buat modal HTML dengan data dokter lengkap
    const profileModalHTML = `
        <div id="profileModal" style="
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
        ">
            <div style="
                background: white;
                border-radius: 12px;
                width: 90%;
                max-width: 450px;
                max-height: 85vh;
                overflow-y: auto;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            ">
                <div style="
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 20px;
                    border-bottom: 1px solid #eee;
                    background: linear-gradient(135deg, #4a83d3 0%, #6b9fe3 100%);
                    color: white;
                ">
                    <h3 class="modal-title" style="margin: 0; font-size: 18px; font-weight: 600;">Detail Pengguna</h3>
                    <button class="profile-modal-close" style="
                        background: none;
                        border: none;
                        font-size: 24px;
                        cursor: pointer;
                        width: 30px;
                        height: 30px;
                        border-radius: 50%;
                        color: white;
                    ">&times;</button>
                </div>
                
                <!-- Avatar Section -->
                <div style="padding: 30px 20px; text-align: center; background: linear-gradient(135deg, #4a83d3 0%, #6b9fe3 100%); color: white;">
                    <div class="profile-avatar-large" style="
                        width: 100px;
                        height: 100px;
                        border-radius: 50%;
                        margin: 0 auto 15px;
                        background-size: cover;
                        background-position: center;
                        border: 4px solid rgba(255,255,255,0.3);
                        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                    "></div>
                    <h4 class="profile-name" style="margin: 0 0 5px 0; font-size: 20px; font-weight: 600;">Dr. Nama Dokter</h4>
                    <p class="profile-specialty" style="margin: 0; opacity: 0.9; font-size: 14px;">Dokter Umum</p>
                </div>
                
                <!-- Doctor Info Section -->
                <div style="padding: 0;">
                    <div class="doctor-info-container">
                        <!-- Loading state -->
                        <div class="loading-info" style="padding: 20px; text-align: center; color: #666;">
                            <i class="fas fa-spinner fa-spin"></i> Memuat data pengguna...
                        </div>
                        
                        <!-- User details will be loaded here -->
                        <div class="user-details" style="display: none;"></div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div style="padding: 20px; border-top: 1px solid #eee; background: #f8f9fa;">
                    <button class="delete-conversation-btn" style="
                        background: #e74c3c;
                        color: white;
                        border: none;
                        padding: 12px 20px;
                        border-radius: 8px;
                        cursor: pointer;
                        width: 100%;
                        font-weight: 500;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        gap: 8px;
                    ">
                        <i class="fas fa-trash"></i> Hapus Percakapan
                    </button>
                </div>
            </div>
        </div>
    `;
    
    // Tambahkan modal ke body
    $('body').append(profileModalHTML);
    console.log('Modal HTML added to body');
    console.log('Modal element:', $('#profileModal').length);
    
    // Disable default Chatify info sidebar handlers
    $(".messenger-infoView nav a, .show-infoSide").off('click');
    
    // Force hide info sidebar with CSS
    $('.messenger-infoView').css({
        'display': 'none !important',
        'visibility': 'hidden',
        'opacity': '0',
        'pointer-events': 'none'
    });
    
    // Handle klik untuk buka modal - HANYA MANUAL CLICK
    // Define functions first
    function showProfileModal() {
        console.log('Showing profile modal...');
        
        // Ambil data user dari header
        const userName = $('.user-name').text() || 'User';
        const userAvatar = $('.header-avatar').css('background-image');
        const userId = typeof getMessengerId === 'function' ? getMessengerId() : null;
        
        console.log('User name:', userName);
        console.log('User avatar:', userAvatar);
        console.log('User ID:', userId);
        console.log('Modal exists:', $('#profileModal').length);
        
        // Update modal content basic
        $('#profileModal .profile-name').text(userName);
        $('#profileModal .profile-avatar-large').css('background-image', userAvatar);
        
        // Show modal dengan display flex
        $('#profileModal').css('display', 'flex').hide().fadeIn(300);
        
        // Load user details
        if (userId) {
            loadUserDetails(userId);
        }
        
        console.log('Modal should be visible now');
    }
    
    function loadUserDetails(userId) {
        console.log('Loading user details for user:', userId);
        
        // Show loading
        $('.loading-info').show();
        $('.user-details').hide();
        
        $.ajax({
            url: url + "/getUserDetails",
            method: "POST",
            data: { 
                _token: csrfToken, 
                user_id: userId 
            },
            dataType: "JSON",
            success: (data) => {
                console.log('User data received:', data);
                
                if (data.user) {
                    const user = data.user;
                    
                    // Update modal title and specialty based on user type
                    if (user.user_type === 'doctor') {
                        $('.modal-title').text('Detail Dokter');
                        $('.profile-specialty').text(user.spesialisasi || 'Dokter');
                        renderDoctorDetails(user);
                    } else {
                        $('.modal-title').text('Detail Pasien');
                        $('.profile-specialty').text('Pasien');
                        renderPatientDetails(user);
                    }
                }
            },
            error: (error) => {
                console.error('Error loading user details:', error);
                $('.loading-info').html('<p style="color: #e74c3c;"><i class="fas fa-exclamation-triangle"></i> Gagal memuat data pengguna</p>');
            }
        });
    }
    
    function renderDoctorDetails(doctor) {
        let doctorInfoHTML = '';
        
        // Kredensial
        if (doctor.str || doctor.sip) {
            doctorInfoHTML += `
                <div style="padding: 15px 20px; border-bottom: 1px solid #eee;">
                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <div style="color: #4a83d3; font-size: 16px; margin-top: 2px;">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <div>
                            <h4 style="margin: 0 0 5px 0; font-size: 14px; font-weight: 600; color: #333;">Kredensial</h4>
                            <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.4;">
                                ${doctor.str ? 'STR: ' + doctor.str : ''}
                                ${doctor.str && doctor.sip ? '<br>' : ''}
                                ${doctor.sip ? 'SIP: ' + doctor.sip : ''}
                            </p>
                        </div>
                    </div>
                </div>
            `;
        }
        
        // Pengalaman
        if (doctor.pengalaman) {
            doctorInfoHTML += `
                <div style="padding: 15px 20px; border-bottom: 1px solid #eee;">
                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <div style="color: #4a83d3; font-size: 16px; margin-top: 2px;">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div>
                            <h4 style="margin: 0 0 5px 0; font-size: 14px; font-weight: 600; color: #333;">Pengalaman</h4>
                            <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.4;">${doctor.pengalaman}</p>
                        </div>
                    </div>
                </div>
            `;
        }
        
        // Jadwal Praktik dengan Jam
        if (doctor.jadwal_shift && doctor.jadwal_shift.length > 0) {
            const jadwalList = doctor.jadwal_shift
                .filter(jadwal => jadwal.hari)
                .map(jadwal => {
                    const jamMulai = jadwal.jam_mulai || '';
                    const jamSelesai = jadwal.jam_selesai || '';
                    const jam = (jamMulai && jamSelesai) ? ` (${jamMulai} - ${jamSelesai})` : '';
                    return `${jadwal.hari}${jam}`;
                });
            
            if (jadwalList.length > 0) {
                doctorInfoHTML += `
                    <div style="padding: 15px 20px; border-bottom: 1px solid #eee;">
                        <div style="display: flex; align-items: flex-start; gap: 12px;">
                            <div style="color: #4a83d3; font-size: 16px; margin-top: 2px;">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div style="width: 100%;">
                                <h4 style="margin: 0 0 8px 0; font-size: 14px; font-weight: 600; color: #333;">Jadwal Praktik</h4>
                                <div style="margin: 0; font-size: 13px; color: #666; line-height: 1.8;">
                                    ${jadwalList.map(jadwal => `<div style="margin-bottom: 4px;">• ${jadwal}</div>`).join('')}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
        }
        
        // Spesialisasi
        doctorInfoHTML += `
            <div style="padding: 15px 20px;">
                <div style="display: flex; align-items: flex-start; gap: 12px;">
                    <div style="color: #4a83d3; font-size: 16px; margin-top: 2px;">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0 0 5px 0; font-size: 14px; font-weight: 600; color: #333;">Spesialisasi</h4>
                        <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.4;">${doctor.spesialisasi || 'Dokter Umum'}</p>
                    </div>
                </div>
            </div>
        `;
        
        // Update modal content
        $('.user-details').html(doctorInfoHTML);
        $('.loading-info').hide();
        $('.user-details').show();
    }
    
    function renderPatientDetails(patient) {
        let patientInfoHTML = '';
        
        // Kontak
        if (patient.no_hp || patient.email) {
            patientInfoHTML += `
                <div style="padding: 15px 20px; border-bottom: 1px solid #eee;">
                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <div style="color: #4a83d3; font-size: 16px; margin-top: 2px;">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <h4 style="margin: 0 0 5px 0; font-size: 14px; font-weight: 600; color: #333;">Kontak</h4>
                            <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.4;">
                                ${patient.no_hp ? 'HP: ' + patient.no_hp : ''}
                                ${patient.no_hp && patient.email ? '<br>' : ''}
                                ${patient.email ? 'Email: ' + patient.email : ''}
                            </p>
                        </div>
                    </div>
                </div>
            `;
        }
        
        // Alamat
        if (patient.alamat) {
            patientInfoHTML += `
                <div style="padding: 15px 20px; border-bottom: 1px solid #eee;">
                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <div style="color: #4a83d3; font-size: 16px; margin-top: 2px;">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h4 style="margin: 0 0 5px 0; font-size: 14px; font-weight: 600; color: #333;">Alamat</h4>
                            <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.4;">${patient.alamat}</p>
                        </div>
                    </div>
                </div>
            `;
        }
        
        // Info Kesehatan
        if (patient.golongan_darah || patient.tinggi_badan || patient.berat_badan) {
            patientInfoHTML += `
                <div style="padding: 15px 20px; border-bottom: 1px solid #eee;">
                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <div style="color: #4a83d3; font-size: 16px; margin-top: 2px;">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <div>
                            <h4 style="margin: 0 0 5px 0; font-size: 14px; font-weight: 600; color: #333;">Info Kesehatan</h4>
                            <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.4;">
                                ${patient.golongan_darah ? 'Golongan Darah: ' + patient.golongan_darah : ''}
                                ${patient.golongan_darah && (patient.tinggi_badan || patient.berat_badan) ? '<br>' : ''}
                                ${patient.tinggi_badan ? 'Tinggi: ' + patient.tinggi_badan + ' cm' : ''}
                                ${patient.tinggi_badan && patient.berat_badan ? ', ' : ''}
                                ${patient.berat_badan ? 'Berat: ' + patient.berat_badan + ' kg' : ''}
                            </p>
                        </div>
                    </div>
                </div>
            `;
        }
        
        // Riwayat Penyakit & Alergi
        if (patient.riwayat_penyakit || patient.alergi) {
            patientInfoHTML += `
                <div style="padding: 15px 20px;">
                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <div style="color: #e74c3c; font-size: 16px; margin-top: 2px;">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <h4 style="margin: 0 0 5px 0; font-size: 14px; font-weight: 600; color: #333;">Riwayat Medis</h4>
                            <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.4;">
                                ${patient.riwayat_penyakit ? 'Riwayat: ' + patient.riwayat_penyakit : ''}
                                ${patient.riwayat_penyakit && patient.alergi ? '<br>' : ''}
                                ${patient.alergi ? 'Alergi: ' + patient.alergi : ''}
                            </p>
                        </div>
                    </div>
                </div>
            `;
        }
        
        // Update modal content
        $('.user-details').html(patientInfoHTML);
        $('.loading-info').hide();
        $('.user-details').show();
    }
    
    function closeProfileModal() {
        console.log('Closing modal...');
        $('#profileModal').fadeOut(300);
    }
    
    function loadSharedPhotos() {
        const userId = typeof getMessengerId === 'function' ? getMessengerId() : null;
        if (userId && typeof url !== 'undefined' && typeof csrfToken !== 'undefined') {
            // Load shared photos dari fungsi yang sudah ada
            $.ajax({
                url: url + "/shared",
                method: "POST",
                data: { _token: csrfToken, user_id: userId },
                dataType: "JSON",
                success: (data) => {
                    $('#profileModal .shared-photos-container').html(data.shared || '<p>Tidak ada media bersama</p>');
                },
                error: () => {
                    $('#profileModal .shared-photos-container').html('<p>Gagal memuat media</p>');
                }
            });
        } else {
            $('#profileModal .shared-photos-container').html('<p>Tidak ada media bersama</p>');
        }
    }
    
    // Event handlers after function definitions
    $(document).on('click', '.header-avatar.show-infoSide', function(e) {
        console.log('Avatar clicked!');
        e.preventDefault();
        e.stopPropagation();
        showProfileModal();
    });
    
    $(document).on('click', '.user-name.show-infoSide', function(e) {
        console.log('User name clicked!');
        e.preventDefault();
        e.stopPropagation();
        showProfileModal();
    });
    
    $(document).on('click', '.info-btn.show-infoSide', function(e) {
        console.log('Info button clicked!');
        e.preventDefault();
        e.stopPropagation();
        showProfileModal();
    });
    
    // Handle close modal
    $(document).on('click', '.profile-modal-close', function(e) {
        console.log('Close button clicked');
        e.preventDefault();
        closeProfileModal();
    });
    
    $(document).on('click', '.profile-modal-overlay', function(e) {
        if (e.target === this) {
            console.log('Overlay clicked');
            closeProfileModal();
        }
    });
    
    // Handle ESC key
    $(document).keydown(function(e) {
        if (e.keyCode === 27) { // ESC key
            console.log('ESC pressed');
            closeProfileModal();
        }
    });
    
    // Handle delete conversation
    $(document).on('click', '.delete-conversation-btn', function() {
        const userId = typeof getMessengerId === 'function' ? getMessengerId() : null;
        
        if (!userId) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Tidak ada percakapan yang dipilih',
                confirmButtonText: 'OK',
                confirmButtonColor: '#4a83d3'
            });
            return;
        }
        
        Swal.fire({
            title: 'Hapus Percakapan?',
            text: 'Apakah Anda yakin ingin menghapus seluruh percakapan ini? Tindakan ini tidak dapat dibatalkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus',
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                if (typeof deleteConversation === 'function') {
                    deleteConversation(userId);
                    closeProfileModal();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Percakapan Dihapus!',
                        text: 'Percakapan berhasil dihapus',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#28a745',
                        timer: 2000,
                        timerProgressBar: true
                    });
                }
            }
        });
    });
    
    // Override any function that tries to show info sidebar
    const originalToggle = $.fn.toggle;
    $.fn.toggle = function() {
        if (this.hasClass('messenger-infoView')) {
            console.log('Blocked messenger-infoView toggle');
            return this;
        }
        return originalToggle.apply(this, arguments);
    };
    
    const originalShow = $.fn.show;
    $.fn.show = function() {
        if (this.hasClass('messenger-infoView')) {
            console.log('Blocked messenger-infoView show');
            return this;
        }
        return originalShow.apply(this, arguments);
    };
    
    // Periodically force hide the sidebar
    setInterval(function() {
        if ($('.messenger-infoView').is(':visible')) {
            console.log('Force hiding messenger-infoView');
            $('.messenger-infoView').hide();
        }
    }, 500);
    
    console.log('Custom chatify loaded successfully - All in one!');
    
}, 1000); // Wait 1 second for all scripts to load
    
    // ========== PREMIUM CHAT SYSTEM ==========
    
    // Get current user role from meta tag or body class
    const currentUserRole = $('meta[name="user-role"]').attr('content') || 
                           ($('body').hasClass('role-dokter') ? 'dokter' : 'pasien');
    
    console.log('Current user role:', currentUserRole);
    
    // Tambah tombol "End Session" untuk dokter
    if (currentUserRole === 'dokter' && $('#endSessionBtn').length === 0) {
        $('body').append(`
            <button id="endSessionBtn" style="
                position: fixed; 
                top: 10px; 
                right: 10px; 
                z-index: 10000; 
                background: #e74c3c; 
                color: white; 
                padding: 10px 15px; 
                border: none; 
                border-radius: 8px; 
                cursor: pointer;
                font-weight: 500;
                box-shadow: 0 2px 8px rgba(0,0,0,0.2);
                display: flex;
                align-items: center;
                gap: 8px;
            ">
                <i class="fas fa-stop-circle"></i> Sesi Selesai
            </button>
        `);
        
        // Check patient token status and update button visibility
        updateEndSessionButton();
        
        $('#endSessionBtn').click(function() {
            const patientId = typeof getMessengerId === 'function' ? getMessengerId() : null;
            
            if (!patientId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Pilih pasien terlebih dahulu',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#4a83d3'
                });
                return;
            }
            
            // Check if button is in disabled state (patient has no tokens)
            if ($(this).css('cursor') === 'not-allowed') {
                Swal.fire({
                    icon: 'info',
                    title: 'Token Pasien Habis',
                    text: 'Pasien sudah menggunakan semua token gratis (0/3). Tidak ada sesi aktif untuk diakhiri.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#17a2b8'
                });
                return;
            }
            
            Swal.fire({
                title: 'Akhiri Sesi Chat?',
                text: 'Apakah Anda yakin ingin mengakhiri sesi chat dengan pasien ini? Sesi yang sudah diakhiri tidak dapat dikembalikan.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-stop-circle"></i> Ya, Akhiri Sesi',
                cancelButtonText: '<i class="fas fa-times"></i> Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    endChatSession(patientId);
                }
            });
        });
        
        console.log('End Session button added for doctor');
    }
    
    // Tambah message counter untuk pasien
    if (currentUserRole === 'pasien' && $('#messageCounter').length === 0) {
        $('.messenger-sendCard').prepend(`
            <div id="messageCounter" style="
                padding: 8px 15px;
                background: #d1ecf1;
                border: 1px solid #17a2b8;
                border-radius: 8px;
                margin-bottom: 10px;
                text-align: center;
                font-size: 13px;
                color: #0c5460;
                display: none;
            ">
                <i class="fas fa-info-circle"></i> 
                <span id="remainingMessages">Sisa pesan gratis: 3/3</span>
            </div>
        `);
        
        console.log('Message counter added for patient');
    }
    
    // Load session saat buka chat
    function loadChatSession() {
        const targetUserId = typeof getMessengerId === 'function' ? getMessengerId() : null;
        
        if (!targetUserId) return;
        
        $.ajax({
            url: url + "/getOrCreateSession",
            method: "POST",
            data: { 
                _token: csrfToken, 
                target_user_id: targetUserId 
            },
            dataType: "JSON",
            success: (data) => {
                console.log('Session loaded:', data);
                
                if (data.session && currentUserRole === 'pasien') {
                    updateSessionCounter(data.session, data.user_premium_status, data.subscription);
                    
                    // Handle premium vs free users
                    if (data.session.is_premium) {
                        showPremiumStatus(data.subscription);
                        enableUnlimitedChat();
                    } else {
                        // Jika bisa chat (ada session aktif), enable chat
                        if (data.session.can_chat) {
                            enableLimitedChat();
                        } else {
                            blockSessionInput();
                        }
                    }
                } else if (data.error && currentUserRole === 'pasien') {
                    // Jika error (token habis), block input dan show modal
                    blockSessionInput();
                    showPaymentModal();
                }
            },
            error: (error) => {
                console.error('Error loading session:', error);
            }
        });
    }
    
    function updateSessionCounter(session, userStatus, subscription) {
        if (session.is_premium) {
            // Premium user - show premium status
            showPremiumStatus(subscription);
        } else {
            // Free user - show session token counter
            $('#messageCounter').show();
            
            const remainingTokens = userStatus.remaining_session_tokens;
            
            if (remainingTokens <= 0) {
                $('#remainingMessages').html(`
                    <strong style="color: #e74c3c;">
                        <i class="fas fa-lock"></i> Session token habis! 
                        Upgrade ke premium untuk unlimited session.
                    </strong>
                `);
                $('#messageCounter').css({
                    'background': '#f8d7da',
                    'border-color': '#e74c3c',
                    'color': '#721c24',
                    'display': 'block'
                });
            } else {
                $('#remainingMessages').html(`
                    Session aktif - Sisa token: <strong>${remainingTokens}/3</strong>
                    <br><small>Token berkurang saat dokter end session</small>
                `);
                $('#messageCounter').css({
                    'background': '#d1ecf1',
                    'border-color': '#17a2b8',
                    'color': '#0c5460',
                    'display': 'block'
                });
            }
        }
    }
    
    function showPremiumStatus(subscription) {
        // Hide free message counter
        $('#messageCounter').hide();
        
        // Show premium status if not already shown
        if ($('#premiumStatus').length === 0) {
            $('.messenger-sendCard').prepend(`
                <div id="premiumStatus" style="
                    padding: 8px 15px;
                    background: linear-gradient(135deg, #28a745, #20c997);
                    border: 1px solid #28a745;
                    border-radius: 8px;
                    margin-bottom: 10px;
                    text-align: center;
                    font-size: 13px;
                    color: white;
                    display: block;
                ">
                    <i class="fas fa-crown"></i> 
                    <strong>Status Premium Aktif</strong>
                    ${subscription ? ` - ${subscription.plan_name === 'monthly' ? 'Bulanan' : 'Tahunan'} (${subscription.days_remaining} hari tersisa)` : ''}
                    ${subscription && subscription.is_expiring_soon ? ' <span style="color: #ffc107;"><i class="fas fa-exclamation-triangle"></i> Segera berakhir!</span>' : ''}
                </div>
            `);
        } else {
            // Update existing premium status
            $('#premiumStatus').html(`
                <i class="fas fa-crown"></i> 
                <strong>Status Premium Aktif</strong>
                ${subscription ? ` - ${subscription.plan_name === 'monthly' ? 'Bulanan' : 'Tahunan'} (${subscription.days_remaining} hari tersisa)` : ''}
                ${subscription && subscription.is_expiring_soon ? ' <span style="color: #ffc107;"><i class="fas fa-exclamation-triangle"></i> Segera berakhir!</span>' : ''}
            `);
        }
    }
    
    function enableUnlimitedChat() {
        // Enable input untuk premium users
        $('#message-form .m-send').removeAttr('disabled').attr('placeholder', 'Ketik pesan Anda...');
        $('#message-form button[type="submit"]').removeAttr('disabled');
        $('.upload-attachment').removeAttr('disabled');
        
        // Remove premium click handler
        $('#message-form .m-send').off('click.premium');
        
        console.log('Unlimited chat enabled for premium user');
    }
    
    function enableLimitedChat() {
        // Enable input untuk free users (belum limit)
        $('#message-form .m-send').removeAttr('disabled').attr('placeholder', 'Ketik pesan Anda...');
        $('#message-form button[type="submit"]').removeAttr('disabled');
        $('.upload-attachment').removeAttr('disabled');
        
        // Remove premium click handler
        $('#message-form .m-send').off('click.premium');
        
        console.log('Limited chat enabled for free user');
    }
    
    function blockSessionInput() {
        $('#message-form .m-send').attr('disabled', 'disabled').attr('placeholder', 'Session token habis. Klik untuk upgrade premium.');
        $('#message-form button[type="submit"]').attr('disabled', 'disabled');
        $('.upload-attachment').attr('disabled', 'disabled');
        
        // Add click handler to show payment modal
        $('#message-form .m-send').off('click.premium').on('click.premium', function() {
            showPaymentModal();
        });
        
        // Show upgrade button in message counter
        $('#messageCounter').html(`
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span>
                    <i class="fas fa-lock"></i> 
                    <strong>Session token habis!</strong>
                </span>
                <button onclick="showPaymentModal()" style="
                    background: #007bff;
                    color: white;
                    border: none;
                    padding: 5px 12px;
                    border-radius: 15px;
                    font-size: 12px;
                    cursor: pointer;
                    margin-left: 10px;
                ">
                    <i class="fas fa-crown"></i> Upgrade Premium
                </button>
            </div>
        `);
    }
    
    function updateMessageCounter(session, subscription) {
        // Show counter
        $('#messageCounter').show();
        
        if (session.has_reached_limit) {
            $('#remainingMessages').html(`
                <strong style="color: #e74c3c;">
                    <i class="fas fa-lock"></i> Limit pesan gratis tercapai! 
                    Upgrade ke premium untuk melanjutkan.
                </strong>
            `);
            $('#messageCounter').css({
                'background': '#f8d7da',
                'border-color': '#e74c3c',
                'color': '#721c24',
                'display': 'block'
            });
        } else {
            $('#remainingMessages').html(`
                Sisa pesan gratis: <strong>${session.remaining_messages}/3</strong>
            `);
            $('#messageCounter').css({
                'background': '#d1ecf1',
                'border-color': '#17a2b8',
                'color': '#0c5460',
                'display': 'block'
            });
        }
    }
    
    function blockMessageInput() {
        $('#message-form .m-send').attr('disabled', 'disabled').attr('placeholder', 'Upgrade ke premium untuk melanjutkan chat.');
        $('#message-form button[type="submit"]').attr('disabled', 'disabled');
        $('.upload-attachment').attr('disabled', 'disabled');
        
        // Add visual indicator
        $('#message-form').addClass('blocked-input');
        
        // Show permanent warning with payment modal trigger
        if ($('#tokenWarning').length === 0) {
            $('.messenger-sendCard').prepend(`
                <div id="tokenWarning" style="
                    padding: 10px 15px;
                    background: #f8d7da;
                    border: 1px solid #e74c3c;
                    border-radius: 8px;
                    margin-bottom: 10px;
                    text-align: center;
                    font-size: 13px;
                    color: #721c24;
                ">
                    <i class="fas fa-lock"></i> 
                    <strong>Upgrade ke Premium!</strong> 
                    <a href="javascript:void(0)" onclick="showPaymentModalWithRetry()" style="color: #721c24; text-decoration: underline;">Upgrade sekarang</a> untuk chat unlimited.
                </div>
            `);
        }
    }

    function endChatSession(patientId) {
        // Debug logging
        console.log('endChatSession called with patientId:', patientId);
        console.log('url variable:', url);
        console.log('csrfToken variable:', csrfToken);
        console.log('Full URL will be:', url + "/endSession");
        
        // Show loading popup
        Swal.fire({
            title: 'Mengakhiri Sesi...',
            text: 'Mohon tunggu sebentar',
            icon: 'info',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.ajax({
            url: url + "/endSession",
            method: "POST",
            data: { 
                _token: csrfToken, 
                patient_id: patientId 
            },
            dataType: "JSON",
            success: (data) => {
                console.log('Session ended:', data);
                
                if (data.success) {
                    let successText = 'Sesi chat dengan pasien telah berakhir. Halaman akan dimuat ulang.';
                    
                    // Add token info if available
                    if (data.patient_remaining_tokens !== undefined) {
                        successText += `\n\nToken pasien tersisa: ${data.patient_remaining_tokens}/3`;
                    }
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Sesi Berhasil Diakhiri!',
                        text: successText,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#28a745',
                        timer: 4000,
                        timerProgressBar: true
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    // Handle different error types
                    let errorTitle = 'Gagal Mengakhiri Sesi';
                    let errorText = data.message || 'Terjadi kesalahan saat mengakhiri sesi';
                    
                    if (data.tokens_exhausted) {
                        errorTitle = 'Sesi Sudah Berakhir';
                        errorText = `Pasien sudah menggunakan semua token gratis (${data.patient_remaining_tokens}/3). Tidak ada sesi aktif untuk diakhiri.`;
                    }
                    
                    Swal.fire({
                        icon: data.tokens_exhausted ? 'info' : 'error',
                        title: errorTitle,
                        text: errorText,
                        confirmButtonText: 'OK',
                        confirmButtonColor: data.tokens_exhausted ? '#17a2b8' : '#e74c3c'
                    });
                }
            },
            error: (error) => {
                console.error('Error ending session:', error);
                
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Tidak dapat terhubung ke server. Silakan coba lagi.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#e74c3c'
                });
            }
        });
    }
    
    // Intercept send message untuk block jika token habis
    const originalSendMessage = window.sendMessage;
    
    console.log('Original sendMessage function:', typeof originalSendMessage);
    console.log('Current user role:', currentUserRole);
    
    if (typeof originalSendMessage === 'function' && currentUserRole === 'pasien') {
        console.log('Setting up sendMessage interceptor for patient');
        
        window.sendMessage = function() {
            console.log('sendMessage intercepted!');
            
            const targetUserId = typeof getMessengerId === 'function' ? getMessengerId() : null;
            console.log('Target user ID:', targetUserId);
            
            if (targetUserId) {
                console.log('Checking chat permission...');
                
                // Block message immediately and check permission
                const messageInput = $('#message-form input[name="message"]');
                const originalValue = messageInput.val();
                
                // Check if user can still send messages BEFORE sending
                $.ajax({
                    url: url + "/checkChatPermission",
                    method: "POST",
                    data: { 
                        _token: csrfToken, 
                        target_user_id: targetUserId 
                    },
                    dataType: "JSON",
                    success: (permissionData) => {
                        console.log('Permission check result:', permissionData);
                        
                        if (!permissionData.can_chat || permissionData.tokens_exhausted) {
                            console.log('User cannot chat - tokens exhausted, showing payment modal');
                            showPaymentModalWithRetry();
                            
                            // Clear the input to prevent message from being sent
                            messageInput.val('');
                            
                            // Hanya tampilkan popup premium tanpa redirect
                            
                            return false; // Block message
                        } else {
                            // User can chat - restore input and send message
                            console.log('User can chat, sending message');
                            messageInput.val(originalValue);
                            
                            // Call original function with proper context
                            setTimeout(() => {
                                originalSendMessage.apply(window, arguments);
                            }, 100);
                        }
                    },
                    error: (xhr, status, error) => {
                        console.log('Permission check failed:', error);
                        console.log('Blocking message due to error to be safe');
                        
                        // Clear input and show error
                        messageInput.val('');
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Tidak dapat memverifikasi status chat. Silakan refresh halaman.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#e74c3c'
                        });
                    }
                });
                
                // Return false to prevent original function from executing immediately
                return false;
            } else {
                // Call original function if no target user
                return originalSendMessage.apply(this, arguments);
            }
        };
    } else {
        console.log('sendMessage interceptor not set up');
        console.log('Reason - sendMessage available:', typeof originalSendMessage === 'function');
        console.log('Reason - user is patient:', currentUserRole === 'pasien');
    }
    
    // Load session saat pilih kontak
    $(document).on('click', '.messenger-list-item', function() {
        setTimeout(loadChatSession, 500);
        
        // Check token status
        setTimeout(checkTokenStatus, 1000);
        
        // Update end session button for doctors
        if (currentUserRole === 'dokter') {
            setTimeout(updateEndSessionButton, 1200);
        }
    });
    
    // Alternative approach: Intercept form submit and send button clicks
    if (currentUserRole === 'pasien') {
        console.log('Setting up form submit interceptor for patient');
        
        // Intercept message form submit
        $(document).on('submit', '#message-form', function(e) {
            console.log('Message form submit intercepted');
            
            const targetUserId = typeof getMessengerId === 'function' ? getMessengerId() : null;
            if (targetUserId) {
                e.preventDefault(); // Stop form submission
                
                console.log('Checking permission before form submit...');
                checkPermissionAndProceed(targetUserId, () => {
                    console.log('Permission OK, submitting form');
                    // Remove event handler temporarily and submit
                    $(e.target).off('submit').submit();
                });
            }
        });
        
        // Intercept send button clicks
        $(document).on('click', '.messenger-sendCard button[type="submit"], .send-button', function(e) {
            console.log('Send button click intercepted');
            
            const targetUserId = typeof getMessengerId === 'function' ? getMessengerId() : null;
            if (targetUserId) {
                e.preventDefault(); // Stop button action
                
                console.log('Checking permission before send button...');
                checkPermissionAndProceed(targetUserId, () => {
                    console.log('Permission OK, triggering send');
                    // Trigger original send logic
                    if (typeof window.sendMessage === 'function') {
                        window.sendMessage();
                    }
                });
            }
        });
        
        // Function to check permission and proceed if allowed
        function checkPermissionAndProceed(targetUserId, callback) {
            $.ajax({
                url: url + "/checkChatPermission",
                method: "POST",
                data: { 
                    _token: csrfToken, 
                    target_user_id: targetUserId 
                },
                dataType: "JSON",
                success: (permissionData) => {
                    console.log('Form permission check result:', permissionData);
                    
                    if (!permissionData.can_chat || permissionData.tokens_exhausted) {
                        console.log('User cannot chat - tokens exhausted, showing payment modal');
                        
                        // Clear message input
                        $('#message-form input[name="message"]').val('');
                        
                        // Show payment modal
                        showPaymentModalWithRetry();
                        
                        // Hanya tampilkan popup premium tanpa redirect
                    } else {
                        console.log('User can chat, proceeding');
                        callback();
                    }
                },
                error: (xhr, status, error) => {
                    console.log('Form permission check failed:', error);
                    console.log('Blocking action due to error to be safe');
                    
                    // Clear message input
                    $('#message-form input[name="message"]').val('');
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Tidak dapat memverifikasi status chat. Silakan refresh halaman.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#e74c3c'
                    });
                }
            });
        }
    }
    
    // Function to check token status and block input if needed
    function checkTokenStatus() {
        const targetUserId = typeof getMessengerId === 'function' ? getMessengerId() : null;
        
        if (targetUserId) {
            $.ajax({
                url: url + "/checkChatPermission",
                method: "POST",
                data: { 
                    _token: csrfToken, 
                    target_user_id: targetUserId 
                },
                dataType: "JSON",
                success: (data) => {
                    if (!data.can_chat && data.remaining_tokens <= 0) {
                        blockMessageInput();
                    }
                    
                    // Update end session button for doctors
                    if (currentUserRole === 'dokter') {
                        updateEndSessionButtonState(data);
                    }
                },
                error: (error) => {
                    console.error('Error checking token status:', error);
                }
            });
        }
    }
    
    // Function to update end session button visibility and state
    function updateEndSessionButton() {
        const targetUserId = typeof getMessengerId === 'function' ? getMessengerId() : null;
        
        if (targetUserId && currentUserRole === 'dokter') {
            $.ajax({
                url: url + "/checkChatPermission",
                method: "POST",
                data: { 
                    _token: csrfToken, 
                    target_user_id: targetUserId 
                },
                dataType: "JSON",
                success: (data) => {
                    updateEndSessionButtonState(data);
                },
                error: (error) => {
                    console.error('Error checking patient token status:', error);
                }
            });
        }
    }
    
    function updateEndSessionButtonState(patientData) {
        const $endBtn = $('#endSessionBtn');
        
        if (patientData.remaining_tokens <= 0 && !patientData.has_active_subscription) {
            // Patient has no tokens left, change button style
            $endBtn.css({
                'background': '#6c757d',
                'cursor': 'not-allowed',
                'opacity': '0.7'
            }).html('<i class="fas fa-info-circle"></i> Token Habis');
            
            // Add tooltip or info
            $endBtn.attr('title', 'Pasien sudah menggunakan semua token gratis (0/3)');
        } else {
            // Normal state
            $endBtn.css({
                'background': '#e74c3c',
                'cursor': 'pointer',
                'opacity': '1'
            }).html('<i class="fas fa-stop-circle"></i> Sesi Selesai');
            
            $endBtn.attr('title', 'Akhiri sesi chat dengan pasien');
        }
    }
    
    // Function to show payment modal with retry mechanism
    function showPaymentModalWithRetry(retryCount = 0) {
        console.log('Attempting to show payment modal, retry:', retryCount);
        console.log('showPaymentModal function available:', typeof showPaymentModal);
        console.log('paymentModal element exists:', $('#paymentModal').length > 0);
        console.log('Bootstrap available:', typeof bootstrap);
        
        // Method 1: Try direct Bootstrap 5 modal first
        const modalEl = document.getElementById('paymentModal');
        if (modalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            console.log('Using Bootstrap 5 modal directly');
            try {
                const modal = new bootstrap.Modal(modalEl, {
                    backdrop: 'static',
                    keyboard: false
                });
                modal.show();
                return;
            } catch (error) {
                console.error('Bootstrap 5 modal error:', error);
            }
        }
        
        // Method 2: Try jQuery modal (Bootstrap 4 compatibility)
        if ($('#paymentModal').length > 0 && typeof $.fn.modal === 'function') {
            console.log('Using jQuery modal (Bootstrap 4)');
            try {
                $('#paymentModal').modal({
                    backdrop: 'static',
                    keyboard: false
                }).modal('show');
                return;
            } catch (error) {
                console.error('jQuery modal error:', error);
            }
        }
        
        // Method 3: Use existing showPaymentModal function
        if (typeof showPaymentModal === 'function') {
            console.log('Using showPaymentModal() function');
            try {
                showPaymentModal();
                return;
            } catch (error) {
                console.error('showPaymentModal error:', error);
            }
        }
        
        // Method 4: Retry after delay (max 3 times)
        if (retryCount < 3) {
            console.log('Retrying in 1000ms...');
            setTimeout(() => {
                showPaymentModalWithRetry(retryCount + 1);
            }, 1000);
            return;
        }
        
        // Method 5: Fallback to SweetAlert2 with better styling
        console.log('All methods failed, using SweetAlert2 fallback');
        Swal.fire({
            icon: 'warning',
            title: '<i class="fas fa-crown"></i> Upgrade ke Premium!',
            html: `
                <div style="text-align: left; margin: 20px 0;">
                    <p><strong>Anda sudah menggunakan 3 session gratis.</strong></p>
                    <p>Upgrade ke premium untuk:</p>
                    <ul style="text-align: left; margin-left: 20px;">
                        <li>✅ Chat unlimited dengan dokter</li>
                        <li>✅ Konsultasi 24/7</li>
                        <li>✅ Riwayat chat tersimpan</li>
                        <li>✅ Prioritas respon dokter</li>
                    </ul>
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 15px;">
                        <strong>Paket Bulanan: Rp 50.000</strong><br>
                        <small>Chat unlimited selama 1 bulan</small>
                    </div>
                </div>
            `,
            confirmButtonText: '<i class="fas fa-credit-card"></i> Upgrade Sekarang',
            confirmButtonColor: '#28a745',
            showCancelButton: true,
            cancelButtonText: 'Nanti Saja',
            cancelButtonColor: '#6c757d',
            width: '500px',
            customClass: {
                popup: 'payment-swal-popup'
            }
        }).then((result) => {
            // Tidak ada redirect, hanya tutup popup
            console.log('Premium popup closed');
        });
    }
    
    // Debug: Log semua elemen yang ada
    console.log('Avatar elements:', $('.header-avatar.show-infoSide').length);
    console.log('User name elements:', $('.user-name.show-infoSide').length);
    console.log('Info button elements:', $('.info-btn.show-infoSide').length);