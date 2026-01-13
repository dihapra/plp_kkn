$(document).ready(async function () {
    let selectData = [];
    const filterBasePath = window.filterBasePath || 'super-admin';

    async function fetchPrograms() {
        try {
            const response = await fetch(`${baseUrl}${filterBasePath}/filter/program`);
            if (!response.ok) {
                throw new Error('Gagal mengambil data program.');
            }
            const result = await response.json();
            const data = result.data || result;
            selectData = data.map((program) => ({
                id: program.id,
                text: program.nama,
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
            placeholder: $element.data('placeholder') || 'Pilih Program',
            allowClear: true,
            width: '100%',
            dropdownParent: dropdownParent ? $(dropdownParent) : undefined,
        });
    }

    function initAllSelects() {
        $('.sa-program-select').each(function () {
            initSelect2($(this));
        });
    }

    await fetchPrograms();

    $('#kepsekModal').on('shown.bs.modal', function () {
        const modalSelector = `#${$(this).attr('id')}`;
        const $select = $(this).find('.sa-program-select');
        initSelect2($select, modalSelector);
    });

    $(document).on('mousedown', '.select2-container', function (e) {
        e.stopPropagation();
    });
});
