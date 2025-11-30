<?php

namespace UseCases;


class AspekPenilaian
{

    public  $laporan_aspek = [
        [
            'key'      => 'struktur_organisasi',
            'label'    => 'Struktur dan Organisasi',
            'criteria' => [
                'Laporan tersusun sangat baik; alur logis; semua bagian lengkap dan tepat.',
                'Laporan tersusun baik; alur cukup logis; sebagian besar bagian lengkap.',
                'Tersusun kurang baik; alur tidak selalu logis; beberapa bagian kurang lengkap.',
                'Tidak tersusun baik; alur tidak logis; banyak bagian tidak lengkap.',
            ],
        ],
        [
            'key'      => 'pendahuluan',
            'label'    => 'Pendahuluan',
            'criteria' => [
                'Sangat jelas: latar belakang, rumusan masalah, tujuan, manfaat disajikan sangat baik.',
                'Jelas: latar belakang, masalah, tujuan, manfaat disajikan baik.',
                'Kurang jelas: sebagian elemen kurang tersaji dengan baik.',
                'Tidak jelas: banyak elemen tidak disajikan atau tidak lengkap.',
            ],
        ],
        [
            'key'      => 'tinjauan_pustaka',
            'label'    => 'Tinjauan Pustaka',
            'criteria' => [
                'Sangat komprehensif, relevan, dan terstruktur dengan sangat baik.',
                'Cukup komprehensif, relevan, terstruktur dengan baik.',
                'Kurang komprehensif; ada referensi kurang relevan/kurang terstruktur.',
                'Tidak komprehensif; banyak referensi tidak relevan/tidak terstruktur.',
            ],
        ],
        [
            'key'      => 'hasil_pembahasan',
            'label'    => 'Hasil dan Pembahasan',
            'criteria' => [
                'Hasil lengkap dan jelas; pembahasan sangat mendalam dan relevan.',
                'Hasil cukup lengkap dan jelas; pembahasan cukup mendalam dan relevan.',
                'Hasil kurang lengkap/kurang jelas; pembahasan kurang mendalam/relevan.',
                'Hasil tidak lengkap/tidak jelas; pembahasan tidak mendalam/tidak relevan.',
            ],
        ],
        [
            'key'      => 'kesimpulan_saran',
            'label'    => 'Kesimpulan dan Saran',
            'criteria' => [
                'Kesimpulan sangat jelas dan sesuai tujuan; saran sangat konstruktif dan relevan.',
                'Kesimpulan jelas dan sesuai; saran cukup konstruktif dan relevan.',
                'Kesimpulan kurang jelas/kurang sesuai; saran kurang konstruktif/relevan.',
                'Kesimpulan tidak jelas/tidak sesuai; saran tidak konstruktif/tidak relevan.',
            ],
        ],
        [
            'key'      => 'kepatuhan_format',
            'label'    => 'Kepatuhan pada Format',
            'criteria' => [
                'Sepenuhnya sesuai format; tidak ada kesalahan.',
                'Sebagian besar sesuai format; ada beberapa kesalahan kecil.',
                'Kurang sesuai format; ada beberapa kesalahan signifikan.',
                'Tidak sesuai format; banyak kesalahan.',
            ],
        ],
        [
            'key'      => 'kebahasaan',
            'label'    => 'Kebahasaan',
            'criteria' => [
                'Bahasa sangat baik; tanpa kesalahan tata bahasa/ejaan/tanda baca.',
                'Bahasa baik; sedikit kesalahan.',
                'Bahasa kurang baik; beberapa kesalahan tampak.',
                'Bahasa tidak baik; banyak kesalahan.',
            ],
        ],
        [
            'key'      => 'orisinalitas_inovasi',
            'label'    => 'Orisinalitas dan Inovasi',
            'criteria' => [
                'Sangat orisinal & inovatif; kontribusi signifikan.',
                'Cukup orisinal & inovatif; kontribusi baik.',
                'Kurang orisinal & inovatif; kontribusi terbatas.',
                'Tidak orisinal & inovatif; tanpa kontribusi.',
            ],
        ],
        [
            'key'      => 'referensi_sitasi',
            'label'    => 'Referensi dan Sitasi',
            'criteria' => [
                'Referensi sangat relevan & terkini; sitasi sangat baik dan konsisten.',
                'Referensi relevan & cukup terkini; sitasi baik dan konsisten.',
                'Referensi kurang relevan/kurang terkini; sitasi kurang baik/konsisten.',
                'Referensi tidak relevan/tidak terkini; sitasi tidak baik/tidak konsisten.',
            ],
        ],
    ];

