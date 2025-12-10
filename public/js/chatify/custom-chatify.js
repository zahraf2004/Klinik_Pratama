/**
 * Custom Chatify - All in One
 * - Custom contact management (dokter-pasien)
 * - Profile modal popup (WhatsApp style)
 * - No auto-open popup
 */

// Wait for all scripts to load
setTimeout(function() {
    console.log('Initializing custom chatify...');
    
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
                    updateMessageCounter(data.session, data.subscription);
                    
                    // Handle premium vs free users
                    if (data.session.is_premium) {
                        showPremiumStatus(data.subscription);
                        enableUnlimitedChat();
                    } else {
                        // Block input jika sudah limit untuk free users
                        if (data.session.has_reached_limit) {
                            blockMessageInput();
                        } else {
                            enableLimitedChat();
                        }
                    }
                }
            },
            error: (error) => {
                console.error('Error loading session:', error);
            }
        });
    }
    
    function updateMessageCounter(session, subscription) {
        if (session.is_premium) {
            // Premium user - show premium status
            showPremiumStatus(subscription);
        } else {
            // Free user - show message counter
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
    
    function blockMessageInput() {
        $('#message-form .m-send').attr('disabled', 'disabled').attr('placeholder', 'Limit pesan tercapai. Klik untuk upgrade premium.');
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
                    <strong>Limit pesan gratis tercapai!</strong>
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
    
    function endChatSession(patientId) {
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Sesi Berhasil Diakhiri!',
                        text: 'Sesi chat dengan pasien telah berakhir. Halaman akan dimuat ulang.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#28a745',
                        timer: 3000,
                        timerProgressBar: true
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Mengakhiri Sesi',
                        text: data.message || 'Terjadi kesalahan saat mengakhiri sesi',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#e74c3c'
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
    
    // Intercept send message untuk increment counter
    const originalSendMessage = window.sendMessage;
    if (typeof originalSendMessage === 'function' && currentUserRole === 'pasien') {
        window.sendMessage = function() {
            const targetUserId = typeof getMessengerId === 'function' ? getMessengerId() : null;
            
            if (targetUserId) {
                // Check current session first
                $.ajax({
                    url: url + "/getOrCreateSession",
                    method: "POST",
                    data: { 
                        _token: csrfToken, 
                        target_user_id: targetUserId 
                    },
                    dataType: "JSON",
                    success: (data) => {
                        // Jika user premium, langsung kirim pesan tanpa cek limit
                        if (data.session && data.session.is_premium) {
                            console.log('Premium user - sending message without limit check');
                            return originalSendMessage.apply(this, arguments);
                        }
                        
                        // Jika free user dan sudah limit, show payment modal
                        if (data.session && data.session.has_reached_limit) {
                            showPaymentModal();
                            return false;
                        }
                        
                        // Jika free user dan belum limit, increment counter dan kirim pesan
                        $.ajax({
                            url: url + "/incrementMessageCount",
                            method: "POST",
                            data: { 
                                _token: csrfToken, 
                                target_user_id: targetUserId 
                            },
                            dataType: "JSON",
                            success: (incrementData) => {
                                console.log('Message count incremented:', incrementData);
                                
                                if (incrementData.session) {
                                    updateMessageCounter(incrementData.session, data.subscription);
                                    
                                    if (incrementData.session.has_reached_limit) {
                                        blockMessageInput();
                                        // Show payment modal after reaching limit
                                        setTimeout(() => {
                                            showPaymentModal();
                                        }, 1000);
                                    }
                                }
                            }
                        });
                        
                        // Call original function
                        return originalSendMessage.apply(this, arguments);
                    }
                });
            } else {
                // Call original function if no target user
                return originalSendMessage.apply(this, arguments);
            }
        };
    }
    
    // Load session saat pilih kontak
    $(document).on('click', '.messenger-list-item', function() {
        setTimeout(loadChatSession, 500);
    });
    
    // Debug: Log semua elemen yang ada
    console.log('Avatar elements:', $('.header-avatar.show-infoSide').length);
    console.log('User name elements:', $('.user-name.show-infoSide').length);
    console.log('Info button elements:', $('.info-btn.show-infoSide').length);