const Reservation = require('../models/ReservationModel');
const Schedule = require('../models/ScheduleModel');
const Patient = require('../models/PatientModel');
const User = require('../models/UserModel');

// ==========================================
// 1. MEMBUAT RESERVASI (DENGAN NOMOR ANTRIAN OTOMATIS)
// ==========================================
exports.createReservation = async (req, res) => {
    try {
        // Ambil data dari inputan Body (JSON)
        const { scheduleId, symptoms } = req.body;
        
        // A. Cek apakah Pasien ada?
        const patient = await Patient.findOne({ where: { userId: req.userId } });
        if(!patient) {
            return res.status(404).json({msg: "Data profil pasien tidak ditemukan. Harap lengkapi profil dulu."});
        }

        // B. Cek apakah Jadwal Dokter ada?
        const schedule = await Schedule.findByPk(scheduleId);
        if(!schedule) {
            return res.status(404).json({msg: "Jadwal tidak ditemukan"});
        }

        // --- MULAI LOGIKA NOMOR ANTRIAN ---
        
        // 1. Cari antrian terakhir di jadwal ini (urutkan dari yang terbesar)
        const lastReservation = await Reservation.findOne({
            where: { scheduleId: scheduleId },
            order: [['queue_number', 'DESC']] 
        });

        // 2. Tentukan nomor antrian baru
        // Jika sudah ada yang daftar, nomor terakhir + 1. 
        // Jika belum ada (kosong), mulai dari 1.
        let newQueueNumber;
        if (lastReservation) {
            newQueueNumber = lastReservation.queue_number + 1;
        } else {
            newQueueNumber = 1;
        }

        // 3. Cek Kuota (Jangan biarkan daftar jika penuh)
        if(newQueueNumber > schedule.quota) {
            return res.status(400).json({msg: "Mohon maaf, kuota antrian untuk jadwal ini sudah PENUH."});
        }
        
        // --- SELESAI LOGIKA NOMOR ANTRIAN ---

        // C. Simpan ke Database
        const newReserv = await Reservation.create({
            reservation_date: new Date(), 
            status: 'pending',           
            symptoms: symptoms,
            patientId: patient.id,
            scheduleId: scheduleId,
            queue_number: newQueueNumber // <--- Masukkan nomor antrian yang baru dihitung
        });

        // D. Kirim balasan sukses
        res.status(201).json({ 
            msg: "Reservasi Berhasil!", 
            data: {
                nomor_antrian: newQueueNumber, // Tampilkan ke layar
                dokter_id: scheduleId,
                status: "pending"
            }
        });

    } catch (error) {
        res.status(500).json({ msg: error.message });
    }
}

// ==========================================
// 2. MELIHAT DAFTAR RESERVASI
// ==========================================
exports.getReservations = async (req, res) => {
    try {
        let response;
        
        if(req.role === "pasien"){
            const patient = await Patient.findOne({ where: { userId: req.userId } });
            if(!patient) return res.status(404).json({msg: "Data pasien tidak ditemukan"});
            
            response = await Reservation.findAll({
                where: { patientId: patient.id },
                include: [{ model: Schedule }]
            });
        } else {
            response = await Reservation.findAll({
                include: [
                    { model: Patient, attributes: ['name', 'phone'] }, 
                    { model: Schedule }
                ]
            });
        }
        res.status(200).json(response);
    } catch (error) {
        res.status(500).json({ msg: error.message });
    }
}

// ==========================================
// 3. UBAH STATUS (Approve / Reject)
// ==========================================
exports.approveReservation = async (req, res) => {
    const reservationId = req.params.id; 
    const { status } = req.body; 

    try {
        const reservation = await Reservation.findByPk(reservationId);
        if(!reservation) return res.status(404).json({msg: "Reservasi tidak ditemukan"});

        await Reservation.update({ status: status }, {
            where: { id: reservationId }
        });

        res.status(200).json({ msg: `Status reservasi berhasil diubah menjadi ${status}` });
    } catch (error) {
        res.status(500).json({ msg: error.message });
    }
}