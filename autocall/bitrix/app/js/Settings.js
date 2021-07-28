class Settings {
    static _url_pip = 'https://smart-dev.pragma.by/api/own/autocall/bitrix/bitrix_pip.php';
    static _url_settings = 'https://smart-dev.pragma.by/api/own/autocall/bitrix/settings.php';

    static async getPip(account_id, referrer) {
        let result = await $.ajax({
            url: Settings._url_pip,
            method: 'post',
            data: {
                flag: 'get',
                account_id: account_id,
                referrer: referrer,
                widget_code: 'pmLirax'

            }
        })
        const parseJsonRes = JSON.parse(result)
        parseJsonRes.pipelines.forEach(item => {
            $('.js-allPip').each(function () {
                if ($(this).attr('id') * 1 === item)
                {
                    $(this).find('input').attr('checked', true)
                }
            })
        })
        new FRONT_CLASS().checkingVoronki()
        new FRONT_CLASS().showCheckbox()
    }

    static async getAllSettings(account_id,referrer) {
        const store = $('#using-stores')
        const funnels = $('#number-funnels')
        const user = $('#user-chek')
        const lead = $('#lead-status')


        let result = await $.ajax({
            url: Settings._url_settings,
            method: 'post',
            data: {
                flag: 'get_settings',
                typeCRM: 'bitrix',
                ID_ACCOUNT: account_id,
                referrer: referrer
            },
        })

        const parseJsonRes = JSON.parse(result)
        parseJsonRes.data.forEach(item => {
            $('#lirax_key').val(item.token)
            $('#time-auto-call-title').val(item.use_responsible)
            if (item.use_store === 'true'){
                store.addClass('active')
                store.attr('data-settings', 'true')
                $('.lirax__content-shops').addClass('visible')
            }
            if (item.use_number === 'true') {
                funnels.addClass('active')
                funnels.attr('data-settings', 'true')
                $('.lirax__content-numbers').addClass('visible')
            }
            if (item.use_priory === 'true') {
                user.addClass('active')
                user.attr('data-settings', 'true')
                $('.lirax__content-priority').addClass('visible')
            }
            if (item.use_lead === 'true') {
                lead.addClass('active')
                lead.attr('data-settings', 'true')
                $('.lirax__content-lead').addClass('visible')
            }
            new FRONT_CLASS().liraxKeyChange(item.token)
        })

        $('#call_work_before').val(parseJsonRes.quantity.work_start)
        $('#call_work_after').val(parseJsonRes.quantity.work_finish)
        $('#call_attempt').val(parseJsonRes.quantity.quantity)
        new FRONT_CLASS().callAttempResult(parseJsonRes.quantity.quantity)

        parseJsonRes.quantity.data_q.forEach(item => {
            $('.lirax__content-call-attempt-lists').find('label').each(function (){
                if($(this).find('p').html() === item.q.toString()){
                    $(this).find('input').val(item.time.toString())
                }
            })
        })

        parseJsonRes.numbers.forEach(item => {
            $('.lirax__content-numbers-table').find('.funnels').each(function () {
                if ($(this).find('input').attr('id') === item.id_pipeline.toString())
                {
                    $(this).parent('tr').find('.num_funnels').find('input').val(item.number.toString())
                }
            })
        })

        parseJsonRes.pipelines.forEach(item => {
            $('.lirax__content-shops-table').find('.store').each(function (){
                if ($(this).find('input').attr('id') === item.id_pipeline.toString())
                {
                    $(this).parent('tr').find('.num_store').find('input').val(item.id_set_pipeline.toString())
                }
            })
        })

        parseJsonRes.priority.forEach(item => {
            $('#table_user').find('.name-user').each(function () {
                if ($(this).find('input').attr('id') === item.id) {
                    $(this).parent('tr').find('.user-priority').find('input').val(item.priory)
                }
            })
        })

        parseJsonRes.lead.forEach(item => {
            $('.lirax__content-lead-table').find('.lead').each(function () {
                if ($(this).find('input').attr('id') === item.lead_id.toString())
                {
                    $(this).parent('tr').find('.num_lead').find('input').val(item.lead_set_number)
                }
            })
        })
    }
}

window['PragmaLiraxSettings'] = Settings
