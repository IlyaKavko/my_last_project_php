$(document).ready(async function () {
    const integrationInfo = $('input[type=hidden]');
    const urlSave = 'https://smart-dev.pragma.by/api/own/autocall/bitrix/bitrix_pip.php';
    const urlSattings = 'https://smart-dev.pragma.by/api/own/autocall/bitrix/settings.php';
    const USE_LEAD = $('#lead-status');
    const USE_STORE = $('#using-stores');
    const USE_NUMBER = $('#number-funnels');
    const USE_RESPONSIBLE = $('#time-auto-call-title');
    const USE_PRIORY = $('#user-chek');
    const TOKEN = $('#lirax_key');
    let ARRAY_PIPELINE = [];
    let ARRAY_NUM_PIP = [];
    const QUANTITY = $('#call_attempt');
    let data_q = [];
    const work_start = $('#call_work_before');
    const work_finish = $('#call_work_after');
    let ARRAY_PRIORY = [];
    let ARRAY_LEAD_PIPELINE = [];

    await PragmaLiraxSettings.getPip(integrationInfo.attr('id'), integrationInfo.attr('name'))
    await PragmaLiraxSettings.getAllSettings(integrationInfo.attr('id'), integrationInfo.attr('name'))


    $('input[name=lirax_voronki_all]').change(function () {
        let infoFunnels = $(this).parent('label').parent('div').find('.js-allPip');
        const arPip = [];
        $(infoFunnels).each(function () {
            let allPip = $(this).attr('id')
            arPip.push(allPip)
        });
        if (this.checked) {
            $.ajax({
                url: urlSave,
                method: 'post',
                dataType: 'json',
                data: {
                    flag: 'save',
                    widget_code: 'pmLirax',
                    CHECK: this.checked,
                    is_all: this.checked,
                    id: arPip,
                    account_id: integrationInfo.attr('id'),
                    referrer: integrationInfo.attr('name')
                },
            })
        } else {
            $.ajax({
                url: urlSave,
                method: 'post',
                dataType: 'json',
                data: {
                    flag: 'save',
                    widget_code: 'pmLirax',
                    CHECK: this.checked,
                    is_all: 'true',
                    id: arPip,
                    account_id: integrationInfo.attr('id'),
                    referrer: integrationInfo.attr('name')
                },
            })
        }
    })

    $('.js-pip').change(function () {
        let infoFunnels = $(this).parent('label');
        if (this.checked) {
            $.ajax({
                url: urlSave,
                method: 'post',
                dataType: 'json',
                data: {
                    flag: 'save',
                    widget_code: 'pmLirax',
                    CHECK: this.checked,
                    is_all: 'false',
                    id: infoFunnels.attr('id'),
                    account_id: integrationInfo.attr('id'),
                    referrer: integrationInfo.attr('name')
                },
            })
        } else {
            $.ajax({
                url: urlSave,
                method: 'post',
                data: {
                    flag: 'save',
                    widget_code: 'pmLirax',
                    CHECK: this.checked,
                    is_all: this.checked,
                    id: infoFunnels.attr('id'),
                    account_id: integrationInfo.attr('id'),
                    referrer: integrationInfo.attr('name')
                },
            })
        }
    })
    $('#lirax_save').on('click', function () {
        ARRAY_PIPELINE = []
        ARRAY_NUM_PIP = []
        data_q = []
        ARRAY_PRIORY = []
        ARRAY_LEAD_PIPELINE =[]


        $('.lirax__content-lead-table').find('.lead').each(function (){
            let lead_name = $(this).find('input').val()
            let lead_set_number = $(this).parent('tr').find('.num_lead').find('input').val()
            let lead_id = $(this).find('input').attr('id');
            ARRAY_LEAD_PIPELINE.push({lead_name,lead_set_number,lead_id})
        })

        $('.lirax__content-shops-table').find('.store').each(function () {
            let pip_name = $(this).find('input').val()
            let pip_set_id = $(this).parent('tr').find('.num_store').find('input').val()
            let pip_id = $(this).find('input').attr('id')
            ARRAY_PIPELINE.push({pip_name, pip_set_id, pip_id})
        })

        $('.lirax__content-numbers-table').find('.funnels').each(function () {
            let name_pipeline = $(this).find('input').val()
            let number = $(this).parent('tr').find('.num_funnels').find('input').val()
            let id_pipeline = $(this).find('input').attr('id')
            ARRAY_NUM_PIP.push({name_pipeline, number, id_pipeline})
        })

        $('.lirax__content-call-attempt-lists').find('label').each(function () {
            let q = $(this).find('p').html();
            let time = $(this).find('input').val();

            data_q.push({q,time})
        })

        $('#table_user').find('.name-user').each(function () {
            let id = $(this).find('input').attr('id');
            let priory = $(this).parent('tr').find('.user-priority').find('input').val()
            let name = $(this).find('input').val();

            ARRAY_PRIORY.push({id, priory, name})

        })

        $.ajax({
            url: urlSattings,
            method: 'post',
            data: {
                flag: 'save_settings',
                typeCRM: 'bitrix',
                REFERRER: integrationInfo.attr('name'),
                ID_ACCOUNT: integrationInfo.attr('id'),
                USE_LEAD:USE_LEAD.attr('data-settings'),
                USE_STORE: USE_STORE.attr('data-settings'),
                USE_NUMBER: USE_NUMBER.attr('data-settings'),
                USE_RESPONSIBLE: USE_RESPONSIBLE.val(),
                USE_PRIORY: USE_PRIORY.attr('data-settings'),
                TOKEN: TOKEN.val(),
                APPLICATION: 0,
                ARRAY_LEAD_PIPELINE: ARRAY_LEAD_PIPELINE,
                ARRAY_PIPELINE: ARRAY_PIPELINE,
                ARRAY_NUM_PIP: ARRAY_NUM_PIP,
                QUANTITY: {
                    quantity: QUANTITY.val(),
                    data_q: data_q,
                    work_start: work_start.val(),
                    work_finish: work_finish.val()
                },
                ARRAY_PRIORY: ARRAY_PRIORY,
            },
            success: function () {
                new FRONT_CLASS().showModal('success', 'Успех', 'Данные успешно сохранены')
            }
        })
    })
})