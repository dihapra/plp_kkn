$(document).ready(async function () {
    let selectData = [];

    async function fetchSchools() {
        try {
            const response = await fetch(`${baseUrl}sekolah/select`);
            if (!response.ok) {
                throw new Error('Gagal mengambil data sekolah.');
            }
            const data = await response.json();
            selectData = data.map(school => ({
                id: school.id,
                text: school.name
            }));
            initAllSelects();
        } catch (error) {
            console.error(error);
        }
    }

    function ensureOptions($element) {
        if (!$element.length) return;
        const existingValues = new Set(
            $element.find('option').map(function () { return $(this).val(); }).get()
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
            dropdownParent: dropdownParent ? $(dropdownParent) : undefined
        });
    }

    function initAllSelects() {
        $('.school-select').each(function () {
            initSelect2($(this));
        });
    }

    await fetchSchools();

    $('#createModal, #editModal').on('shown.bs.modal', function () {
        const modalSelector = `#${$(this).attr('id')}`;
        const $select = $(this).find('.school-select');
        initSelect2($select, modalSelector);
    });

    $(document).on('mousedown', '.select2-container', function (e) {
        e.stopPropagation();
    });
});