    public $presentasi_aspek = [
        [
            'key'      => 'konten_struktur',
            'label'    => 'Konten dan Struktur',
            'criteria' => [
                'Konten sangat relevan, mendalam, dan terstruktur dengan jelas.',
                'Konten relevan dan terstruktur dengan baik; ada sedikit kekurangan.',
                'Konten relevan tetapi kurang mendalam/kurang terstruktur.',
                'Konten tidak relevan, tidak mendalam, dan tidak terstruktur.',
            ],
        ],
        [
            'key'      => 'pemahaman_topik',
            'label'    => 'Pemahaman Topik',
            'criteria' => [
                'Pemahaman sangat mendalam; menunjukkan pengetahuan luas.',
                'Pemahaman baik; ada beberapa kekurangan kedalaman.',
                'Pemahaman cukup; kurang mendalam dan ada kekurangan.',
                'Pemahaman kurang; tidak memadai.',
            ],
        ],
        [
            'key'      => 'kejelasan_keterbacaan',
            'label'    => 'Kejelasan dan Keterbacaan',
            'criteria' => [
                'Materi sangat jelas dan mudah dibaca.',
                'Materi jelas; beberapa bagian kurang mudah dibaca.',
                'Materi sering kurang jelas dan sulit dibaca.',
                'Materi tidak jelas dan sulit dibaca.',
            ],
        ],
        [
            'key'      => 'keterampilan_berbicara',
            'label'    => 'Keterampilan Berbicara',
            'criteria' => [
                'Sangat percaya diri; artikulasi jelas; intonasi & volume sangat baik.',
                'Percaya diri; artikulasi jelas; intonasi & volume baik.',
                'Kurang percaya diri; artikulasi kadang tidak jelas; intonasi/volume kurang konsisten.',
                'Tidak percaya diri; artikulasi tidak jelas; intonasi/volume tidak memadai.',
            ],
        ],
        [
            'key'      => 'penggunaan_alat_bantu',
            'label'    => 'Penggunaan Alat Bantu',
            'criteria' => [
                'Alat bantu sangat efektif dan meningkatkan pemahaman audiens.',
                'Alat bantu digunakan baik; ada beberapa kekurangan.',
                'Penggunaan alat bantu kurang efektif/tidak selalu mendukung.',
                'Alat bantu tidak digunakan atau tidak mendukung.',
            ],
        ],
        [
            'key'      => 'keterlibatan_audiens',
            'label'    => 'Keterlibatan Audiens',
            'criteria' => [
                'Sangat berhasil melibatkan audiens; interaksi aktif.',
                'Melibatkan audiens dengan baik; ada beberapa interaksi.',
                'Keterlibatan cukup; interaksi terbatas.',
                'Tidak berhasil melibatkan audiens; minim interaksi.',
            ],
        ],
        [
            'key'      => 'pengelolaan_waktu',
            'label'    => 'Pengelolaan Waktu',
            'criteria' => [
                'Sangat baik; sesuai waktu yang ditentukan.',
                'Baik; terdapat sedikit kekurangan dalam alokasi waktu.',
                'Kurang baik; sering melebihi/kurang dari waktu.',
                'Tidak efektif; terlalu lama atau terlalu singkat.',
            ],
        ],
        [
            'key'      => 'kreativitas_inovasi',
            'label'    => 'Kreativitas dan Inovasi',
            'criteria' => [
                'Sangat kreatif dan inovatif dalam penyampaian.',
                'Kreatif dan inovatif dengan beberapa elemen standar.',
                'Kurang kreatif; lebih mengandalkan metode standar.',
                'Tidak kreatif; sangat standar dan membosankan.',
            ],
        ],
        [
            'key'      => 'menjawab_pertanyaan',
            'label'    => 'Kemampuan Menjawab Pertanyaan',
            'criteria' => [
                'Jawaban sangat baik, jelas, dan mendalam; mampu menangani pertanyaan sulit.',
                'Jawaban baik dan jelas; ada sedikit kekurangan kedalaman.',
                'Jawaban cukup; sering kurang mendalam/memadai.',
                'Jawaban tidak memadai; tidak jelas/tidak relevan.',
            ],
        ],
    ];
    public $bahan_ajar_aspek = [
        [
            'key'      => 'relevansi_kurikulum',
            'label'    => 'Relevansi dengan Kurikulum',
            'criteria' => [
                'Bahan ajar sangat relevan dengan kurikulum dan mendukung pencapaian kompetensi dasar.',
                'Bahan ajar relevan dengan kurikulum, namun ada beberapa bagian yang kurang mendukung kompetensi dasar.',
                'Bahan ajar cukup relevan, namun ada beberapa bagian yang tidak mendukung kompetensi dasar.',
                'Bahan ajar tidak relevan dengan kurikulum dan tidak mendukung pencapaian kompetensi dasar.',
            ],
        ],
        [
            'key'      => 'tujuan_pembelajaran',
            'label'    => 'Kejelasan Tujuan Pembelajaran',
            'criteria' => [
                'Tujuan pembelajaran sangat jelas, spesifik, dan terukur.',
                'Tujuan pembelajaran jelas dan spesifik, namun kurang terukur.',
                'Tujuan pembelajaran kurang jelas dan tidak sepenuhnya terukur.',
                'Tujuan pembelajaran tidak jelas, tidak spesifik, dan tidak terukur.',
            ],
        ],
        [
            'key'      => 'kelengkapan_materi',
            'label'    => 'Kelengkapan Materi',
            'criteria' => [
                'Materi sangat lengkap dan mendalam, mencakup semua aspek yang diperlukan.',
                'Materi lengkap dan mendalam, namun ada beberapa aspek yang kurang detail.',
                'Materi cukup lengkap, namun ada beberapa aspek penting yang terlewat.',
                'Materi tidak lengkap dan dangkal, banyak aspek penting yang terlewat.',
            ],
        ],
        [
            'key'      => 'keakuratan_materi',
            'label'    => 'Keakuratan Materi',
            'criteria' => [
                'Materi sangat akurat, sesuai dengan fakta dan konsep yang benar.',
                'Materi akurat, namun ada beberapa kekurangan kecil.',
                'Materi cukup akurat, namun ada beberapa kesalahan yang perlu diperbaiki.',
                'Materi tidak akurat, banyak kesalahan yang perlu diperbaiki.',
            ],
        ],
        [
            'key'      => 'kejelasan_penyampaian',
            'label'    => 'Kejelasan Penyampaian',
            'criteria' => [
                'Materi disajikan dengan sangat jelas, mudah dipahami oleh siswa.',
                'Materi disajikan dengan jelas, namun ada beberapa bagian yang perlu penjelasan tambahan.',
                'Materi kurang jelas, beberapa bagian sulit dipahami oleh siswa.',
                'Materi tidak jelas, sulit dipahami oleh siswa.',
            ],
        ],
        [
            'key'      => 'contoh_ilustrasi',
            'label'    => 'Contoh dan Ilustrasi',
            'criteria' => [
                'Contoh dan ilustrasi sangat relevan dan membantu pemahaman materi.',
                'Contoh dan ilustrasi relevan, namun ada beberapa yang kurang mendukung pemahaman materi.',
                'Contoh dan ilustrasi cukup relevan, namun banyak yang kurang mendukung pemahaman materi.',
                'Contoh dan ilustrasi tidak relevan, tidak membantu pemahaman materi.',
            ],
        ],
        [
            'key'      => 'keterlibatan_siswa',
            'label'    => 'Keterlibatan Siswa',
            'criteria' => [
                'Bahan ajar sangat mendorong keterlibatan siswa dalam proses pembelajaran.',
                'Bahan ajar cukup mendorong keterlibatan siswa, namun ada beberapa bagian yang kurang interaktif.',
                'Bahan ajar kurang mendorong keterlibatan siswa, banyak bagian yang tidak interaktif.',
                'Bahan ajar tidak mendorong keterlibatan siswa, sangat tidak interaktif.',
            ],
        ],
        [
            'key'      => 'tugas_latihan',
            'label'    => 'Tugas dan Latihan',
            'criteria' => [
                'Tugas dan latihan sangat relevan, menantang, dan mendukung pencapaian kompetensi.',
                'Tugas dan latihan relevan, namun ada beberapa yang kurang menantang.',
                'Tugas dan latihan cukup relevan, namun banyak yang tidak menantang.',
                'Tugas dan latihan tidak relevan, tidak menantang, dan tidak mendukung pencapaian kompetensi.',
            ],
        ],
        [
            'key'      => 'penggunaan_bahasa',
            'label'    => 'Penggunaan Bahasa',
            'criteria' => [
                'Bahasa sangat jelas, sesuai dengan tingkat pemahaman siswa.',
                'Bahasa jelas, namun ada beberapa istilah yang perlu penjelasan tambahan.',
                'Bahasa kurang jelas, banyak istilah yang sulit dipahami oleh siswa.',
                'Bahasa tidak jelas, banyak istilah yang tidak sesuai dengan tingkat pemahaman siswa.',
            ],
        ],
        [
            'key'      => 'kesesuaian_teknologi',
            'label'    => 'Kesesuaian dengan Teknologi',
            'criteria' => [
                'Bahan ajar sangat sesuai dengan teknologi yang digunakan dalam pembelajaran.',
                'Bahan ajar cukup sesuai dengan teknologi, namun ada beberapa yang kurang mendukung.',
                'Bahan ajar kurang sesuai dengan teknologi, banyak yang tidak mendukung.',
                'Bahan ajar tidak sesuai dengan teknologi yang digunakan dalam pembelajaran.',
            ],
        ],
        [
            'key'      => 'evaluasi_feedback',
            'label'    => 'Evaluasi dan Feedback',
            'criteria' => [
                'Bahan ajar menyediakan evaluasi dan feedback yang sangat membantu bagi siswa.',
                'Bahan ajar menyediakan evaluasi dan feedback yang cukup membantu, namun ada beberapa kekurangan.',
                'Bahan ajar kurang menyediakan evaluasi dan feedback yang membantu bagi siswa.',
                'Bahan ajar tidak menyediakan evaluasi dan feedback yang membantu bagi siswa.',
            ],
        ],
    ];
    public  $modul_ajar_aspek = [
        [
            'key'      => 'identitas_modul',
            'label'    => 'Identitas Modul',
            'criteria' => [
                'Identitas modul jelas dan berisikan informasi lengkap Nama Penyusun, Jenjang Sekolah, Kelas, Alokasi Waktu',
                'Identitas modul jelas dan berisikan sebagian besar informasi identitas modul',
                'Minim informasi identitas modul dan kurang jelas',
                'Tidak ada informasi identitas modul',
            ],
        ],
        [
            'key'      => 'kompetensi_awal',
            'label'    => 'Kompetensi Awal',
            'criteria' => [
                'Pengetahuan dan keterampilan awal ... sangat jelas dan relevan dengan konten modul ajar.',
                'Pengetahuan dan keterampilan awal ... disebutkan dengan baik dan sebagian besar relevan dengan konten modul ajar.',
                'Pengetahuan dan keterampilan awal ... kurang baik dan kurang relevan dengan konten modul ajar.',
                'Pengetahuan dan keterampilan awal ... tidak disebutkan dan tidak relevan dengan konten modul ajar.',
            ],
        ],
        [
            'key'      => 'profil_pelajar_pancasila_1',
            'label'    => 'Profil Pelajar Pancasila (Integrasi ke Materi/Pedagogi/Projek/Asesmen)',
            'criteria' => [
                'Profil Pelajar Pancasila terintegrasi dalam materi, pedagogi, kegiatan projek dan asesmen',
                'Sebagian besar Profil Pelajar Pancasila terintegrasi dalam materi, pedagogi, kegiatan projek dan asesmen',
                'Profil Pelajar Pancasila hanya terintegrasi dalam salah satu komponen materi, pedagogi, kegiatan projek atau asesmen',
                'Profil Pelajar Pancasila tidak terintegrasi dalam materi, pedagogi, kegiatan projek dan asesmen',
            ],
        ],
        [
            'key'      => 'profil_pelajar_pancasila_2',
            'label'    => 'Profil Pelajar Pancasila (Integrasi pada Kegiatan Pembelajaran)',
            'criteria' => [
                'Profil Pelajar Pancasila terintegrasi dengan sangat relevan pada kegiatan pembelajaran',
                'Profil Pelajar Pancasila terintegrasi dengan baik pada kegiatan pembelajaran',
                'Profil Pelajar Pancasila terintegrasi pada kegiatan pembelajaran namun kurang sesuai',
                'Profil Pelajar Pancasila tidak terintegrasi pada kegiatan pembelajaran',
            ],
        ],
        [
            'key'      => 'sarana_prasarana',
            'label'    => 'Sarana dan Prasarana',
            'criteria' => [
                'Alat/bahan atau sumber ajar sangat relevan untuk pembelajaran bermakna dan efektif',
                'Sebagian besar alat/bahan atau sumber ajar relevan untuk pembelajaran bermakna dan efektif',
                'Sebagian kecil alat/bahan atau sumber ajar relevan untuk pembelajaran bermakna dan efektif',
                'Belum menunjukkan relevansi pada kegiatan pembelajaran bermakna dan efektif',
            ],
        ],
        [
            'key'      => 'target_peserta_didik',
            'label'    => 'Target Peserta Didik',
            'criteria' => [
                'Sesuai dengan tiga karakter kelompok peserta didik',
                'Sesuai dengan dua karakter kelompok peserta didik',
                'Sesuai dengan satu karakter kelompok peserta didik',
                'Tidak sesuai dengan karakter kelompok peserta didik',
            ],
        ],
        [
            'key'      => 'model_pembelajaran',
            'label'    => 'Model Pembelajaran',
            'criteria' => [
                'Model pembelajaran sangat jelas, terstruktur, mendetail',
                'Model pembelajaran jelas dan terstruktur, kurang mendetail',
                'Model pembelajaran tidak lengkap dan tidak terstruktur dengan baik',
                'Tidak memiliki model pembelajaran yang jelas',
            ],
        ],
        [
            'key'      => 'tujuan_pembelajaran_1',
            'label'    => 'Tujuan Pembelajaran (Relevansi)',
            'criteria' => [
                'Sangat relevan dengan kurikulum dan kebutuhan siswa',
                'Cukup relevan dengan kurikulum dan kebutuhan siswa',
                'Agak relevan namun masih ada yang tidak sesuai',
                'Tidak relevan dengan kurikulum dan kebutuhan siswa',
            ],
        ],
        [
            'key'      => 'tujuan_pembelajaran_2',
            'label'    => 'Tujuan Pembelajaran (Keterukuran)',
            'criteria' => [
                'Sangat terukur dengan indikator yang jelas dan rinci',
                'Dapat diukur dengan indikator yang cukup jelas',
                'Agak dapat diukur namun kurang jelas',
                'Tidak dapat diukur atau dinilai',
            ],
        ],
        [
            'key'      => 'pemahaman_bermakna',
            'label'    => 'Pemahaman Bermakna',
            'criteria' => [
                'Sangat relevan dan dapat diterapkan langsung dalam kehidupan nyata siswa',
                'Memiliki kaitan jelas dengan kehidupan nyata siswa',
                'Cukup memiliki kaitan namun tidak signifikan',
                'Tidak memiliki kaitan dengan kehidupan nyata siswa',
            ],
        ],
        [
            'key'      => 'pertanyaan_pemantik_1',
            'label'    => 'Pertanyaan Pemantik (Rasa Ingin Tahu)',
            'criteria' => [
                'Sangat memancing rasa ingin tahu dan membuat peserta didik ingin mencari tahu lebih banyak',
                'Memancing rasa ingin tahu peserta didik dengan baik',
                'Kurang memancing rasa ingin tahu',
                'Tidak memancing rasa ingin tahu peserta didik',
            ],
        ],
        [
            'key'      => 'pertanyaan_pemantik_2',
            'label'    => 'Pertanyaan Pemantik (Berpikir Kritis)',
            'criteria' => [
                'Sangat mendorong berpikir kritis secara mendalam',
                'Mendorong berpikir kritis dengan baik',
                'Kurang mendorong berpikir kritis',
                'Tidak mendorong berpikir kritis',
            ],
        ],
        [
            'key'      => 'kegiatan_pembelajaran_1',
            'label'    => 'Kegiatan Pembelajaran (Langkah)',
            'criteria' => [
                'Langkah kegiatan sangat jelas, terstruktur, konkret, mendetail',
                'Langkah kegiatan baik dan terstruktur',
                'Langkah kegiatan cukup jelas namun kurang terstruktur',
                'Langkah kegiatan tidak jelas dan tidak terstruktur',
            ],
        ],
        [
            'key'      => 'kegiatan_pembelajaran_2',
            'label'    => 'Kegiatan Pembelajaran (Alternatif)',
            'criteria' => [
                'Terdapat pilihan pembelajaran alternatif dan sangat sesuai kebutuhan belajar siswa',
                'Terdapat pilihan pembelajaran alternatif dan sesuai kebutuhan belajar siswa',
                'Terdapat pilihan pembelajaran alternatif namun kurang sesuai',
                'Tidak terdapat pilihan pembelajaran alternatif',
            ],
        ],
        [
            'key'      => 'asesmen_1',
            'label'    => 'Asesmen (Jenis)',
            'criteria' => [
                'Terdapat asesmen diagnostik, formatif, dan sumatif',
                'Hanya dua jenis asesmen',
                'Hanya satu jenis asesmen',
                'Tidak ada asesmen',
            ],
        ],
        [
            'key'      => 'asesmen_2',
            'label'    => 'Asesmen (Rubrik)',
            'criteria' => [
                'Kriteria rubrik sangat jelas, spesifik, mudah dipahami, sesuai tujuan',
                'Kriteria rubrik jelas dan sesuai tujuan',
                'Kriteria rubrik kurang jelas atau kurang sesuai',
                'Kriteria rubrik tidak jelas atau tidak sesuai',
            ],
        ],
        [
            'key'      => 'pengayaan',
            'label'    => 'Pengayaan',
            'criteria' => [
                'Kegiatan pengayaan sangat relevan dan mendukung tujuan pembelajaran secara optimal',
                'Kegiatan pengayaan relevan dan mendukung tujuan pembelajaran dengan baik',
                'Kegiatan pengayaan cukup relevan namun tidak sepenuhnya mendukung tujuan',
                'Kegiatan pengayaan tidak relevan dengan tujuan pembelajaran',
            ],
        ],
        [
            'key'      => 'lembar_kerja',
            'label'    => 'Lembar Kerja',
            'criteria' => [
                'Lembar kerja relevan dengan tujuan pembelajaran',
                'Lembar kerja cukup relevan dengan tujuan pembelajaran',
                'Lembar kerja kurang relevan dengan tujuan pembelajaran',
                'Lembar kerja tidak relevan dengan tujuan pembelajaran',
            ],
        ],
        [
            'key'      => 'bahan_bacaan',
            'label'    => 'Bahan Bacaan',
            'criteria' => [
                'Terdapat bahan bacaan relevan untuk memperdalam materi',
                'Bahan bacaan cukup relevan untuk memperdalam materi',
                'Bahan bacaan kurang relevan untuk memperdalam materi',
                'Bahan bacaan tidak relevan untuk memperdalam materi',
            ],
        ],
        [
            'key'      => 'glosarium',
            'label'    => 'Glosarium',
            'criteria' => [
                'Glosarium menjelaskan seluruh istilah dalam modul ajar secara detail dan mendalam',
                'Glosarium menjelaskan sebagian besar istilah dalam modul ajar',
                'Glosarium minim memberikan penjelasan istilah',
                'Tidak terdapat glosarium pada modul ajar',
            ],
        ],
    ];

