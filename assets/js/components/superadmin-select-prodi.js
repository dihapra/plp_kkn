$(document).ready(async function () {
    let selectData = [];
    const filterBasePath = window.filterBasePath || 'super-admin';

    async function fetchProdi() {
        try {
            const response = await fetch(`${baseUrl}${filterBasePath}/filter/prodi`);
            if (!response.ok) {
                throw new Error('Gagal mengambil data prodi.');
            }
            const result = await response.json();
            const data = result.data || result;
            selectData = (data || []).map((prodi) => ({
                id: prodi.id,
                text: prodi.nama,
                fakultas: prodi.fakultas || '',
            }));
            initFakultasSelects();
            initAllProdiSelects();
        } catch (error) {
            console.error(error);
        }
    }

    function getFakultasList() {
        const list = new Set();
        selectData.forEach((item) => {
            if (item.fakultas) {
                list.add(item.fakultas);
            }
        });
        return Array.from(list).sort();
    }

    function initFakultasSelects() {
        const fakultasList = getFakultasList();
        $('.sa-fakultas-select').each(function () {
            const $select = $(this);
            const currentValue = $select.val();
            const placeholder = $select.data('placeholder') || 'Pilih Fakultas';
            $select.empty();
            $select.append(new Option(placeholder, '', false, false));
            fakultasList.forEach((nama) => {
                $select.append(new Option(nama, nama, false, false));
            });
            if (currentValue && fakultasList.includes(currentValue)) {
                $select.val(currentValue);
            } else {
                $select.val('');
            }
        });
    }

    function filterDataByFakultas(fakultas) {
        if (!fakultas) {
            return selectData;
        }
        return selectData.filter(
            (item) => (item.fakultas || '').toLowerCase() === fakultas.toLowerCase()
        );
    }

    function setOptions($element, dataset) {
        if (!$element.length) return;
        const placeholder = $element.data('placeholder') || 'Pilih Prodi';
        const pendingValue = $element.data('pendingValue');
        const currentValue = typeof pendingValue !== 'undefined' ? pendingValue : $element.val();
        $element.removeData('pendingValue');

        $element.empty();
        $element.append(new Option(placeholder, '', false, false));
        dataset.forEach(({ id, text }) => {
            $element.append(new Option(text, id, false, false));
        });

        const hasCurrent = dataset.some((item) => String(item.id) === String(currentValue));
        if (hasCurrent) {
            $element.val(currentValue);
        } else {
            $element.val('');
        }
    }

    function initSelect2($element, dropdownParent = null) {
        if (!$element.length) return;

        const facultySelector = $element.data('faculty-source');
        const fakultasValue = facultySelector ? $(facultySelector).val() : null;
        const dataset = filterDataByFakultas(fakultasValue);

        setOptions($element, dataset);

        if ($element.data('select2')) {
            $element.select2('destroy');
        }

        $element.select2({
            data: dataset,
            placeholder: $element.data('placeholder') || 'Pilih Prodi',
            allowClear: true,
            width: '100%',
            dropdownParent: dropdownParent ? $(dropdownParent) : undefined,
        });

    }

    function initAllProdiSelects() {
        $('.sa-prodi-select').each(function () {
            const $select = $(this);
            const $modal = $select.closest('.modal');
            initSelect2($select, $modal.length ? $modal : null);
        });
    }

    function syncProdiWithFaculty($faculty, desiredValue) {
        const targetSelector = $faculty.data('prodi-target');
        if (!targetSelector) return;
        const $target = $(targetSelector);
        if (!$target.length) return;
        if (typeof desiredValue !== 'undefined') {
            $target.data('pendingValue', desiredValue);
        } else {
            $target.data('pendingValue', '');
        }
        const $modal = $target.closest('.modal');
        initSelect2($target, $modal.length ? $modal : null);
    }

    await fetchProdi();

    $(document).on('change', '.sa-fakultas-select', function (event, options) {
        const desiredValue = options && Object.prototype.hasOwnProperty.call(options, 'desiredValue')
            ? options.desiredValue
            : undefined;
        syncProdiWithFaculty($(this), desiredValue);
    });

    $(document).on('shown.bs.modal', '.modal', function () {
        const $modal = $(this);
        const $selects = $modal.find('.sa-prodi-select');
        const $fakultasSelects = $modal.find('.sa-fakultas-select');

        if ($fakultasSelects.length) {
            $fakultasSelects.each(function () {
                syncProdiWithFaculty($(this), $(this).val());
            });
        } else if ($selects.length) {
            $selects.each(function () {
                initSelect2($(this), $modal);
            });
        }
    });

    $(document).on('mousedown', '.select2-container', function (e) {
        e.stopPropagation();
    });
});
