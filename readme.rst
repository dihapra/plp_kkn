# 📦 Folder `services/` – CodeIgniter 3 Boilerplate

Folder `application/services/` digunakan untuk menyimpan **service classes** yang bertanggung jawab atas **logika bisnis aplikasi**. Tujuannya adalah untuk **memisahkan logika dari controller**, sehingga controller tetap ramping dan mudah dikelola.

Struktur ini meniru gaya Laravel (Service Layer Architecture) yang membuat kode lebih terorganisir dan scalable.

---

## ✅ Keuntungan Menggunakan `services/`

- Memisahkan logika bisnis dari controller
- Controller jadi lebih ringkas dan fokus ke request/response
- Service bisa digunakan lintas controller atau di cron job/CLI
- Lebih mudah di-*test* dan di-*mock*

---

## 🧠 Contoh Struktur

application/
├── controllers/
│ └── Produk.php
├── models/
│ └── Produk_model.php
├── services/
│ └── ProdukService.php
├── config/
│ └── config.php (sudah diatur autoload custom)


## ⚙️ Autoload Setup

Agar file dalam folder `services/` bisa digunakan tanpa `require_once`, tambahkan ini ke `application/config/config.php`:

```php
spl_autoload_register(function ($class) {
    $paths = [
        APPPATH . 'services/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});


🧩 Contoh Service
📄 application/services/ProdukService.php

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProdukService {
    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->model('Produk_model');
    }

    public function getAll() {
        return $this->CI->Produk_model->get_all();
    }

    public function create($data) {
        return $this->CI->Produk_model->insert($data);
    }
}

🧪 Contoh Controller
📄 application/controllers/Produk.php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends CI_Controller {
    protected $produkService;

    public function __construct() {
        parent::__construct();
        $this->produkService = new ProdukService();
    }

    public function index() {
        $produk = $this->produkService->getAll();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($produk));
    }

    public function store() {
        $data = $this->input->post();
        $this->produkService->create($data);
        redirect('produk');
    }
}


🎯 Kapan Menggunakan Service?
Gunakan service jika:

Logika terlalu kompleks untuk ditaruh langsung di controller

Membutuhkan akses ke beberapa model dalam satu operasi

Ingin membuat unit test untuk logika bisnis

Ada operasi seperti: validasi, notifikasi, transformasi data, transaksi database


📌 Tips Tambahan
Gunakan nama berakhiran Service, contoh: UserService, AuthService.

Gunakan $this->CI =& get_instance(); untuk akses ke model, session, input, dsb.

Service bisa dipanggil dari controller, job, atau CLI.