    public $modul_projek_aspek = [
        [
            'key'      => 'tema_topik',
            'label'    => 'Tema dan Topik',
            'criteria' => [
                'Topik modul projek jelas dan relevan dengan tema',
                'Topik modul projek cukup relevan dengan tema',
                'Topik modul projek kurang relevan dengan tema',
                'Topik modul projek tidak relevan dengan tema',
            ],
        ],
        [
            'key'      => 'tujuan',
            'label'    => 'Tujuan',
            'criteria' => [
                'Tujuan sangat relevan dengan dimensi, elemen, dan sub elemen Profil Pelajar Pancasila',
                'Tujuan cukup relevan dengan dimensi, elemen, dan sub elemen Profil Pelajar Pancasila',
                'Tujuan kurang relevan dengan dimensi, elemen, dan sub elemen Profil Pelajar Pancasila',
                'Tujuan tidak relevan dengan dimensi, elemen, dan sub elemen Profil Pelajar Pancasila',
            ],
        ],
        [
            'key'      => 'target',
            'label'    => 'Target',
            'criteria' => [
                'Target dijelaskan secara spesifik dan terukur berdasarkan dimensi Profil Pelajar Pancasila',
                'Target dijelaskan cukup spesifik dan terukur berdasarkan dimensi Profil Pelajar Pancasila',
                'Target dijelaskan kurang spesifik dan terukur berdasarkan dimensi Profil Pelajar Pancasila',
                'Target tidak dijelaskan dengan spesifik dan terukur',
            ],
        ],
        [
            'key'      => 'alur_tema',
            'label'    => 'Alur Tema',
            'criteria' => [
                'Alur tema dijelaskan dengan jelas dalam gambaran umum dan sesuai dengan target',
                'Alur tema cukup jelas dan sesuai dengan target',
                'Alur tema kurang jelas dan kurang sesuai dengan target',
                'Alur tema tidak jelas dan tidak sesuai dengan target',
            ],
        ],
        [
            'key'      => 'pemetaan_dimensi',
            'label'    => 'Pemetaan Dimensi, Elemen, Sub-elemen Profil Pelajar Pancasila',
            'criteria' => [
                'Elemen, Sub elemen, target pencapaian akhir, dan aktivitas dipetakan dengan jelas dan lengkap',
                'Elemen, Sub elemen, target pencapaian akhir, dan aktivitas dipetakan cukup jelas dan lengkap',
                'Elemen, Sub elemen, target pencapaian akhir, dan aktivitas dipetakan kurang jelas dan lengkap',
                'Elemen, Sub elemen, target pencapaian akhir, dan aktivitas tidak dipetakan',
            ],
        ],
        [
            'key'      => 'perkembangan_sub_elemen',
            'label'    => 'Perkembangan Sub Elemen',
            'criteria' => [
                'Perkembangan Sub elemen dipetakan dengan jelas dalam setiap fase',
                'Perkembangan Sub elemen dipetakan cukup jelas dalam setiap fase',
                'Perkembangan Sub elemen dipetakan kurang jelas dalam setiap fase',
                'Perkembangan Sub elemen tidak dipetakan dalam setiap fase',
            ],
        ],
        [
            'key'      => 'relevansi_projek',
            'label'    => 'Relevansi Projek',
            'criteria' => [
                'Modul projek relevan dengan kebutuhan sekolah',
                'Modul projek cukup relevan dengan kebutuhan sekolah',
                'Modul projek kurang relevan dengan kebutuhan sekolah',
                'Modul projek tidak relevan dengan kebutuhan sekolah',
            ],
        ],
        [
            'key'      => 'tahapan_projek',
            'label'    => 'Tahapan Projek',
            'criteria' => [
                'Tahapan projek dijelaskan dengan lengkap dan sistematis',
                'Tahapan projek dijelaskan cukup lengkap dan sistematis',
                'Tahapan projek dijelaskan kurang lengkap dan belum sistematis',
                'Tahapan projek tidak dijelaskan',
            ],
        ],
        [
            'key'      => 'alur_setiap_projek',
            'label'    => 'Alur Setiap Projek',
            'criteria' => [
                'Aktivitas, refleksi, konsep, dan aplikasi dalam alur pelaksanaan setiap projek diuraikan dengan jelas',
                'Aktivitas, refleksi, konsep, dan aplikasi dalam alur pelaksanaan setiap projek diuraikan cukup jelas',
                'Aktivitas, refleksi, konsep, dan aplikasi dalam alur pelaksanaan projek diuraikan kurang jelas',
                'Aktivitas, refleksi, konsep, dan aplikasi dalam alur pelaksanaan projek tidak ada',
            ],
        ],
        [
            'key'      => 'formatif',
            'label'    => 'Asesmen Formatif',
            'criteria' => [
                'Asesmen formatif sesuai untuk mengamati proses aktifitas peserta didik dalam projek',
                'Asesmen formatif cukup sesuai untuk mengamati proses aktifitas peserta didik dalam projek',
                'Asesmen formatif kurang sesuai untuk mengamati proses aktifitas peserta didik dalam projek',
                'Asesmen formatif tidak sesuai untuk mengamati proses aktifitas peserta didik dalam projek',
            ],
        ],
        [
            'key'      => 'sumatif',
            'label'    => 'Asesmen Sumatif',
            'criteria' => [
                'Asesmen sumatif sesuai untuk mengevaluasi pencapaian peserta didik dalam pelaksanaan projek',
                'Asesmen sumatif cukup sesuai untuk mengevaluasi pencapaian peserta didik dalam pelaksanaan projek',
                'Asesmen sumatif kurang sesuai untuk mengevaluasi pencapaian peserta didik dalam pelaksanaan projek',
                'Asesmen sumatif tidak sesuai untuk mengevaluasi pencapaian peserta didik dalam pelaksanaan projek',
            ],
        ],
        [
            'key'      => 'refleksi_peserta_didik',
            'label'    => 'Refleksi Peserta Didik',
            'criteria' => [
                'Lembar refleksi peserta didik sesuai untuk mengevaluasi diri terkait pemahaman dan mengembangkan rencana tindak lanjut',
                'Lembar refleksi peserta didik cukup sesuai untuk mengevaluasi diri terkait pemahaman dan mengembangkan rencana tindak lanjut',
                'Lembar refleksi peserta didik kurang sesuai untuk mengevaluasi diri terkait pemahaman dan mengembangkan rencana tindak lanjut',
                'Lembar refleksi peserta didik tidak sesuai untuk mengevaluasi diri terkait pemahaman dan mengembangkan rencana tindak lanjut',
            ],
        ],
        [
            'key'      => 'lembar_observasi_guru',
            'label'    => 'Lembar Observasi Guru',
            'criteria' => [
                'Lembar observasi guru dikembangkan sesuai dengan dimensi Profil Pelajar Pancasila',
                'Lembar observasi guru dikembangkan cukup sesuai dengan dimensi Profil Pelajar Pancasila',
                'Lembar observasi guru dikembangkan kurang sesuai dengan dimensi Profil Pelajar Pancasila',
                'Lembar observasi guru dikembangkan tidak sesuai dengan dimensi Profil Pelajar Pancasila',
            ],
        ],
    ];
    public $penilaian_asistensi_intrakurikuler = [

        // =========================
        // 1) PENDAHULUAN (9 indikator)
        // =========================
        [
            'key'      => 'pendahuluan_pembuka_dan_doa',
            'label'    => 'Pendahuluan – Pembuka & Doa',
            'criteria' => [
                'Selalu mengucapkan dalam pembuka dan berdoa untuk memulai pembelajaran',
                'Sering mengucapkan dalam pembuka dan berdoa untuk memulai pembelajaran',
                'Jarang mengucapkan dalam pembuka dan berdoa untuk memulai pembelajaran',
                'Tidak mengucapkan dalam pembuka dan berdoa untuk memulai pembelajaran',
            ],
        ],
        [
            'key'      => 'pendahuluan_periksa_kehadiran',
            'label'    => 'Pendahuluan – Pemeriksaan Kehadiran',
            'criteria' => [
                'Selalu memeriksa kehadiran peserta didik',
                'Sering memeriksa kehadiran peserta didik',
                'Jarang memeriksa kehadiran peserta didik',
                'Tidak memeriksa kehadiran peserta didik',
            ],
        ],
        [
            'key'      => 'pendahuluan_siapkan_fisik_psikis',
            'label'    => 'Pendahuluan – Menyiapkan Fisik & Psikis Peserta Didik',
            'criteria' => [
                'Selalu menyiapkan fisik dan psikis peserta didik',
                'Sering menyiapkan fisik dan psikis peserta didik',
                'Jarang menyiapkan fisik dan psikis peserta didik',
                'Tidak menyiapkan fisik dan psikis peserta didik',
            ],
        ],
        [
            'key'      => 'pendahuluan_kaitkan_pengalaman',
            'label'    => 'Pendahuluan – Mengaitkan Materi dengan Pengalaman Sebelumnya',
            'criteria' => [
                'Selalu mengaitkan materi yang akan dilakukan dengan pengalaman sebelumnya',
                'Sering mengaitkan materi yang akan dilakukan dengan pengalaman sebelumnya',
                'Jarang mengaitkan materi yang akan dilakukan dengan pengalaman sebelumnya',
                'Tidak mengaitkan materi yang akan dilakukan dengan pengalaman sebelumnya',
            ],
        ],
        [
            'key'      => 'pendahuluan_pertanyaan_relevan',
            'label'    => 'Pendahuluan – Pertanyaan Relevan',
            'criteria' => [
                'Mengajukan pertanyaan yang relevan dan ada keterkaitannya dengan pelajaran yang akan dilakukan',
                'Mengajukan pertanyaan yang kurang relevan namun masih ada keterkaitannya dengan pelajaran yang akan dilakukan',
                'Mengajukan pertanyaan yang tidak relevan dan tidak ada keterkaitannya dengan pelajaran yang akan dilakukan',
                'Tidak mengajukan pertanyaan yang berkaitan dengan pelajaran yang akan dilakukan',
            ],
        ],
        [
            'key'      => 'pendahuluan_manfaat_materi',
            'label'    => 'Pendahuluan – Menjelaskan Manfaat Materi',
            'criteria' => [
                'Selalu menjelaskan gambaran manfaat materi pelajaran',
                'Sering menjelaskan gambaran manfaat materi pelajaran',
                'Jarang menjelaskan gambaran manfaat materi pelajaran',
                'Tidak menjelaskan gambaran manfaat materi pelajaran',
            ],
        ],
        [
            'key'      => 'pendahuluan_beri_info_materi',
            'label'    => 'Pendahuluan – Memberi Informasi Materi',
            'criteria' => [
                'Selalu memberitahu materi pelajaran yang akan dibahas',
                'Sering memberitahu materi pelajaran yang akan dibahas',
                'Jarang memberitahu materi pelajaran yang akan dibahas',
                'Tidak memberitahu materi pelajaran yang akan dibahas',
            ],
        ],
        [
            'key'      => 'pendahuluan_beri_tujuan_pembelajaran',
            'label'    => 'Pendahuluan – Menyampaikan Tujuan Pembelajaran',
            'criteria' => [
                'Selalu memberitahu tujuan pembelajaran',
                'Sering memberitahu tujuan pembelajaran',
                'Jarang memberitahu tujuan pembelajaran',
                'Tidak memberitahu tujuan pembelajaran',
            ],
        ],
        [
            'key'      => 'pendahuluan_jelaskan_langkah',
            'label'    => 'Pendahuluan – Menjelaskan Langkah-langkah Pembelajaran',
            'criteria' => [
                'Selalu menjelaskan langkah-langkah pembelajaran',
                'Sering menjelaskan langkah-langkah pembelajaran',
                'Jarang menjelaskan langkah-langkah pembelajaran',
                'Tidak menjelaskan langkah-langkah pembelajaran',
            ],
        ],

        // =========================
        // 2) KEGIATAN INTI (4 indikator)
        // =========================
        [
            'key'      => 'inti_partisipasi_diskusi',
            'label'    => 'Kegiatan Inti – Partisipasi Tanya Jawab & Diskusi',
            'criteria' => [
                'Terlibat aktif dalam sesi tanya jawab dan diskusi',
                'Cukup aktif terlibat dalam sesi tanya jawab dan diskusi',
                'Kurang aktif terlibat dalam sesi tanya jawab dan diskusi',
                'Tidak terlibat aktif dalam sesi tanya jawab dan diskusi',
            ],
        ],
        [
            'key'      => 'inti_penguasaan_materi',
            'label'    => 'Kegiatan Inti – Penguasaan Materi',
            'criteria' => [
                'Penguasaan materi pembelajaran baik dan mendalam',
                'Penguasaan materi pembelajaran cukup baik',
                'Penguasaan materi pembelajaran kurang baik',
                'Tidak menguasai materi pembelajaran',
            ],
        ],
        [
            'key'      => 'inti_keselarasan_tujuan',
            'label'    => 'Kegiatan Inti – Kesesuaian Pelaksanaan dengan Tujuan',
            'criteria' => [
                'Mampu melanjutkan pembelajaran sesuai dengan tujuan',
                'Mampu melanjutkan pembelajaran dan cukup sesuai dengan tujuan',
                'Mampu melanjutkan pembelajaran namun kurang sesuai dengan tujuan',
                'Mampu melanjutkan pembelajaran namun tidak sesuai dengan tujuan',
            ],
        ],
        [
            'key'      => 'inti_menjawab_pertanyaan',
            'label'    => 'Kegiatan Inti – Menjawab Pertanyaan Siswa',
            'criteria' => [
                'Mampu memberikan jawaban yang tepat atas pertanyaan siswa',
                'Mampu memberikan jawaban yang cukup tepat atas pertanyaan siswa',
                'Memberikan jawaban yang kurang tepat atas pertanyaan siswa',
                'Memberikan jawaban yang tidak tepat atas pertanyaan siswa',
            ],
        ],

        // =========================
        // 3) PENUTUP (3 indikator)
        // =========================
        [
            'key'      => 'penutup_meringkas_materi',
            'label'    => 'Penutup – Meringkas Materi',
            'criteria' => [
                'Mampu meringkas materi sesuai dengan tujuan pembelajaran',
                'Mampu meringkas materi dan cukup sesuai dengan tujuan pembelajaran',
                'Mampu meringkas materi namun kurang sesuai dengan tujuan pembelajaran',
                'Meringkas materi namun tidak sesuai dengan tujuan pembelajaran',
            ],
        ],
        [
            'key'      => 'penutup_memberi_tugas',
            'label'    => 'Penutup – Memberi Tugas',
            'criteria' => [
                'Memberikan tugas yang sesuai dengan tujuan pembelajaran',
                'Memberikan tugas yang cukup sesuai dengan tujuan pembelajaran',
                'Memberikan tugas yang kurang sesuai dengan tujuan pembelajaran',
                'Memberikan tugas yang tidak sesuai dengan tujuan pembelajaran',
            ],
        ],
        [
            'key'      => 'penutup_kuis_formatif',
            'label'    => 'Penutup – Kuis/Tes Formatif',
            'criteria' => [
                'Memberikan kuis singkat atau tes formatif yang sesuai dengan tujuan pembelajaran',
                'Memberikan kuis singkat atau tes formatif yang cukup sesuai dengan tujuan pembelajaran',
                'Memberikan kuis singkat atau tes formatif yang kurang sesuai dengan tujuan pembelajaran',
                'Memberikan kuis singkat atau tes formatif yang tidak sesuai dengan tujuan pembelajaran',
            ],
        ],

        // =========================
        // 4) KOMUNIKASI (2 indikator)
        // =========================
        [
            'key'      => 'komunikasi_interaktif',
            'label'    => 'Komunikasi – Interaksi dengan Peserta Didik',
            'criteria' => [
                'Komunikasi yang interaktif dengan peserta didik',
                'Komunikasi yang cukup interaktif dengan peserta didik',
                'Komunikasi yang kurang interaktif dengan peserta didik',
                'Komunikasi yang tidak interaktif dengan peserta didik',
            ],
        ],
        [
            'key'      => 'komunikasi_bahasa_tubuh',
            'label'    => 'Komunikasi – Bahasa Tubuh Positif',
            'criteria' => [
                'Menunjukkan bahasa tubuh positif: senyuman, anggukan, gerakan tangan, semangat dalam menyampaikan, dan kontak visual',
                'Menunjukkan 4 dari 5 bahasa tubuh positif di atas',
                'Menunjukkan 3 dari 5 bahasa tubuh positif di atas',
                'Bahasa tubuh kaku dan tidak luwes',
            ],
        ],

        // =========================
        // 5) KEDISIPLINAN (2 indikator)
        // =========================
        [
            'key'      => 'disiplin_kehadiran',
            'label'    => 'Kedisiplinan – Kehadiran & Ketepatan Waktu',
            'criteria' => [
                'Selalu hadir dan tepat waktu',
                'Selalu hadir dan beberapa kali terlambat',
                'Selalu hadir dan sering kali terlambat',
                'Jarang hadir dan sering kali terlambat',
            ],
        ],
        [
            'key'      => 'disiplin_inisiatif',
            'label'    => 'Kedisiplinan – Inisiatif & Kepedulian',
            'criteria' => [
                'Selalu aktif mengambil inisiatif dan membantu kegiatan.',
                'Sering mengambil inisiatif dan membantu kegiatan.',
                'Kadang-kadang mengambil inisiatif, tetapi kurang konsisten.',
                'Jarang atau tidak pernah mengambil inisiatif.',
            ],
        ],
    ];

