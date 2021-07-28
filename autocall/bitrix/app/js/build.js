const liraxKey = document.querySelector('#lirax_key'),
    liraxBlock = document.querySelector('.lirax__block'),
    liraxItemBtn = document.querySelectorAll('.lirax__item-btn'),
    liraxItemContent = document.querySelectorAll('.lirax__item-content'),
    inputVoronkiAll = document.querySelector('#lirax_voronki_all'),
    inputVoronki = document.querySelectorAll("input[name='lirax_voronki[]']"),
    resultVoronkiTable = document.querySelector('.lirax__content-voronki-result-table'),
    resultVoronkiBtnMore = document.querySelector('.lirax__content-voronki-result-btn'),
    callAttemptSelect = document.querySelector('#call_attempt'),
    callAttemptLists = document.querySelector('.lirax__content-call-attempt-lists'),
    tableUser = document.querySelector('#table_user');

const liraxModal = document.querySelector('#lirax_modal'),
    liraxModalClose = document.querySelector('#lirax_modal_close'),
    liraxModaIcon = document.querySelector('#lirax_modal-icon'),
    liraxModaTitle = document.querySelector('#lirax_modal_title'),
    liraxModaDescr = document.querySelector('#lirax_modal_descr');


let resultVoronkiArr = [],
    btnLocked = false;


const classLirax = class {
    showModal = (type, title, descr) => {
        liraxModal.classList.add('visible');
        liraxModaIcon.classList.add(type)
        liraxModaTitle.innerHTML = title
        liraxModaDescr.innerHTML = descr
    };

    closeModal = () => {
        liraxModal.classList.remove('visible')
        liraxModaIcon.classList.remove('danger', 'success')
    };

    selectAllVoronki = (val) => {
        for (let i = 0; i < inputVoronki.length; i++) {
            inputVoronki[i].checked = val.checked
            if (!inputVoronki[i].checked) {
                resultVoronkiArr = []
            } else {
                resultVoronkiArr.push(inputVoronki[i].value)
            }
        }
        this.resultVoronki()
    };

    resultVoronki = () => {
        let table = document.createElement('table');
        let tbody = table.appendChild(document.createElement('tbody'));

        let resultVoronkiOut = resultVoronkiArr.map((_, i, a) => a.slice(i * 2, i * 2 + 2)).filter((el) => el.length);

        resultVoronkiOut.forEach(items => {
            let tr = document.createElement('tr');

            for (let item of items) {
                tr.innerHTML += `<td>${item}</td>`;
            }
            tbody.insertAdjacentHTML('beforeend', tr.outerHTML)
        })

        if (resultVoronkiArr.length > 10 && !btnLocked) {
            resultVoronkiBtnMore.classList.add('visible')
        } else {
            resultVoronkiBtnMore.classList.remove('visible')
        }
        resultVoronkiTable.innerHTML = table.outerHTML
    };

    checkingVoronki = () => {
        resultVoronkiArr = []
        for (let i = 0; i < inputVoronki.length; i++) {
            if (inputVoronki[i].checked) {
                resultVoronkiArr.push(inputVoronki[i].value)
            }
        }

        inputVoronkiAll.checked = inputVoronki.length === resultVoronkiArr.length
        this.resultVoronki()
        this.showCheckbox()
    };

    callAttempResult = (count) => {
        callAttemptLists.innerHTML = '';

        for (let i = 1; i <= count; i++) {
            const list = `
        <label>
            <p>${i}</p>
            <input type="time" name="call_times[]"/>
        </label>`;
            callAttemptLists.insertAdjacentHTML('beforeend', list)
        }
    };

    liraxKeyChange = val => {
        if (val.length > 0) {
            liraxKey.classList.add('active')
        } else {
            liraxKey.classList.remove('active')
        }
    }

    ////////////////////////////////////
    //                                //
    //          WORK AREA             //
    //                                //
    ////////////////////////////////////

    showCheckbox = () => {
        const contentFunnelsChooseAll = document.querySelector('.js-all')
        const contentFunnelsList = document.querySelectorAll('.js-allPip')
        const funnelsNumbersTable = document.querySelectorAll('.lirax__content-numbers-table tbody tr')
        const funnelsShopsTable = document.querySelectorAll('.lirax__content-shops-table tbody tr')

        contentFunnelsList.forEach(el_of_main_sel => {
            if (!el_of_main_sel.children[0].checked) {
                funnelsNumbersTable.forEach(el_funnelsNumbersTable => el_funnelsNumbersTable.querySelector('input').id === el_of_main_sel.id ? el_funnelsNumbersTable.style.display = "none" : null)
                funnelsShopsTable.forEach(el_funnelsShopsTable => el_funnelsShopsTable.querySelector('input').id === el_of_main_sel.id ? el_funnelsShopsTable.style.display = "none" : null)
            } else {
                funnelsNumbersTable.forEach(el_funnelsNumbersTable => {
                    if (el_funnelsNumbersTable.querySelector('input').id === el_of_main_sel.id) {
                        el_of_main_sel.addEventListener('change', () => el_funnelsNumbersTable.querySelector('.num_funnels input').value = '')
                        el_funnelsNumbersTable.style.display = "block"
                    }
                })
                funnelsShopsTable.forEach(el_funnelsShopsTable => {
                    if (el_funnelsShopsTable.querySelector('input').id === el_of_main_sel.id) {
                        el_of_main_sel.addEventListener('change', () => el_funnelsShopsTable.querySelector('.num_store input').value = '')
                        el_funnelsShopsTable.style.display = "block"
                    }
                })

                contentFunnelsChooseAll.children[0].addEventListener('change', () => {
                    if (el_of_main_sel.children[0].checked) {
                        funnelsNumbersTable.forEach(el_funnelsNumbersTable => {
                            el_funnelsNumbersTable.style.display === "none" ? el_funnelsNumbersTable.querySelector('.num_funnels input').value = '' : null
                            el_funnelsNumbersTable.style.display = "block"
                        })
                        funnelsShopsTable.forEach(el_funnelsShopsTable => {
                            el_funnelsShopsTable.style.display === "none" ? el_funnelsShopsTable.querySelector('.num_store input').value = '' : null
                            el_funnelsShopsTable.style.display = "block"
                        })
                    } else {
                        funnelsNumbersTable.forEach(el_funnelsNumbersTable => el_funnelsNumbersTable.style.display = "none")
                        funnelsShopsTable.forEach(el_funnelsShopsTable => el_funnelsShopsTable.style.display = "none")
                    }
                })
            }
        })
    }
};

