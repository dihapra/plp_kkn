$(document).ready(async function () {
    let selectData = [];
    const filterBasePath = window.filterBasePath || 'super-admin';

    async function fetchSchools() {
        try {
            const response = await fetch(`${baseUrl}${filterBasePath}/filter/sekolah`);
            if (!response.ok) {
                throw new Error('Gagal mengambil data sekolah.');
            }
            const result = await response.json();
            const data = result.data || result; // jaga-jaga jika langsung array
            selectData = data.map((school) => ({
                id: school.id,
                text: school.nama,
            }));
            initAllSelects();
        } catch (error) {
            console.error(error);
        }
    }

    function ensureOptions($element) {
        if (!$element.length) return;
        const existingValues = new Set(
            $element
                .find('option')
                .map(function () {
                    return $(this).val();
                })
                .get()
        );
        selectData.forEach(({ id, text }) => {
            const value = String(id);
            if (!existingValues.has(value)) {
                $element.append(new Option(text, id, false, false));
            }
        });
    }

    function initSelect2($element, dropdownParent = null) {
        if (!$element.length) return;
        ensureOptions($element);
        if ($element.data('select2')) {
            $element.select2('destroy');
        }

        $element.select2({
            data: selectData,
            placeholder: $element.data('placeholder') || 'Pilih Sekolah',
            allowClear: true,
            width: '100%',
            dropdownParent: dropdownParent ? $(dropdownParent) : undefined,
        });
    }

    function initAllSelects() {
        $('.sa-school-select').each(function () {
            const $select = $(this);
            const $modal = $select.closest('.modal');
            const parent = $modal.length ? $modal : null;
            initSelect2($select, parent);
        });
    }

    await fetchSchools();

    $(document).on('shown.bs.modal', '.modal', function () {
        const $modal = $(this);
        const $select = $modal.find('.sa-school-select');
        if (!$select.length) return;
        initSelect2($select, $modal);
    });

    $(document).on('mousedown', '.select2-container', function (e) {
        e.stopPropagation();
    });
});
