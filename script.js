let btnEdit = document.getElementById('btnEditar');
let btnTrash = document.getElementById('btTrash');
let formBtnTrash = document.getElementById('formTrash');

btnEdit.addEventListener('click', editar)

window.addEventListener('load', function(){
    document.getElementById('fieldPass').focus();
});

function editar(){
    let fieldEdit = document.querySelectorAll('.editField');
    let btnCheck = document.getElementById('editCheck');
    let campoDel = document.getElementById('editDel');
    let formBtnCheck = document.getElementById('formCheck');

    fieldEdit.forEach(fieldEdit => {
        if (fieldEdit.disabled) {
            btnEdit.style.display = 'none';
            btnTrash.style.display = 'none';
            fieldEdit.disabled = false;  
            btnCheck.hidden = false;
            campoDel.hidden = false;

            btnCheck.addEventListener('click', function() {
                formBtnCheck.disabled = false;
                document.getElementById('formPeca').submit();
            })

            campoDel.addEventListener('click', function() {
                btnEdit.style.display = 'block';
                btnTrash.style.display = 'block';
                fieldEdit.disabled = true;  
                btnCheck.hidden = true;
                campoDel.hidden = true;
                formBtnCheck.disabled = true;
                formBtnTrash.disabled = true;
            })
        } else {
            btnEdit.style.display = 'block';
            btnTrash.style.display = 'block';
            fieldEdit.disabled = true;
        }
    })
}

btnTrash.addEventListener('click', function() {
    formBtnTrash.disabled = false;
    document.getElementById('formPeca').submit();
})

document.querySelectorAll('.editField').forEach(fieldEdit => {
    fieldEdit.addEventListener('input', function() {
        let btnCheck = document.getElementById('editCheck');
        btnCheck.disabled = false;
    })
})