const liraxWidget = new classLirax();

for (let i = 0; i < liraxItemBtn.length; i++) {
    liraxItemBtn[i].addEventListener('click', (e) => {
        e.preventDefault();
        const settings = liraxItemBtn[i].getAttribute('data-settings');
        liraxItemBtn[i].setAttribute('data-settings', (settings === 'false'))
        liraxItemBtn[i].classList.toggle('active');
        liraxItemContent[i].classList.toggle('visible');
    })
}

resultVoronkiBtnMore.addEventListener('click', () => {
    btnLocked = true
    resultVoronkiTable.classList.add('visible')
    resultVoronkiBtnMore.classList.remove('visible')
});


callAttemptSelect.addEventListener('change', e => {
    const count = e.target.value
    liraxWidget.callAttempResult(count)
});


liraxBlock.addEventListener('change', (e) => {
    if (e.target.type === 'number') {
        const inputVal = Number(e.target.value);

        if (inputVal < 0 || isNaN(inputVal)) {
            e.target.value = 0;
        } else {
            e.target.value = inputVal
        }
    }

    if (e.target.name === 'user_priority[]') {
        const inputUserName = liraxBlock.querySelectorAll("input[name='user_name[]']"),
            inputUserPriority = liraxBlock.querySelectorAll("input[name='user_priority[]']");

        let objPriority = []

        for (let i = 0; i < inputUserPriority.length; i++) {
            objPriority.push({
                name: inputUserName[i].value,
                position: inputUserPriority[i].value,
                id: inputUserName[i].getAttribute('id')
            })
        }

        objPriority.sort((prev, next) => next.position - prev.position);
        tableUser.innerHTML = ''

        objPriority.forEach((item, index) => {
            let tableTr = `
            <tr>
                  <td class="number-list">${index}</td>
                  <td class="name-user">
                    <input type="text" name="user_name[]" disabled="true" id="${item.id}" value="${item.name}" />
                  </td>
                  <td class="user-priority">
                    <input type="number" name="user_priority[]" value="${item.position.length > 0 ? item.position : 0}" />
                  </td>
            </tr>
        `;

            tableUser.insertAdjacentHTML('beforeend', tableTr)
        })
    }
});


liraxModalClose.addEventListener('click', liraxWidget.closeModal)

liraxKey.oninput = e => {
    const val = e.target.value
    liraxWidget.liraxKeyChange(val)
};

inputVoronkiAll.addEventListener('click', e => {
    liraxWidget.selectAllVoronki(e.target)
});

inputVoronki.forEach(item => {
    item.addEventListener('change', liraxWidget.checkingVoronki)
});

liraxWidget.checkingVoronki()
liraxWidget.callAttempResult(callAttemptSelect.value)
liraxWidget.liraxKeyChange(liraxKey.value)

window['FRONT_CLASS'] = classLirax