    public $penilaian_asistensi_kokurikuler = [
        [
            'aspek' => 'Keterlibatan dan Komitmen',
            'indikator' => [
                [
                    'key'      => 'kehadiran',
                    'label'    => 'Kehadiran',
                    'criteria' => [
                        'Selalu hadir tepat waktu di setiap pertemuan.',
                        'Hadir tepat waktu di sebagian besar pertemuan.',
                        'Kadang-kadang terlambat atau tidak hadir.',
                        'Sering terlambat atau sering tidak hadir.',
                    ],
                ],
                [
                    'key'      => 'komitmen',
                    'label'    => 'Komitmen',
                    'criteria' => [
                        'Menunjukkan komitmen yang tinggi terhadap kegiatan ekstrakurikuler.',
                        'Menunjukkan komitmen yang baik, meskipun ada beberapa kekurangan.',
                        'Komitmen kadang-kadang kurang terlihat.',
                        'Kurang menunjukkan komitmen terhadap kegiatan.',
                    ],
                ],
            ],
        ],
        [
            'aspek' => 'Partisipasi Aktif',
            'indikator' => [
                [
                    'key'      => 'inisiatif',
                    'label'    => 'Inisiatif',
                    'criteria' => [
                        'Selalu aktif mengambil inisiatif dan membantu kegiatan.',
                        'Sering mengambil inisiatif dan membantu kegiatan.',
                        'Kadang-kadang mengambil inisiatif, tetapi kurang konsisten.',
                        'Jarang atau tidak pernah mengambil inisiatif.',
                    ],
                ],
                [
                    'key'      => 'kontribusi',
                    'label'    => 'Kontribusi',
                    'criteria' => [
                        'Memberikan kontribusi yang signifikan dalam setiap kegiatan.',
                        'Memberikan kontribusi yang baik, meskipun tidak selalu signifikan.',
                        'Kontribusi kadang-kadang kurang terlihat.',
                        'Kontribusi sangat minim atau tidak ada.',
                    ],
                ],
            ],
        ],
        [
            'aspek' => 'Keterampilan dan Kompetensi',
            'indikator' => [
                [
                    'key'      => 'keterampilan_teknis',
                    'label'    => 'Keterampilan Teknis',
                    'criteria' => [
                        'Memiliki keterampilan teknis yang sangat baik dan relevan dengan kegiatan.',
                        'Memiliki keterampilan teknis yang baik dan cukup relevan.',
                        'Keterampilan teknis cukup, tetapi kurang relevan atau mendalam.',
                        'Keterampilan teknis kurang atau tidak relevan.',
                    ],
                ],
                [
                    'key'      => 'kreativitas',
                    'label'    => 'Kreativitas',
                    'criteria' => [
                        'Selalu menunjukkan kreativitas tinggi dalam kegiatan.',
                        'Sering menunjukkan kreativitas dalam kegiatan.',
                        'Kadang-kadang menunjukkan kreativitas.',
                        'Jarang atau tidak pernah menunjukkan kreativitas.',
                    ],
                ],
            ],
        ],
        [
            'aspek' => 'Kemampuan Berkomunikasi',
            'indikator' => [
                [
                    'key'      => 'komunikasi_verbal',
                    'label'    => 'Komunikasi Verbal',
                    'criteria' => [
                        'Sangat jelas dan efektif dalam berkomunikasi secara verbal.',
                        'Cukup jelas dan efektif dalam komunikasi verbal.',
                        'Komunikasi verbal kadang-kadang kurang jelas atau efektif.',
                        'Komunikasi verbal sering tidak jelas atau tidak efektif.',
                    ],
                ],
                [
                    'key'      => 'komunikasi_non_verbal',
                    'label'    => 'Komunikasi Non-Verbal',
                    'criteria' => [
                        'Selalu menunjukkan komunikasi non-verbal yang mendukung.',
                        'Sering menunjukkan komunikasi non-verbal yang mendukung.',
                        'Kadang-kadang menunjukkan komunikasi non-verbal yang mendukung.',
                        'Jarang atau tidak pernah menunjukkan komunikasi non-verbal yang mendukung.',
                    ],
                ],
            ],
        ],
        [
            'aspek' => 'Kerjasama Tim',
            'indikator' => [
                [
                    'key'      => 'kerjasama_teman',
                    'label'    => 'Kerjasama dengan Teman',
                    'criteria' => [
                        'Sangat baik dalam bekerja sama dengan teman se-tim.',
                        'Baik dalam bekerja sama dengan teman se-tim.',
                        'Kadang-kadang kurang baik dalam bekerja sama.',
                        'Kesulitan dalam bekerja sama dengan teman se-tim.',
                    ],
                ],
                [
                    'key'      => 'kerjasama_pembina',
                    'label'    => 'Kerjasama dengan Pembina',
                    'criteria' => [
                        'Sangat baik dalam bekerja sama dengan pembina.',
                        'Baik dalam bekerja sama dengan pembina.',
                        'Kadang-kadang kurang baik dalam bekerja sama dengan pembina.',
                        'Kesulitan dalam bekerja sama dengan pembina.',
                    ],
                ],
            ],
        ],
        [
            'aspek' => 'Pengelolaan Waktu',
            'indikator' => [
                [
                    'key'      => 'pengelolaan_waktu',
                    'label'    => 'Pengelolaan Waktu',
                    'criteria' => [
                        'Sangat baik dalam mengelola waktu untuk setiap kegiatan.',
                        'Baik dalam mengelola waktu, meskipun ada beberapa kekurangan.',
                        'Pengelolaan waktu kadang-kadang kurang baik.',
                        'Kesulitan dalam mengelola waktu.',
                    ],
                ],
                [
                    'key'      => 'ketepatan_penyelesaian_tugas',
                    'label'    => 'Ketepatan Penyelesaian Tugas',
                    'criteria' => [
                        'Selalu menyelesaikan tugas tepat waktu dengan kualitas tinggi.',
                        'Sering menyelesaikan tugas tepat waktu dengan kualitas baik.',
                        'Kadang-kadang terlambat menyelesaikan tugas atau kualitasnya kurang.',
                        'Sering terlambat atau tugas tidak selesai.',
                    ],
                ],
            ],
        ],
        [
            'aspek' => 'Kepemimpinan',
            'indikator' => [
                [
                    'key'      => 'kemampuan_memimpin',
                    'label'    => 'Kemampuan Memimpin',
                    'criteria' => [
                        'Menunjukkan kemampuan memimpin yang sangat baik dalam setiap kesempatan.',
                        'Menunjukkan kemampuan memimpin yang baik.',
                        'Kadang-kadang menunjukkan kemampuan memimpin, tetapi kurang konsisten.',
                        'Jarang atau tidak pernah menunjukkan kemampuan memimpin.',
                    ],
                ],
            ],
        ],
        [
            'aspek' => 'Pemecahan Masalah',
            'indikator' => [
                [
                    'key'      => 'identifikasi_masalah',
                    'label'    => 'Identifikasi Masalah',
                    'criteria' => [
                        'Sangat cepat dan tepat dalam mengidentifikasi masalah.',
                        'Cepat dan tepat dalam mengidentifikasi masalah.',
                        'Kadang-kadang tepat dalam mengidentifikasi masalah.',
                        'Sering kesulitan dalam mengidentifikasi masalah.',
                    ],
                ],
                [
                    'key'      => 'penyelesaian_masalah',
                    'label'    => 'Penyelesaian Masalah',
                    'criteria' => [
                        'Sangat efektif dalam menyelesaikan masalah dengan solusi yang inovatif.',
                        'Efektif dalam menyelesaikan masalah.',
                        'Kadang-kadang efektif dalam menyelesaikan masalah.',
                        'Kesulitan dalam menyelesaikan masalah.',
                    ],
                ],
            ],
        ],
        [
            'aspek' => 'Tanggung Jawab',
            'indikator' => [
                [
                    'key'      => 'kepemilikan_tugas',
                    'label'    => 'Kepemilikan Tugas',
                    'criteria' => [
                        'Selalu menunjukkan tanggung jawab yang tinggi terhadap tugas yang diberikan.',
                        'Menunjukkan tanggung jawab yang baik terhadap tugas.',
                        'Kadang-kadang menunjukkan tanggung jawab terhadap tugas.',
                        'Kurang menunjukkan tanggung jawab terhadap tugas.',
                    ],
                ],
                [
                    'key'      => 'pelaporan_tugas',
                    'label'    => 'Pelaporan Tugas',
                    'criteria' => [
                        'Selalu melaporkan perkembangan tugas secara teratur dan jelas.',
                        'Sering melaporkan perkembangan tugas dengan baik.',
                        'Kadang-kadang melaporkan perkembangan tugas.',
                        'Jarang atau tidak pernah melaporkan perkembangan tugas.',
                    ],
                ],
            ],
        ],
    ];
    public
        $penilaian_sikap = [

            // 1) KEJUJURAN
            [
                'aspek' => 'Kejujuran',
                'indikator' => [
                    [
                        'key'      => 'kejujuran_kebenaran',
                        'label'    => 'Berbicara, bersikap dan bertindak atas dasar kebenaran',
                        'criteria' => [
                            'Selalu jujur dan konsisten dalam ucapan dan tindakan',
                            'Umumnya jujur dalam berkata dan bertindak',
                            'Kadang berbicara atau bertindak tidak sesuai kenyataan',
                            'Sering menyembunyikan atau memutarbalikkan fakta',
                        ],
                    ],
                    [
                        'key'      => 'kejujuran_dapat_dipercaya',
                        'label'    => 'Dapat dipercaya',
                        'criteria' => [
                            'Sangat dapat dipercaya dan menjadi panutan untuk mahasiswa lainnya',
                            'Menjalankan tugas dengan tanggung jawab',
                            'Kadang tidak konsisten dalam menjalankan tugas',
                            'Tidak dapat dipercaya dan sering ingkar janji',
                        ],
                    ],
                    [
                        'key'      => 'kejujuran_plagiarisme',
                        'label'    => 'Tidak plagiat, menyontek dan sejenisnya',
                        'criteria' => [
                            'Selalu menunjukkan keaslian karya dan menghargai etika akademik',
                            'Menyusun tugas secara mandiri',
                            'Kadang mengambil sebagian dari orang lain tanpa izin',
                            'Sering menyalin atau menjiplak pekerjaan orang lain',
                        ],
                    ],
                    [
                        'key'      => 'kejujuran_ungkapan_perasaan',
                        'label'    => 'Mengungkapkan perasaan apa adanya',
                        'criteria' => [
                            'Sangat terbuka dan apa adanya dengan cara yang santun',
                            'Terbuka dan jujur dalam menyampaikan perasaan',
                            'Kadang menyampaikan perasaan dengan ragu atau setengah hati',
                            'Menyembunyikan perasaan dan cenderung tidak jujur',
                        ],
                    ],
                    [
                        'key'      => 'kejujuran_mengakui_kesalahan',
                        'label'    => 'Mengakui kesalahan atau kekurangan yang dimiliki',
                        'criteria' => [
                            'Secara reflektif mengakui kekurangan dan berkomitmen memperbaikinya',
                            'Mau mengakui kesalahan secara sadar',
                            'Mengakui kesalahan setelah diberi tahu',
                            'Menyangkal atau menyalahkan orang lain',
                        ],
                    ],
                    [
                        'key'      => 'kejujuran_laporan_berdasar_data',
                        'label'    => 'Membuat laporan berdasarkan data',
                        'criteria' => [
                            'Laporan sangat objektif dan berbasis data valid',
                            'Laporan cukup sesuai berdasarkan data',
                            'Laporan kurang akurat atau disesuaikan',
                            'Laporan tidak sesuai data',
                        ],
                    ],
                ],
            ],

            // 2) KEDISIPLINAN
            [
                'aspek' => 'Kedisiplinan',
                'indikator' => [
                    [
                        'key'      => 'disiplin_tepat_waktu',
                        'label'    => 'Datang tepat waktu',
                        'criteria' => [
                            'Selalu hadir dan tepat waktu tanpa pengecualian',
                            'Datang tepat waktu dengan sedikit pengecualian',
                            'Kadang terlambat atau absen',
                            'Sering terlambat atau absen tanpa alasan',
                        ],
                    ],
                    [
                        'key'      => 'disiplin_tata_tertib',
                        'label'    => 'Patuh pada tata tertib/aturan bersama',
                        'criteria' => [
                            'Selalu patuh dan menjadi contoh bagi orang lain',
                            'Patuh pada aturan, meskipun butuh pengingat',
                            'Kadang patuh, kadang melanggar',
                            'Sering melanggar aturan',
                        ],
                    ],
                    [
                        'key'      => 'disiplin_kumpul_tugas',
                        'label'    => 'Mengerjakan & mengumpulkan tugas tepat waktu',
                        'criteria' => [
                            'Selalu mengumpulkan tugas sebelum atau tepat waktu',
                            'Umumnya mengumpulkan tugas tepat waktu',
                            'Kadang mengumpulkan tugas melebihi batas waktu',
                            'Sering tidak mengumpulkan atau terlambat',
                        ],
                    ],
                    [
                        'key'      => 'disiplin_bahasa_tulis',
                        'label'    => 'Menulis mengikuti kaidah bahasa tulis yang baik & benar',
                        'criteria' => [
                            'Penulisan rapi, logis, dan sesuai kaidah bahasa tulis',
                            'Penulisan cukup sesuai kaidah, hanya sedikit kesalahan',
                            'Masih sering ditemukan kesalahan dalam penulisan',
                            'Banyak kesalahan dalam ejaan dan struktur',
                        ],
                    ],
                ],
            ],

            // 3) ETIKA BERKOMUNIKASI
            [
                'aspek' => 'Etika berkomunikasi',
                'indikator' => [
                    [
                        'key'      => 'komunikasi_santun',
                        'label'    => 'Tidak berkata kasar, kotor, dan takabur',
                        'criteria' => [
                            'Selalu berbicara santun dan rendah hati',
                            'Umumnya berbicara sopan',
                            'Kadang berkata tidak sopan saat emosi',
                            'Sering berkata kasar atau sombong',
                        ],
                    ],
                    [
                        'key'      => 'komunikasi_tidak_menyela',
                        'label'    => 'Tidak menyela pembicaraan pada waktu yang tidak tepat',
                        'criteria' => [
                            'Selalu menunggu giliran dan mendengarkan dengan aktif',
                            'Umumnya menghargai giliran bicara',
                            'Kadang menyela tanpa menyadari',
                            'Sering memotong pembicaraan orang lain',
                        ],
                    ],
                    [
                        'key'      => 'komunikasi_terima_kasih',
                        'label'    => 'Mengucapkan terima kasih setelah menerima bantuan',
                        'criteria' => [
                            'Selalu mengucapkan terima kasih dengan tulus',
                            'Umumnya mengucapkan terima kasih saat dibantu',
                            'Kadang mengucapkan terima kasih',
                            'Jarang mengucapkan terima kasih',
                        ],
                    ],
                    [
                        'key'      => 'komunikasi_3s',
                        'label'    => 'Bersikap 3S: salam, senyum, sapa',
                        'criteria' => [
                            'Selalu menunjukkan sikap 3S secara konsisten',
                            'Umumnya bersikap ramah dengan 3S',
                            'Kadang lupa atau enggan memberi salam',
                            'Jarang atau enggan menyapa orang lain',
                        ],
                    ],
                    [
                        'key'      => 'komunikasi_minta_izin',
                        'label'    => 'Meminta izin ketika memasuki ruangan',
                        'criteria' => [
                            'Selalu meminta izin dan bersikap sopan sebelum masuk ruangan',
                            'Biasanya meminta izin atau memberi salam',
                            'Kadang lupa meminta izin',
                            'Masuk tanpa izin atau salam',
                        ],
                    ],
                ],
            ],

            // 4) KEPEDULIAN
            [
                'aspek' => 'Kepedulian',
                'indikator' => [
                    [
                        'key'      => 'peduli_inisiatif_belajar',
                        'label'    => 'Memiliki inisiatif dalam tugas-tugas belajar di ruang kuliah',
                        'criteria' => [
                            'Sangat proaktif, sering memulai kegiatan/tugas secara mandiri',
                            'Cukup aktif dan kadang mengambil peran',
                            'Kadang menunjukkan inisiatif setelah diingatkan',
                            'Tidak menunjukkan inisiatif, selalu menunggu instruksi',
                        ],
                    ],
                    [
                        'key'      => 'peduli_rasa_ingin_tahu',
                        'label'    => 'Menunjukkan rasa ingin tahu',
                        'criteria' => [
                            'Sangat ingin tahu dan antusias mengeksplorasi materi secara mandiri',
                            'Sering menunjukkan minat dan keingintahuan',
                            'Bertanya atau mencari tahu bila diminta',
                            'Tidak menunjukkan minat atau keingintahuan',
                        ],
                    ],
                    [
                        'key'      => 'peduli_kepada_teman',
                        'label'    => 'Perhatian kepada sesama teman dalam penyelesaian tugas belajar',
                        'criteria' => [
                            'Sangat peduli dan menjadi penggerak dalam kolaborasi kelompok',
                            'Cukup peduli dan berkontribusi dalam kelompok',
                            'Kadang membantu, tapi masih pasif',
                            'Acuh tak acuh atau individualis',
                        ],
                    ],
                    [
                        'key'      => 'peduli_responsif_kelas',
                        'label'    => 'Responsif terhadap situasi pembelajaran di ruang kuliah',
                        'criteria' => [
                            'Sangat responsif, cepat dan tepat menangkap arahan/situasi kelas',
                            'Umumnya tanggap dan aktif dalam interaksi',
                            'Kadang merespons lambat atau kurang tepat',
                            'Sering tidak merespons atau tidak fokus',
                        ],
                    ],
                ],
            ],

            // 5) KETANGGUHAN (daya juang)
            [
                'aspek' => 'Ketangguhan (daya juang)',
                'indikator' => [
                    [
                        'key'      => 'tangguh_teguh_pendirian',
                        'label'    => 'Tetap pada pendirian jika sudah meyakini kebenaran',
                        'criteria' => [
                            'Sangat konsisten dan berani menyampaikan serta mempertahankan kebenaran secara santun',
                            'Cukup teguh pada pendirian yang diyakini benar',
                            'Kadang ragu dalam mempertahankan pendapat',
                            'Mudah goyah dan mengikuti pendapat orang lain tanpa pertimbangan',
                        ],
                    ],
                    [
                        'key'      => 'tangguh_selesaikan_tugas',
                        'label'    => 'Aktif berusaha menyelesaikan setiap tugas yang diberikan oleh dosen',
                        'criteria' => [
                            'Sangat aktif dan inisiatif dalam menyelesaikan semua tugas secara tuntas',
                            'Umumnya mengerjakan tugas secara mandiri dan cukup baik',
                            'Kadang mengerjakan tetapi tidak maksimal',
                            'Sering mengabaikan tugas atau mengandalkan orang lain',
                        ],
                    ],
                    [
                        'key'      => 'tangguh_pantang_menyerah',
                        'label'    => 'Pantang menyerah setiap menghadapi kesulitan',
                        'criteria' => [
                            'Konsisten mencari solusi dan tidak pernah menyerah hingga berhasil',
                            'Tetap berusaha meskipun ada kesulitan',
                            'Kadang menyerah, tapi bisa bangkit lagi',
                            'Mudah menyerah dan berhenti berusaha',
                        ],
                    ],
                ],
            ],

            // 6) KETEKUNAN
            [
                'aspek' => 'Ketekunan',
                'indikator' => [
                    [
                        'key'      => 'tekun_semangat_belajar',
                        'label'    => 'Giat dan bersemangat dalam belajar',
                        'criteria' => [
                            'Sangat bersemangat dan aktif sepanjang pembelajaran',
                            'Umumnya semangat dan fokus',
                            'Kadang tampak antusias, kadang tidak',
                            'Terlihat pasif, tidak semangat',
                        ],
                    ],
                    [
                        'key'      => 'tekun_aktif_bertanya',
                        'label'    => 'Bersikap aktif dalam belajar (bertanya tentang materi)',
                        'criteria' => [
                            'Sangat aktif bertanya dan berdiskusi secara konstruktif',
                            'Aktif bertanya saat diperlukan',
                            'Kadang bertanya tapi kurang fokus',
                            'Tidak pernah bertanya atau terlibat',
                        ],
                    ],
                    [
                        'key'      => 'tekun_tidak_putus_asa',
                        'label'    => 'Tidak mudah putus asa dalam mengerjakan tugas',
                        'criteria' => [
                            'Sangat tekun dan tidak menyerah meski ada tantangan',
                            'Umumnya menyelesaikan meski dengan usaha ekstra',
                            'Mengerjakan tapi kadang berhenti saat sulit',
                            'Mudah menyerah, banyak tugas tidak selesai',
                        ],
                    ],
                    [
                        'key'      => 'tekun_mandiri_tugas',
                        'label'    => 'Tidak tergantung kepada orang lain dalam mengerjakan tugas kuliah',
                        'criteria' => [
                            'Sepenuhnya mandiri dan menjadi contoh kemandirian akademik',
                            'Umumnya mandiri dalam menyelesaikan tugas',
                            'Masih sering meminta bantuan berlebih',
                            'Sering meniru atau tergantung orang lain',
                        ],
                    ],
                    [
                        'key'      => 'tekun_ekstrakurikuler',
                        'label'    => 'Rajin mengikuti kegiatan ekstrakurikuler untuk meningkatkan prestasi diri',
                        'criteria' => [
                            'Sangat aktif dan berprestasi di kegiatan ekstrakurikuler',
                            'Aktif mengikuti kegiatan pendukung',
                            'Kadang ikut, tetapi tidak konsisten',
                            'Tidak pernah ikut kegiatan',
                        ],
                    ],
                ],
            ],

            // 7) KEMANDIRIAN
            [
                'aspek' => 'Kemandirian',
                'indikator' => [
                    [
                        'key'      => 'mandiri_selesaikan_tugas',
                        'label'    => 'Menyelesaikan tugas/produk/outcome matakuliah',
                        'criteria' => [
                            'Menyelesaikan tugas sangat baik, tepat waktu, dan melebihi ekspektasi',
                            'Menyelesaikan tugas tepat waktu dan sesuai petunjuk',
                            'Menyelesaikan sebagian tugas namun tidak tepat waktu/ tidak sesuai arahan',
                            'Tidak menyelesaikan tugas/produk sesuai ketentuan',
                        ],
                    ],
                    [
                        'key'      => 'mandiri_tidak_bergantung',
                        'label'    => 'Tidak bergantung pada orang lain',
                        'criteria' => [
                            'Sangat mandiri dan mampu menyelesaikan semua tugas secara otonom',
                            'Cukup mandiri dan hanya meminta bantuan bila sangat diperlukan',
                            'Kadang masih meminta bantuan dalam hal yang bisa dikerjakan sendiri',
                            'Sering menyalin/menunggu arahan/bergantung orang lain',
                        ],
                    ],
                ],
            ],

            // 8) KERJA SAMA
            [
                'aspek' => 'Kerja sama',
                'indikator' => [
                    [
                        'key'      => 'kerjasama_kesediaan_tugas',
                        'label'    => 'Kesediaan melakukan tugas sesuai kesepakatan',
                        'criteria' => [
                            'Selalu menjalankan tugas tepat waktu dan bertanggung jawab',
                            'Melaksanakan tugas sesuai kesepakatan',
                            'Kadang menjalankan tugas, tapi tidak konsisten',
                            'Tidak menjalankan tugas atau mengabaikan kesepakatan',
                        ],
                    ],
                    [
                        'key'      => 'kerjasama_membantu_tanpa_imbal',
                        'label'    => 'Bersedia membantu orang lain tanpa mengharap imbalan',
                        'criteria' => [
                            'Selalu siap membantu tanpa diminta dan tanpa pamrih',
                            'Sering membantu secara aktif',
                            'Hanya membantu jika diminta',
                            'Tidak pernah menawarkan bantuan',
                        ],
                    ],
                    [
                        'key'      => 'kerjasama_aktif_kelompok',
                        'label'    => 'Aktif dalam kerja kelompok',
                        'criteria' => [
                            'Sangat aktif dan menjadi penggerak dalam kelompok',
                            'Aktif berkontribusi dalam kelompok',
                            'Terlibat sesekali, kurang inisiatif',
                            'Pasif atau tidak terlibat',
                        ],
                    ],
                    [
                        'key'      => 'kerjasama_fokus_tujuan',
                        'label'    => 'Memusatkan perhatian pada tujuan kelompok',
                        'criteria' => [
                            'Selalu menempatkan kepentingan kelompok sebagai prioritas utama',
                            'Umumnya peduli & berorientasi tujuan bersama',
                            'Kadang mengutamakan kelompok, kadang tidak',
                            'Fokus pada diri sendiri & tidak peduli hasil kelompok',
                        ],
                    ],
                    [
                        'key'      => 'kerjasama_tidak_egois',
                        'label'    => 'Tidak mendahulukan kepentingan pribadi',
                        'criteria' => [
                            'Konsisten mendahulukan kepentingan tim di atas kepentingan pribadi',
                            'Umumnya mendahulukan kepentingan bersama',
                            'Kadang mengutamakan diri sendiri',
                            'Sering memaksakan kehendak/mencari keuntungan pribadi',
                        ],
                    ],
                    [
                        'key'      => 'kerjasama_kelola_perbedaan',
                        'label'    => 'Mencari jalan mengatasi perbedaan pendapat/pikiran',
                        'criteria' => [
                            'Proaktif menyatukan pendapat & menjadi penengah yang bijak',
                            'Bersedia berdiskusi & kompromi',
                            'Cenderung defensif saat berbeda pendapat',
                            'Tidak mampu mencari jalan mengatasi perbedaan',
                        ],
                    ],
                ],
            ],

            // 9) KEBERINISIATIFAN
            [
                'aspek' => 'Keberinisiatifan',
                'indikator' => [
                    [
                        'key'      => 'inisiatif_bertindak_tanpa_disuruh',
                        'label'    => 'Melakukan sesuatu tanpa harus disuruh',
                        'criteria' => [
                            'Selalu bertindak proaktif tanpa menunggu arahan',
                            'Sering melakukan sesuatu tanpa disuruh',
                            'Kadang melakukan sesuatu setelah diarahkan',
                            'Tidak melakukan apa pun kecuali diperintah',
                        ],
                    ],
                    [
                        'key'      => 'inisiatif_aktif_bertanya',
                        'label'    => 'Aktif bertanya & menggali informasi lebih lanjut',
                        'criteria' => [
                            'Selalu aktif bertanya dan mencari informasi secara mandiri',
                            'Sering bertanya untuk memperjelas pemahaman',
                            'Bertanya hanya jika dipaksa/disuruh',
                            'Tidak pernah bertanya atau mencari info tambahan',
                        ],
                    ],
                    [
                        'key'      => 'inisiatif_sampaikan_gagasan',
                        'label'    => 'Berani menyampaikan ide/pendapat',
                        'criteria' => [
                            'Sangat percaya diri dan aktif menyampaikan gagasan',
                            'Sering menyampaikan pendapat saat diskusi',
                            'Kadang menyampaikan ide jika diminta',
                            'Tidak pernah menyampaikan ide/ragu berbicara',
                        ],
                    ],
                    [
                        'key'      => 'inisiatif_siap_sebelum_diminta',
                        'label'    => 'Menyiapkan diri sebelum diminta',
                        'criteria' => [
                            'Selalu siap dengan keperluan & informasi tanpa perlu diminta',
                            'Umumnya sudah siap sebelum diminta',
                            'Kadang menyiapkan diri tetapi tidak konsisten',
                            'Tidak pernah mempersiapkan diri kecuali disuruh',
                        ],
                    ],
                ],
            ],

            // 10) TANGGUNG JAWAB
            [
                'aspek' => 'Tanggung jawab',
                'indikator' => [
                    [
                        'key'      => 'tanggungjawab_tugas_individu',
                        'label'    => 'Melaksanakan tugas individu dengan baik',
                        'criteria' => [
                            'Tugas selesai tepat waktu, rapi, dan melampaui ekspektasi',
                            'Tugas selesai dengan baik dan sesuai instruksi',
                            'Tugas selesai tapi kurang lengkap/asal jadi',
                            'Tidak menyelesaikan tugas atau asal-asalan',
                        ],
                    ],
                    [
                        'key'      => 'tanggungjawab_menerima_resiko',
                        'label'    => 'Menerima risiko dari tindakan yang dilakukan',
                        'criteria' => [
                            'Selalu menerima tanggung jawab dengan terbuka dan jujur',
                            'Umumnya bersedia menerima konsekuensi',
                            'Kadang menerima tanggung jawab jika ditegur',
                            'Menolak bertanggung jawab atas akibat perbuatannya',
                        ],
                    ],
                    [
                        'key'      => 'tanggungjawab_tidak_menyalahkan',
                        'label'    => 'Tidak menyalahkan/menuduh orang lain tanpa bukti',
                        'criteria' => [
                            'Selalu objektif dan tidak menghakimi tanpa bukti',
                            'Cukup berhati-hati dalam menyimpulkan kesalahan orang',
                            'Kadang menuduh orang lain tanpa konfirmasi',
                            'Sering menyalahkan orang lain tanpa dasar',
                        ],
                    ],
                    [
                        'key'      => 'tanggungjawab_mengembalikan_barang',
                        'label'    => 'Mengembalikan barang yang dipinjam',
                        'criteria' => [
                            'Selalu mengembalikan tepat waktu & dalam kondisi baik',
                            'Umumnya mengembalikan tepat waktu',
                            'Mengembalikan tapi sering terlambat/harus diingatkan',
                            'Sering lupa atau tidak mengembalikan barang',
                        ],
                    ],
                    [
                        'key'      => 'tanggungjawab_menepati_janji',
                        'label'    => 'Menepati janji',
                        'criteria' => [
                            'Selalu menepati janji & komitmen tanpa diingatkan',
                            'Biasanya menepati janji',
                            'Kadang menepati janji tapi sering terlambat/lupa',
                            'Sering ingkar janji tanpa alasan jelas',
                        ],
                    ],
                    [
                        'key'      => 'tanggungjawab_tidak_mengkambinghitamkan',
                        'label'    => 'Tidak menyalahkan orang lain untuk kesalahan sendiri',
                        'criteria' => [
                            'Selalu jujur mengakui kesalahan tanpa menyalahkan orang lain',
                            'Umumnya mengakui kesalahan',
                            'Kadang menyalahkan orang lain saat ditegur',
                            'Sering mencari kambing hitam/alasan',
                        ],
                    ],
                    [
                        'key'      => 'tanggungjawab_melaksanakan_ucapan',
                        'label'    => 'Melaksanakan apa yang pernah dikatakan tanpa disuruh',
                        'criteria' => [
                            'Konsisten melaksanakan apa yang dikatakan tanpa diminta ulang',
                            'Umumnya melaksanakan sesuai ucapan',
                            'Menjalankan hanya jika disuruh ulang/diingatkan',
                            'Tidak menindaklanjuti janji/rencana yang pernah disampaikan',
                        ],
                    ],
                ],
            ],
        ];

