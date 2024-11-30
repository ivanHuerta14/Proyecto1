let currentPage = 1;
let searchQuery = '';
let sortColumn = 'nombre';
let sortOrder = 'ASC';

function loadTableData() {
    searchQuery = $('#searchInput').val() || '';
    
    $.ajax({
        url: `api.php?action=read&search=${searchQuery}&column=${sortColumn}&order=${sortOrder}&page=${currentPage}`,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            const tbody = $("#dataTable tbody");
            tbody.empty();
            
            data.forEach(row => {
                const tr = `
                    <tr id="row-${row.id}">
                        <td>${row.nombre}</td>
                        <td>${row.valor}</td>
                        <td>
                            <button onclick="editRecord(${row.id})">Editar</button>
                            <button onclick="deleteRecord(${row.id})">Eliminar</button>
                        </td>
                    </tr>
                `;
                tbody.append(tr);
            });
        },
        error: function(error) {
            console.error('Error:', error);
            alert("Hubo un problema al cargar los datos.");
        }
    });
}


function addRecord() {
    const nombre = prompt("Nombre:");
    const valor = prompt("Valor:");

    if (nombre && valor) {
        $.ajax({
            url: 'api.php?action=create',
            method: 'POST',
            data: {
                nombre: nombre,
                valor: valor
            },
            success: function(response) {
                console.log('Registro agregado con éxito:', response);

                
                const newRow = `
                    <tr id="row-${response.id}" style="display: none;">
                        <td>${nombre}</td>
                        <td>${valor}</td>
                        <td>
                            <button onclick="editRecord(${response.id})">Editar</button>
                            <button onclick="deleteRecord(${response.id})">Eliminar</button>
                        </td>
                    </tr>
                `;

               
                $("#dataTable tbody").prepend(newRow);
                $(`#row-${response.id}`).fadeIn(1500);
            },
            error: function(error) {
                console.error('Error al agregar el registro:', error);
                alert("No se pudo agregar el registro. Inténtalo de nuevo.");
            }
        });
    }
}


function editRecord(id) {
    const nombre = prompt("wNuevo nombre:");
    const valor = prompt("Nwuevo valor:");

    if (nombre && valor) {
        $.ajax({
            url: 'api.php?action=update',
            method: 'POST',
            data: { id, nombre, valor },
            success: function() {
                $(`#row-${id}`).effect('highlight', { color: '#008000' }, 1500); 
                $(`#row-${id} td:nth-child(1)`).text(nombre);
                $(`#row-${id} td:nth-child(2)`).text(valor);
                showDialog('Registro actualizado con éxito.');
            },
            error: function() {
                showDialog('Error al actualizar el registro.', true);
            }
        });
    } else {
        showDialog('Por favor, complete todos los campos.', true);
    }
}

function deleteRecord(id) {
    console.log(`Intentando animar la fila con id: #row-${id}`);
    const row = $(`#row-${id}`);

    if (row.length) {
        console.log('Fila encontrada:', row); 
         row.css("background-color", "red");
        row.fadeOut(1500, function () {
            console.log('Fila oculta con éxito.');
           
            $.ajax({
                url: 'api.php?action=delete',
                method: 'POST',
                data: { id: id },
                success: function(response) {
                    console.log('Registro eliminado del servidor:', response);
                    showDialog("Registro eliminado correctamente.");
                    loadTableData(); 
                },
                error: function(error) {
                    console.error('Error al eliminar el registro:', error);
                    showDialog("Error al eliminar el registro.", true);
                }
            });
        });
    } else {
        console.warn(`No se encontró la fila con id #row-${id}`);
    }
}


function sortTable(column) {
    sortColumn = column;
    sortOrder = (sortOrder === 'ASC') ? 'DESC' : 'ASC';

    const tbody = $("#dataTable tbody");
    tbody.fadeOut(500, function () {
        loadTableData();
        tbody.fadeIn(500);
    });
}


// Función para ir a la página anterior
function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        loadTableData();
    }
}

// Función para ir a la página siguiente
function nextPage() {
    currentPage++;
    loadTableData();
}


function showDialog(message, isError = false) {
    const dialogBox = document.createElement("div");
    dialogBox.textContent = message;
    dialogBox.style.padding = "10px";
    dialogBox.style.border = "1px solid";
    dialogBox.style.backgroundColor = isError ? "#ffcccc" : "#ccffcc";
    dialogBox.style.color = isError ? "#ff0000" : "#008000";
    dialogBox.style.position = "fixed";
    dialogBox.style.top = "20px";
    dialogBox.style.right = "20px";
    dialogBox.style.zIndex = "1000";
    dialogBox.style.borderRadius = "5px";

    document.body.appendChild(dialogBox);

    setTimeout(() => {
        dialogBox.remove();
    }, 3000); // Elimina el mensaje después de 3 segundos
}