    public $penilaian_analisis_mahasiswa = [
        [
            'aspek' => 'Differentiating (Menentukan potongan informasi yang relevan)',
            'indikator' => [
                [
                    'key'      => 'mendeteksi',
                    'label'    => 'Mendeteksi',
                    'criteria' => [
                        'Mampu mendeteksi adanya bias antara harapan dan kenyataan secara akurat dan detail.',
                        'Mampu mendeteksi sebagian besar bias antara harapan dan kenyataan dengan baik.',
                        'Mampu mendeteksi beberapa bias antara harapan dan kenyataan, namun ada yang terlewat.',
                        'Mendeteksi sebagian kecil bias antara harapan dan kenyataan.',
                    ],
                ],
                [
                    'key'      => 'menemukan',
                    'label'    => 'Menemukan',
                    'criteria' => [
                        'Mampu menemukan informasi yang akurat dan relevan.',
                        'Mampu menemukan sebagian besar informasi yang akurat dan relevan.',
                        'Mampu menemukan beberapa informasi yang akurat dan relevan.',
                        'Menemukan informasi yang tidak relevan.',
                    ],
                ],
                [
                    'key'      => 'menyeleksi',
                    'label'    => 'Menyeleksi',
                    'criteria' => [
                        'Memilih informasi yang sangat mendalam dan lengkap, mencakup berbagai aspek yang diperlukan.',
                        'Memilih informasi yang cukup mendalam dan lengkap, meskipun ada beberapa aspek yang kurang terbahas.',
                        'Memilih informasi yang cukup mendalam, tetapi banyak aspek yang tidak lengkap.',
                        'Memilih informasi yang dangkal dan tidak lengkap; banyak aspek penting terlewat.',
                    ],
                ],
                [
                    'key'      => 'mengelompokkan',
                    'label'    => 'Mengelompokkan',
                    'criteria' => [
                        'Mampu mengelompokkan informasi secara sangat relevan dan logis berdasarkan kategori.',
                        'Mampu mengelompokkan informasi dengan baik, meskipun ada beberapa bagian yang kurang relevan atau logis.',
                        'Mampu mengelompokkan informasi dengan cukup baik, namun masih terdapat bagian yang kurang relevan atau logis.',
                        'Pengelompokan informasi sering kali tidak relevan atau tidak logis.',
                    ],
                ],
            ],
        ],
        [
            'aspek' => 'Organizing (Menata potongan informasi yang relevan)',
            'indikator' => [
                [
                    'key'      => 'merinci',
                    'label'    => 'Merinci',
                    'criteria' => [
                        'Menyajikan informasi dengan sangat jelas, tepat, dan mendetail.',
                        'Menyajikan sebagian besar informasi dengan jelas dan tepat, meskipun ada bagian yang kurang mendetail.',
                        'Menyajikan informasi cukup jelas, tetapi banyak bagian kurang tepat atau kurang detail.',
                        'Menyajikan informasi tidak jelas, tidak tepat, dan minim detail.',
                    ],
                ],
                [
                    'key'      => 'menominasikan',
                    'label'    => 'Menominasikan',
                    'criteria' => [
                        'Menominasikan informasi yang sangat penting dan berdampak besar pada analisis/argumen.',
                        'Menominasikan sebagian besar informasi penting dan berpengaruh, meski ada beberapa yang kurang signifikan.',
                        'Menominasikan informasi yang cukup penting, namun banyak yang kurang signifikan atau berdampak kecil.',
                        'Menominasikan informasi yang kurang penting dan berdampak kecil atau tidak berdampak.',
                    ],
                ],
                [
                    'key'      => 'merasionalkan',
                    'label'    => 'Merasionalkan',
                    'criteria' => [
                        'Memberikan argumen yang sangat logis dan koheren berdasarkan informasi yang digunakan.',
                        'Memberikan argumen yang logis dan koheren meskipun ada beberapa bagian kurang terbahas.',
                        'Argumen cukup logis/koheren namun banyak aspek tidak terbahas dengan baik.',
                        'Kurang menunjukkan pemahaman konteks informasi dalam argumen.',
                    ],
                ],
                [
                    'key'      => 'menelaah',
                    'label'    => 'Menelaah',
                    'criteria' => [
                        'Menilai kualitas dan keakuratan informasi dengan sangat kritis dan terperinci.',
                        'Menilai sebagian besar informasi dengan kritis, meskipun ada bagian yang kurang terperinci.',
                        'Menilai informasi cukup kritis, tetapi banyak aspek tidak sepenuhnya terperinci.',
                        'Tidak kritis dalam menilai; banyak aspek tidak terperinci.',
                    ],
                ],
            ],
        ],
        [
            'aspek' => 'Attributing (Menentukan tujuan & hubungan informasi)',
            'indikator' => [
                [
                    'key'      => 'mengkorelasikan',
                    'label'    => 'Mengkorelasikan',
                    'criteria' => [
                        'Sangat baik mengidentifikasi dan menjelaskan hubungan antar informasi/data.',
                        'Baik mengidentifikasi & menjelaskan sebagian besar hubungan, meski beberapa kurang jelas.',
                        'Cukup mengidentifikasi hubungan, namun banyak yang tidak jelas atau tidak teridentifikasi.',
                        'Kesulitan mengidentifikasi/menjelaskan hubungan; sering tidak jelas atau terabaikan.',
                    ],
                ],
                [
                    'key'      => 'mengaitkan',
                    'label'    => 'Mengaitkan',
                    'criteria' => [
                        'Sangat baik mengintegrasikan berbagai informasi—menunjukkan keterkaitan yang mendukung analisis.',
                        'Baik mengintegrasikan sebagian besar informasi, meski ada hubungan yang kurang jelas.',
                        'Cukup mengintegrasikan informasi, namun banyak hubungan kurang terlihat/teridentifikasi.',
                        'Kesulitan mengintegrasikan informasi; hubungan antar informasi sering tidak jelas/tidak terlihat.',
                    ],
                ],
                [
                    'key'      => 'menyimpulkan',
                    'label'    => 'Menyimpulkan',
                    'criteria' => [
                        'Kesimpulan sangat akurat, berdasar analisis yang lengkap dan relevan.',
                        'Kesimpulan umumnya akurat, meski ada beberapa detail kurang tepat.',
                        'Kesimpulan cukup akurat, namun ada sejumlah kesalahan/ketidaktepatan analisis.',
                        'Kesimpulan tidak akurat; berdasar informasi salah atau analisis tidak tepat.',
                    ],
                ],
                [
                    'key'      => 'mendiagramkan',
                    'label'    => 'Mendiagramkan',
                    'criteria' => [
                        'Diagram sangat mendukung pemahaman—efektif & efisien menyajikan informasi/data.',
                        'Diagram mendukung pemahaman, meski ada aspek yang kurang efektif.',
                        'Diagram cukup mendukung, namun banyak aspek kurang efektif/efisien.',
                        'Diagram kurang mendukung; sering tidak efektif/efisien menyajikan data.',
                    ],
                ],
                [
                    'key'      => 'membagankan',
                    'label'    => 'Membagankan',
                    'criteria' => [
                        'Bagan sangat mendukung pemahaman—efektif & efisien menyajikan informasi.',
                        'Bagan mendukung pemahaman, meski ada aspek yang kurang efektif.',
                        'Bagan cukup mendukung, namun banyak aspek kurang efektif/efisien.',
                        'Bagan kurang mendukung; sering tidak efektif/efisien menyajikan data.',
                    ],
                ],
            ],
        ],
    ];
}
